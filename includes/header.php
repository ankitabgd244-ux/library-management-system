<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
check_auth();
$settings = get_settings($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($settings['library_name']) ?></title>
    <link rel="stylesheet" href="/library_management/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="main-wrapper">
        <header class="top-nav">
            <div><strong><?= sanitize($settings['library_name']) ?></strong></div>
            <div class="user-info">
                <button class="theme-toggle" id="theme-toggle-btn" title="Toggle Light/Dark Mode">
                    <i class="fas fa-moon" id="theme-icon"></i>
                </button>
                <span><i class="fas fa-user-circle"></i> <?= sanitize($_SESSION['admin_name'] ?? 'Admin') ?></span>
                <a href="/library_management/logout.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </header>
        <div class="content-area">
