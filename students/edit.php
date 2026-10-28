<?php
include '../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = sanitize($_POST['student_code']);
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $department = sanitize($_POST['department']);

    $stmt = $pdo->prepare("UPDATE students SET student_code=?, name=?, email=?, phone=?, department=? WHERE id=?");
    $stmt->execute([$code, $name, $email, $phone, $department, $id]);
    header("Location: index.php");
    exit;
}
?>
<h2>Edit Student Information</h2>
<br>
<div class="card">
    <form method="POST">
        <div class="form-group">
            <label>Student Code</label>
            <input type="text" name="student_code" class="form-control" value="<?= sanitize($student['student_code']) ?>" required>
        </div>
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" value="<?= sanitize($student['name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= sanitize($student['email']) ?>" required>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= sanitize($student['phone']) ?>" required>
        </div>
        <div class="form-group">
            <label>Department</label>
            <input type="text" name="department" class="form-control" value="<?= sanitize($student['department']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="index.php" class="btn btn-danger">Cancel</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
