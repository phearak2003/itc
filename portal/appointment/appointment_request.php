<?php
require_once 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>User not logged in.</div>";
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$stmtHospital = $mysqli->prepare("
    SELECT h.id
    FROM hospitals h
    JOIN users u ON h.user_id = u.id
    WHERE h.user_id = ?
    LIMIT 1
");
$stmtHospital->bind_param("i", $user_id);
$stmtHospital->execute();
$stmtHospital->bind_result($hospital_id);
$stmtHospital->fetch();
$stmtHospital->close();

$stmt = $mysqli->prepare("
    SELECT 
        da.id,
        da.appointment_date,
        da.created_at,
        h.name AS hospital_name,
        h.city,
        h.country,
        (
            SELECT status 
            FROM donation_appointment_status_history 
            WHERE donation_appointment_id = da.id 
            ORDER BY created_at DESC 
            LIMIT 1
        ) AS latest_status, da.hospital_id
    FROM donation_appointments da
    JOIN hospitals h ON da.hospital_id = h.id
    WHERE da.hospital_id = ?
    ORDER BY da.appointment_date DESC
");
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'], $_POST['status'], $_POST['comment'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<div class='alert alert-danger'>Unauthorized access.</div>";
        exit;
    }

    $appointment_id = (int)$_POST['appointment_id'];
    $status = $_POST['status'];
    $comment = trim($_POST['comment']);
    $created_by = (int)$_SESSION['user_id'];

    if (!in_array($status, ['Accepted', 'Rejected'])) {
        echo "<div class='alert alert-danger'>Invalid status.</div>";
    } else {
        $stmtInsert = $mysqli->prepare("
            INSERT INTO donation_appointment_status_history 
            (donation_appointment_id, status, comment, created_by)
            VALUES (?, ?, ?, ?)
        ");
        $stmtInsert->bind_param("sssi", $appointment_id, $status, $comment, $created_by);

        if ($stmtInsert->execute()) {
            echo "<div class='alert alert-success'>Status updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmtInsert->error) . "</div>";
        }
        $stmtInsert->close();
    }
}
?>

<h2 class="mb-4">My Donation Appointments</h2>

<?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>#</th>
                <th>Appointment Date</th>
                <th>Hospital</th>
                <th>City</th>
                <th>Country</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                    <td><?= htmlspecialchars($row['hospital_name']) ?></td>
                    <td><?= htmlspecialchars($row['city']) ?></td>
                    <td><?= htmlspecialchars($row['country']) ?></td>
                    <td>
                        <span class="badge bg-<?= match ($row['latest_status']) {
                                                    'Accepted' => 'success',
                                                    'Rejected' => 'danger',
                                                    'Completed' => 'primary',
                                                    'Expired' => 'warning',
                                                    default => 'secondary'
                                                } ?>">
                            <?= htmlspecialchars($row['latest_status'] ?? 'Pending') ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td>
                        <a href="view_appointment.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">View</a>
                        <button
                            class="btn btn-sm btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#statusModal"
                            data-id="<?= $row['id'] ?>"
                            data-action="Accepted">
                            Approve
                        </button>
                        <button
                            class="btn btn-sm btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#statusModal"
                            data-id="<?= $row['id'] ?>"
                            data-action="Rejected">
                            Reject
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info">You have no appointments.</div>
<?php endif; ?>

<!-- Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Appointment Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="appointment_id" id="modal-appointment-id">
                <input type="hidden" name="status" id="modal-status">

                <div class="mb-3">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea name="comment" id="comment" class="form-control" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusModal = document.getElementById('statusModal');
        statusModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const appointmentId = button.getAttribute('data-id');
            const status = button.getAttribute('data-action');

            document.getElementById('modal-appointment-id').value = appointmentId;
            document.getElementById('modal-status').value = status;
        });
    });
</script>