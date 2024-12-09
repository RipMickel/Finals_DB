<?php
session_start();
include 'db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user's posts
$sql = "SELECT content, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts</title>
    <style>
        /* Reset and Base Styles */
        body {
            font-family: 'Georgia', serif;
            background-color: #f4f1e1;
            background-image: url('https://www.transparenttextures.com/patterns/old-map.png');
            margin: 0;
            padding: 0;
        }

        /* Fancy Header Styles */
        header {
            color: #f8ede3;
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #734e3e;

        }

        header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }

        .home-btn {
            text-decoration: none;
            background: #ffffff;
            color: #6b4226;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .home-btn:hover {
            background-color: #8b2f2f;
            color: #ffffff;
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
        }

        /* Container Styles */
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 15px;
        }

        /* Post Card Styles */
        .post-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .post {
            background: wheat;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .post:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .post h3 {
            margin: 0 0 10px;
            font-size: 1.4rem;
            color: #1e293b;
        }

        .post p {
            margin: 0 0 10px;
            color: #475569;
            line-height: 1.6;
        }

        .post time {
            display: block;
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 10px;
        }

        /* No Posts Message */
        .no-posts {
            text-align: center;
            color: #475569;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Design */
        @media (min-width: 768px) {
            .post-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>
    <header>
        <a href="user_dashboard.php" class="home-btn">←</a>
        <h1>My Posts</h1>
    </header>
    <div class="container">
        <?php if (!empty($posts)): ?>
            <div class="post-list">
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <h3>My Post</h3>
                        <p><?= htmlspecialchars($post['content']) ?></p>
                        <time>Posted on: <?= htmlspecialchars(date('F j, Y, g:i a', strtotime($post['created_at']))) ?></time>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-posts">
                <p>You have not created any posts yet.</p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>