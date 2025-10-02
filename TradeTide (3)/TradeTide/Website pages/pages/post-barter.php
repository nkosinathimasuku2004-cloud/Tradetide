<?php
require_once '../../includes/auth.php';
require_once '../../config/classes/Barter.php';

// Require authentication
requireAuth();

$error = '';
$success = '';

// Process form submission
if ($_POST) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = $_POST['category'] ?? '';
    $type = $_POST['type'] ?? '';
    $value_range = trim($_POST['value_range'] ?? '');
    $location = trim($_POST['location'] ?? '');

    // Validation
    if (empty($title) || empty($description) || empty($category) || empty($type)) {
        $error = 'Please fill in all required fields.';
    } else {
        $barter = new Barter();
        $barter->user_id = getCurrentUserId();
        $barter->title = $title;
        $barter->description = $description;
        $barter->category = $category;
        $barter->type = $type;
        $barter->value_range = $value_range;
        $barter->location = $location;

        if ($barter->create()) {
            $success = 'Barter posted successfully!';
            // Clear form data
            $_POST = array();
        } else {
            $error = 'There was an error posting your barter. Please try again.';
        }
    }
}

$categories = [
    'Technology', 'Food & Catering', 'Home & Garden', 'Education',
    'Health & Wellness', 'Transportation', 'Entertainment', 'Business Services',
    'Art & Crafts', 'Sports & Fitness', 'Other'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post New Barter - TradeTide</title>
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
        <div class="row">
            <div class="col-8" style="margin: 0 auto;">
                <div class="card">
                    <div class="card-header">
                        <h2>Post New Barter</h2>
                        <p class="text-secondary">Share your skills, services, or items with the community</p>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <form method="POST" data-validate>
                            <div class="form-group">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" id="title" name="title" class="form-control"
                                       value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
                                       placeholder="e.g., Professional Website Development"
                                       required>
                                <small class="text-secondary">A clear, descriptive title for your barter</small>
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">Description *</label>
                                <textarea id="description" name="description" class="form-control" rows="6"
                                          placeholder="Describe what you're offering in detail. Include any requirements, timeframes, or special conditions..."
                                          required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                                <small class="text-secondary">Provide detailed information about your offer</small>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="category" class="form-label">Category *</label>
                                        <select id="category" name="category" class="form-control form-select" required>
                                            <option value="">Select a category</option>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?php echo $cat; ?>"
                                                        <?php echo (($_POST['category'] ?? '') === $cat) ? 'selected' : ''; ?>>
                                                    <?php echo $cat; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="type" class="form-label">Type *</label>
                                        <select id="type" name="type" class="form-control form-select" required>
                                            <option value="">Select type</option>
                                            <option value="service" <?php echo (($_POST['type'] ?? '') === 'service') ? 'selected' : ''; ?>>Service</option>
                                            <option value="item" <?php echo (($_POST['type'] ?? '') === 'item') ? 'selected' : ''; ?>>Item</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="value_range" class="form-label">Value Range</label>
                                <input type="text" id="value_range" name="value_range" class="form-control"
                                       value="<?php echo htmlspecialchars($_POST['value_range'] ?? ''); ?>"
                                       placeholder="e.g., R500-R2000, Free, Negotiable">
                                <small class="text-secondary">Estimated value range for your barter</small>
                            </div>

                            <div class="form-group">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" id="location" name="location" class="form-control"
                                       value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>"
                                       placeholder="e.g., Garsfontein, Pretoria">
                                <small class="text-secondary">Where you're located or where the service will be provided</small>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" style="width: 100%;">
                                    Post Barter
                                </button>
                            </div>
                        </form>
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
                    <a href="my-barters.php">My Barters</a>
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