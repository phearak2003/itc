<?php
if($_SESSION['role'] !== 'admin' || $_SESSION['role'] !== 'staff') {
    header("Location: dashboard.php?page=no_permission");
    exit;
}

$result = $mysqli->query("SELECT * FROM assessments WHERE status = 'Pass' ORDER BY appointment_date DESC");
?>

<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">Eligible Donors - Full Assessment Details</h5>
    </div>
    <div class="card-body">
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Q1: Age</th>
                        <th>Q2: Weight</th>
                        <th>Q3: Illness</th>
                        <th>Q4: Pregnancy</th>
                        <th>Q5: Medication</th>
                        <th>Status</th>
                        <th>Appointment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= ucfirst($row['q1']) ?></td>
                            <td><?= ucfirst($row['q2']) ?></td>
                            <td><?= ucfirst($row['q3']) ?></td>
                            <td><?= ucfirst($row['q4']) ?></td>
                            <td><?= ucfirst($row['q5']) ?></td>
                            <td><span class="badge bg-success"><?= $row['status'] ?></span></td>
                            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">No passed assessments found.</p>
        <?php endif; ?>
    </div>
</div>