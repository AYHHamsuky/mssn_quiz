<?php
/**
 * Upload Verification Script
 * 
 * This script checks if all required files are uploaded correctly.
 * Access: https://mssnkac.com.ng/mssn_quiz/verify.php
 * 
 * Delete this file after verification!
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Verification</title>
    <style>
        body { 
            background: #1a1a1a; 
            color: #0f0; 
            font-family: 'Courier New', monospace; 
            padding: 20px; 
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: #000; 
            padding: 30px; 
            border: 2px solid #0f0; 
            border-radius: 10px;
        }
        h1 { 
            text-align: center; 
            color: #0ff; 
        }
        .check { 
            margin: 15px 0; 
            padding: 10px; 
            border-left: 4px solid #0f0; 
            background: #001100;
        }
        .success { 
            color: #0f0; 
        }
        .error { 
            color: #f00; 
            border-left-color: #f00;
            background: #110000;
        }
        .warning { 
            color: #ff0; 
            border-left-color: #ff0;
            background: #111100;
        }
        .info {
            background: #004400;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .status {
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🔍 Upload Verification</h1>
    
    <?php
    $errors = 0;
    $warnings = 0;
    
    // Check 1: vendor/ folder
    echo '<div class="check ' . (is_dir(__DIR__ . '/vendor') ? 'success' : 'error') . '">';
    echo is_dir(__DIR__ . '/vendor') ? '✅' : '❌';
    echo ' <strong>vendor/</strong> folder: ';
    if (is_dir(__DIR__ . '/vendor')) {
        $size = 0;
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/vendor')) as $file) {
            $size += $file->getSize();
        }
        $sizeMB = round($size / 1024 / 1024, 2);
        echo "EXISTS ($sizeMB MB)";
        if ($sizeMB < 100) {
            echo ' <span class="warning">⚠️ Too small! Should be ~165 MB</span>';
            $warnings++;
        }
    } else {
        echo 'MISSING! You must upload vendor/ folder.';
        $errors++;
    }
    echo '</div>';
    
    // Check 2: artisan file
    echo '<div class="check ' . (file_exists(__DIR__ . '/artisan') ? 'success' : 'error') . '">';
    echo file_exists(__DIR__ . '/artisan') ? '✅' : '❌';
    echo ' <strong>artisan</strong> file: ';
    echo file_exists(__DIR__ . '/artisan') ? 'EXISTS' : 'MISSING!';
    if (!file_exists(__DIR__ . '/artisan')) $errors++;
    echo '</div>';
    
    // Check 3: app/ folder
    echo '<div class="check ' . (is_dir(__DIR__ . '/app') ? 'success' : 'error') . '">';
    echo is_dir(__DIR__ . '/app') ? '✅' : '❌';
    echo ' <strong>app/</strong> folder: ';
    echo is_dir(__DIR__ . '/app') ? 'EXISTS' : 'MISSING!';
    if (!is_dir(__DIR__ . '/app')) $errors++;
    echo '</div>';
    
    // Check 4: .env file
    echo '<div class="check ' . (file_exists(__DIR__ . '/.env') ? 'success' : 'error') . '">';
    echo file_exists(__DIR__ . '/.env') ? '✅' : '❌';
    echo ' <strong>.env</strong> file: ';
    if (file_exists(__DIR__ . '/.env')) {
        echo 'EXISTS';
        // Check for APP_KEY
        $env = file_get_contents(__DIR__ . '/.env');
        if (strpos($env, 'APP_KEY=base64:') !== false) {
            echo ' <span class="success">(APP_KEY configured)</span>';
        } else {
            echo ' <span class="warning">⚠️ APP_KEY not set!</span>';
            $warnings++;
        }
    } else {
        echo 'MISSING! Rename .env.production to .env';
        $errors++;
    }
    echo '</div>';
    
    // Check 5: storage/ folder
    echo '<div class="check ' . (is_dir(__DIR__ . '/storage') ? 'success' : 'error') . '">';
    echo is_dir(__DIR__ . '/storage') ? '✅' : '❌';
    echo ' <strong>storage/</strong> folder: ';
    if (is_dir(__DIR__ . '/storage')) {
        echo 'EXISTS';
        // Check permissions
        $perms = substr(sprintf('%o', fileperms(__DIR__ . '/storage')), -3);
        if ($perms >= '755') {
            echo " <span class=\"success\">(Permissions: $perms)</span>";
        } else {
            echo " <span class=\"warning\">⚠️ Permissions: $perms (should be 755)</span>";
            $warnings++;
        }
    } else {
        echo 'MISSING!';
        $errors++;
    }
    echo '</div>';
    
    // Check 6: bootstrap/cache folder
    echo '<div class="check ' . (is_dir(__DIR__ . '/bootstrap/cache') ? 'success' : 'error') . '">';
    echo is_dir(__DIR__ . '/bootstrap/cache') ? '✅' : '❌';
    echo ' <strong>bootstrap/cache/</strong> folder: ';
    if (is_dir(__DIR__ . '/bootstrap/cache')) {
        echo 'EXISTS';
        $perms = substr(sprintf('%o', fileperms(__DIR__ . '/bootstrap/cache')), -3);
        if ($perms >= '755') {
            echo " <span class=\"success\">(Permissions: $perms)</span>";
        } else {
            echo " <span class=\"warning\">⚠️ Permissions: $perms (should be 755)</span>";
            $warnings++;
        }
    } else {
        echo 'MISSING!';
        $errors++;
    }
    echo '</div>';
    
    // Check 7: public/ folder
    echo '<div class="check ' . (is_dir(__DIR__ . '/public') ? 'success' : 'error') . '">';
    echo is_dir(__DIR__ . '/public') ? '✅' : '❌';
    echo ' <strong>public/</strong> folder: ';
    echo is_dir(__DIR__ . '/public') ? 'EXISTS' : 'MISSING!';
    if (!is_dir(__DIR__ . '/public')) $errors++;
    echo '</div>';
    
    // Check 8: composer.json
    echo '<div class="check ' . (file_exists(__DIR__ . '/composer.json') ? 'success' : 'error') . '">';
    echo file_exists(__DIR__ . '/composer.json') ? '✅' : '❌';
    echo ' <strong>composer.json</strong> file: ';
    echo file_exists(__DIR__ . '/composer.json') ? 'EXISTS' : 'MISSING!';
    if (!file_exists(__DIR__ . '/composer.json')) $errors++;
    echo '</div>';
    
    // Check 9: Database connection
    echo '<div class="check">';
    if (file_exists(__DIR__ . '/.env')) {
        $env = parse_ini_file(__DIR__ . '/.env');
        $dbHost = $env['DB_HOST'] ?? 'localhost';
        $dbName = $env['DB_DATABASE'] ?? '';
        $dbUser = $env['DB_USERNAME'] ?? '';
        $dbPass = $env['DB_PASSWORD'] ?? '';
        
        try {
            $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
            echo '✅ <strong>Database Connection:</strong> <span class="success">SUCCESS!</span>';
            echo '<br>   Connected to: ' . $dbName;
        } catch (PDOException $e) {
            echo '❌ <strong>Database Connection:</strong> <span class="error">FAILED!</span>';
            echo '<br>   Error: ' . htmlspecialchars($e->getMessage());
            $errors++;
        }
    } else {
        echo '⚠️ <strong>Database Connection:</strong> <span class="warning">Cannot test (.env missing)</span>';
        $warnings++;
    }
    echo '</div>';
    
    // Final status
    echo '<div class="info">';
    echo '<div class="status">';
    if ($errors === 0 && $warnings === 0) {
        echo '<span class="success">✅ ALL CHECKS PASSED!</span>';
        echo '<p>You can now run setup.php:</p>';
        echo '<p><a href="setup.php" style="color: #0ff;">→ Go to setup.php</a></p>';
    } elseif ($errors === 0) {
        echo '<span class="warning">⚠️ WARNINGS DETECTED</span>';
        echo '<p>You have ' . $warnings . ' warning(s). Fix them before proceeding.</p>';
    } else {
        echo '<span class="error">❌ ERRORS DETECTED</span>';
        echo '<p>You have ' . $errors . ' error(s) and ' . $warnings . ' warning(s).</p>';
        echo '<p><strong>You must upload the ENTIRE Laravel project!</strong></p>';
        echo '<p>See CPANEL_UPLOAD_CHECKLIST.md for instructions.</p>';
    }
    echo '</div>';
    echo '</div>';
    
    // Instructions
    echo '<div class="info">';
    echo '<h3>What to do next:</h3>';
    if ($errors > 0) {
        echo '<ol>';
        echo '<li>Go back to your local computer</li>';
        echo '<li>Run: <code>prepare_for_upload.bat</code></li>';
        echo '<li>Create ZIP of ENTIRE project (including vendor/)</li>';
        echo '<li>Upload ZIP to cPanel</li>';
        echo '<li>Extract to public_html/quiz/</li>';
        echo '<li>Refresh this page to verify</li>';
        echo '</ol>';
    } else {
        echo '<ol>';
        if ($warnings > 0) {
            echo '<li>Fix the warnings listed above</li>';
        }
        echo '<li>Run <a href="setup.php" style="color: #0ff;">setup.php</a></li>';
        echo '<li>Delete both verify.php and setup.php after success</li>';
        echo '</ol>';
    }
    echo '</div>';
    
    // File info
    echo '<div class="info">';
    echo '<h3>Server Information:</h3>';
    echo '<p>PHP Version: ' . phpversion() . '</p>';
    echo '<p>Current Directory: ' . __DIR__ . '</p>';
    echo '<p>Document Root: ' . $_SERVER['DOCUMENT_ROOT'] . '</p>';
    echo '</div>';
    ?>
    
    <div class="info" style="background: #440000; border: 1px solid #f00;">
        <strong>⚠️ SECURITY WARNING:</strong>
        <p>Delete this verify.php file after you've confirmed everything is uploaded correctly!</p>
    </div>
</div>
</body>
</html>
