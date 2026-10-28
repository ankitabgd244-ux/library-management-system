<?php
include 'includes/header.php';

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['library_name']);
    $period = (int)$_POST['borrowing_period'];
    $max = (int)$_POST['max_books'];
    $fine = (float)$_POST['fine_per_day'];

    $stmt = $pdo->prepare("UPDATE settings SET library_name=?, borrowing_period=?, max_books_per_student=?, fine_per_day=? WHERE id=1");
    $stmt->execute([$name, $period, $max, $fine]);
    $success = "Settings updated successfully!";
}

$settings = get_settings($pdo);
?>
<h2>System Settings</h2>
<br>
<div class="card">
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Library Name</label>
            <input type="text" name="library_name" class="form-control" value="<?= sanitize($settings['library_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Default Borrowing Period (Days)</label>
            <input type="number" name="borrowing_period" class="form-control" value="<?= $settings['borrowing_period'] ?>" required>
        </div>
        <div class="form-group">
            <label>Max Books Per Student</label>
            <input type="number" name="max_books" class="form-control" value="<?= $settings['max_books_per_student'] ?>" required>
        </div>
        <div class="form-group">
            <label>Fine Per Overdue Day ($)</label>
            <input type="number" step="0.01" name="fine_per_day" class="form-control" value="<?= $settings['fine_per_day'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
