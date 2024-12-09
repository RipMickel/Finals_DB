<?php
include 'db.php';

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    $reviews_sql = "SELECT r.review, r.rating, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.book_id = :book_id";
    $reviews_stmt = $conn->prepare($reviews_sql);
    $reviews_stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
    $reviews_stmt->execute();

    $reviews = $reviews_stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($reviews);
}
