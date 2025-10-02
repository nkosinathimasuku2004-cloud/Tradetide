<?php
require_once '../../includes/auth.php';
require_once '../../config/classes/User.php';

requireAuth();

$current_user = getCurrentUser();
$error = '';
$success = '';

// Process form submission
if ($_POST) {
    $action = $_POST['action'] ?? '';

    if ($action === 'change_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = 'Please fill in all password fields.';
        } elseif (!$current_user->verifyPassword($current_password)) {
            $error = 'Current password is incorrect.';
        } elseif (strlen($new_password) < 6) {
            $error = 'New password must be at least 6 characters long.';
        } elseif ($new_password !== $confirm_password) {
            $error = 'New passwords do not match.';
        } else {
            // Update password in database
            $database = new Database();
            $conn = $database->connect();

            $query = "UPDATE users SET password = :password WHERE id = :id";
            $stmt = $conn->prepare($query);
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':id', $current_user->id);

            if ($stmt->execute()) {
                $success = 'Password changed successfully!';
            } else {
                $error = 'Error changing password. Please try again.';
            }
        }
    } elseif ($action === 'update_preferences') {
        // Handle notification preferences
        $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;
        $message_notifications = isset($_POST['message_notifications']) ? 1 : 0;
        $trade_notifications = isset($_POST['trade_notifications']) ? 1 : 0;

        // For now, just show success (would normally save to database)
        $success = 'Preferences updated successfully!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - TradeTide</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="nav-container">
            <a href="index.php" class="logo">TradeTide</a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="../dashboard/index.php">Dashboard</a></li>
                    <li><a href="browse.php">Browse</a></li>
                    <li><a href="my-barters.php">My Barters</a></li>
                    <li><a href="messages.php">Messages</a></li>
                </ul>
            </nav>
            <div class="nav-buttons">
                <a href="post-barter.php" class="btn btn-primary">Post Barter</a>
                <a href="../../api/auth/logout.php" class="btn btn-secondary">Sign Out</a>
            </div>
        </div>
    </header>

    <div class="container" style="padding: 2rem 0;">
        <div class="row">
            <div class="col-8" style="margin: 0 auto;">
                <h1>Account Settings</h1>
                <p class="text-secondary">Manage your account preferences and security settings</p>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <!-- Account Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Account Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($current_user->first_name . ' ' . $current_user->last_name); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($current_user->email); ?></p>
                                <?php if ($current_user->phone): ?>
                                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($current_user->phone); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="col-6">
                                <?php if ($current_user->area): ?>
                                    <p><strong>Area:</strong> <?php echo htmlspecialchars($current_user->area); ?></p>
                                <?php endif; ?>
                                <p><strong>Member Since:</strong> <?php echo date('F j, Y', strtotime($current_user->created_at)); ?></p>
                                <p><strong>Rating:</strong> <?php echo number_format($current_user->rating, 1); ?>/5.0</p>
                            </div>
                        </div>
                        <div style="margin-top: 1rem;">
                            <a href="profile.php" class="btn btn-secondary">Edit Profile</a>
                        </div>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Change Password</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" data-validate>
                            <input type="hidden" name="action" value="change_password">

                            <div class="form-group">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" id="current_password" name="current_password" class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                                        <small class="text-secondary">Minimum 6 characters</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </form>
                    </div>
                </div>

                <!-- Notification Preferences -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Notification Preferences</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_preferences">

                            <div style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" name="email_notifications" checked>
                                    <span>Email notifications for new messages</span>
                                </label>
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" name="message_notifications" checked>
                                    <span>Push notifications for messages</span>
                                </label>
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" name="trade_notifications" checked>
                                    <span>Notifications for trade requests</span>
                                </label>
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" name="newsletter">
                                    <span>TradeTide newsletter and updates</span>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Preferences</button>
                        </form>
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Privacy Settings</h3>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 1rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" checked>
                                <span>Show my profile to other users</span>
                            </label>
                            <small class="text-secondary">Others can see your name, rating, and location</small>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" checked>
                                <span>Allow messages from other users</span>
                            </label>
                            <small class="text-secondary">Users can contact you about your barters</small>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox">
                                <span>Show my phone number to verified traders</span>
                            </label>
                            <small class="text-secondary">Only users you're actively trading with can see your phone</small>
                        </div>

                        <button class="btn btn-secondary">Update Privacy Settings</button>
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3>Account Actions</h3>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 2rem;">
                            <h4>Export Your Data</h4>
                            <p class="text-secondary">Download a copy of your account data including barters, messages, and profile information.</p>
                            <button class="btn btn-secondary">Request Data Export</button>
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <h4>Deactivate Account</h4>
                            <p class="text-secondary">Temporarily hide your profile and barters. You can reactivate anytime.</p>
                            <button class="btn btn-warning">Deactivate Account</button>
                        </div>

                        <div>
                            <h4>Delete Account</h4>
                            <p class="text-secondary" style="color: var(--error-color);">
                                <strong>Warning:</strong> This will permanently delete your account, barters, messages, and all data. This action cannot be undone.
                            </p>
                            <button class="btn btn-danger" onclick="confirmDeleteAccount()">Delete Account</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
    <script>
        function confirmDeleteAccount() {
            if (confirm('Are you absolutely sure you want to delete your account? This action cannot be undone and all your data will be permanently lost.')) {
                if (confirm('This is your final warning. Are you sure you want to proceed with account deletion?')) {
                    // Would normally redirect to account deletion handler
                    alert('Account deletion feature would be implemented here with proper confirmation flow.');
                }
            }
        }
    </script>
</body>
</html>
