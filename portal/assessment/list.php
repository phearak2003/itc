<?php
require_once 'connection.php';
include __DIR__ . '/../telegram/send.php';

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
    ORDER BY 
        da.appointment_date DESC
";

$stmt = $mysqli->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
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