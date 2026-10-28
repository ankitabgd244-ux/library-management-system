<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
check_auth();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM borrow_records WHERE book_id = ? AND status = 'issued'");
$stmt->execute([$id]);
if ($stmt->fetchColumn() > 0) {
    die("Cannot delete book with active borrowing records. <a href='index.php'>Go Back</a>");
}

$stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
$stmt->execute([$id]);
header("Location: index.php");
exit;
?>