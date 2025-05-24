<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

if($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff') {
    header("Location: dashboard.php");
    exit;
}

$roleCounts = [];
$roleQuery = $mysqli->query("SELECT r.name AS role_name, COUNT(u.id) AS total FROM users u JOIN roles r ON u.role_id = r.id GROUP BY r.name");
while ($row = $roleQuery->fetch_assoc()) {
    $roleCounts[$row['role_name']] = $row['total'];
}

$assessmentQuery = $mysqli->query("SELECT COUNT(*) AS total FROM assessments");
$assessmentCount = $assessmentQuery->fetch_assoc()['total'];
?>

<style>
    .chart-container {
        position: relative;
        height: 300px;
    }
</style>

<div class="row">
    <div class="col-md-6">
        <h2>User Roles Distribution</h2>
        <div class="chart-container">
            <canvas id="rolesChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <h2>Total Assessments</h2>
        <div class="chart-container">
            <canvas id="assessmentsChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const roleData = <?= json_encode(array_values($roleCounts)) ?>;
    const roleLabels = <?= json_encode(array_keys($roleCounts)) ?>;

    new Chart(document.getElementById('rolesChart'), {
        type: 'bar',
        data: {
            labels: roleLabels,
            datasets: [{
                label: 'Number of Users',
                data: roleData,
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    new Chart(document.getElementById('assessmentsChart'), {
        type: 'doughnut',
        data: {
            labels: ['Assessments'],
            datasets: [{
                label: 'Total Assessments',
                data: [<?= $assessmentCount ?>],
                backgroundColor: ['#17a2b8']
            }]
        },
        options: {
            responsive: true
        }
    });
</script>