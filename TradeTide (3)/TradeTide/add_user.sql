-- Add User Script for TradeTide
-- This script adds a new user to the database

USE tradetide_db;

-- Insert new user
INSERT INTO users (
    first_name, 
    last_name, 
    email, 
    password, 
    phone, 
    area, 
    bio, 
    skills, 
    rating, 
    total_trades
) VALUES (
    'Nathi',
    'User',
    'Nathi@2004.co.za',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- This is the hash for '12345678'
    '',
    'Pretoria CBD',
    'New user on TradeTide platform',
    'General Services',
    0.00,
    0
);

-- Verify the user was added
SELECT 
    id,
    first_name,
    last_name,
    email,
    area,
    created_at
FROM users 
WHERE email = 'Nathi@2004.co.za';

