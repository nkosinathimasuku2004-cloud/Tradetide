<?php
$servername = "localhost"; // XAMPP default
$username = "root";       // XAMPP default, no password
$password = "";           // XAMPP default, no password
$dbname = "tradetide_db"; // The name you chose in step 2

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // In development, you can show the error. In production, log it.
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully to database!"; // For testing connection
?>