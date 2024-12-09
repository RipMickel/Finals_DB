<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['followed_id'])) {
    $followed_id = $_POST['followed_id'];

    // Remove the follow relationship
    $sql = "DELETE FROM followers WHERE follower_id = :follower_id AND followed_id = :followed_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':follower_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':followed_id', $followed_id, PDO::PARAM_INT);
    $stmt->execute();
}

header("Location: user_dashboard.php");
exit;
