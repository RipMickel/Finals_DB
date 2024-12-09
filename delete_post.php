<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    $sql = "DELETE FROM posts WHERE id = :post_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['post_id' => $post_id]);

    header("Location: admin_dashboard.php");
    exit;
} else {
    echo "Invalid request!";
}
