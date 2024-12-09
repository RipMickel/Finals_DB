<?php
session_start();
include 'db.php';  // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get the user profile based on the ID in the URL
if (isset($_GET['id'])) {
    $profile_id = $_GET['id'];

    // Fetch the user data
    $sql = "SELECT id, username FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $profile_id, PDO::PARAM_INT);
    $stmt->execute();
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$profile) {
        echo "User not found.";
        exit;
    }

    // Fetch the user's posts (using the correct column name, assumed as 'content' here)
    $sql = "SELECT content FROM posts WHERE user_id = :id ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $profile_id, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "No user specified.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?php echo htmlspecialchars($profile['username']); ?></title>
    <style>
        /* Basic Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f1e1;
            color: #4e3629;
            margin: 0;
            padding: 0;
            background-image: url('https://www.transparenttextures.com/patterns/coffee-stains.png');
            background-repeat: repeat;
        }

        /* Navigation Bar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #734e3e;
            color: #fff;
        }

        .navbar a {
            text-decoration: none;
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .navbar a:hover {
            background-color: #a33b3b;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            color: #8b2f2f;
            margin-top: 30px;
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            color: #6b4226;
        }

        /* Post Styling */
        .post {
            background-color: #fff;
            border-radius: 8px;
            margin: 20px;
            padding: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .post p {
            color: #4e3629;
            font-size: 1.1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar a {
                padding: 10px;
                font-size: 1rem;
            }

            h1,
            h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="index.php">←</a>
        <a href="follow.php">Follow Users</a>
    </div>

    <h1><?php echo htmlspecialchars($profile['username']); ?>'s Profile</h1>

    <!-- Display posts -->
    <h2>Posts by <?php echo htmlspecialchars($profile['username']); ?>:</h2>

    <?php
    if ($posts) {
        foreach ($posts as $post) {
            echo "<div class='post'>";
            echo "<p>" . nl2br(htmlspecialchars($post['content'])) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No posts found.</p>";
    }
    ?>

</body>

</html>