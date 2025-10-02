<?php
require_once '../../includes/auth.php';
require_once '../../config/classes/Barter.php';

requireAuth();

$user_id = getCurrentUserId();
$barter = new Barter();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $barter_id = intval($_GET['id']);
    $barter->id = $barter_id;
    $barter->user_id = $user_id;

    if ($barter->delete()) {
        $success = 'Barter deleted successfully.';
    } else {
        $error = 'Error deleting barter.';
    }
}

// Get user's barters
$user_barters = $barter->readByUser($user_id);
$barters = $user_barters->fetchAll(PDO::FETCH_ASSOC);

$current_user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Barters - TradeTide</title>
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
                    <li><a href="my-barters.php" class="active">My Barters</a></li>
                    <li><a href="messages.php">Messages</a></li>
                </ul>
            </nav>
            <div class="nav-buttons">
                <a href="post-barter.php" class="btn btn-primary">Post New Barter</a>
                <a href="../../api/auth/logout.php" class="btn btn-secondary">Sign Out</a>
            </div>
        </div>
    </header>

    <div class="container" style="padding: 2rem 0;">
        <div class="mb-4" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1>My Barters</h1>
                <p class="text-secondary">Manage your posted offers and track their performance</p>
            </div>
            <a href="post-barter.php" class="btn btn-primary">+ Post New Barter</a>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (count($barters) > 0): ?>
            <div class="card">
                <div class="card-body" style="padding: 0;">
                    <?php foreach ($barters as $index => $barter_item): ?>
                        <div style="padding: 1.5rem; <?php echo $index < count($barters) - 1 ? 'border-bottom: 1px solid var(--border-light);' : ''; ?>">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 2rem;">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                                        <h3 style="margin: 0;"><?php echo htmlspecialchars($barter_item['title']); ?></h3>
                                        <span class="barter-type" style="padding: 4px 8px; background: var(--accent-color); color: white; border-radius: 4px; font-size: 0.75rem;">
                                            <?php echo ucfirst($barter_item['type']); ?>
                                        </span>
                                        <span class="barter-category" style="padding: 4px 12px; background: var(--primary-color); color: white; border-radius: 20px; font-size: 0.75rem;">
                                            <?php echo htmlspecialchars($barter_item['category']); ?>
                                        </span>
                                    </div>

                                    <p style="color: var(--text-secondary); margin-bottom: 1rem; line-height: 1.5;">
                                        <?php echo htmlspecialchars(substr($barter_item['description'], 0, 200)); ?>
                                        <?php if (strlen($barter_item['description']) > 200): ?>...<?php endif; ?>
                                    </p>

                                    <div style="display: flex; gap: 2rem; font-size: 0.875rem; color: var(--text-secondary);">
                                        <span>👁️ <?php echo $barter_item['views']; ?> views</span>
                                        <span>📅 <?php echo date('M j, Y', strtotime($barter_item['created_at'])); ?></span>
                                        <?php if ($barter_item['value_range']): ?>
                                            <span>💰 <?php echo htmlspecialchars($barter_item['value_range']); ?></span>
                                        <?php endif; ?>
                                        <?php if ($barter_item['location']): ?>
                                            <span>📍 <?php echo htmlspecialchars($barter_item['location']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 0.5rem; flex-direction: column; min-width: 120px;">
                                    <a href="barter-details.php?id=<?php echo $barter_item['id']; ?>" class="btn btn-secondary btn-sm">View</a>
                                    <a href="edit-barter.php?id=<?php echo $barter_item['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                                    <button onclick="confirmDelete(<?php echo $barter_item['id']; ?>)" class="btn btn-danger btn-sm">Delete</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Statistics Summary -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3>Your Statistics</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="text-center">
                                <div style="font-size: 2rem; font-weight: bold; color: var(--primary-color);"><?php echo count($barters); ?></div>
                                <div style="color: var(--text-secondary);">Total Barters</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-center">
                                <div style="font-size: 2rem; font-weight: bold; color: var(--accent-color);">
                                    <?php echo array_sum(array_column($barters, 'views')); ?>
                                </div>
                                <div style="color: var(--text-secondary);">Total Views</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-center">
                                <div style="font-size: 2rem; font-weight: bold; color: var(--warning-color);">
                                    <?php echo count(array_filter($barters, function($b) { return $b['status'] === 'active'; })); ?>
                                </div>
                                <div style="color: var(--text-secondary);">Active</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-center">
                                <div style="font-size: 2rem; font-weight: bold; color: var(--success-color);">
                                    <?php echo number_format($current_user->rating, 1); ?>
                                </div>
                                <div style="color: var(--text-secondary);">Your Rating</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="card">
                <div class="card-body text-center" style="padding: 4rem 2rem;">
                    <div style="font-size: 4rem; color: var(--text-secondary); margin-bottom: 2rem;">📝</div>
                    <h3>No barters posted yet</h3>
                    <p class="text-secondary" style="margin-bottom: 2rem;">
                        Start building your reputation by posting your first barter offer.<br>
                        Share your skills, services, or items with the community.
                    </p>
                    <a href="post-barter.php" class="btn btn-primary btn-lg">Post Your First Barter</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="../../assets/js/main.js"></script>
    <script>
        function confirmDelete(barterId) {
            if (confirm('Are you sure you want to delete this barter? This action cannot be undone.')) {
                window.location.href = `my-barters.php?action=delete&id=${barterId}`;
            }
        }
    </script>
</body>
</html>
