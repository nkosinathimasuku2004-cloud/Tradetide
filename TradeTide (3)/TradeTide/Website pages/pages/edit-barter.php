<?php
require_once '../../includes/auth.php';
require_once '../../config/classes/Barter.php';

requireAuth();

$current_user_id = getCurrentUserId();
$barter_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$barter_id) {
    header('Location: my-barters.php');
    exit();
}

$barter = new Barter();
$barter_data = $barter->readOne($barter_id);

if (!$barter_data || $barter_data['user_id'] != $current_user_id) {
    header('Location: my-barters.php');
    exit();
}

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
    } elseif (strlen($title) < 10) {
        $error = 'Title must be at least 10 characters long.';
    } elseif (strlen($description) < 50) {
        $error = 'Description must be at least 50 characters long.';
    } else {
        // Update barter
        $barter->id = $barter_id;
        $barter->user_id = $current_user_id;
        $barter->title = $title;
        $barter->description = $description;
        $barter->category = $category;
        $barter->type = $type;
        $barter->value_range = $value_range;
        $barter->location = $location;
        $barter->images = ''; // Will be implemented later

        if ($barter->update()) {
            $success = 'Your barter has been updated successfully!';
            // Refresh data
            $barter_data = $barter->readOne($barter_id);
        } else {
            $error = 'There was an error updating your barter. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barter - TradeTide</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="nav-container">
            <a href="index.php" class="logo">TradeTide</a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="dashboard.php">Dashboard</a></li>
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
                <div class="card">
                    <div class="card-header">
                        <h2>Edit Barter</h2>
                        <p class="text-secondary">Update your barter details</p>
                        <div style="margin-top: 1rem;">
                            <a href="my-barters.php" class="text-secondary" style="text-decoration: none;">← Back to My Barters</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success); ?>
                                <div style="margin-top: 1rem;">
                                    <a href="barter-details.php?id=<?php echo $barter_id; ?>" class="btn btn-secondary">View Barter</a>
                                    <a href="my-barters.php" class="btn btn-primary">Back to My Barters</a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form method="POST" data-validate>
                            <div class="form-group">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" id="title" name="title" class="form-control"
                                       value="<?php echo htmlspecialchars($_POST['title'] ?? $barter_data['title']); ?>"
                                       placeholder="e.g., Professional Website Development" required>
                                <small class="text-secondary">Be specific and descriptive (minimum 10 characters)</small>
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">Description *</label>
                                <textarea id="description" name="description" class="form-control" rows="6"
                                          placeholder="Describe what you're offering, your experience, what you're looking for in return..." required><?php echo htmlspecialchars($_POST['description'] ?? $barter_data['description']); ?></textarea>
                                <small class="text-secondary">Provide detailed information (minimum 50 characters)</small>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="category" class="form-label">Category *</label>
                                        <select id="category" name="category" class="form-control form-select" required>
                                            <option value="">Select a category</option>
                                            <?php
                                            $categories = ['Technology', 'Food & Catering', 'Home & Garden', 'Education', 'Health & Fitness', 'Creative Services', 'Transportation', 'Business Services', 'Personal Care', 'Events', 'Other'];
                                            $current_category = $_POST['category'] ?? $barter_data['category'];
                                            foreach ($categories as $cat):
                                            ?>
                                                <option value="<?php echo $cat; ?>" <?php echo ($current_category === $cat) ? 'selected' : ''; ?>>
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
                                            <?php
                                            $types = ['service', 'skill', 'item'];
                                            $current_type = $_POST['type'] ?? $barter_data['type'];
                                            foreach ($types as $t):
                                            ?>
                                                <option value="<?php echo $t; ?>" <?php echo ($current_type === $t) ? 'selected' : ''; ?>>
                                                    <?php echo ucfirst($t); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="value_range" class="form-label">Estimated Value Range</label>
                                        <input type="text" id="value_range" name="value_range" class="form-control"
                                               value="<?php echo htmlspecialchars($_POST['value_range'] ?? $barter_data['value_range']); ?>"
                                               placeholder="e.g., R500-R2000">
                                        <small class="text-secondary">Optional: Help others understand the value</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="location" class="form-label">Preferred Location</label>
                                        <input type="text" id="location" name="location" class="form-control"
                                               value="<?php echo htmlspecialchars($_POST['location'] ?? $barter_data['location']); ?>"
                                               placeholder="e.g., Garsfontein, Pretoria">
                                        <small class="text-secondary">Where you can provide this service/item</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Barter Statistics -->
                            <div style="background: var(--background-color); padding: 1rem; border-radius: var(--border-radius); margin-bottom: 1.5rem;">
                                <h4>Barter Statistics</h4>
                                <div style="display: flex; gap: 2rem; margin-top: 1rem;">
                                    <div>
                                        <strong><?php echo $barter_data['views']; ?></strong>
                                        <div style="font-size: 0.875rem; color: var(--text-secondary);">Views</div>
                                    </div>
                                    <div>
                                        <strong><?php echo date('M j, Y', strtotime($barter_data['created_at'])); ?></strong>
                                        <div style="font-size: 0.875rem; color: var(--text-secondary);">Posted</div>
                                    </div>
                                    <div>
                                        <strong><?php echo ucfirst($barter_data['status']); ?></strong>
                                        <div style="font-size: 0.875rem; color: var(--text-secondary);">Status</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">Update Barter</button>
                                <a href="my-barters.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
</body>
</html>
