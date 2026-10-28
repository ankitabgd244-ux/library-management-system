<?php
include '../includes/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = sanitize($_POST['student_code']);
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $department = sanitize($_POST['department']);

    $stmt = $pdo->prepare("INSERT INTO students (student_code, name, email, phone, department) VALUES (?, ?, ?, ?, ?)");
    try {
        $stmt->execute([$code, $name, $email, $phone, $department]);
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Registration failed: " . $e->getMessage();
    }
}
?>
<h2>Register Student</h2>
<br>
<div class="card">
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Student Code / ID</label>
            <input type="text" name="student_code" class="form-control" required placeholder="STU-1001">
        </div>
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Department</label>
            <input type="text" name="department" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
        <a href="index.php" class="btn btn-danger">Cancel</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
