<?php
include '../includes/header.php';

$success = '';
$error = '';

if (isset($_GET['action']) && $_GET['action'] === 'return') {
    $record_id = (int)$_GET['id'];
    $return_date = date('Y-m-d');
    
    $stmt = $pdo->prepare("SELECT * FROM borrow_records WHERE id = ?");
    $stmt->execute([$record_id]);
    $record = $stmt->fetch();

    if ($record && $record['status'] === 'issued') {
        $due = new DateTime($record['due_date']);
        $ret = new DateTime($return_date);
        $fine = 0.00;

        if ($ret > $due) {
            $days = $ret->diff($due)->days;
            $settings = get_settings($pdo);
            $fine = $days * $settings['fine_per_day'];
        }

        $stmt = $pdo->prepare("UPDATE borrow_records SET return_date = ?, status = 'returned', fine_amount = ? WHERE id = ?");
        $stmt->execute([$return_date, $fine, $record_id]);
        $success = "Book returned successfully! Fine calculated: $" . number_format($fine, 2);
    }
}

$borrowed = $pdo->query("SELECT br.*, s.name as student_name, b.title as book_title 
                         FROM borrow_records br 
                         JOIN students s ON br.student_id = s.id 
                         JOIN books b ON br.book_id = b.id 
                         WHERE br.status = 'issued' 
                         ORDER BY br.due_date ASC")->fetchAll();
?>

<h2>Return Book</h2>
<br>
<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Book</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($borrowed as $r): ?>
                <?php $is_overdue = strtotime($r['due_date']) < strtotime(date('Y-m-d')); ?>
                <tr>
                    <td><?= sanitize($r['student_name']) ?></td>
                    <td><?= sanitize($r['book_title']) ?></td>
                    <td><?= $r['issue_date'] ?></td>
                    <td style="<?= $is_overdue ? 'color: red; font-weight: bold;' : '' ?>"><?= $r['due_date'] ?></td>
                    <td><span class="badge <?= $is_overdue ? 'badge-danger' : 'badge-warning' ?>"><?= $is_overdue ? 'Overdue' : 'Issued' ?></span></td>
                    <td>
                        <a href="return.php?action=return&id=<?= $r['id'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Mark this book as returned?')">Process Return</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
