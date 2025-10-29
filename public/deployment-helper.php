<?php
/**
 * DEPLOYMENT HELPER
 * 
 * This file helps run artisan commands via web browser
 * when you don't have SSH access.
 * 
 * IMPORTANT: DELETE THIS FILE AFTER DEPLOYMENT!
 * 
 * Access: https://scholarsquiver.com/deployment-helper.php
 */

// Security: Only allow in production environment
// Remove this check if you need to test locally
if ($_SERVER['HTTP_HOST'] !== 'scholarsquiver.com' && 
    $_SERVER['HTTP_HOST'] !== 'www.scholarsquiver.com') {
    die('This file can only be accessed on the production server.');
}

// Optional: Add password protection
$DEPLOYMENT_PASSWORD = 'change_this_password'; // Change this!
if (!isset($_GET['password']) || $_GET['password'] !== $DEPLOYMENT_PASSWORD) {
    die('Invalid password. Add ?password=your_password to the URL.');
}

// Load Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deployment Helper</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: #1f2937;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 { margin-bottom: 10px; }
        .warning {
            background: #fee;
            border-left: 4px solid #f00;
            padding: 15px;
            margin: 20px;
            border-radius: 4px;
        }
        .content { padding: 30px; }
        .command-group {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }
        .command-group h3 {
            color: #1f2937;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            text-decoration: none;
            margin: 5px;
        }
        .btn:hover { background: #5568d3; transform: translateY(-2px); }
        .btn-danger { background: #dc2626; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-success { background: #059669; }
        .btn-success:hover { background: #047857; }
        .output {
            background: #1f2937;
            color: #10b981;
            padding: 20px;
            border-radius: 6px;
            margin-top: 15px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
        }
        .success { color: #10b981; }
        .error { color: #ef4444; }
        .info { color: #3b82f6; }
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
        .status.done { background: #d1fae5; color: #065f46; }
        .status.pending { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Deployment Helper</h1>
            <p>Run artisan commands without SSH access</p>
        </div>

        <div class="warning">
            <strong>⚠️ SECURITY WARNING:</strong> Delete this file immediately after deployment is complete!
        </div>

        <div class="content">
            <?php
            $command = $_GET['command'] ?? '';
            
            if ($command) {
                echo '<div class="command-group">';
                echo '<h3>Executing: <code>' . htmlspecialchars($command) . '</code></h3>';
                echo '<div class="output">';
                
                try {
                    ob_start();
                    $exitCode = $kernel->call($command);
                    $output = ob_get_clean();
                    
                    if ($exitCode === 0) {
                        echo '<span class="success">✓ Command executed successfully!</span>' . "\n\n";
                    } else {
                        echo '<span class="error">✗ Command failed with exit code: ' . $exitCode . '</span>' . "\n\n";
                    }
                    
                    echo htmlspecialchars($output);
                } catch (Exception $e) {
                    echo '<span class="error">✗ Error: ' . htmlspecialchars($e->getMessage()) . '</span>';
                }
                
                echo '</div>';
                echo '<a href="?password=' . $DEPLOYMENT_PASSWORD . '" class="btn">← Back to Commands</a>';
                echo '</div>';
            } else {
            ?>

            <!-- Storage Link -->
            <div class="command-group">
                <h3>📁 Create Storage Symbolic Link</h3>
                <p>Creates symlink from public/storage to storage/app/public</p>
                <a href="?password=<?= $DEPLOYMENT_PASSWORD ?>&command=storage:link" class="btn btn-success">
                    Run storage:link
                </a>
            </div>

            <!-- Clear Caches -->
            <div class="command-group">
                <h3>🧹 Clear All Caches</h3>
                <p>Clears application, config, route, and view caches</p>
                <a href="?password=<?= $DEPLOYMENT_PASSWORD ?>&command=cache:clear" class="btn">
                    Clear Cache
                </a>
                <a href="?password=<?= $DEPLOYMENT_PASSWORD ?>&command=config:clear" class="btn">
                    Clear Config
                </a>
                <a href="?password=<?= $DEPLOYMENT_PASSWORD ?>&command=route:clear" class="btn">
                    Clear Routes
                </a>
                <a href="?password=<?= $DEPLOYMENT_PASSWORD ?>&command=view:clear" class="btn">
                    Clear Views
                </a>
            </div>

            <!-- Optimize -->
            <div class="command-group">
                <h3>⚡ Optimize Application</h3>
                <p>Cache configuration, routes, and views for better performance</p>
                <a href="?password=<?= $DEPLOYMENT_PASSWORD ?>&command=config:cache" class="btn btn-success">
                    Cache Config
                </a>
                <a href="?password=<?= $DEPLOYMENT_PASSWORD ?>&command=route:cache" class="btn btn-success">
                    Cache Routes
                </a>
                <a href="?password=<?= $DEPLOYMENT_PASSWORD ?>&command=view:cache" class="btn btn-success">
                    Cache Views
                </a>
                <a href="?password=<?= $DEPLOYMENT_PASSWORD ?>&command=optimize" class="btn btn-success">
                    Optimize All
                </a>
            </div>

            <!-- Database -->
            <div class="command-group">
                <h3>🗄️ Database Operations</h3>
                <p>Run migrations (⚠️ Use with caution!)</p>
                <a href="?password=<?= $DEPLOYMENT_PASSWORD ?>&command=migrate" class="btn">
                    Run Migrations
                </a>
                <a href="?password=<?= $DEPLOYMENT_PASSWORD ?>&command=migrate:status" class="btn">
                    Migration Status
                </a>
            </div>

            <!-- Environment Info -->
            <div class="command-group">
                <h3>ℹ️ Environment Information</h3>
                <p>Check application status and environment</p>
                <a href="?password=<?= $DEPLOYMENT_PASSWORD ?>&command=about" class="btn">
                    App Info
                </a>
                <a href="?password=<?= $DEPLOYMENT_PASSWORD ?>&command=route:list" class="btn">
                    List Routes
                </a>
            </div>

            <!-- Danger Zone -->
            <div class="command-group" style="border-color: #fee; background: #fef2f2;">
                <h3 style="color: #dc2626;">⚠️ Danger Zone</h3>
                <p style="color: #991b1b;">These commands can destroy data!</p>
                <a href="?password=<?= $DEPLOYMENT_PASSWORD ?>&command=migrate:fresh" 
                   class="btn btn-danger"
                   onclick="return confirm('This will DELETE ALL DATA! Are you sure?')">
                    Fresh Migration (DELETES ALL DATA!)
                </a>
            </div>

            <?php } ?>

            <!-- Quick Actions Completed -->
            <div class="command-group">
                <h3>✅ Deployment Checklist</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="padding: 10px; border-bottom: 1px solid #e5e7eb;">
                        <span>1. Upload all files</span>
                        <span class="status pending">Manual</span>
                    </li>
                    <li style="padding: 10px; border-bottom: 1px solid #e5e7eb;">
                        <span>2. Update .env file</span>
                        <span class="status pending">Manual</span>
                    </li>
                    <li style="padding: 10px; border-bottom: 1px solid #e5e7eb;">
                        <span>3. Import database</span>
                        <span class="status pending">Manual</span>
                    </li>
                    <li style="padding: 10px; border-bottom: 1px solid #e5e7eb;">
                        <span>4. Create storage link</span>
                        <span class="status pending">Click above</span>
                    </li>
                    <li style="padding: 10px; border-bottom: 1px solid #e5e7eb;">
                        <span>5. Clear all caches</span>
                        <span class="status pending">Click above</span>
                    </li>
                    <li style="padding: 10px; border-bottom: 1px solid #e5e7eb;">
                        <span>6. Optimize application</span>
                        <span class="status pending">Click above</span>
                    </li>
                    <li style="padding: 10px;">
                        <span>7. Delete this file!</span>
                        <span class="status pending">Manual</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
