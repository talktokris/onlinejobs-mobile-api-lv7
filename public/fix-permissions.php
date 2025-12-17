<?php
/**
 * Permission Fixer Script
 * 
 * This script helps fix directory permissions for image uploads.
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to: /public_html/mobile-api/fix-permissions.php
 * 2. Access it via browser: https://onlinejobs.my/mobile-api/fix-permissions.php
 * 3. It will create directories and set permissions automatically
 * 4. DELETE THIS FILE after running it for security!
 * 
 * SECURITY WARNING: Delete this file immediately after use!
 */

// Security: Only allow if accessed directly (not via include)
if (basename($_SERVER['PHP_SELF']) !== 'fix-permissions.php') {
    die('Direct access only');
}

// Optional: Add a simple password protection
$password = 'CHANGE_THIS_PASSWORD'; // Change this before uploading!
if (isset($_GET['pass']) && $_GET['pass'] === $password) {
    // Continue
} else {
    die('Access denied. Add ?pass=YOUR_PASSWORD to the URL.');
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Image Upload Permissions</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; margin: 10px 0; }
        .info { color: #004085; padding: 10px; background: #d1ecf1; border: 1px solid #bee5eb; margin: 10px 0; }
        pre { background: #f4f4f4; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Fix Image Upload Permissions</h1>
    
    <?php
    $basePath = __DIR__ . '/assets/user_images';
    $results = [];
    
    // Step 1: Create base directory if it doesn't exist
    if (!file_exists($basePath)) {
        if (@mkdir($basePath, 0775, true)) {
            $results[] = ['type' => 'success', 'message' => "✅ Created directory: assets/user_images"];
        } else {
            $results[] = ['type' => 'error', 'message' => "❌ Failed to create directory: assets/user_images"];
        }
    } else {
        $results[] = ['type' => 'info', 'message' => "ℹ️ Directory already exists: assets/user_images"];
    }
    
    // Step 2: Set permissions on base directory
    if (file_exists($basePath)) {
        if (@chmod($basePath, 0775)) {
            $results[] = ['type' => 'success', 'message' => "✅ Set permissions (775) on: assets/user_images"];
        } else {
            $results[] = ['type' => 'error', 'message' => "❌ Failed to set permissions on: assets/user_images"];
        }
        
        // Check if writable
        if (is_writable($basePath)) {
            $results[] = ['type' => 'success', 'message' => "✅ Directory is writable: assets/user_images"];
        } else {
            $results[] = ['type' => 'error', 'message' => "❌ Directory is NOT writable: assets/user_images"];
        }
    }
    
    // Step 3: Check and fix existing user directories
    if (file_exists($basePath) && is_dir($basePath)) {
        $userDirs = @scandir($basePath);
        if ($userDirs) {
            $userDirs = array_filter($userDirs, function($dir) use ($basePath) {
                return $dir !== '.' && $dir !== '..' && is_dir($basePath . '/' . $dir);
            });
            
            if (count($userDirs) > 0) {
                $results[] = ['type' => 'info', 'message' => "ℹ️ Found " . count($userDirs) . " user directory(ies)"];
                
                foreach ($userDirs as $userDir) {
                    $userPath = $basePath . '/' . $userDir;
                    if (@chmod($userPath, 0775)) {
                        $results[] = ['type' => 'success', 'message' => "✅ Fixed permissions for user directory: $userDir"];
                    } else {
                        $results[] = ['type' => 'error', 'message' => "❌ Failed to fix permissions for: $userDir"];
                    }
                    
                    // Fix file permissions in user directory
                    $files = @scandir($userPath);
                    if ($files) {
                        $imageFiles = array_filter($files, function($file) use ($userPath) {
                            return $file !== '.' && $file !== '..' && is_file($userPath . '/' . $file);
                        });
                        
                        foreach ($imageFiles as $file) {
                            @chmod($userPath . '/' . $file, 0644);
                        }
                        if (count($imageFiles) > 0) {
                            $results[] = ['type' => 'info', 'message' => "ℹ️ Fixed permissions for " . count($imageFiles) . " file(s) in $userDir"];
                        }
                    }
                }
            }
        }
    }
    
    // Display results
    foreach ($results as $result) {
        echo '<div class="' . $result['type'] . '">' . htmlspecialchars($result['message']) . '</div>';
    }
    
    // Display current permissions
    echo '<h2>Current Directory Status</h2>';
    echo '<pre>';
    echo "Base Path: " . $basePath . "\n";
    echo "Exists: " . (file_exists($basePath) ? 'Yes' : 'No') . "\n";
    if (file_exists($basePath)) {
        echo "Readable: " . (is_readable($basePath) ? 'Yes' : 'No') . "\n";
        echo "Writable: " . (is_writable($basePath) ? 'Yes' : 'No') . "\n";
        echo "Permissions: " . substr(sprintf('%o', fileperms($basePath)), -4) . "\n";
    }
    echo '</pre>';
    
    // Check if .htaccess might be blocking access
    $htaccessPath = __DIR__ . '/.htaccess';
    if (file_exists($htaccessPath)) {
        echo '<h2>.htaccess Check</h2>';
        echo '<div class="info">ℹ️ .htaccess file exists. Make sure it allows access to image files.</div>';
        echo '<pre>';
        echo file_get_contents($htaccessPath);
        echo '</pre>';
    }
    ?>
    
    <hr>
    <div class="info">
        <strong>⚠️ SECURITY WARNING:</strong><br>
        Please DELETE this file (fix-permissions.php) immediately after running it!
    </div>
    
    <h2>Next Steps:</h2>
    <ol>
        <li>If all checks passed (green), try uploading an image again</li>
        <li>Check Laravel logs: <code>storage/logs/laravel.log</code> for any errors</li>
        <li>Verify the image URL is accessible: <code>https://onlinejobs.my/mobile-api/assets/user_images/{user_id}/{image_name}</code></li>
        <li><strong>DELETE THIS FILE</strong> for security!</li>
    </ol>
</body>
</html>
