<?php
/**
 * Image Access Test Script
 * 
 * This script tests if images are accessible and verifies the file structure.
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to: /public_html/mobile-api/test-image-access.php
 * 2. Access it via browser: https://onlinejobs.my/mobile-api/test-image-access.php?user_id=9602
 * 3. It will show all images for that user and test if they're accessible
 * 4. DELETE THIS FILE after testing for security!
 * 
 * SECURITY WARNING: Delete this file immediately after use!
 */

// Security: Only allow if accessed directly
if (basename($_SERVER['PHP_SELF']) !== 'test-image-access.php') {
    die('Direct access only');
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Image Access</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 50px auto; padding: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; margin: 10px 0; }
        .info { color: #004085; padding: 10px; background: #d1ecf1; border: 1px solid #bee5eb; margin: 10px 0; }
        .warning { color: #856404; padding: 10px; background: #fff3cd; border: 1px solid #ffeeba; margin: 10px 0; }
        pre { background: #f4f4f4; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }
        img { max-width: 200px; max-height: 200px; border: 2px solid #ddd; margin: 10px; }
        .image-test { display: inline-block; margin: 10px; padding: 10px; border: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background: #f8f9fa; }
    </style>
</head>
<body>
    <h1>Image Access Test</h1>
    
    <?php
    $basePath = __DIR__ . '/assets/user_images';
    $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 9602;
    $userPath = $basePath . '/' . $userId;
    
    echo '<div class="info">';
    echo '<strong>Testing User ID:</strong> ' . htmlspecialchars($userId) . '<br>';
    echo '<strong>Base Path:</strong> ' . htmlspecialchars($basePath) . '<br>';
    echo '<strong>User Path:</strong> ' . htmlspecialchars($userPath) . '<br>';
    echo '</div>';
    
    // Check if user directory exists
    if (!file_exists($userPath)) {
        echo '<div class="error">❌ User directory does not exist: ' . htmlspecialchars($userPath) . '</div>';
        echo '<div class="info">Available user directories:</div>';
        if (file_exists($basePath) && is_dir($basePath)) {
            $dirs = @scandir($basePath);
            $dirs = array_filter($dirs, function($d) use ($basePath) {
                return $d !== '.' && $d !== '..' && is_dir($basePath . '/' . $d);
            });
            echo '<ul>';
            foreach ($dirs as $dir) {
                echo '<li><a href="?user_id=' . htmlspecialchars($dir) . '">User ID: ' . htmlspecialchars($dir) . '</a></li>';
            }
            echo '</ul>';
        }
        exit;
    }
    
    // Get all image files in user directory
    $files = @scandir($userPath);
    $imageFiles = [];
    
    if ($files) {
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && is_file($userPath . '/' . $file)) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $imageFiles[] = $file;
                }
            }
        }
    }
    
    if (empty($imageFiles)) {
        echo '<div class="warning">⚠️ No image files found in user directory</div>';
    } else {
        echo '<div class="success">✅ Found ' . count($imageFiles) . ' image file(s)</div>';
        
        echo '<h2>Image Files Test</h2>';
        echo '<table>';
        echo '<tr><th>Filename</th><th>File Exists</th><th>Readable</th><th>Size</th><th>Permissions</th><th>URL</th><th>Access Test</th></tr>';
        
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        $baseUrl = rtrim($baseUrl, '/');
        
        foreach ($imageFiles as $file) {
            $filePath = $userPath . '/' . $file;
            $imageUrl = $baseUrl . '/assets/user_images/' . $userId . '/' . $file;
            
            $exists = file_exists($filePath);
            $readable = is_readable($filePath);
            $size = $exists ? filesize($filePath) : 0;
            $perms = $exists ? substr(sprintf('%o', fileperms($filePath)), -4) : 'N/A';
            
            // Test if URL is accessible
            $accessible = false;
            $httpCode = 0;
            if ($exists) {
                $ch = curl_init($imageUrl);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $accessible = ($httpCode === 200);
            }
            
            echo '<tr>';
            echo '<td>' . htmlspecialchars($file) . '</td>';
            echo '<td>' . ($exists ? '✅ Yes' : '❌ No') . '</td>';
            echo '<td>' . ($readable ? '✅ Yes' : '❌ No') . '</td>';
            echo '<td>' . ($size > 0 ? number_format($size / 1024, 2) . ' KB' : 'N/A') . '</td>';
            echo '<td>' . htmlspecialchars($perms) . '</td>';
            echo '<td><a href="' . htmlspecialchars($imageUrl) . '" target="_blank">' . htmlspecialchars($imageUrl) . '</a></td>';
            echo '<td>' . ($accessible ? '<span style="color:green">✅ HTTP ' . $httpCode . '</span>' : '<span style="color:red">❌ HTTP ' . $httpCode . '</span>') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        
        // Display images
        echo '<h2>Image Preview</h2>';
        echo '<div style="display: flex; flex-wrap: wrap;">';
        foreach ($imageFiles as $file) {
            $imageUrl = $baseUrl . '/assets/user_images/' . $userId . '/' . $file;
            echo '<div class="image-test">';
            echo '<strong>' . htmlspecialchars($file) . '</strong><br>';
            echo '<img src="' . htmlspecialchars($imageUrl) . '" alt="' . htmlspecialchars($file) . '" onerror="this.style.border=\'2px solid red\'; this.alt=\'Failed to load\';"><br>';
            echo '<a href="' . htmlspecialchars($imageUrl) . '" target="_blank">Open in new tab</a>';
            echo '</div>';
        }
        echo '</div>';
    }
    
    // Test .htaccess
    echo '<h2>.htaccess Check</h2>';
    $htaccessPath = __DIR__ . '/.htaccess';
    if (file_exists($htaccessPath)) {
        echo '<div class="success">✅ .htaccess file exists</div>';
        echo '<pre>' . htmlspecialchars(file_get_contents($htaccessPath)) . '</pre>';
    } else {
        echo '<div class="error">❌ .htaccess file not found at: ' . htmlspecialchars($htaccessPath) . '</div>';
    }
    
    // Check if assets directory has .htaccess that might block
    $assetsHtaccess = __DIR__ . '/assets/.htaccess';
    if (file_exists($assetsHtaccess)) {
        echo '<div class="warning">⚠️ Found .htaccess in assets directory - this might block image access!</div>';
        echo '<pre>' . htmlspecialchars(file_get_contents($assetsHtaccess)) . '</pre>';
    } else {
        echo '<div class="info">ℹ️ No .htaccess in assets directory (this is good)</div>';
    }
    ?>
    
    <hr>
    <div class="info">
        <strong>⚠️ SECURITY WARNING:</strong><br>
        Please DELETE this file (test-image-access.php) immediately after testing!
    </div>
</body>
</html>
