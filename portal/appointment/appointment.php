<?php
ob_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT id FROM assessments WHERE user_id = ? ORDER BY create_date DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$assessment = $result->fetch_assoc();

if ($assessment) {
    $assessment_id = $assessment['id'];
} else {
    echo "No assessment found for this user.";
    exit();
}

$hospitals = $mysqli->query("SELECT id, name, address FROM hospitals");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hospital_id = $_POST['hospital_id'];
    $appointment_date = $_POST['appointment_date'];

    $stmt = $mysqli->prepare("INSERT INTO donation_appointments (user_id, hospital_id, assessment_id, appointment_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $hospital_id, $assessment_id, $appointment_date);
    $stmt->execute();
    $assessmentId = $mysqli->insert_id;

    $status = "Pending";
    $stmt = $mysqli->prepare("INSERT INTO donation_appointment_status_history (donation_appointment_id, status) VALUES (?, ?)");
    $stmt->bind_param("is", $assessmentId, $status);

    if ($stmt->execute()) {
        // Update the assessment record with pass status
        $isBookAppointment = 1;
        $stmt = $mysqli->prepare("UPDATE assessments SET is_book_appointment = ? WHERE id = ?");
        $stmt->bind_param('ii', $isBookAppointment, $assessment_id);
        $stmt->execute();
        $mysqli->commit();

        echo "<div class='alert alert-success'>Appointment successfully created!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

?>

<div class="assessment-container my-5" style="max-width: 600px; margin: 0 auto;">
    <h2>Create Donation Appointment</h2>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="hospital_id" class="form-label">Hospital</label>
            <select class="form-select" id="hospital_id" name="hospital_id" required onchange="updateHospitalAddress()">
                <option value="">Select Hospital</option>
                <?php while ($row = $hospitals->fetch_assoc()) : ?>
                    <option value="<?= $row['id'] ?>" data-address="<?= htmlspecialchars($row['address']) ?>"><?= $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
            <span id="hospital-address" class="text-muted small" style="display: none;"></span>
        </div>

        <div class="mb-3">
            <label for="appointment_date" class="form-label">Appointment Date</label>
            <input type="date" class="form-control" id="appointment_date" name="appointment_date" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Create Appointment</button>
    </form>
</div>

<script>
    function updateHospitalAddress() {
        const selectElement = document.getElementById('hospital_id');
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const address = "Address: " + selectedOption.getAttribute('data-address');
        const addressSpan = document.getElementById('hospital-address');

        if (address) {
            addressSpan.textContent = address;
            addressSpan.style.display = 'inline';
        } else {
            addressSpan.style.display = 'none';
        }
    }
</script>