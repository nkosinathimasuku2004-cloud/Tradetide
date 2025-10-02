<?php
// TradeTide Configuration File

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'tradetide_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('APP_NAME', 'TradeTide');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/TradeTide');
define('APP_TIMEZONE', 'Africa/Johannesburg');

// Security Configuration
define('SESSION_LIFETIME', 3600); // 1 hour
define('CSRF_TOKEN_LIFETIME', 3600); // 1 hour
define('PASSWORD_MIN_LENGTH', 6);

// File Upload Configuration
define('UPLOAD_MAX_SIZE', 5242880); // 5MB
define('UPLOAD_ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('UPLOAD_PATH', 'uploads/');

// Email Configuration (for future use)
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('FROM_EMAIL', 'noreply@tradetide.co.za');
define('FROM_NAME', 'TradeTide');

// Pagination Configuration
define('ITEMS_PER_PAGE', 12);
define('MESSAGES_PER_PAGE', 20);

// Pretoria Areas
define('PRETORIA_AREAS', [
    'Arcadia', 'Brooklyn', 'Centurion', 'Garsfontein', 'Hatfield', 'Menlyn',
    'Moot', 'Muckleneuk', 'Pretoria CBD', 'Pretoria East', 'Pretoria North',
    'Pretoria West', 'Sunnyside', 'Waterkloof', 'Wonderboom', 'Lynnwood',
    'Faerie Glen', 'Monument Park', 'Colbyn', 'Erasmuskloof'
]);

// Barter Categories
define('BARTER_CATEGORIES', [
    'Technology', 'Food & Catering', 'Home & Garden', 'Education',
    'Health & Wellness', 'Transportation', 'Entertainment', 'Business Services',
    'Art & Crafts', 'Sports & Fitness', 'Other'
]);

// Barter Types
define('BARTER_TYPES', ['service', 'item']);

// Barter Statuses
define('BARTER_STATUSES', ['active', 'pending', 'completed', 'cancelled']);

// Trade Request Statuses
define('TRADE_REQUEST_STATUSES', ['pending', 'accepted', 'rejected', 'cancelled']);

// Message Statuses
define('MESSAGE_STATUSES', ['unread', 'read']);

// Set timezone
date_default_timezone_set(APP_TIMEZONE);

// Error reporting (disable in production)
if (defined('DEBUG') && DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>

