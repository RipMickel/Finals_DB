<?php
// Start session to check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
include('db.php'); // Ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    if (!empty($content)) {
        try {
            $stmt = $conn->prepare("INSERT INTO posts (user_id, content, created_at) VALUES (:user_id, :content, NOW())");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':content', $content);
            $stmt->execute();

            // Redirect to the newsfeed after successful post creation
            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Post content cannot be empty!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post - Book Lovers</title>

    <!-- Integrated CSS -->
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body and general styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f8fa;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container for centering form */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 20px;
        }

        /* Styling for the form container */
        .form-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        /* Form heading */
        h2 {
            color: #4a4a4a;
            margin-bottom: 20px;
        }

        /* Input Group for Content */
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        .input-group textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f1f1f1;
            resize: vertical;
        }

        .input-group textarea:focus {
            outline: none;
            border-color: #4c9f70;
            background-color: #fff;
        }

        /* Submit button */
        .btn-submit {
            background-color: #1da1f2;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 30px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #0d8ecf;
        }

        /* Back to newsfeed link */
        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #1da1f2;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <!-- Create Post Form Section -->
    <div class="container">
        <div class="form-container">
            <h2>What's on your mind?</h2>
            <form action="create_post.php" method="post">
                <div class="input-group">
                    <textarea name="content" id="content" rows="5" placeholder="Share something..." required></textarea>
                </div>

                <button type="submit" class="btn-submit">Post</button>
            </form>
            <a href="index.php" class="back-link">Back to Newsfeed</a>
        </div>
    </div>

</body>

</html>