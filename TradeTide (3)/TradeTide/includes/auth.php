<?php
session_start();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Get current user ID
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

// Get current user data
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }

    require_once __DIR__ . '/../config/classes/User.php';
    $user = new User();
    if ($user->findById($_SESSION['user_id'])) {
        return $user;
    }
    return null;
}

// Login user
function loginUser($user_id, $email, $first_name, $last_name) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['email'] = $email;
    $_SESSION['first_name'] = $first_name;
    $_SESSION['last_name'] = $last_name;
}

// Logout user
function logoutUser() {
    session_unset();
    session_destroy();
}

// Require authentication (redirect if not logged in)
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: ../auth/login.php');
        exit();
    }
}

// Redirect if already logged in
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        header('Location: ../dashboard/index.php');
        exit();
    }
}

// Generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Get user's full name
function getUserFullName() {
    if (isLoggedIn()) {
        return $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
    }
    return '';
}
?>

