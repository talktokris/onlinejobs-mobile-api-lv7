<?php
/**
 * Check Specific Image File
 * 
 * This script checks if a specific image file exists and is accessible.
 * 
 * USAGE:
 * https://onlinejobs.my/mobile-api/check-image.php?user_id=9602&image=1765958186_6942622a41ca4.png
 * 
 * SECURITY: Delete this file after testing!
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Check Image File</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; margin: 10px 0; }
        .info { color: #004085; padding: 10px; background: #d1ecf1; border: 1px solid #bee5eb; margin: 10px 0; }
        pre { background: #f4f4f4; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }
        img { max-width: 300px; max-height: 300px; border: 2px solid #ddd; margin: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background: #f8f9fa; }
    </style>
</head>
<body>
    <h1>Check Image File</h1>
    
    <?php
    $userId = isset($_GET['user_id']) ? $_GET['user_id'] : '9602';
    $imageName = isset($_GET['image']) ? $_GET['image'] : '1765958186_6942622a41ca4.png';
    
    $basePath = __DIR__ . '/assets/user_images';
    $userPath = $basePath . '/' . $userId;
    $filePath = $userPath . '/' . $imageName;
    
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    $baseUrl = rtrim($baseUrl, '/');
    $imageUrl = $baseUrl . '/assets/user_images/' . $userId . '/' . $imageName;
    
    echo '<div class="info">';
    echo '<strong>Checking Image:</strong> ' . htmlspecialchars($imageName) . '<br>';
    echo '<strong>User ID:</strong> ' . htmlspecialchars($userId) . '<br>';
    echo '</div>';
    
    echo '<h2>File System Check</h2>';
    echo '<table>';
    echo '<tr><th>Check</th><th>Status</th><th>Details</th></tr>';
    
    // Check base directory
    $baseExists = file_exists($basePath);
    echo '<tr><td>Base Directory Exists</td><td>' . ($baseExists ? '✅ Yes' : '❌ No') . '</td><td>' . htmlspecialchars($basePath) . '</td></tr>';
    
    // Check user directory
    $userExists = file_exists($userPath);
    echo '<tr><td>User Directory Exists</td><td>' . ($userExists ? '✅ Yes' : '❌ No') . '</td><td>' . htmlspecialchars($userPath) . '</td></tr>';
    
    // Check file exists
    $fileExists = file_exists($filePath);
    echo '<tr><td>Image File Exists</td><td>' . ($fileExists ? '✅ Yes' : '❌ No') . '</td><td>' . htmlspecialchars($filePath) . '</td></tr>';
    
    if ($fileExists) {
        $fileSize = filesize($filePath);
        $filePerms = substr(sprintf('%o', fileperms($filePath)), -4);
        $isReadable = is_readable($filePath);
        $isWritable = is_writable($filePath);
        $fileType = mime_content_type($filePath);
        
        echo '<tr><td>File Size</td><td>✅ ' . number_format($fileSize / 1024, 2) . ' KB</td><td>' . $fileSize . ' bytes</td></tr>';
        echo '<tr><td>File Permissions</td><td>' . ($filePerms === '0644' || $filePerms === '0664' ? '✅' : '⚠️') . ' ' . $filePerms . '</td><td>Should be 0644 or 0664</td></tr>';
        echo '<tr><td>File Readable</td><td>' . ($isReadable ? '✅ Yes' : '❌ No') . '</td><td></td></tr>';
        echo '<tr><td>File Writable</td><td>' . ($isWritable ? '✅ Yes' : '⚠️ No (normal for images)' : '') . '</td><td></td></tr>';
        echo '<tr><td>File Type (MIME)</td><td>✅ ' . htmlspecialchars($fileType) . '</td><td></td></tr>';
        
        // Check directory permissions
        $dirPerms = substr(sprintf('%o', fileperms($userPath)), -4);
        $dirReadable = is_readable($userPath);
        echo '<tr><td>Directory Permissions</td><td>' . ($dirPerms === '0775' || $dirPerms === '0755' ? '✅' : '⚠️') . ' ' . $dirPerms . '</td><td>Should be 0775</td></tr>';
        echo '<tr><td>Directory Readable</td><td>' . ($dirReadable ? '✅ Yes' : '❌ No') . '</td><td></td></tr>';
    }
    
    echo '</table>';
    
    // HTTP Access Test
    echo '<h2>HTTP Access Test</h2>';
    echo '<div class="info">';
    echo '<strong>Image URL:</strong> <a href="' . htmlspecialchars($imageUrl) . '" target="_blank">' . htmlspecialchars($imageUrl) . '</a><br>';
    echo '</div>';
    
    if ($fileExists) {
        // Test HTTP access
        $ch = curl_init($imageUrl);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $error = curl_error($ch);
        curl_close($ch);
        
        echo '<table>';
        echo '<tr><th>Test</th><th>Result</th></tr>';
        echo '<tr><td>HTTP Status Code</td><td>' . ($httpCode === 200 ? '✅ 200 OK' : '❌ ' . $httpCode) . '</td></tr>';
        echo '<tr><td>Content Type</td><td>' . ($contentType ? htmlspecialchars($contentType) : 'N/A') . '</td></tr>';
        if ($error) {
            echo '<tr><td>CURL Error</td><td class="error">' . htmlspecialchars($error) . '</td></tr>';
        }
        echo '</table>';
        
        // Display image preview
        if ($httpCode === 200) {
            echo '<h2>Image Preview</h2>';
            echo '<img src="' . htmlspecialchars($imageUrl) . '" alt="Image Preview" onerror="this.style.border=\'3px solid red\'; this.alt=\'Failed to load image\';"><br>';
            echo '<a href="' . htmlspecialchars($imageUrl) . '" target="_blank">Open image in new tab</a>';
        } else {
            echo '<div class="error">❌ Image is not accessible via HTTP. Status code: ' . $httpCode . '</div>';
            echo '<div class="info">Possible causes:</div>';
            echo '<ul>';
            echo '<li>.htaccess file is blocking image access</li>';
            echo '<li>File permissions are incorrect</li>';
            echo '<li>Web server configuration issue</li>';
            echo '<li>File path mismatch</li>';
            echo '</ul>';
        }
    } else {
        echo '<div class="error">❌ Image file does not exist on the server!</div>';
        echo '<div class="info">The file should be at: <code>' . htmlspecialchars($filePath) . '</code></div>';
        echo '<div class="info">Possible causes:</div>';
        echo '<ul>';
        echo '<li>Image upload failed silently</li>';
        echo '<li>File was saved to a different location</li>';
        echo '<li>File was deleted after upload</li>';
        echo '<li>Path mismatch between save location and access location</li>';
        echo '</ul>';
        
        // List files in user directory
        if ($userExists && is_dir($userPath)) {
            $files = @scandir($userPath);
            $imageFiles = array_filter($files, function($f) use ($userPath) {
                return $f !== '.' && $f !== '..' && is_file($userPath . '/' . $f);
            });
            
            if (count($imageFiles) > 0) {
                echo '<h3>Files in User Directory:</h3>';
                echo '<ul>';
                foreach ($imageFiles as $file) {
                    echo '<li><a href="?user_id=' . htmlspecialchars($userId) . '&image=' . urlencode($file) . '">' . htmlspecialchars($file) . '</a></li>';
                }
                echo '</ul>';
            }
        }
    }
    
    // Check .htaccess
    echo '<h2>.htaccess Check</h2>';
    $htaccessPath = __DIR__ . '/.htaccess';
    if (file_exists($htaccessPath)) {
        $htaccessContent = file_get_contents($htaccessPath);
        echo '<div class="success">✅ .htaccess file exists</div>';
        
        // Check if it allows image files
        if (preg_match('/\.(jpg|jpeg|png|gif|webp|svg|ico)/i', $htaccessContent)) {
            echo '<div class="success">✅ .htaccess contains image file rules</div>';
        } else {
            echo '<div class="error">❌ .htaccess does NOT contain explicit image file rules</div>';
        }
        
        echo '<pre>' . htmlspecialchars($htaccessContent) . '</pre>';
    } else {
        echo '<div class="error">❌ .htaccess file not found</div>';
    }
    ?>
    
    <hr>
    <div class="info">
        <strong>⚠️ SECURITY WARNING:</strong><br>
        Please DELETE this file (check-image.php) immediately after testing!
    </div>
</body>
</html>
