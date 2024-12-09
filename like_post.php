<?php
session_start();
include 'db.php';  // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];

// Check if the user has already liked the post
$sql = "SELECT COUNT(*) FROM likes WHERE post_id = :post_id AND user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$liked = $stmt->fetchColumn();

if ($liked == 0) {
    // Insert a new like
    $sql = "INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect back to the dashboard or wherever necessary
    header("Location: user_dashboard.php");
} else {
    // User has already liked the post, handle this case if needed
    header("Location: user_dashboard.php");
}
