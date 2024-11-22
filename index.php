<?php
session_start();

// Redirect user to the dashboard if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit;
}
if (isset($_SESSION['user_id'])) {
    header("Location: user_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Lovers Platform</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="index-container">
        <header>
            <h1>Welcome to Book Lovers Platform</h1>
            <p>Connect with other book enthusiasts, post your reviews, and discover new reads!</p>
        </header>
        <div class="login-options">
            <a href="user_login.php" class="btn">User Login</a>
            <a href="admin_login.php" class="btn admin-btn">Admin Login</a>
        </div>
    </div>
</body>

</html>