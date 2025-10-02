<?php
require_once '../../includes/auth.php';
require_once '../../config/classes/Message.php';
require_once '../../config/classes/User.php';
require_once '../../config/classes/Barter.php';

requireAuth();

$current_user_id = getCurrentUserId();
$error = '';
$success = '';

// Get recipient and barter info
$recipient_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$barter_id = isset($_GET['barter_id']) ? intval($_GET['barter_id']) : 0;
$reply_to = isset($_GET['reply_to']) ? intval($_GET['reply_to']) : 0;

$recipient = null;
$barter_data = null;
$original_message = null;

// Handle reply
if ($reply_to) {
    $message_obj = new Message();
    $original_result = $message_obj->readInbox($current_user_id, 1, 0);
    // This is simplified - in a real app you'd get the specific message
}

// Get recipient info
if ($recipient_id) {
    $user = new User();
    if ($user->findById($recipient_id)) {
        $recipient = $user;
    }
}

// Get barter info
if ($barter_id) {
    $barter = new Barter();
    $barter_data = $barter->readOne($barter_id);

    // If barter exists and no recipient specified, use barter owner
    if ($barter_data && !$recipient_id) {
        $recipient_id = $barter_data['user_id'];
        $user = new User();
        if ($user->findById($recipient_id)) {
            $recipient = $user;
        }
    }
}

// Validation
if (!$recipient_id || $recipient_id == $current_user_id) {
    header('Location: browse.php');
    exit();
}

if (!$recipient) {
    header('Location: browse.php');
    exit();
}

// Process form submission
if ($_POST) {
    $subject = trim($_POST['subject'] ?? '');
    $message_text = trim($_POST['message'] ?? '');

    if (empty($subject) || empty($message_text)) {
        $error = 'Please fill in both subject and message.';
    } elseif (strlen($message_text) < 10) {
        $error = 'Message must be at least 10 characters long.';
    } else {
        $message = new Message();
        $message->sender_id = $current_user_id;
        $message->receiver_id = $recipient_id;
        $message->barter_id = $barter_id ?: null;
        $message->subject = $subject;
        $message->message = $message_text;

        if ($message->create()) {
            $success = 'Message sent successfully!';
        } else {
            $error = 'Error sending message. Please try again.';
        }
    }
}

$current_user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message - TradeTide</title>
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
                        <h2>Send Message</h2>
                        <p class="text-secondary">
                            <?php if ($reply_to): ?>
                                Reply to message
                            <?php elseif ($barter_data): ?>
                                Contact about: <?php echo htmlspecialchars($barter_data['title']); ?>
                            <?php else: ?>
                                Send a message to <?php echo htmlspecialchars($recipient->first_name); ?>
                            <?php endif; ?>
                        </p>
                        <div style="margin-top: 1rem;">
                            <a href="<?php echo $barter_data ? 'barter-details.php?id=' . $barter_id : 'messages.php'; ?>" class="text-secondary" style="text-decoration: none;">← Back</a>
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
                                    <a href="messages.php" class="btn btn-primary">View Messages</a>
                                    <?php if ($barter_data): ?>
                                        <a href="barter-details.php?id=<?php echo $barter_id; ?>" class="btn btn-secondary">Back to Barter</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Recipient Info -->
                            <div style="background: var(--background-color); padding: 1rem; border-radius: var(--border-radius); margin-bottom: 1.5rem;">
                                <h4>Sending to:</h4>
                                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                                    <div class="user-avatar" style="width: 50px; height: 50px;">
                                        <?php echo strtoupper(substr($recipient->first_name, 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;"><?php echo htmlspecialchars($recipient->first_name . ' ' . $recipient->last_name); ?></div>
                                        <?php if ($recipient->area): ?>
                                            <div style="font-size: 0.875rem; color: var(--text-secondary);">📍 <?php echo htmlspecialchars($recipient->area); ?></div>
                                        <?php endif; ?>
                                        <?php if ($recipient->rating > 0): ?>
                                            <div style="font-size: 0.875rem; color: var(--warning-color);">⭐ <?php echo number_format($recipient->rating, 1); ?> (<?php echo $recipient->total_trades; ?> trades)</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Barter Context -->
                            <?php if ($barter_data): ?>
                                <div style="background: var(--background-color); padding: 1rem; border-radius: var(--border-radius); margin-bottom: 1.5rem;">
                                    <h4>About this barter:</h4>
                                    <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                                        <div class="barter-image" style="width: 80px; height: 80px; font-size: 0.75rem;">
                                            <?php echo htmlspecialchars(substr($barter_data['title'], 0, 15)); ?>
                                        </div>
                                        <div>
                                            <h5><?php echo htmlspecialchars($barter_data['title']); ?></h5>
                                            <p style="color: var(--text-secondary); margin: 0; font-size: 0.875rem;">
                                                <?php echo htmlspecialchars(substr($barter_data['description'], 0, 100)); ?>...
                                            </p>
                                            <div style="margin-top: 0.5rem;">
                                                <span class="barter-category" style="font-size: 0.75rem;"><?php echo htmlspecialchars($barter_data['category']); ?></span>
                                                <span class="barter-type" style="font-size: 0.75rem; margin-left: 0.5rem;"><?php echo htmlspecialchars($barter_data['type']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <form method="POST" data-validate>
                                <div class="form-group">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <input type="text" id="subject" name="subject" class="form-control"
                                           value="<?php echo htmlspecialchars($_POST['subject'] ?? ($barter_data ? 'Re: ' . $barter_data['title'] : '')); ?>"
                                           placeholder="Message subject" required>
                                </div>

                                <div class="form-group">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea id="message" name="message" class="form-control" rows="8"
                                              placeholder="Write your message here..." required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                                    <small class="text-secondary">Minimum 10 characters. Be clear and respectful.</small>
                                </div>

                                <!-- Message Templates -->
                                <div style="margin-bottom: 1.5rem;">
                                    <h4>Quick Templates:</h4>
                                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem;">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="insertTemplate('trade_inquiry')">Trade Inquiry</button>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="insertTemplate('question')">Ask Question</button>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="insertTemplate('schedule')">Schedule Meeting</button>
                                    </div>
                                </div>

                                <!-- Your Info Summary -->
                                <div style="background: var(--background-color); padding: 1rem; border-radius: var(--border-radius); margin-bottom: 1.5rem;">
                                    <h4>Your contact info (will be shared):</h4>
                                    <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                                        <div class="user-avatar" style="width: 40px; height: 40px;">
                                            <?php echo strtoupper(substr($current_user->first_name, 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600;"><?php echo htmlspecialchars($current_user->first_name . ' ' . $current_user->last_name); ?></div>
                                            <?php if ($current_user->area): ?>
                                                <div style="font-size: 0.875rem; color: var(--text-secondary);">📍 <?php echo htmlspecialchars($current_user->area); ?></div>
                                            <?php endif; ?>
                                            <?php if ($current_user->skills): ?>
                                                <div style="font-size: 0.875rem; color: var(--text-secondary);">
                                                    <strong>Skills:</strong> <?php echo htmlspecialchars(substr($current_user->skills, 0, 50)); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                                        Send Message
                                    </button>
                                </div>

                                <div class="text-center">
                                    <p class="text-secondary">
                                        <a href="<?php echo $barter_data ? 'barter-details.php?id=' . $barter_id : 'messages.php'; ?>" class="text-primary">Cancel</a>
                                    </p>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
    <script>
        function insertTemplate(type) {
            const messageField = document.getElementById('message');
            let template = '';

            switch(type) {
                case 'trade_inquiry':
                    template = "Hi there!\n\nI'm interested in your barter offering. I'd like to discuss a potential trade with you.\n\nWhat I can offer in return: [Describe what you can trade]\n\nWhen would be a good time to discuss this further?\n\nBest regards,\n<?php echo $current_user->first_name; ?>";
                    break;
                case 'question':
                    template = "Hi!\n\nI have a question about your barter listing:\n\n[Your question here]\n\nThanks for your time!\n\n<?php echo $current_user->first_name; ?>";
                    break;
                case 'schedule':
                    template = "Hello!\n\nI'd like to schedule a time to discuss your barter offering. Are you available this week?\n\nSome times that work for me:\n- [Time option 1]\n- [Time option 2]\n- [Time option 3]\n\nLet me know what works best for you!\n\n<?php echo $current_user->first_name; ?>";
                    break;
            }

            messageField.value = template;
            messageField.focus();
        }
    </script>
</body>
</html>
