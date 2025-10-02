<?php
require_once '../../includes/auth.php';
require_once '../../config/classes/User.php';
require_once '../../config/classes/Barter.php';

// Require authentication
requireAuth();

$user = getCurrentUser();
$barter = new Barter();

// Get user's barters
$user_barters = $barter->readByUser($user->id);
$user_barters_data = $user_barters->fetchAll();

// Get recent barters
$recent_barters = $barter->read(6, 0);
$recent_barters_data = $recent_barters->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TradeTide</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <a href="index.php" class="logo">TradeTide</a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="browse.php">Browse Barters</a></li>
                    <li><a href="my-barters.php">My Barters</a></li>
                    <li><a href="messages.php">Messages</a></li>
                    <li><a href="profile.php">Profile</a></li>
                </ul>
            </nav>
            <div class="nav-buttons">
                <span class="text-white">Welcome, <?php echo htmlspecialchars($user->first_name); ?>!</span>
                <a href="../../api/auth/logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container" style="padding: 2rem 0;">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h1>Welcome back, <?php echo htmlspecialchars($user->first_name); ?>!</h1>
                        <p class="text-secondary">Here's what's happening in your trading community.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="row mb-4">
            <div class="col-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($user_barters_data); ?></div>
                    <div class="stat-label">Your Barters</div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $user->total_trades; ?></div>
                    <div class="stat-label">Total Trades</div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $user->rating; ?></div>
                    <div class="stat-label">Your Rating</div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $user->area; ?></div>
                    <div class="stat-label">Your Area</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <a href="post-barter.php" class="btn btn-primary" style="width: 100%;">
                                    Post New Barter
                                </a>
                            </div>
                            <div class="col-3">
                                <a href="browse.php" class="btn btn-secondary" style="width: 100%;">
                                    Browse Barters
                                </a>
                            </div>
                            <div class="col-3">
                                <a href="messages.php" class="btn btn-secondary" style="width: 100%;">
                                    Check Messages
                                </a>
                            </div>
                            <div class="col-3">
                                <a href="profile.php" class="btn btn-secondary" style="width: 100%;">
                                    Update Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Your Recent Barters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Your Recent Barters</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($user_barters_data)): ?>
                            <p class="text-secondary">You haven't posted any barters yet.</p>
                            <a href="post-barter.php" class="btn btn-primary">Post Your First Barter</a>
                        <?php else: ?>
                            <div class="barter-grid">
                                <?php foreach (array_slice($user_barters_data, 0, 3) as $barter_item): ?>
                                    <div class="barter-card">
                                        <div class="barter-image">
                                            <?php echo htmlspecialchars($barter_item['title']); ?>
                                        </div>
                                        <div class="barter-content">
                                            <h4 class="barter-title"><?php echo htmlspecialchars($barter_item['title']); ?></h4>
                                            <p class="barter-description"><?php echo htmlspecialchars(substr($barter_item['description'], 0, 100)) . '...'; ?></p>
                                            <div class="barter-meta">
                                                <span class="barter-category"><?php echo htmlspecialchars($barter_item['category']); ?></span>
                                                <span class="barter-type"><?php echo htmlspecialchars($barter_item['type']); ?></span>
                                            </div>
                                            <div class="barter-footer">
                                                <small class="text-secondary"><?php echo date('M j, Y', strtotime($barter_item['created_at'])); ?></small>
                                                <a href="barter-details.php?id=<?php echo $barter_item['id']; ?>" class="btn btn-sm btn-primary">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center mt-3">
                                <a href="my-barters.php" class="btn btn-secondary">View All My Barters</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Community Barters -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Recent Community Barters</h3>
                    </div>
                    <div class="card-body">
                        <div class="barter-grid">
                            <?php foreach ($recent_barters_data as $barter_item): ?>
                                <div class="barter-card">
                                    <div class="barter-image">
                                        <?php echo htmlspecialchars($barter_item['title']); ?>
                                    </div>
                                    <div class="barter-content">
                                        <h4 class="barter-title"><?php echo htmlspecialchars($barter_item['title']); ?></h4>
                                        <p class="barter-description"><?php echo htmlspecialchars(substr($barter_item['description'], 0, 100)) . '...'; ?></p>
                                        <div class="barter-meta">
                                            <span class="barter-category"><?php echo htmlspecialchars($barter_item['category']); ?></span>
                                            <span class="barter-type"><?php echo htmlspecialchars($barter_item['type']); ?></span>
                                        </div>
                                        <div class="barter-footer">
                                            <div class="user-info">
                                                <div class="user-avatar"><?php echo strtoupper(substr($barter_item['first_name'], 0, 1)); ?></div>
                                                <span class="user-name"><?php echo htmlspecialchars($barter_item['first_name'] . ' ' . $barter_item['last_name']); ?></span>
                                            </div>
                                            <small class="text-secondary"><?php echo htmlspecialchars($barter_item['user_area']); ?></small>
                                        </div>
                                        <div class="mt-3">
                                            <a href="barter-details.php?id=<?php echo $barter_item['id']; ?>" class="btn btn-primary" style="width: 100%;">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="browse.php" class="btn btn-secondary">Browse All Barters</a>
                        </div>
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
                    <a href="browse.php">Browse Barters</a>
                    <a href="post-barter.php">Post Barter</a>
                    <a href="messages.php">Messages</a>
                    <a href="profile.php">Profile</a>
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

