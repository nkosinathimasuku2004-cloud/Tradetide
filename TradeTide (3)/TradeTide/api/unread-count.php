<?php
require_once '../includes/auth.php';
require_once '../config/classes/Message.php';

// Require authentication
requireAuth();

header('Content-Type: application/json');

$user_id = getCurrentUserId();

try {
    $message = new Message();
    $unread_count = $message->countUnread($user_id);
    
    echo json_encode(['count' => $unread_count]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['count' => 0, 'error' => 'Server error']);
}
?>

