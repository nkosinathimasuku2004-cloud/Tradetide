<?php
require_once '../../includes/auth.php';
require_once '../../config/classes/Barter.php';
require_once '../../config/classes/User.php';

// Require authentication
requireAuth();

$barter = new Barter();
$user = new User();

// Get barter ID
$barter_id = $_GET['id'] ?? 0;

if (!$barter_id) {
    header('Location: browse.php');
    exit();
}

// Get barter details
$barter_data = $barter->readOne($barter_id);

if (!$barter_data) {
    header('Location: browse.php');
    exit();
}

// Increment views
$barter->incrementViews($barter_id);

// Get barter owner details
$owner = new User();
$owner->findById($barter_data['user_id']);

$current_user = getCurrentUser();
$is_owner = ($current_user->id == $barter_data['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($barter_data['title']); ?> - TradeTide</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <a href="index.php" class="logo">TradeTide</a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="browse.php">Browse Barters</a></li>
                    <li><a href="my-barters.php">My Barters</a></li>
                    <li><a href="messages.php">Messages</a></li>
                    <li><a href="profile.php">Profile</a></li>
                </ul>
            </nav>
            <div class="nav-buttons">
                <a href="../../api/auth/logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container" style="padding: 2rem 0;">
        <!-- Breadcrumb -->
        <div class="row mb-3">
            <div class="col-12">
                <nav>
                    <a href="browse.php" class="text-primary">← Back to Browse</a>
                </nav>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12">
                                <h1><?php echo htmlspecialchars($barter_data['title']); ?></h1>
                                <div class="barter-meta mb-3">
                                    <span class="barter-category"><?php echo htmlspecialchars($barter_data['category']); ?></span>
                                    <span class="barter-type"><?php echo htmlspecialchars($barter_data['type']); ?></span>
                                    <span class="text-secondary"><?php echo $barter_data['views']; ?> views</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h3>Description</h3>
                                <p><?php echo nl2br(htmlspecialchars($barter_data['description'])); ?></p>
                            </div>
                        </div>

                        <?php if ($barter_data['value_range']): ?>
                        <div class="row mb-4">
                            <div class="col-12">
                                <h3>Value Range</h3>
                                <p class="text-primary font-weight-bold"><?php echo htmlspecialchars($barter_data['value_range']); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($barter_data['location']): ?>
                        <div class="row mb-4">
                            <div class="col-12">
                                <h3>Location</h3>
                                <p><?php echo htmlspecialchars($barter_data['location']); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h3>Posted</h3>
                                <p class="text-secondary"><?php echo date('F j, Y \a\t g:i A', strtotime($barter_data['created_at'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-4">
                <!-- Owner Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Posted by</h3>
                    </div>
                    <div class="card-body">
                        <div class="user-info mb-3">
                            <div class="user-avatar" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                <?php echo strtoupper(substr($owner->first_name, 0, 1)); ?>
                            </div>
                            <div>
                                <h4><?php echo htmlspecialchars($owner->first_name . ' ' . $owner->last_name); ?></h4>
                                <p class="text-secondary"><?php echo htmlspecialchars($owner->area); ?></p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-between">
                                <span>Rating:</span>
                                <span class="text-primary font-weight-bold"><?php echo $owner->rating; ?>/5</span>
                            </div>
                            <div class="d-flex justify-between">
                                <span>Total Trades:</span>
                                <span class="text-primary font-weight-bold"><?php echo $owner->total_trades; ?></span>
                            </div>
                        </div>

                        <?php if ($owner->bio): ?>
                        <div class="mb-3">
                            <h5>About</h5>
                            <p class="text-secondary"><?php echo htmlspecialchars($owner->bio); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if ($owner->skills): ?>
                        <div class="mb-3">
                            <h5>Skills</h5>
                            <p class="text-secondary"><?php echo htmlspecialchars($owner->skills); ?></p>
                        </div>
                        <?php endif; ?>

                        <a href="profile.php?id=<?php echo $owner->id; ?>" class="btn btn-secondary" style="width: 100%;">View Profile</a>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3>Actions</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($is_owner): ?>
                            <a href="edit-barter.php?id=<?php echo $barter_data['id']; ?>" class="btn btn-primary mb-2" style="width: 100%;">Edit Barter</a>
                            <a href="delete-barter.php?id=<?php echo $barter_data['id']; ?>" class="btn btn-danger mb-2" style="width: 100%;" 
                               onclick="return confirm('Are you sure you want to delete this barter?')">Delete Barter</a>
                        <?php else: ?>
                            <a href="send-message.php?barter_id=<?php echo $barter_data['id']; ?>&receiver_id=<?php echo $owner->id; ?>" 
                               class="btn btn-primary mb-2" style="width: 100%;">Send Message</a>
                            <a href="trade-request.php?barter_id=<?php echo $barter_data['id']; ?>&receiver_id=<?php echo $owner->id; ?>" 
                               class="btn btn-success mb-2" style="width: 100%;">Make Trade Request</a>
                        <?php endif; ?>
                        
                        <a href="browse.php" class="btn btn-secondary" style="width: 100%;">Back to Browse</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>TradeTide</h4>
                    <p>South Africa's premier bartering platform connecting communities through skills and services exchange.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="browse.php">Browse Barters</a>
                    <a href="post-barter.php">Post Barter</a>
                    <a href="messages.php">Messages</a>
                </div>
                <div class="footer-section">
                    <h4>Support</h4>
                    <a href="help.php">Help Center</a>
                    <a href="terms.php">Terms of Service</a>
                    <a href="privacy.php">Privacy Policy</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 TradeTide. All rights reserved. Built for South African communities.</p>
            </div>
        </div>
    </footer>

    <script src="../../assets/js/main.js"></script>
</body>
</html>