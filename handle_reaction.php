<?php
// Start session to ensure user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Database connection (Adjust DB settings as needed)
try {
    $pdo = new PDO('mysql:host=localhost;dbname=booklovers', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle the reaction (Like or Heart)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reaction_type'], $_POST['post_id'])) {
    $reaction_type = $_POST['reaction_type']; // either 'like' or 'heart'
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    // Check if the user has already reacted
    $stmt_check = $pdo->prepare("SELECT id FROM reactions WHERE post_id = :post_id AND user_id = :user_id AND reaction_type = :reaction_type");
    $stmt_check->bindParam(':post_id', $post_id);
    $stmt_check->bindParam(':user_id', $user_id);
    $stmt_check->bindParam(':reaction_type', $reaction_type);
    $stmt_check->execute();

    if ($stmt_check->rowCount() == 0) {
        // Insert the reaction if the user hasn't already reacted
        $stmt_insert = $pdo->prepare("INSERT INTO reactions (post_id, user_id, reaction_type) VALUES (:post_id, :user_id, :reaction_type)");
        $stmt_insert->bindParam(':post_id', $post_id);
        $stmt_insert->bindParam(':user_id', $user_id);
        $stmt_insert->bindParam(':reaction_type', $reaction_type);
        $stmt_insert->execute();
    }

    // Redirect back to the newsfeed page or the page with the post
    header("Location: newsfeed.php");
    exit();
}
