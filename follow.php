<?php
session_start();
include 'db.php';  // Make sure to include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if follow/unfollow action is set
if (isset($_POST['followed_id']) && isset($_POST['action'])) {
    $followed_id = $_POST['followed_id'];
    $action = $_POST['action']; // "follow" or "unfollow"

    // Ensure followed_id exists in the users table
    $check_user_sql = "SELECT COUNT(*) FROM users WHERE user_id = :followed_id";
    $check_user_stmt = $conn->prepare($check_user_sql);
    $check_user_stmt->bindParam(':followed_id', $followed_id, PDO::PARAM_INT);
    $check_user_stmt->execute();
    $user_exists = $check_user_stmt->fetchColumn();

    if ($user_exists) {
        if ($action === 'follow') {
            // Check if already following
            $check_follow_sql = "SELECT COUNT(*) FROM followers WHERE follower_id = :follower_id AND followed_id = :followed_id";
            $check_follow_stmt = $conn->prepare($check_follow_sql);
            $check_follow_stmt->bindParam(':follower_id', $user_id, PDO::PARAM_INT);
            $check_follow_stmt->bindParam(':followed_id', $followed_id, PDO::PARAM_INT);
            $check_follow_stmt->execute();
            $is_following = $check_follow_stmt->fetchColumn();

            if (!$is_following) {
                // Add follow relationship
                $sql = "INSERT INTO followers (follower_id, followed_id) VALUES (:follower_id, :followed_id)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':follower_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':followed_id', $followed_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        } elseif ($action === 'unfollow') {
            // Remove follow relationship
            $sql = "DELETE FROM followers WHERE follower_id = :follower_id AND followed_id = :followed_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':follower_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':followed_id', $followed_id, PDO::PARAM_INT);
            $stmt->execute();
        }
    } else {
        // If the user being followed does not exist, redirect to follow page with an error
        $_SESSION['error'] = 'User does not exist.';
        header("Location: follow.php");
        exit;
    }
}

// Redirect back to the follow page to see the updated list of users
header("Location: follow.php");
exit;
