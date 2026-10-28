<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            $error = "Passwords do not match!";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM admins WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $error = "An account with this email address already exists.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
                if ($stmt->execute([$name, $email, $hashed_password])) {
                    $success = "Registration successful! You can now log in.";
                } else {
                    $error = "Something went wrong during registration.";
                }
            }
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Registration - Library Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-body">
    <div class="login-card">
        <h2 style="text-align: center; margin-bottom: 20px;"><i class="fas fa-user-plus"></i> Admin Registration</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= $success ?> <br><br>
                <a href="login.php" class="btn btn-primary" style="display: block; text-align: center;">Go to Login</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="admin2@library.com">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="reg-pass" class="form-control" required placeholder="******">
                        <i class="fas fa-eye" id="toggle-pass" onclick="togglePasswordVisibility('reg-pass', 'toggle-pass')" style="position: absolute; right: 10px; top: 12px; cursor: pointer;"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required placeholder="******">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Register Account</button>
            </form>
            <div style="text-align: center; margin-top: 15px;">
                <small>Already have an account? <a href="login.php" style="color: var(--accent-color);">Login here</a></small>
            </div>
        <?php endif; ?>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>
