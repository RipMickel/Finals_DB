<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch logged-in users
$sql_users = "SELECT u.user_id, u.username, u.email, 
                     (SELECT COUNT(*) FROM followers WHERE followed_id = u.user_id) AS followers_count
              FROM users u";
$stmt_users = $conn->query($sql_users);
$users = $stmt_users->fetchAll();

// Fetch posts
$sql_posts = "SELECT posts.id AS post_id, posts.content, users.username 
              FROM posts 
              JOIN users ON posts.user_id = users.user_id";
$stmt_posts = $conn->query($sql_posts);
$posts = $stmt_posts->fetchAll();

// Fetch books and their average ratings
$sql_books = "SELECT b.book_id, b.title, b.author, AVG(r.rating) AS average_rating
              FROM books b
              LEFT JOIN reviews r ON b.book_id = r.book_id
              GROUP BY b.book_id";

$stmt_books = $conn->query($sql_books);
$books = $stmt_books->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Admin Dashboard Container */
        .admin-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #f5f5f5;
        }

        /* Header Section */
        .admin-header {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header h1 {
            font-size: 24px;
            margin-left: 20px;
        }

        .logout-btn {
            text-decoration: none;
            background: #e74c3c;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        /* Content Section */
        .admin-content {
            flex-grow: 1;
            padding: 20px;
        }

        /* Sections */
        section {
            margin-bottom: 30px;
        }

        section h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background: #34495e;
            color: #ecf0f1;
        }

        table th,
        table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table tbody tr:hover {
            background: #f1f1f1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-header h1 {
                font-size: 20px;
            }

            table th,
            table td {
                padding: 10px;
            }

            .logout-btn {
                padding: 8px 15px;
            }
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1>Admin Dashboard</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </header>
        <div class="admin-content">
            <section>
                <h2>Logged-in Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Followers</th> <!-- Added Followers Column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['user_id']) ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['followers_count']) ?></td> <!-- Displaying Followers Count -->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
            <section>
                <h2>Posts</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Post ID</th>
                            <th>Content</th>
                            <th>Username</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?= htmlspecialchars($post['post_id']) ?></td>
                                <td><?= htmlspecialchars($post['content']) ?></td>
                                <td><?= htmlspecialchars($post['username']) ?></td>
                                <td><a href="delete_post.php?id=<?= $post['post_id'] ?>" class="delete-btn">Delete</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
            <section>
                <h2>Books and Ratings</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Book ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Average Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td><?= htmlspecialchars($book['book_id']) ?></td>
                                <td><?= htmlspecialchars($book['title']) ?></td>
                                <td><?= htmlspecialchars($book['author']) ?></td>
                                <td><?= htmlspecialchars(round($book['average_rating'], 2)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</body>

</html>