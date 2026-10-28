<?php
include '../includes/header.php';

$search = sanitize($_GET['search'] ?? '');
$category = sanitize($_GET['category'] ?? '');

$query = "SELECT b.*, (b.total_quantity - COALESCE(issued.cnt, 0)) as available_quantity 
          FROM books b 
          LEFT JOIN (SELECT book_id, COUNT(*) as cnt FROM borrow_records WHERE status = 'issued' GROUP BY book_id) issued 
          ON b.id = issued.book_id 
          WHERE 1=1";

$params = [];
if (!empty($search)) {
    $query .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($category)) {
    $query .= " AND b.category = ?";
    $params[] = $category;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$books = $stmt->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Book Management</h2>
    <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Book</a>
</div>

<div class="card">
    <form method="GET" style="display: flex; gap: 10px; margin-bottom: 20px;">
        <input type="text" name="search" class="form-control" placeholder="Search title, author, ISBN..." value="<?= $search ?>">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ISBN</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Total</th>
                    <th>Available</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $b): ?>
                <tr>
                    <td><?= sanitize($b['isbn']) ?></td>
                    <td><?= sanitize($b['title']) ?></td>
                    <td><?= sanitize($b['author']) ?></td>
                    <td><?= sanitize($b['category']) ?></td>
                    <td><?= $b['total_quantity'] ?></td>
                    <td><span class="badge <?= $b['available_quantity'] > 0 ? 'badge-success' : 'badge-danger' ?>"><?= $b['available_quantity'] ?></span></td>
                    <td>
                        <a href="edit.php?id=<?= $b['id'] ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                        <a href="delete.php?id=<?= $b['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
