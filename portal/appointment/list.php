<?php
require_once 'connection.php';

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
    LEFT JOIN 
        donation_appointment_status_history hs ON da.id = hs.donation_appointment_id
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

?>

<h2>Your Donation Appointments</h2>
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
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['appointment_id'] ?></td>
                            <td><?= $row['appointment_date'] ?></td>
                            <td><?= $row['hospital_name'] ?></td>
                            <td><?= $row['hospital_address'] ?></td>
                            <td><?= $row['current_status'] ?></td>
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