<?php
include '../includes/header.php';

$search = sanitize($_GET['search'] ?? '');
$query = "SELECT s.*, COALESCE(b.issued_cnt, 0) as currently_borrowed 
          FROM students s 
          LEFT JOIN (SELECT student_id, COUNT(*) as issued_cnt FROM borrow_records WHERE status = 'issued' GROUP BY student_id) b 
          ON s.id = b.student_id 
          WHERE 1=1";

$params = [];
if (!empty($search)) {
    $query .= " AND (s.name LIKE ? OR s.student_code LIKE ? OR s.email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$students = $stmt->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Student Management</h2>
    <a href="add.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Register Student</a>
</div>

<div class="card">
    <form method="GET" style="display: flex; gap: 10px; margin-bottom: 20px;">
        <input type="text" name="search" class="form-control" placeholder="Search code, name, email..." value="<?= $search ?>">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Borrowed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td><?= sanitize($s['student_code']) ?></td>
                    <td><?= sanitize($s['name']) ?></td>
                    <td><?= sanitize($s['email']) ?></td>
                    <td><?= sanitize($s['department']) ?></td>
                    <td><span class="badge badge-warning"><?= $s['currently_borrowed'] ?></span></td>
                    <td>
                        <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                        <a href="delete.php?id=<?= $s['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
