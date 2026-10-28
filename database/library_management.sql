-- Database Schema for Library Management System
CREATE DATABASE IF NOT EXISTS `library_management` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `library_management`;

-- Admins Table
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Settings Table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT PRIMARY KEY DEFAULT 1,
  `library_name` VARCHAR(150) NOT NULL DEFAULT 'Central Library Management',
  `borrowing_period` INT NOT NULL DEFAULT 14,
  `max_books_per_student` INT NOT NULL DEFAULT 3,
  `fine_per_day` DECIMAL(8,2) NOT NULL DEFAULT 2.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Students Table
CREATE TABLE IF NOT EXISTS `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_code` VARCHAR(50) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `phone` VARCHAR(20) NOT NULL,
  `department` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Books Table
CREATE TABLE IF NOT EXISTS `books` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `isbn` VARCHAR(20) NOT NULL UNIQUE,
  `title` VARCHAR(255) NOT NULL,
  `author` VARCHAR(150) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `publisher` VARCHAR(150) NOT NULL,
  `publication_year` INT NOT NULL,
  `total_quantity` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Borrow Records Table
CREATE TABLE IF NOT EXISTS `borrow_records` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `book_id` INT NOT NULL,
  `issue_date` DATE NOT NULL,
  `due_date` DATE NOT NULL,
  `return_date` DATE DEFAULT NULL,
  `status` ENUM('issued', 'returned') NOT NULL DEFAULT 'issued',
  `fine_amount` DECIMAL(8,2) DEFAULT 0.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`book_id`) REFERENCES `books`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Initial Settings Insert
INSERT INTO `settings` (`id`, `library_name`, `borrowing_period`, `max_books_per_student`, `fine_per_day`)
VALUES (1, 'City Knowledge Hub Library', 14, 3, 1.50)
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Default Admin Account: admin@library.com / admin123
INSERT INTO `admins` (`id`, `name`, `email`, `password`)
VALUES (1, 'System Administrator', 'admin@library.com', '$2y$10$4.p4nQG8s6Sj.P07x8b7/.YxMh0yV6tN1nKqZq3v0Q2/f5J6K7L7S')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Sample Data for Books
INSERT INTO `books` (`isbn`, `title`, `author`, `category`, `publisher`, `publication_year`, `total_quantity`) VALUES
('978-0131103627', 'The C Programming Language', 'Brian W. Kernighan, Dennis M. Ritchie', 'Computer Science', 'Prentice Hall', 1988, 5),
('978-0262033848', 'Introduction to Algorithms', 'Thomas H. Cormen', 'Computer Science', 'MIT Press', 2009, 4),
('978-0132350884', 'Clean Code', 'Robert C. Martin', 'Software Engineering', 'Prentice Hall', 2008, 6),
('978-0596007126', 'Head First Design Patterns', 'Eric Freeman', 'Software Engineering', 'O Reilly Media', 2004, 3),
('978-0743273565', 'The Great Gatsby', 'F. Scott Fitzgerald', 'Fiction', 'Scribner', 1925, 3);

-- Sample Data for Students
INSERT INTO `students` (`student_code`, `name`, `email`, `phone`, `department`) VALUES
('STU-1001', 'Alice Johnson', 'alice@example.com', '555-0192', 'Computer Science'),
('STU-1002', 'Bob Smith', 'bob@example.com', '555-0193', 'Information Technology'),
('STU-1003', 'Charlie Brown', 'charlie@example.com', '555-0194', 'Electrical Engineering');
