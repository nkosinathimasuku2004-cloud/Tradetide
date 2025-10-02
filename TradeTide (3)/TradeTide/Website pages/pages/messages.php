<?php
require_once '../../includes/auth.php';
require_once '../../config/classes/Message.php';

requireAuth();

$user_id = getCurrentUserId();
$message = new Message();

// Get tab selection
$tab = $_GET['tab'] ?? 'inbox';

// Handle message actions
if ($_POST) {
    if (isset($_POST['action']) && $_POST['action'] === 'send') {
        $receiver_id = intval($_POST['receiver_id']);
        $barter_id = intval($_POST['barter_id']) ?: null;
        $subject = trim($_POST['subject']);
        $message_text = trim($_POST['message']);

        if ($receiver_id && $subject && $message_text) {
            $message->sender_id = $user_id;
            $message->receiver_id = $receiver_id;
            $message->barter_id = $barter_id;
            $message->subject = $subject;
            $message->message = $message_text;

            if ($message->create()) {
                $success = 'Message sent successfully!';
            } else {
                $error = 'Error sending message.';
            }
        }
    }
}

// Get messages based on tab
if ($tab === 'sent') {
    $messages_result = $message->readSent($user_id);
} else {
    $messages_result = $message->readInbox($user_id);
}

$messages = $messages_result->fetchAll(PDO::FETCH_ASSOC);
$unread_count = $message->countUnread($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - TradeTide</title>
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
                    <li><a href="messages.php" class="active">Messages</a></li>
                </ul>
            </nav>
            <div class="nav-buttons">
                <a href="post-barter.php" class="btn btn-primary">Post Barter</a>
                <a href="../../api/auth/logout.php" class="btn btn-secondary">Sign Out</a>
            </div>
        </div>
    </header>

    <div class="container" style="padding: 2rem 0;">
        <div class="mb-4">
            <h1>Messages</h1>
            <p class="text-secondary">Communicate with other traders and manage your conversations</p>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Message Tabs -->
        <div class="card">
            <div class="card-header" style="padding: 0;">
                <div style="display: flex; border-bottom: 1px solid var(--border-light);">
                    <a href="?tab=inbox"
                       style="padding: 1rem 2rem; text-decoration: none; color: var(--text-primary); border-bottom: 3px solid <?php echo $tab === 'inbox' ? 'var(--primary-color)' : 'transparent'; ?>;">
                        Inbox <?php if ($unread_count > 0): ?><span class="unread-badge" style="background: var(--error-color); color: white; border-radius: 50%; padding: 2px 6px; font-size: 0.75rem; margin-left: 4px;"><?php echo $unread_count; ?></span><?php endif; ?>
                    </a>
                    <a href="?tab=sent"
                       style="padding: 1rem 2rem; text-decoration: none; color: var(--text-primary); border-bottom: 3px solid <?php echo $tab === 'sent' ? 'var(--primary-color)' : 'transparent'; ?>;">
                        Sent
                    </a>
                </div>
            </div>
            <div class="card-body" style="padding: 0;">
                <?php if (count($messages) > 0): ?>
                    <div class="message-list">
                        <?php foreach ($messages as $msg): ?>
                            <div class="message-item <?php echo (!$msg['is_read'] && $tab === 'inbox') ? 'unread' : ''; ?>"
                                 onclick="openMessage(<?php echo $msg['id']; ?>)"
                                 style="cursor: pointer;">
                                <div class="message-header">
                                    <span class="message-sender">
                                        <?php
                                        if ($tab === 'sent') {
                                            echo 'To: ' . htmlspecialchars($msg['receiver_first_name'] . ' ' . $msg['receiver_last_name']);
                                        } else {
                                            echo 'From: ' . htmlspecialchars($msg['sender_first_name'] . ' ' . $msg['sender_last_name']);
                                        }
                                        ?>
                                    </span>
                                    <span class="message-time"><?php echo date('M j, g:i A', strtotime($msg['created_at'])); ?></span>
                                </div>
                                <div class="message-subject"><?php echo htmlspecialchars($msg['subject']); ?></div>
                                <div class="message-preview">
                                    <?php echo htmlspecialchars(substr($msg['message'], 0, 100)); ?>
                                    <?php if (strlen($msg['message']) > 100): ?>...<?php endif; ?>
                                </div>
                                <?php if ($msg['barter_title']): ?>
                                    <div style="margin-top: 0.5rem;">
                                        <small style="color: var(--primary-color);">Re: <?php echo htmlspecialchars($msg['barter_title']); ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center" style="padding: 4rem 2rem;">
                        <div style="font-size: 3rem; color: var(--text-secondary); margin-bottom: 1rem;">📬</div>
                        <h3>No messages yet</h3>
                        <p class="text-secondary">
                            <?php if ($tab === 'sent'): ?>
                                You haven't sent any messages yet.
                            <?php else: ?>
                                Your inbox is empty. Start browsing barters to connect with other traders.
                            <?php endif; ?>
                        </p>
                        <a href="browse.php" class="btn btn-primary">Browse Barters</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;" onclick="closeModal()">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: var(--border-radius); max-width: 600px; width: 90%; max-height: 80%; overflow-y: auto;" onclick="event.stopPropagation()">
            <div id="messageContent"></div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
    <script>
        function openMessage(messageId) {
            // In a real implementation, this would fetch message details via AJAX
            // For now, we'll just show a simple modal
            const modal = document.getElementById('messageModal');
            const content = document.getElementById('messageContent');

            content.innerHTML = `
                <div style="padding: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                        <h3>Message Details</h3>
                        <button onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
                    </div>
                    <p>Message details would be loaded here via AJAX in a full implementation.</p>
                    <div style="margin-top: 2rem;">
                        <button onclick="replyToMessage()" class="btn btn-primary">Reply</button>
                        <button onclick="closeModal()" class="btn btn-secondary">Close</button>
                    </div>
                </div>
            `;

            modal.style.display = 'block';

            // Mark as read if it's an inbox message
            <?php if ($tab === 'inbox'): ?>
            markMessageAsRead(messageId);
            <?php endif; ?>
        }

        function closeModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        function replyToMessage() {
            // In a real implementation, this would open a reply form
            alert('Reply functionality would be implemented here');
        }

        function markMessageAsRead(messageId) {
            fetch('../../api/mark-read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message_id: messageId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Refresh to update unread count
                }
            });
        }
    </script>
</body>
</html>
