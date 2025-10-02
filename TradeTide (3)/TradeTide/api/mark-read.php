<?php
require_once '../includes/auth.php';
require_once '../config/classes/Message.php';

// Require authentication
requireAuth();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['message_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Message ID required']);
    exit();
}

$message_id = intval($input['message_id']);
$user_id = getCurrentUserId();

if (!$message_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid message ID']);
    exit();
}

try {
    $message = new Message();
    $success = $message->markAsRead($message_id, $user_id);
    
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to mark message as read']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error']);
}
?>

