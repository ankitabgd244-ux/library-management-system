<?php
include 'includes/header.php';

$total_books = $pdo->query("SELECT SUM(total_quantity) FROM books")->fetchColumn() ?: 0;
$total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn() ?: 0;
$issued_books = $pdo->query("SELECT COUNT(*) FROM borrow_records WHERE status = 'issued'")->fetchColumn() ?: 0;
$overdue_books = $pdo->query("SELECT COUNT(*) FROM borrow_records WHERE status = 'issued' AND due_date < CURDATE()")->fetchColumn() ?: 0;
$available_books = $total_books - $issued_books;

$recent_books = $pdo->query("SELECT * FROM books ORDER BY id DESC LIMIT 5")->fetchAll();
$recent_borrowings = $pdo->query("SELECT br.*, s.name as student_name, b.title as book_title 
                                   FROM borrow_records br 
                                   JOIN students s ON br.student_id = s.id 
                                   JOIN books b ON br.book_id = b.id 
                                   ORDER BY br.id DESC LIMIT 5")->fetchAll();
?>

<h2>Dashboard Overview</h2>
<br>
<div class="stats-grid">
    <div class="stat-card">
        <div>
            <div class="lbl">Total Books</div>
            <div class="val"><?= $total_books ?></div>
        </div>
        <i class="fas fa-book fa-2x" style="color: var(--accent-color);"></i>
    </div>
    <div class="stat-card">
        <div>
            <div class="lbl">Registered Students</div>
            <div class="val"><?= $total_students ?></div>
        </div>
        <i class="fas fa-users fa-2x" style="color: #10b981;"></i>
    </div>
    <div class="stat-card">
        <div>
            <div class="lbl">Currently Issued</div>
            <div class="val"><?= $issued_books ?></div>
        </div>
        <i class="fas fa-hand-holding fa-2x" style="color: #f59e0b;"></i>
    </div>
    <div class="stat-card">
        <div>
            <div class="lbl">Available Books</div>
            <div class="val"><?= $available_books ?></div>
        </div>
        <i class="fas fa-check-circle fa-2x" style="color: #3b82f6;"></i>
    </div>
    <div class="stat-card">
        <div>
            <div class="lbl">Overdue Books</div>
            <div class="val"><?= $overdue_books ?></div>
        </div>
        <i class="fas fa-exclamation-triangle fa-2x" style="color: #ef4444;"></i>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
    <div class="card">
        <div class="card-title">Recent Borrowing Activity</div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Book</th>
                        <th>Issue Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_borrowings as $b): ?>
                    <tr>
                        <td><?= sanitize($b['student_name']) ?></td>
                        <td><?= sanitize($b['book_title']) ?></td>
                        <td><?= $b['issue_date'] ?></td>
                        <td>
                            <span class="badge <?= $b['status'] === 'issued' ? 'badge-warning' : 'badge-success' ?>">
                                <?= ucfirst($b['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-title">Recently Added Books</div>
        <ul style="list-style: none;">
            <?php foreach ($recent_books as $rb): ?>
                <li style="padding: 10px 0; border-bottom: 1px solid var(--border-color);">
                    <strong><?= sanitize($rb['title']) ?></strong><br>
                    <small style="color: var(--text-secondary);">By <?= sanitize($rb['author']) ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
