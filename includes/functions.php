<?php
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function get_settings($pdo) {
    $stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
    return $stmt->fetch() ?: [
        'library_name' => 'Library System',
        'borrowing_period' => 14,
        'max_books_per_student' => 3,
        'fine_per_day' => 1.50
    ];
}
?>