<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';  // Database connection

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle post creation
    $post_content = $_POST['post_content'];

    // Insert the post content into the database
    $sql = "INSERT INTO posts (user_id, content) VALUES (:user_id, :content)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':content', $post_content, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Redirect to the dashboard after posting
        header("Location: user_dashboard.php");
        exit;
    } else {
        echo "There was an error while creating your post.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Georgia', serif;
            background-color: #fdf6e3;
            background-image: url('https://www.transparenttextures.com/patterns/old-map.png');
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
            font-size: 36px;
            color: #4b3832;
            font-weight: bold;
            letter-spacing: 2px;
        }

        /* Container for the form */
        .post-form-container {
            width: 60%;
            margin: 40px auto;
            background-color: #fff8e1;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            font-size: 16px;
            line-height: 1.6;
        }

        .post-form-container textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            font-family: 'Georgia', serif;
            resize: vertical;
            background-color: #fffdf0;
        }

        .post-form-container textarea:focus {
            border-color: #6b4226;
            outline: none;
            background-color: #fffbe0;
        }

        .post-form-container button {
            background-color: #6b4226;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 18px;
            margin-top: 20px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .post-form-container button:hover {
            background-color: #8c5431;
        }

        /* Button container for cancel (optional) */
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .button-container a {
            color: #6b4226;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }

        .button-container a:hover {
            text-decoration: underline;
        }

        /* Header Navigation Bar */
        .navbar {
            background-color: #6b4226;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .navbar a:hover {
            color: #ffca85;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .post-form-container {
                width: 90%;
            }

            h1 {
                font-size: 28px;
            }

            .navbar a {
                font-size: 16px;
                margin: 0 10px;
            }
        }
    </style>
</head>

<body>
    <h1>Create a New Post</h1>
    <div class="post-form-container">
        <form method="POST" action="">
            <textarea name="post_content" placeholder="Share your thoughts..." rows="6" required></textarea>
            <div class="button-container">
                <button type="submit">Post</button>
                <!-- Optional Cancel button, linking back to dashboard or main page -->
                <a href="user_dashboard.php">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>