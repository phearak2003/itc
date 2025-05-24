<?php
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $mysqli->prepare("
    SELECT a.id, a.is_pass, a.is_book_appointment, a.create_date,
           COUNT(ad.id) AS total_questions,
           SUM(ad.is_correct) AS correct_answers
    FROM assessments a
    LEFT JOIN assessment_details ad ON a.id = ad.assessment_id
    WHERE a.user_id = ?
    GROUP BY a.id
    ORDER BY a.create_date DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Load detail
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['load_details']) && isset($_POST['assessment_id'])) {
    $assessmentId = (int)$_POST['assessment_id'];

    $detailsStmt = $mysqli->prepare("
        SELECT q.question_text, ad.answer, ad.is_correct
        FROM assessment_details ad
        JOIN assessment_questions q ON ad.question_id = q.id
        WHERE ad.assessment_id = ?
    ");
    $detailsStmt->bind_param("i", $assessmentId);
    $detailsStmt->execute();
    $detailsResult = $detailsStmt->get_result();

    if ($detailsResult->num_rows > 0) {
        echo '<table class="table table-bordered">';
        echo '<thead class="table-secondary">
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Your Answer</th>
                    <th>Correct?</th>
                </tr>
              </thead>
              <tbody>';
        $i = 1;
        while ($detail = $detailsResult->fetch_assoc()) {
            echo '<tr>
                    <td>' . $i++ . '</td>
                    <td>' . htmlspecialchars($detail['question_text']) . '</td>
                    <td>' . htmlspecialchars($detail['answer']) . '</td>
                    <td><span class="badge bg-' . ($detail['is_correct'] ? 'success' : 'danger') . '">' . ($detail['is_correct'] ? 'Yes' : 'No') . '</span></td>
                  </tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-warning">No details found for this assessment.</div>';
    }

    exit;
}
?>
<a href="dashboard.php?page=assessment" class="btn btn-success mb-3">Create Assessment</a>

<?php if ($result->num_rows > 0): ?>
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5>Assessment History</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Correct Answers</th>
                        <th>Total Questions</th>
                        <th>Passed</th>
                        <th>Booked Appointment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['create_date']) ?></td>
                            <td><?= $row['correct_answers'] ?? 0 ?></td>
                            <td><?= $row['total_questions'] ?></td>
                            <td>
                                <span class="badge bg-<?= $row['is_pass'] ? 'success' : 'danger' ?>">
                                    <?= $row['is_pass'] ? 'Yes' : 'No' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $row['is_book_appointment'] ? 'info' : 'secondary' ?>">
                                    <?= $row['is_book_appointment'] ? 'Yes' : 'No' ?>
                                </span>
                            </td>
                            <td>
                                <a href="?page=assessment_detail&assessment_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <?php if ($row['is_book_appointment']): ?>
                                    <a href="?page=view_appointment&id=<?= $row['id'] ?>" class="btn btn-sm btn-success" title="View">
                                        Track
                                    </a>
                                <?php else: ?>
                                    <a href="dashboard.php?page=appointment" class="btn btn-sm btn-success">Book Appointment</a>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- View Modal -->
                        <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="viewLabel">Assessment Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="viewModalBody">
                                        <div class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Track Appointment Modal -->
                        <div class="modal fade" id="trackModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="trackLabel<?= $row['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title" id="trackLabel<?= $row['id'] ?>">Track Appointment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Tracking info for appointment of assessment #<?= $row['id'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-warning">No assessments found.</div>
<?php endif; ?>