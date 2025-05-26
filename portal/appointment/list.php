<?php
require_once 'connection.php';
include __DIR__ . '/../telegram/send.php';

if (!isset($_SESSION['user_id'])) {
    echo "You are not logged in.";
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "
    SELECT 
        da.id AS appointment_id, 
        da.appointment_date, 
        da.created_at AS appointment_created, 
        da.updated_at AS appointment_updated, 
        hs.status AS current_status, 
        h.name AS hospital_name,
        h.address AS hospital_address
    FROM 
        donation_appointments da
    LEFT JOIN (
        SELECT 
            donation_appointment_id, 
            status
        FROM 
            donation_appointment_status_history
        WHERE 
            (donation_appointment_id, created_at) IN (
                SELECT 
                    donation_appointment_id, 
                    MAX(created_at)
                FROM 
                    donation_appointment_status_history
                GROUP BY 
                    donation_appointment_id
            )
    ) hs ON da.id = hs.donation_appointment_id
    LEFT JOIN 
        hospitals h ON da.hospital_id = h.id
    WHERE 
        da.user_id = ?
    ORDER BY 
        da.appointment_date DESC
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_appointment_id'], $_SESSION['user_id'])) {
    $appointment_id = (int) $_POST['cancel_appointment_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $mysqli->prepare("
        SELECT id FROM donation_appointments 
        WHERE id = ? AND user_id = ? AND id NOT IN (
            SELECT donation_appointment_id 
            FROM donation_appointment_status_history 
            WHERE status IN ('Cancelled', 'Completed', 'Rejected')
        )
    ");
    $stmt->bind_param("ii", $appointment_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $full_name = $_SESSION['full_name'] ?? 'Unknown User';
        $comment = "Cancelled by $full_name";

        $stmt = $mysqli->prepare("
            INSERT INTO donation_appointment_status_history (donation_appointment_id, status, comment, created_by)
            VALUES (?, 'Cancelled', ?, ?)
        ");
        $stmt->bind_param("isi", $appointment_id, $comment, $user_id);
        $stmt->execute();

        $_SESSION['message'] = "Appointment cancelled successfully.";
    } else {
        $_SESSION['message'] = "Unable to cancel the appointment.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
?>

<h2>Your Donation Appointments</h2>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info">
        <?= $_SESSION['message'] ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if ($result->num_rows > 0): ?>
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5>Appointments History</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Appointment Date</th>
                        <th>Hospital</th>
                        <th>Hospital Address</th>
                        <th>Latest Status</th>
                        <th>Created At</th>
                        <th colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['appointment_id'] ?></td>
                            <td><?= $row['appointment_date'] ?></td>
                            <td><?= $row['hospital_name'] ?></td>
                            <td><?= $row['hospital_address'] ?></td>
                            <td>
                                <span class="badge bg-<?= match ($row['current_status']) {
                                                            'Accepted' => 'success',
                                                            'Rejected' => 'danger',
                                                            'Cancelled' => 'warning',
                                                            'Completed' => 'primary',
                                                            'Expired' => 'dark',
                                                            default => 'secondary'
                                                        } ?>">
                                    <?= htmlspecialchars($row['current_status'] ?? 'Pending') ?>
                                </span>
                            </td>
                            <td><?= $row['appointment_created'] ?></td>
                            <td>
                                <a href="?page=view_appointment&id=<?= $row['appointment_id'] ?>" class="btn btn-primary btn-sm" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <?php if (!in_array($row['current_status'], ['Completed', 'Rejected', 'Cancelled'])): ?>
                                    <form method="post" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                        <input type="hidden" name="cancel_appointment_id" value="<?= $row['appointment_id'] ?>">
                                        <button type="submit" class="btn btn-warning btn-sm" title="Cancel">
                                            Cancel
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <p>You have no donation appointments yet.</p>
<?php endif; ?>