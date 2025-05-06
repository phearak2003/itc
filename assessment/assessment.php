<?php
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$canDonate = null;
$statusMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $appointmentDate = $_POST['appointment_date'];

    $answers = [
        'age' => $_POST['age'] ?? '',
        'weight' => $_POST['weight'] ?? '',
        'illness' => $_POST['illness'] ?? '',
        'pregnancy' => $_POST['pregnancy'] ?? '',
        'medication' => $_POST['medication'] ?? ''
    ];

    $status = in_array('no', $answers) ? 'Fail' : 'Pass';
    $canDonate = $status === 'Pass';

    $stmt = $mysqli->prepare("
        INSERT INTO assessments (username, q1, q2, q3, q4, q5, status, appointment_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "ssssssss",
        $username,
        $answers['age'],
        $answers['weight'],
        $answers['illness'],
        $answers['pregnancy'],
        $answers['medication'],
        $status,
        $appointmentDate
    );

    if ($stmt->execute()) {
        $statusMessage = $canDonate
            ? 'Congratulations! You are eligible to donate blood.'
            : 'Sorry, you are not eligible to donate blood.';
    } else {
        $statusMessage = 'Error saving assessment: ' . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Blood Donation Assessment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container py-5">
        <div class="d-flex justify-content-between mb-4">
            <h2>Blood Donation Assessment</h2>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <?php if ($canDonate !== null): ?>
            <div class="alert <?= $canDonate ? 'alert-success' : 'alert-danger' ?>">
                <?= $statusMessage ?>
            </div>
        <?php endif; ?>

        <form method="post" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label class="form-label">Are you between 18 and 60 years old?</label>
                <select name="age" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Do you weigh more than 45kg?</label>
                <select name="weight" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Are you currently free from any illness?</label>
                <select name="illness" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Are you currently not pregnant (if female)?</label>
                <select name="pregnancy" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="yes">Yes / Not Applicable</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Are you not taking any restricted medication?</label>
                <select name="medication" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Appointment Date</label>
                <input type="date" name="appointment_date" class="form-control" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-danger btn-lg">Submit Assessment</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>