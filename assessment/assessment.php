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

<div class="container">
    <?php if ($canDonate !== null): ?>
        <div class="alert <?= $canDonate ? 'alert-success' : 'alert-danger' ?>">
            <?= $statusMessage ?>
        </div>
    <?php endif; ?>

    <form method="post" class="card p-4 shadow-sm" style="max-width: 800px; margin: auto;">
        <h2 class="mb-4">Assessment</h2>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Are you between 18 and 60 years old?</label>
                <select name="age" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Do you weigh more than 45kg?</label>
                <select name="weight" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Are you currently free from any illness?</label>
                <select name="illness" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Are you currently not pregnant (if female)?</label>
                <select name="pregnancy" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="yes">Yes / Not Applicable</option>
                    <option value="no">No</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Are you not taking any restricted medication?</label>
                <select name="medication" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Appointment Date</label>
                <input type="date" name="appointment_date" class="form-control" required>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-primary w-100" href="dashboard.php">Back</a>
            </div>

            <div class="col-md-6">
                <button type="submit" class="btn btn-danger w-100">Submit Assessment</button>
            </div>
        </div>
    </form>
</div>