<?php
// api/check_session.php

session_start(); // Start the session to access $_SESSION variables
header('Content-Type: application/json');

$response = ['logged_in' => false];

if (isset($_SESSION['user_id'])) {
    $response['logged_in'] = true;
    // Optionally, send back some user details (NEVER password_hash)
    $response['user'] = [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'] ?? null // Use null if not always set
    ];
}

echo json_encode($response);
?>