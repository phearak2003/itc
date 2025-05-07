<?php
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $mysqli->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();

$assessments = [];
$stmt = $mysqli->prepare("SELECT * FROM assessments WHERE username = ? ORDER BY create_date DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $assessments[] = $row;
}
$stmt->close();
?>

<div class="container mt-4">
    <h2 class="mb-4">Assessment History</h2>

    <?php if (count($assessments) === 0): ?>
        <div class="alert alert-info">No assessment records found.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>Q1: Age</th>
                    <th>Q2: Weight</th>
                    <th>Q3: Illness</th>
                    <th>Q4: Pregnancy</th>
                    <th>Q5: Medication</th>
                    <th>Status</th>
                    <th>Appointment</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assessments as $index => $a): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($a['q1']) ?></td>
                        <td><?= htmlspecialchars($a['q2']) ?></td>
                        <td><?= htmlspecialchars($a['q3']) ?></td>
                        <td><?= htmlspecialchars($a['q4']) ?></td>
                        <td><?= htmlspecialchars($a['q5']) ?></td>
                        <td><?= htmlspecialchars($a['status']) ?></td>
                        <td><?= htmlspecialchars($a['appointment_date']) ?></td>
                        <td><?= htmlspecialchars($a['create_date']) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
</div>