-- TradeTide Database Schema
-- This schema matches the PHP classes structure

CREATE DATABASE IF NOT EXISTS tradetide_db;
USE tradetide_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    area VARCHAR(100),
    bio TEXT,
    skills TEXT,
    profile_image VARCHAR(255),
    rating DECIMAL(3,2) DEFAULT 0.00,
    total_trades INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Barters table
CREATE TABLE IF NOT EXISTS barters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    type ENUM('service', 'item') NOT NULL DEFAULT 'service',
    value_range VARCHAR(100),
    location VARCHAR(255),
    images TEXT,
    status ENUM('active', 'pending', 'completed', 'cancelled') DEFAULT 'active',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Messages table
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    barter_id INT,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (barter_id) REFERENCES barters(id) ON DELETE CASCADE
);

-- Trade requests table
CREATE TABLE IF NOT EXISTS trade_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_id INT NOT NULL,
    receiver_id INT NOT NULL,
    barter_id INT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected', 'cancelled') DEFAULT 'pending',
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (barter_id) REFERENCES barters(id) ON DELETE CASCADE
);

-- Insert demo users
INSERT INTO users (first_name, last_name, email, password, phone, area, bio, skills, rating, total_trades) VALUES
('Sipho', 'Mthembu', 'sipho@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '082 123 4567', 'Garsfontein', 'Professional web developer with 5+ years experience', 'Web Development, PHP, JavaScript, CSS', 4.8, 15),
('Nomsa', 'Van Der Merwe', 'nomsa@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '083 987 6543', 'Menlyn', 'Traditional South African cooking expert', 'Cooking, Catering, Braai Services', 4.9, 23),
('Lerato', 'Sithole', 'lerato@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '084 555 1234', 'Brooklyn', 'Language tutor specializing in South African languages', 'Language Tutoring, Afrikaans, Zulu, English', 4.7, 12);

-- Insert demo barters
INSERT INTO barters (user_id, title, description, category, type, value_range, location, status) VALUES
(1, 'Professional Website Development', 'I can create modern, responsive websites using latest technologies. Perfect for small businesses looking to establish online presence.', 'Technology', 'service', 'R500-R2000', 'Garsfontein', 'active'),
(2, 'Traditional Braai Catering', 'Authentic South African braai experience for your events. Includes boerewors, lamb chops, and traditional sides.', 'Food & Catering', 'service', 'R300-R800', 'Menlyn', 'active'),
(3, 'Afrikaans & Zulu Language Lessons', 'Learn South African languages with a native speaker. Individual or group sessions available.', 'Education', 'service', 'R150-R300', 'Brooklyn', 'active'),
(1, 'Mobile App Development', 'Custom mobile applications for iOS and Android platforms using React Native.', 'Technology', 'service', 'R1000-R5000', 'Garsfontein', 'active'),
(2, 'Home-cooked Traditional Meals', 'Delicious traditional South African meals delivered to your home. Perfect for busy families.', 'Food & Catering', 'service', 'R200-R500', 'Menlyn', 'active');

-- Create indexes for better performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_barters_user_id ON barters(user_id);
CREATE INDEX idx_barters_category ON barters(category);
CREATE INDEX idx_barters_status ON barters(status);
CREATE INDEX idx_messages_sender_id ON messages(sender_id);
CREATE INDEX idx_messages_receiver_id ON messages(receiver_id);
CREATE INDEX idx_trade_requests_requester_id ON trade_requests(requester_id);
CREATE INDEX idx_trade_requests_receiver_id ON trade_requests(receiver_id);

