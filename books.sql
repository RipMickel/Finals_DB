-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 02:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `book_lovers`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `published_date` date DEFAULT NULL,
  `book_title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `cover_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `published_date`, `book_title`, `author`, `cover_image`) VALUES
(17, 'To Kill a Mockingbird', NULL, '', 'Harper Lee', 'https://cdn.britannica.com/21/182021-050-666DB6B1/book-cover-To-Kill-a-Mockingbird-many-1961.jpg'),
(18, '1984', NULL, '', 'George Orwell', 'https://covers.openlibrary.org/b/id/7222246-L.jpg'),
(19, 'Pride and Prejudice', NULL, '', 'Jane Austen', 'https://covers.openlibrary.org/b/id/6564102-L.jpg'),
(20, 'The Great Gatsby', NULL, '', 'F. Scott Fitzgerald', 'https://covers.openlibrary.org/b/id/7142876-L.jpg'),
(21, 'Moby Dick', NULL, '', 'Herman Melville', 'https://covers.openlibrary.org/b/id/6111610-L.jpg'),
(22, 'War and Peace', NULL, '', 'Leo Tolstoy', 'https://covers.openlibrary.org/b/id/8126257-L.jpg'),
(23, 'The Catcher in the Rye', NULL, '', 'J.D. Salinger', 'https://covers.openlibrary.org/b/id/8224827-L.jpg'),
(24, 'The Lord of the Rings', NULL, '', 'J.R.R. Tolkien', 'https://covers.openlibrary.org/b/id/7583657-L.jpg'),
(25, 'The Alchemist', NULL, '', 'Paulo Coelho', 'https://covers.openlibrary.org/b/id/7998232-L.jpg'),
(26, 'Harry Potter and the Philosopher\'s Stone', NULL, '', 'J.K. Rowling', 'https://covers.openlibrary.org/b/id/8133989-L.jpg'),
(27, 'The Hobbit', NULL, '', 'J.R.R. Tolkien', 'https://covers.openlibrary.org/b/id/7583767-L.jpg'),
(28, 'Brave New World', NULL, '', 'Aldous Huxley', 'https://covers.openlibrary.org/b/id/7367717-L.jpg'),
(29, 'Crime and Punishment', NULL, '', 'Fyodor Dostoevsky', 'https://covers.openlibrary.org/b/id/7220961-L.jpg'),
(30, 'The Odyssey', NULL, '', 'Homer', 'https://covers.openlibrary.org/b/id/10897646-L.jpg'),
(31, 'The Picture of Dorian Gray', NULL, '', 'Oscar Wilde', 'https://covers.openlibrary.org/b/id/8221512-L.jpg'),
(32, 'Frankenstein', NULL, '', 'Mary Shelley', 'https://covers.openlibrary.org/b/id/10851857-L.jpg'),
(33, 'Jane Eyre', NULL, '', 'Charlotte Bront?', 'https://covers.openlibrary.org/b/id/7222774-L.jpg'),
(34, 'The Brothers Karamazov', NULL, '', 'Fyodor Dostoevsky', 'https://covers.openlibrary.org/b/id/7387347-L.jpg'),
(35, 'Catch-22', NULL, '', 'Joseph Heller', 'https://covers.openlibrary.org/b/id/7084612-L.jpg'),
(36, 'The Hitchhiker\'s Guide to the Galaxy', NULL, '', 'Douglas Adams', 'https://covers.openlibrary.org/b/id/8261801-L.jpg'),
(37, 'Don Quixote', NULL, '', 'Miguel de Cervantes', 'https://covers.openlibrary.org/b/id/7082112-L.jpg'),
(38, 'The Divine Comedy', NULL, '', 'Dante Alighieri', 'https://covers.openlibrary.org/b/id/6469433-L.jpg'),
(39, 'Les Mis?rables', NULL, '', 'Victor Hugo', 'https://covers.openlibrary.org/b/id/7173875-L.jpg'),
(40, 'Anna Karenina', NULL, '', 'Leo Tolstoy', 'https://covers.openlibrary.org/b/id/7084531-L.jpg'),
(41, 'The Old Man and the Sea', NULL, '', 'Ernest Hemingway', 'https://covers.openlibrary.org/b/id/6111608-L.jpg'),
(42, 'The Secret Garden', NULL, '', 'Frances Hodgson Burnett', 'https://covers.openlibrary.org/b/id/7478043-L.jpg'),
(43, 'Little Women', NULL, '', 'Louisa May Alcott', 'https://covers.openlibrary.org/b/id/6672966-L.jpg'),
(44, 'The Grapes of Wrath', NULL, '', 'John Steinbeck', 'https://covers.openlibrary.org/b/id/7220717-L.jpg'),
(45, 'Wuthering Heights', NULL, '', 'Emily Bront?', 'https://covers.openlibrary.org/b/id/7891005-L.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
