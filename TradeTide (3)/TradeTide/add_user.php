<?php
/**
 * Add User Script for TradeTide
 * This script adds a new user to the database
 */

require_once 'config/database.php';
require_once 'config/classes/User.php';

// User details
$user_data = [
    'first_name' => 'Nathi',
    'last_name' => 'User',
    'email' => 'Nathi@2004.co.za',
    'password' => '12345678',
    'phone' => '',
    'area' => 'Pretoria CBD',
    'bio' => 'New user on TradeTide platform',
    'skills' => 'General Services'
];

try {
    // Create user object
    $user = new User();
    
    // Check if email already exists
    if ($user->emailExists($user_data['email'])) {
        echo "❌ Error: User with email '{$user_data['email']}' already exists!\n";
        exit();
    }
    
    // Set user data
    $user->first_name = $user_data['first_name'];
    $user->last_name = $user_data['last_name'];
    $user->email = $user_data['email'];
    $user->password = $user_data['password']; // Will be hashed in create() method
    $user->phone = $user_data['phone'];
    $user->area = $user_data['area'];
    $user->bio = $user_data['bio'];
    $user->skills = $user_data['skills'];
    
    // Create the user
    if ($user->create()) {
        echo "✅ Success: User '{$user_data['first_name']}' has been added successfully!\n";
        echo "📧 Email: {$user_data['email']}\n";
        echo "🔑 Password: {$user_data['password']}\n";
        echo "📍 Area: {$user_data['area']}\n";
        echo "\n🎉 User can now login to TradeTide!\n";
    } else {
        echo "❌ Error: Failed to create user. Please check database connection.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>

