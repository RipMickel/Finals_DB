<?php
// Database connection settings
$servername = "localhost";  // Change to your server address if different
$username = "root";         // Your database username
$password = "";             // Your database password
$dbname = "book_lovers";    // Your database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate star rating
function generateStars($rating)
{
    $rating = max(0, min(5, $rating)); // Ensure rating is between 0 and 5
    $fullStars = floor($rating);  // Number of full stars
    $halfStar = ($rating - $fullStars >= 0.5) ? true : false;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

    $starsHtml = str_repeat('★', $fullStars);  // Full stars
    if ($halfStar) {
        $starsHtml .= '☆';  // Half star
    }
    $starsHtml .= str_repeat('☆', $emptyStars);  // Empty stars

    return $starsHtml;
}

// SQL query to get books from the books table
$sql_books = "SELECT book_title, author, cover_image FROM books ORDER BY book_title ASC";
$result_books = $conn->query($sql_books);

// Check if the query was successful
if (!$result_books) {
    die("Error executing query: " . $conn->error);  // Shows the specific error if the query fails
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Reviews</title>
    <style>
        /* Book list styling */
        .book-card {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            text-align: center;
        }

        .book-card img {
            width: 150px;
            height: 200px;
            object-fit: cover;
        }

        .book-card h3 {
            font-size: 1.2em;
            color: #333;
        }

        .book-card p {
            font-size: 1em;
            color: #555;
        }
    </style>
</head>

<body>

    <h2>Recent Books</h2>

    <?php
    // Check if there are books
    if ($result_books->num_rows > 0) {
        // Output data for each book
        while ($row = $result_books->fetch_assoc()) {
            // Check if the image path is not empty or invalid
            $coverImagePath = htmlspecialchars($row['cover_image']);
            if (empty($coverImagePath)) {
                $coverImagePath = 'images/default-cover.jpg';  // Use a default cover image if not set
            }

            // Display the book
            echo "<div class='book-card'>";
            echo "<img src='" . $coverImagePath . "' alt='Book Cover'>";  // Corrected the image path
            echo "<h3>" . htmlspecialchars($row['book_title']) . "</h3>";
            echo "<p><strong>Author:</strong> " . htmlspecialchars($row['author']) . "</p>";
            echo "</div><hr>";
        }
    } else {
        echo "No books found.";
    }

    // Close the database connection
    $conn->close();
    ?>
</body>

</html>