<?php
// Database connection configuration
$host = 'localhost'; // Database host
$dbname = 'booklovers'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    // Create PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection errors
    die("Database connection failed: " . $e->getMessage());
}
