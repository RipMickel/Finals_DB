<?php
// Start session to check if user is logged in
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

// Fetch the username of the logged-in user
$user_id = $_SESSION['user_id'];
$stmt_user = $pdo->prepare("SELECT username FROM users WHERE id = :user_id");
$stmt_user->bindParam(':user_id', $user_id);
$stmt_user->execute();
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);
$username = $user['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Lovers - Newsfeed</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
            color: #333;
        }

        header {
            background-color: #6c5ce7;
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        header .logo h1 {
            font-size: 24px;
        }

        header nav ul {
            display: flex;
            list-style: none;
        }

        header nav ul li {
            margin-left: 20px;
        }

        header nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        .content-area {
            display: flex;
            flex-direction: row;
            margin-top: 20px;
        }

        .sidebar {
            width: 250px;
            background-color: #6c5ce7;
            height: 100vh;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            padding: 15px 20px;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            display: block;
        }

        .sidebar ul li a:hover {
            background-color: #5a4db2;
            border-radius: 8px;
        }

        main.newsfeed {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .newsfeed h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .news-posts {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .news-posts .post {
            background-color: #f7f7f7;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .news-posts .post h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .news-posts .post p {
            font-size: 16px;
        }

        .news-posts .post .reactions {
            margin-top: 10px;
        }

        .news-posts .post .reactions button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            font-size: 18px;
            margin-right: 10px;
        }

        .news-posts .post .comments {
            margin-top: 20px;
        }

        .news-posts .post .comments textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .news-posts .post .comments .comment {
            margin-top: 10px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }

        .toggle-comments-btn {
            background-color: #6c5ce7;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            margin-top: 10px;
        }

        .toggle-comments-btn:hover {
            background-color: #5a4db2;
        }

        .comments-container {
            display: none;
            /* Initially hide comments */
            margin-top: 10px;
        }
    </style>
    <script>
        function toggleComments(postId) {
            var commentContainer = document.getElementById('comments-' + postId);
            var btn = document.getElementById('toggle-btn-' + postId);

            if (commentContainer.style.display === "none" || commentContainer.style.display === "") {
                commentContainer.style.display = "block";
                btn.textContent = "Hide Comments";
            } else {
                commentContainer.style.display = "none";
                btn.textContent = "Show Comments";
            }
        }
    </script>
</head>

<body>

    <header>
        <div class="logo">
            <h1>📚 Book Lovers</h1>
        </div>
        <nav>
            <ul>
                <li><a href="newsfeed.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="content-area">
        <!-- Sidebar Section -->
        <aside class="sidebar">
            <ul>
                <li><a href="create_post.php">✍️ Create Post</a></li>
                <li><a href="reviews.php">📖 Reviews</a></li>
                <li><a href="follow_users.php">👥 Follow Users</a></li>
                <li><a href="profile.php">👤 View Profile</a></li>
            </ul>
        </aside>

        <!-- Main Content / Newsfeed Section -->
        <main class="newsfeed">
            <h1>📢 Newsfeed</h1>

            <!-- Sample Posts -->
            <div class="news-posts">
                <!-- Dynamic Posts from Database -->
                <?php
                $stmt = $pdo->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
                while ($post = $stmt->fetch()) {
                    // Get the like count for the post
                    $stmt_reactions_like = $pdo->prepare("SELECT COUNT(*) AS like_count FROM reactions WHERE post_id = :post_id AND reaction_type = 'like'");
                    $stmt_reactions_like->bindParam(':post_id', $post['id']);
                    $stmt_reactions_like->execute();
                    $like_count = $stmt_reactions_like->fetchColumn();

                    // Get the heart count for the post
                    $stmt_reactions_heart = $pdo->prepare("SELECT COUNT(*) AS heart_count FROM reactions WHERE post_id = :post_id AND reaction_type = 'heart'");
                    $stmt_reactions_heart->bindParam(':post_id', $post['id']);
                    $stmt_reactions_heart->execute();
                    $heart_count = $stmt_reactions_heart->fetchColumn();
                ?>
                    <div class="post">
                        <h3><?php echo htmlspecialchars($post['username']); ?></h3>
                        <p><?php echo htmlspecialchars($post['content']); ?></p>
                        <small>Posted on: <?php echo htmlspecialchars($post['created_at']); ?></small>

                        <!-- Reactions (Like and Heart) -->
                        <div class="reactions">
                            <form action="handle_reaction.php" method="POST" style="display:inline;">
                                <button type="submit" name="reaction_type" value="like">👍 Like (<?php echo $like_count; ?>)</button>
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            </form>
                            <form action="handle_reaction.php" method="POST" style="display:inline;">
                                <button type="submit" name="reaction_type" value="heart">❤️ Heart (<?php echo $heart_count; ?>)</button>
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            </form>
                        </div>

                        <!-- Show/Hide Comments Button -->
                        <button id="toggle-btn-<?php echo $post['id']; ?>" class="toggle-comments-btn" onclick="toggleComments(<?php echo $post['id']; ?>)">Show Comments</button>

                        <!-- Comments Section -->
                        <div id="comments-<?php echo $post['id']; ?>" class="comments-container">
                            <?php
                            $stmt_comments = $pdo->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE post_id = :post_id ORDER BY comments.created_at ASC");
                            $stmt_comments->bindParam(':post_id', $post['id']);
                            $stmt_comments->execute();
                            while ($comment = $stmt_comments->fetch()) { ?>
                                <div class="comment">
                                    <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                                    <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- Comment Form -->
                        <form action="post_comment.php" method="POST">
                            <textarea name="comment" placeholder="Write a comment..." required></textarea>
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <button type="submit">Post Comment</button>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </main>
    </div>

</body>

</html>