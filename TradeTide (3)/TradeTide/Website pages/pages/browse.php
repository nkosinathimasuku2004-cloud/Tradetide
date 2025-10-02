<?php
require_once '../../includes/auth.php';
require_once '../../config/classes/Barter.php';

// Require authentication
requireAuth();

$barter = new Barter();

// Get search parameters
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$type = $_GET['type'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 12;
$offset = ($page - 1) * $limit;

// Get barters
$barters_result = $barter->read($limit, $offset, $search, $category, $type);
$barters = $barters_result->fetchAll();

// Get categories for filter
$categories_result = $barter->getCategories();
$categories = $categories_result->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Barters - TradeTide</title>
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
                    <li><a href="browse.php" class="active">Browse Barters</a></li>
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
        <!-- Search and Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" id="searchForm">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="searchInput" class="form-label">Search</label>
                                        <input type="text" id="searchInput" name="search" class="form-control" 
                                               value="<?php echo htmlspecialchars($search); ?>" 
                                               placeholder="Search barters...">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="categoryFilter" class="form-label">Category</label>
                                        <select id="categoryFilter" name="category" class="form-control form-select">
                                            <option value="">All Categories</option>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?php echo htmlspecialchars($cat['category']); ?>"
                                                        <?php echo ($category === $cat['category']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($cat['category']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="typeFilter" class="form-label">Type</label>
                                        <select id="typeFilter" name="type" class="form-control form-select">
                                            <option value="">All Types</option>
                                            <option value="service" <?php echo ($type === 'service') ? 'selected' : ''; ?>>Service</option>
                                            <option value="item" <?php echo ($type === 'item') ? 'selected' : ''; ?>>Item</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary" style="width: 100%;">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-between align-center">
                    <h2>Available Barters</h2>
                    <a href="post-barter.php" class="btn btn-primary">Post New Barter</a>
                </div>
            </div>
        </div>

        <!-- Barters Grid -->
        <div class="row">
            <div class="col-12">
                <?php if (empty($barters)): ?>
                    <div class="card">
                        <div class="card-body text-center">
                            <h3>No barters found</h3>
                            <p class="text-secondary">Try adjusting your search criteria or be the first to post a barter!</p>
                            <a href="post-barter.php" class="btn btn-primary">Post New Barter</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="barter-grid">
                        <?php foreach ($barters as $barter_item): ?>
                            <div class="barter-card">
                                <div class="barter-image">
                                    <?php echo htmlspecialchars($barter_item['title']); ?>
                                </div>
                                <div class="barter-content">
                                    <h4 class="barter-title"><?php echo htmlspecialchars($barter_item['title']); ?></h4>
                                    <p class="barter-description"><?php echo htmlspecialchars(substr($barter_item['description'], 0, 120)) . '...'; ?></p>
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
                <?php endif; ?>
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