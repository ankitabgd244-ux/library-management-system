<?php
include 'includes/header.php';

$books = $pdo->query("SELECT b.*, (b.total_quantity - COALESCE(issued.cnt, 0)) as available_quantity 
                      FROM books b 
                      LEFT JOIN (SELECT book_id, COUNT(*) as cnt FROM borrow_records WHERE status = 'issued' GROUP BY book_id) issued 
                      ON b.id = issued.book_id")->fetchAll();
?>
<h2>Book Availability Status</h2>
<br>
<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ISBN</th>
                    <th>Book Title</th>
                    <th>Total Copies</th>
                    <th>Available Copies</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $b): ?>
                <tr>
                    <td><?= sanitize($b['isbn']) ?></td>
                    <td><?= sanitize($b['title']) ?></td>
                    <td><?= $b['total_quantity'] ?></td>
                    <td><?= $b['available_quantity'] ?></td>
                    <td>
                        <span class="badge <?= $b['available_quantity'] > 0 ? 'badge-success' : 'badge-danger' ?>">
                            <?= $b['available_quantity'] > 0 ? 'In Stock' : 'Out of Stock' ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
