<?php
include '../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    header("Location: index.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = sanitize($_POST['isbn']);
    $title = sanitize($_POST['title']);
    $author = sanitize($_POST['author']);
    $category = sanitize($_POST['category']);
    $publisher = sanitize($_POST['publisher']);
    $publication_year = (int)$_POST['publication_year'];
    $total_quantity = (int)$_POST['total_quantity'];

    $stmt = $pdo->prepare("UPDATE books SET isbn=?, title=?, author=?, category=?, publisher=?, publication_year=?, total_quantity=? WHERE id=?");
    if ($stmt->execute([$isbn, $title, $author, $category, $publisher, $publication_year, $total_quantity, $id])) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Failed to update book.";
    }
}
?>
<h2>Edit Book</h2>
<br>
<div class="card">
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>ISBN</label>
            <input type="text" name="isbn" class="form-control" value="<?= sanitize($book['isbn']) ?>" required>
        </div>
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?= sanitize($book['title']) ?>" required>
        </div>
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="author" class="form-control" value="<?= sanitize($book['author']) ?>" required>
        </div>
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" class="form-control" value="<?= sanitize($book['category']) ?>" required>
        </div>
        <div class="form-group">
            <label>Publisher</label>
            <input type="text" name="publisher" class="form-control" value="<?= sanitize($book['publisher']) ?>">
        </div>
        <div class="form-group">
            <label>Publication Year</label>
            <input type="number" name="publication_year" class="form-control" value="<?= $book['publication_year'] ?>">
        </div>
        <div class="form-group">
            <label>Total Quantity</label>
            <input type="number" name="total_quantity" class="form-control" value="<?= $book['total_quantity'] ?>" min="1">
        </div>
        <button type="submit" class="btn btn-primary">Update Book</button>
        <a href="index.php" class="btn btn-danger">Cancel</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
