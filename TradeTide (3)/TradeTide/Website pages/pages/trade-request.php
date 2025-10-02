<?php
require_once '../../includes/auth.php';
require_once '../../config/classes/Barter.php';
require_once '../../config/classes/Message.php';
require_once '../../config/classes/User.php';

requireAuth();

$error = '';
$success = '';
$barter_id = intval($_GET['barter_id'] ?? 0);

if (!$barter_id) {
    header('Location: browse.php');
    exit();
}

// Get barter details
$barter = new Barter();
$barter_details = $barter->readOne($barter_id);

if (!$barter_details) {
    header('Location: browse.php');
    exit();
}

// Check if user is trying to request their own barter
if ($barter_details['user_id'] == getCurrentUserId()) {
    header('Location: barter-details.php?id=' . $barter_id);
    exit();
}

// Process form submission
if ($_POST) {
    $subject = trim($_POST['subject'] ?? '');
    $message_text = trim($_POST['message'] ?? '');
    $offered_barter_id = intval($_POST['offered_barter_id'] ?? 0);

    if (empty($subject) || empty($message_text)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Send message
        $message = new Message();
        $message->sender_id = getCurrentUserId();
        $message->receiver_id = $barter_details['user_id'];
        $message->barter_id = $barter_id;
        $message->subject = $subject;
        $message->message = $message_text;

        if ($message->create()) {
            $success = 'Your trade request has been sent successfully!';
        } else {
            $error = 'There was an error sending your request. Please try again.';
        }
    }
}

// Get current user's barters for offering
$current_user_id = getCurrentUserId();
$user_barters = $barter->readByUser($current_user_id);

$current_user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Trade Request - TradeTide</title>
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
            <!-- Barter Details -->
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Requesting Trade For:</h3>
                    </div>
                    <div class="card-body">
                        <div class="barter-image" style="height: 150px; margin-bottom: 1rem;">
                            <?php echo htmlspecialchars($barter_details['title']); ?>
                        </div>
                        <h4><?php echo htmlspecialchars($barter_details['title']); ?></h4>
                        <p class="text-secondary"><?php echo htmlspecialchars(substr($barter_details['description'], 0, 100)); ?>...</p>

                        <div style="margin: 1rem 0;">
                            <span class="barter-category"><?php echo htmlspecialchars($barter_details['category']); ?></span>
                            <span class="barter-type" style="margin-left: 0.5rem;"><?php echo htmlspecialchars($barter_details['type']); ?></span>
                        </div>

                        <div class="user-info" style="margin-top: 1rem;">
                            <div class="user-avatar">
                                <?php echo strtoupper(substr($barter_details['first_name'], 0, 1)); ?>
                            </div>
                            <div>
                                <div class="user-name"><?php echo htmlspecialchars($barter_details['first_name'] . ' ' . substr($barter_details['last_name'], 0, 1) . '.'); ?></div>
                                <small><?php echo htmlspecialchars($barter_details['user_area']); ?></small>
                            </div>
                        </div>

                        <div style="margin-top: 1rem;">
                            <a href="barter-details.php?id=<?php echo $barter_id; ?>" class="btn btn-secondary btn-sm">View Full Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trade Request Form -->
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Send Trade Request</h2>
                        <p class="text-secondary">Introduce yourself and propose what you can offer in exchange</p>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success); ?>
                                <div style="margin-top: 1rem;">
                                    <a href="messages.php" class="btn btn-primary">View Messages</a>
                                    <a href="browse.php" class="btn btn-secondary">Browse More</a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!$success): ?>
                        <form method="POST" data-validate>
                            <div class="form-group">
                                <label for="subject" class="form-label">Subject *</label>
                                <input type="text" id="subject" name="subject" class="form-control"
                                       value="<?php echo htmlspecialchars($_POST['subject'] ?? 'Trade Request: ' . $barter_details['title']); ?>"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="offered_barter_id" class="form-label">What Can You Offer? (Optional)</label>
                                <select id="offered_barter_id" name="offered_barter_id" class="form-control form-select">
                                    <option value="">Select one of your barters (optional)</option>
                                    <?php while ($user_barter = $user_barters->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?php echo $user_barter['id']; ?>"><?php echo htmlspecialchars($user_barter['title']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <small class="text-secondary">You can also describe what you offer in the message below</small>
                            </div>

                            <div class="form-group">
                                <label for="message" class="form-label">Your Message *</label>
                                <textarea id="message" name="message" class="form-control" rows="8"
                                          placeholder="Hi! I'm interested in your <?php echo htmlspecialchars($barter_details['title']); ?>. Here's what I can offer in exchange..."
                                          required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                                <small class="text-secondary">Include details about yourself, your experience, and what you can offer</small>
                            </div>

                            <!-- User Info Preview -->
                            <div style="background: var(--background-color); padding: 1rem; border-radius: var(--border-radius); margin-bottom: 1rem;">
                                <h5>Your Contact Information (will be shared):</h5>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div class="user-avatar">
                                        <?php echo strtoupper(substr($current_user->first_name, 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div><strong><?php echo htmlspecialchars($current_user->first_name . ' ' . $current_user->last_name); ?></strong></div>
                                        <div style="color: var(--text-secondary); font-size: 0.875rem;">
                                            <?php echo htmlspecialchars($current_user->email); ?>
                                            <?php if ($current_user->phone): ?>
                                                • <?php echo htmlspecialchars($current_user->phone); ?>
                                            <?php endif; ?>
                                            <?php if ($current_user->area): ?>
                                                • <?php echo htmlspecialchars($current_user->area); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">Send Trade Request</button>
                                <a href="barter-details.php?id=<?php echo $barter_id; ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
</body>
</html>
