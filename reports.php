<?php
include 'includes/header.php';

$monthly_data = $pdo->query("SELECT MONTHNAME(issue_date) as mth, COUNT(*) as cnt FROM borrow_records GROUP BY MONTH(issue_date)")->fetchAll();
$labels = array_column($monthly_data, 'mth');
$counts = array_column($monthly_data, 'cnt');
?>

<h2>Reports & Analytics</h2>
<br>
<div class="card">
    <div class="card-title">Monthly Borrowing Trends</div>
    <canvas id="borrowChart" style="max-height: 350px;"></canvas>
</div>

<script>
const ctx = document.getElementById('borrowChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Books Issued',
            data: <?= json_encode($counts) ?>,
            backgroundColor: '#2563eb'
        }]
    }
});
</script>

<?php include 'includes/footer.php'; ?>
