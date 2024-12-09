<?php
// Start session to check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include the database connection file
include('db.php'); // Ensure this path is correct

// Check if $conn is defined
if (!isset($conn)) {
    die("Database connection is not established.");
}

// Check if a comment is being submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $post_id = $_POST['post_id'];  // The ID of the post being commented on
    $user_id = $_SESSION['user_id']; // The ID of the logged-in user
    $comment = $_POST['comment']; // The comment content

    if (!empty($comment)) {
        try {
            // Insert the comment into the database
            $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (:post_id, :user_id, :comment)");
            $stmt->bindParam(':post_id', $post_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':comment', $comment);
            $stmt->execute();

            // After comment is posted, redirect to the index page
            header("Location: index.php"); // Redirect to newsfeed after posting comment
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Comment cannot be empty!";
    }
}

// Fetch the post based on `post_id` (for displaying comments if needed)
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Fetch post details
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = :post_id");
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
    $post = $stmt->fetch();

    if (!$post) {
        die("Post not found.");
    }

    // Fetch comments for the post
    $stmt_comments = $conn->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = :post_id ORDER BY comments.created_at DESC");
    $stmt_comments->bindParam(':post_id', $post_id);
    $stmt_comments->execute();
    $comments = $stmt_comments->fetchAll();
} else {
    die("Post ID is required.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Comments</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .post {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .comments-section {
            margin-top: 20px;
        }

        .comment {
            background-color: #fafafa;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .toggle-comments-btn {
            background-color: #6c5ce7;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }

        .toggle-comments-btn:hover {
            background-color: #5a4db2;
        }

        .comment-container {
            display: none;
            /* Initially hide comments */
        }
    </style>
    <script>
        function toggleComments() {
            var commentContainer = document.getElementById("comment-container");
            var btn = document.getElementById("toggle-btn");

            // Toggle the display property
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

    <div class="post">
        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
    </div>

    <!-- Comment Section -->
    <div class="comments-section">
        <button id="toggle-btn" class="toggle-comments-btn" onclick="toggleComments()">Show Comments</button>

        <!-- Comment Container -->
        <div id="comment-container" class="comment-container">
            <?php foreach ($comments as $comment) { ?>
                <div class="comment">
                    <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                    <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                    <small>Posted on: <?php echo htmlspecialchars($comment['created_at']); ?></small>
                </div>
            <?php } ?>
        </div>

        <!-- Comment Form -->
        <form action="post_comment.php" method="POST">
            <textarea name="comment" placeholder="Write a comment..." required></textarea>
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <button type="submit" class="toggle-comments-btn">Post Comment</button>
        </form>
    </div>

</body>

</html>