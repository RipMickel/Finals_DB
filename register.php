<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the username already exists
    $checkUsername = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($checkUsername);
    $stmt->execute(['username' => $username]);

    if ($stmt->rowCount() > 0) {
        $error = "Username already exists. Please choose another.";
    } else {
        // Check if the email already exists
        $checkEmail = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($checkEmail);
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $error = "Email already exists. Please use another.";
        } else {
            // Insert new user if username and email are unique
            $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password]);

            header('Location: login.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <h2>Register</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="register.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>

</html>