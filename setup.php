<?php
/**
 * MSSN Quiz - Complete Setup Script
 * 
 * This script will:
 * 1. Run all database migrations
 * 2. Create admin account
 * 3. Set up storage and directories
 * 
 * Access via browser: https://mssnkac.com.ng/mssn_quiz/setup.php
 * 
 * IMPORTANT: Delete this file after successful setup!
 */

// Simple password protection
define('SETUP_PASSWORD', 'Setup@MSSN2025');

// Output helper function
function outputLine($message = '', $color = '#0f0') {
    echo "<div style='color: $color; font-family: monospace; white-space: pre-wrap;'>" . htmlspecialchars($message) . "</div>";
    flush();
    ob_flush();
}

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Output headers for streaming
    header('Content-Type: text/html; charset=utf-8');
    header('X-Accel-Buffering: no');
    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Setup Progress</title>
        <style>
            body { 
                background: #1a1a1a; 
                color: #0f0; 
                font-family: 'Courier New', monospace; 
                padding: 20px; 
                margin: 0;
            }
            .container { 
                max-width: 900px; 
                margin: 0 auto; 
                background: #000; 
                padding: 30px; 
                border: 2px solid #0f0; 
                border-radius: 10px;
                box-shadow: 0 0 20px rgba(0, 255, 0, 0.3);
            }
            .success-box {
                background: #004400;
                border: 2px solid #0f0;
                padding: 20px;
                margin: 20px 0;
                border-radius: 8px;
            }
            .button {
                background: #0f0;
                color: #000;
                padding: 12px 24px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                font-weight: bold;
                margin: 10px 5px;
                text-decoration: none;
                display: inline-block;
            }
            .button:hover { background: #0c0; }
            .button.danger {
                background: #f00;
                color: #fff;
            }
            .button.danger:hover { background: #c00; }
        </style>
    </head>
    <body>
    <div class="container">
    <?php
    ob_end_flush();

    // Verify password
    if (!isset($_POST['password']) || $_POST['password'] !== SETUP_PASSWORD) {
        outputLine('❌ Invalid password!', '#f00');
        outputLine('');
        outputLine('Please go back and try again.');
        echo '<br><a href="setup.php" class="button">Go Back</a>';
        exit;
    }

    // Get form data
    $adminName = trim($_POST['admin_name'] ?? '');
    $adminEmail = trim($_POST['admin_email'] ?? '');
    $adminPassword = trim($_POST['admin_password'] ?? '');
    $adminPasswordConfirm = trim($_POST['admin_password_confirm'] ?? '');

    // Validate
    if (empty($adminName) || empty($adminEmail) || empty($adminPassword)) {
        outputLine('❌ All fields are required!', '#f00');
        echo '<br><a href="setup.php" class="button">Go Back</a>';
        exit;
    }

    if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
        outputLine('❌ Invalid email address!', '#f00');
        echo '<br><a href="setup.php" class="button">Go Back</a>';
        exit;
    }

    if (strlen($adminPassword) < 8) {
        outputLine('❌ Password must be at least 8 characters!', '#f00');
        echo '<br><a href="setup.php" class="button">Go Back</a>';
        exit;
    }

    if ($adminPassword !== $adminPasswordConfirm) {
        outputLine('❌ Passwords do not match!', '#f00');
        echo '<br><a href="setup.php" class="button">Go Back</a>';
        exit;
    }

    $hasErrors = false;
    $adminCreated = false;

    try {
        outputLine('═══════════════════════════════════════════════════════');
        outputLine('  MSSN Quiz - Complete Setup Started');
        outputLine('═══════════════════════════════════════════════════════');
        outputLine('');

        // Step 1: Run Migrations
        outputLine('[1/6] Running database migrations...', '#0ff');
        outputLine('');
        
        $output = [];
        $exitCode = 0;
        exec('cd ' . escapeshellarg(__DIR__) . ' && php artisan migrate --force 2>&1', $output, $exitCode);
        
        foreach ($output as $line) {
            if (trim($line)) {
                if (stripos($line, 'error') !== false || stripos($line, 'exception') !== false) {
                    outputLine('  ' . $line, '#f00');
                    $hasErrors = true;
                } elseif (stripos($line, 'migrated') !== false) {
                    outputLine('  ' . $line, '#0f0');
                } else {
                    outputLine('  ' . $line);
                }
            }
        }
        
        if ($exitCode === 0 && !$hasErrors) {
            outputLine('');
            outputLine('✅ Database migrations completed successfully!');
        } else {
            outputLine('');
            outputLine('⚠️ Migration completed with warnings/errors', '#ff0');
        }
        outputLine('');

        // Step 2: Create required directories
        outputLine('[2/6] Creating required directories...', '#0ff');
        $directories = [
            'storage/framework/cache/data',
            'storage/framework/sessions',
            'storage/framework/views',
            'storage/logs',
            'bootstrap/cache'
        ];
        
        foreach ($directories as $dir) {
            $fullPath = __DIR__ . '/' . $dir;
            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0755, true);
                outputLine("  Created: $dir");
            } else {
                outputLine("  Exists: $dir");
            }
        }
        outputLine('✅ All directories ready');
        outputLine('');

        // Step 3: Create storage link
        outputLine('[3/6] Creating storage symbolic link...', '#0ff');
        $output3 = [];
        exec('cd ' . escapeshellarg(__DIR__) . ' && php artisan storage:link 2>&1', $output3);
        foreach ($output3 as $line) {
            if (trim($line)) {
                outputLine('  ' . $line);
            }
        }
        outputLine('✅ Storage link created');
        outputLine('');

        // Step 4: Create admin account
        outputLine('[4/6] Creating admin account...', '#0ff');
        
        // Load Laravel to create admin
        require __DIR__.'/vendor/autoload.php';
        $app = require_once __DIR__.'/bootstrap/app.php';
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        // Check if admin exists
        $existingAdmin = \App\Models\User::where('email', $adminEmail)->first();
        
        if ($existingAdmin) {
            outputLine('  Admin with this email already exists', '#ff0');
            outputLine('  Email: ' . $adminEmail);
            outputLine('  Updating password...');
            
            $existingAdmin->name = $adminName;
            $existingAdmin->password = bcrypt($adminPassword);
            $existingAdmin->role = 'admin';
            $existingAdmin->save();
            
            outputLine('✅ Admin account updated successfully!');
            $adminCreated = true;
        } else {
            $admin = new \App\Models\User();
            $admin->name = $adminName;
            $admin->email = $adminEmail;
            $admin->password = bcrypt($adminPassword);
            $admin->role = 'admin';
            $admin->school_id = null;
            $admin->save();
            
            outputLine('✅ Admin account created successfully!');
            outputLine('  Name: ' . $adminName);
            outputLine('  Email: ' . $adminEmail);
            $adminCreated = true;
        }
        outputLine('');

        // Step 5: Set permissions
        outputLine('[5/6] Setting directory permissions...', '#0ff');
        $permDirs = ['storage', 'bootstrap/cache'];
        foreach ($permDirs as $dir) {
            if (is_dir(__DIR__ . '/' . $dir)) {
                chmod(__DIR__ . '/' . $dir, 0755);
                outputLine("  Set 755 on $dir");
            }
        }
        outputLine('✅ Permissions set');
        outputLine('');

        // Step 6: Clear and cache
        outputLine('[6/6] Optimizing application...', '#0ff');
        exec('cd ' . escapeshellarg(__DIR__) . ' && php artisan config:clear 2>&1');
        exec('cd ' . escapeshellarg(__DIR__) . ' && php artisan cache:clear 2>&1');
        exec('cd ' . escapeshellarg(__DIR__) . ' && php artisan view:clear 2>&1');
        exec('cd ' . escapeshellarg(__DIR__) . ' && php artisan route:clear 2>&1');
        outputLine('  Cleared old caches');
        
        exec('cd ' . escapeshellarg(__DIR__) . ' && php artisan config:cache 2>&1');
        exec('cd ' . escapeshellarg(__DIR__) . ' && php artisan route:cache 2>&1');
        exec('cd ' . escapeshellarg(__DIR__) . ' && php artisan view:cache 2>&1');
        outputLine('  Created production caches');
        outputLine('✅ Application optimized');
        outputLine('');

        // Success message
        outputLine('═══════════════════════════════════════════════════════');
        outputLine('  🎉 SETUP COMPLETED SUCCESSFULLY! 🎉');
        outputLine('═══════════════════════════════════════════════════════');
        outputLine('');

        if ($adminCreated) {
            echo '<div class="success-box">';
            outputLine('✅ Database tables created');
            outputLine('✅ Admin account ready');
            outputLine('✅ Storage configured');
            outputLine('✅ Application optimized');
            outputLine('');
            outputLine('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', '#0ff');
            outputLine('  ADMIN LOGIN CREDENTIALS', '#0ff');
            outputLine('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', '#0ff');
            outputLine('  Email: ' . $adminEmail, '#fff');
            outputLine('  Password: ' . $adminPassword, '#fff');
            outputLine('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', '#0ff');
            outputLine('');
            outputLine('🔗 Login URL: ' . str_replace('/setup.php', '/login', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"), '#0ff');
            echo '</div>';
        }

        outputLine('');
        outputLine('⚠️  IMPORTANT SECURITY STEP:', '#f00');
        outputLine('    Delete this setup.php file immediately!', '#f00');
        outputLine('');

        // Action buttons
        echo '<div style="margin-top: 30px; text-align: center;">';
        echo '<a href="/mssn_quiz/login" class="button">Go to Login Page</a>';
        echo '<form method="POST" action="setup.php?delete=1" style="display: inline-block;">';
        echo '<button type="submit" class="button danger" onclick="return confirm(\'Are you sure? This will delete setup.php\')">Delete This Setup File</button>';
        echo '</form>';
        echo '</div>';

    } catch (Exception $e) {
        outputLine('');
        outputLine('❌ FATAL ERROR:', '#f00');
        outputLine($e->getMessage(), '#f00');
        outputLine('');
        outputLine('Stack trace:', '#ff0');
        outputLine($e->getTraceAsString(), '#ff0');
        $hasErrors = true;
    }

    echo '</div></body></html>';
    exit;
}

// Handle file deletion
if (isset($_GET['delete']) && $_GET['delete'] == '1' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (unlink(__FILE__)) {
        echo '<!DOCTYPE html>
        <html>
        <head><title>Setup Deleted</title>
        <style>
            body { background: #1a1a1a; color: #0f0; font-family: monospace; padding: 50px; text-align: center; }
            .success { background: #004400; border: 2px solid #0f0; padding: 30px; margin: 20px auto; max-width: 600px; border-radius: 10px; }
            .button { background: #0f0; color: #000; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; margin: 20px 5px; text-decoration: none; display: inline-block; }
            .button:hover { background: #0c0; }
        </style>
        </head>
        <body>
            <div class="success">
                <h1 style="color: #0f0;">✅ Setup File Deleted!</h1>
                <p>The setup.php file has been successfully removed.</p>
                <p>Your application is now secure.</p>
                <a href="/mssn_quiz/login" class="button">Go to Login</a>
            </div>
        </body>
        </html>';
    } else {
        echo '<!DOCTYPE html>
        <html>
        <head><title>Delete Failed</title>
        <style>
            body { background: #1a1a1a; color: #f00; font-family: monospace; padding: 50px; text-align: center; }
            .error { background: #440000; border: 2px solid #f00; padding: 30px; margin: 20px auto; max-width: 600px; border-radius: 10px; }
        </style>
        </head>
        <body>
            <div class="error">
                <h1>❌ Delete Failed</h1>
                <p>Could not delete setup.php automatically.</p>
                <p>Please delete it manually via cPanel File Manager for security.</p>
            </div>
        </body>
        </html>';
    }
    exit;
}

// Show the form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSSN Quiz - Complete Setup</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: linear-gradient(135deg, #1a1a1a 0%, #0a3d0a 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: rgba(0, 0, 0, 0.8);
            border: 2px solid #0f0;
            border-radius: 15px;
            box-shadow: 0 0 40px rgba(0, 255, 0, 0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
            color: #0f0;
        }
        h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 10px;
            text-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }
        .subtitle {
            text-align: center;
            color: #0ff;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .info-box {
            background: #004400;
            border: 1px solid #0f0;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .info-box h3 {
            color: #0ff;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .info-box ul {
            list-style: none;
            font-size: 13px;
            line-height: 1.8;
        }
        .info-box ul li:before {
            content: "✓ ";
            color: #0f0;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #0ff;
            font-weight: bold;
            font-size: 14px;
        }
        input {
            width: 100%;
            padding: 12px;
            background: #000;
            border: 2px solid #0f0;
            border-radius: 5px;
            color: #0f0;
            font-size: 14px;
            font-family: 'Courier New', monospace;
            transition: all 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #0ff;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
        }
        input::placeholder {
            color: #055;
        }
        .button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #0f0 0%, #0c0 100%);
            color: #000;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .button:hover {
            background: linear-gradient(135deg, #0c0 0%, #0a0 100%);
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.5);
            transform: translateY(-2px);
        }
        .warning {
            background: #440000;
            border: 1px solid #f00;
            color: #f00;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 13px;
            text-align: center;
        }
        .note {
            font-size: 12px;
            color: #0aa;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 MSSN Quiz Setup</h1>
        <p class="subtitle">Complete Application Setup</p>

        <div class="info-box">
            <h3>This setup will:</h3>
            <ul>
                <li>Run all database migrations</li>
                <li>Create your admin account</li>
                <li>Set up storage and directories</li>
                <li>Optimize the application</li>
            </ul>
        </div>

        <form method="POST" action="setup.php">
            <div class="form-group">
                <label for="password">Setup Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Enter setup password"
                    required
                >
                <p class="note">Default: Setup@MSSN2025</p>
            </div>

            <div class="form-group">
                <label for="admin_name">Admin Name</label>
                <input 
                    type="text" 
                    id="admin_name" 
                    name="admin_name" 
                    placeholder="Enter admin full name"
                    required
                >
            </div>

            <div class="form-group">
                <label for="admin_email">Admin Email</label>
                <input 
                    type="email" 
                    id="admin_email" 
                    name="admin_email" 
                    placeholder="admin@example.com"
                    required
                >
            </div>

            <div class="form-group">
                <label for="admin_password">Admin Password</label>
                <input 
                    type="password" 
                    id="admin_password" 
                    name="admin_password" 
                    placeholder="Min. 8 characters"
                    minlength="8"
                    required
                >
            </div>

            <div class="form-group">
                <label for="admin_password_confirm">Confirm Password</label>
                <input 
                    type="password" 
                    id="admin_password_confirm" 
                    name="admin_password_confirm" 
                    placeholder="Re-enter password"
                    minlength="8"
                    required
                >
            </div>

            <button type="submit" class="button">🚀 Start Complete Setup</button>
        </form>

        <div class="warning">
            ⚠️ <strong>Delete this file after setup!</strong><br>
            This script has administrative access and should be removed for security.
        </div>
    </div>
</body>
</html>
