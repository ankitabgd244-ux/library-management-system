<?php
include '../includes/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = sanitize($_POST['isbn']);
    $title = sanitize($_POST['title']);
    $author = sanitize($_POST['author']);
    $category = sanitize($_POST['category']);
    $publisher = sanitize($_POST['publisher']);
    $publication_year = (int)$_POST['publication_year'];
    $total_quantity = (int)$_POST['total_quantity'];

    if (!empty($isbn) && !empty($title) && $total_quantity > 0) {
        $stmt = $pdo->prepare("INSERT INTO books (isbn, title, author, category, publisher, publication_year, total_quantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$isbn, $title, $author, $category, $publisher, $publication_year, $total_quantity]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $error = "Error adding book: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all required fields properly.";
    }
}
?>
<h2>Add New Book</h2>
<br>
<div class="card">
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>ISBN</label>
            <input type="text" name="isbn" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="author" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Publisher</label>
            <input type="text" name="publisher" class="form-control">
        </div>
        <div class="form-group">
            <label>Publication Year</label>
            <input type="number" name="publication_year" class="form-control" value="2024">
        </div>
        <div class="form-group">
            <label>Total Quantity</label>
            <input type="number" name="total_quantity" class="form-control" value="1" min="1">
        </div>
        <button type="submit" class="btn btn-primary">Save Book</button>
        <a href="index.php" class="btn btn-danger">Cancel</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
