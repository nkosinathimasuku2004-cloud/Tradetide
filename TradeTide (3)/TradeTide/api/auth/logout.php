<?php
require_once '../includes/auth.php';

// Logout user
logoutUser();

// Redirect to home page
header('Location: ../../Website pages/pages/index.php');
exit();
?>