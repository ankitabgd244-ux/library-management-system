<?php
include '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int)$_POST['student_id'];
    $book_id = (int)$_POST['book_id'];
    $issue_date = $_POST['issue_date'];
    
    $settings = get_settings($pdo);
    $due_date = date('Y-m-d', strtotime($issue_date . " + {$settings['borrowing_period']} days"));

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM borrow_records WHERE student_id = ? AND status = 'issued'");
    $stmt->execute([$student_id]);
    $current_borrowed = $stmt->fetchColumn();

    if ($current_borrowed >= $settings['max_books_per_student']) {
        $error = "Student has reached the maximum allowed limit of {$settings['max_books_per_student']} books.";
    } else {
        $stmt = $pdo->prepare("SELECT total_quantity - (SELECT COUNT(*) FROM borrow_records WHERE book_id = ? AND status = 'issued') as avail FROM books WHERE id = ?");
        $stmt->execute([$book_id, $book_id]);
        $avail = $stmt->fetchColumn();

        if ($avail <= 0) {
            $error = "This book is currently out of stock.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO borrow_records (student_id, book_id, issue_date, due_date, status) VALUES (?, ?, ?, ?, 'issued')");
            $stmt->execute([$student_id, $book_id, $issue_date, $due_date]);
            $success = "Book issued successfully! Due date: " . $due_date;
        }
    }
}

$students = $pdo->query("SELECT * FROM students ORDER BY name ASC")->fetchAll();
$books = $pdo->query("SELECT * FROM books ORDER BY title ASC")->fetchAll();
?>

<h2>Issue Book</h2>
<br>
<div class="card">
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Select Student</label>
            <select name="student_id" class="form-control" required>
                <option value="">-- Choose Student --</option>
                <?php foreach ($students as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= sanitize($s['name']) ?> (<?= sanitize($s['student_code']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Select Book</label>
            <select name="book_id" class="form-control" required>
                <option value="">-- Choose Book --</option>
                <?php foreach ($books as $b): ?>
                    <option value="<?= $b['id'] ?>"><?= sanitize($b['title']) ?> (ISBN: <?= sanitize($b['isbn']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Issue Date</label>
            <input type="date" name="issue_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Confirm Issue</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
