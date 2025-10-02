<?php
/**
 * TradeTide Installation Script
 * Run this script to set up the TradeTide application
 */

// Include configuration
require_once 'config/config.php';

// Check if already installed
if (file_exists('config/installed.lock')) {
    die('TradeTide is already installed. Delete config/installed.lock to reinstall.');
}

$error = '';
$success = '';

// Process installation
if ($_POST && isset($_POST['install'])) {
    $db_host = $_POST['db_host'] ?? DB_HOST;
    $db_name = $_POST['db_name'] ?? DB_NAME;
    $db_user = $_POST['db_user'] ?? DB_USER;
    $db_pass = $_POST['db_pass'] ?? DB_PASS;
    
    try {
        // Test database connection
        $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create database if it doesn't exist
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name`");
        $pdo->exec("USE `$db_name`");
        
        // Read and execute SQL schema
        $sql = file_get_contents('database_schema.sql');
        $pdo->exec($sql);
        
        // Update database configuration
        $config_content = file_get_contents('config/database.php');
        $config_content = str_replace("'localhost'", "'$db_host'", $config_content);
        $config_content = str_replace("'tradetide_db'", "'$db_name'", $config_content);
        $config_content = str_replace("'root'", "'$db_user'", $config_content);
        $config_content = str_replace("''", "'$db_pass'", $config_content);
        file_put_contents('config/database.php', $config_content);
        
        // Create installed lock file
        file_put_contents('config/installed.lock', date('Y-m-d H:i:s'));
        
        $success = 'TradeTide has been successfully installed! You can now access the application.';
        
    } catch (PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    } catch (Exception $e) {
        $error = 'Installation error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeTide Installation</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .install-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
        }
        .install-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .install-steps {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }
        .install-steps h3 {
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        .install-steps ol {
            margin-left: 1.5rem;
        }
        .install-steps li {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="install-container">
            <div class="install-header">
                <h1>TradeTide Installation</h1>
                <p class="text-secondary">Welcome to TradeTide setup wizard</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                    <div class="mt-3">
                        <a href="Website pages/pages/index.php" class="btn btn-primary">Go to Application</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="install-steps">
                    <h3>Before Installation</h3>
                    <ol>
                        <li>Ensure MySQL server is running</li>
                        <li>Create a MySQL database (or use existing one)</li>
                        <li>Have database credentials ready</li>
                        <li>Ensure PHP has PDO MySQL extension enabled</li>
                    </ol>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2>Database Configuration</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label for="db_host" class="form-label">Database Host</label>
                                <input type="text" id="db_host" name="db_host" class="form-control" 
                                       value="<?php echo htmlspecialchars(DB_HOST); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="db_name" class="form-label">Database Name</label>
                                <input type="text" id="db_name" name="db_name" class="form-control" 
                                       value="<?php echo htmlspecialchars(DB_NAME); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="db_user" class="form-label">Database Username</label>
                                <input type="text" id="db_user" name="db_user" class="form-control" 
                                       value="<?php echo htmlspecialchars(DB_USER); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="db_pass" class="form-label">Database Password</label>
                                <input type="password" id="db_pass" name="db_pass" class="form-control" 
                                       value="<?php echo htmlspecialchars(DB_PASS); ?>">
                            </div>

                            <div class="form-group">
                                <button type="submit" name="install" class="btn btn-primary" style="width: 100%;">
                                    Install TradeTide
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h3>Demo Accounts</h3>
                    </div>
                    <div class="card-body">
                        <p>After installation, you can use these demo accounts:</p>
                        <ul>
                            <li><strong>Sipho Mthembu:</strong> sipho@example.com / password</li>
                            <li><strong>Nomsa Van Der Merwe:</strong> nomsa@example.com / password</li>
                            <li><strong>Lerato Sithole:</strong> lerato@example.com / password</li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

