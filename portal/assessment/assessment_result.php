<?php
ob_start();
require_once 'connection.php';

$userId = $_SESSION['user_id'] ?? null;

// 
$stmtCheck = $mysqli->prepare("
    SELECT * FROM assessments 
    WHERE user_id = ? 
    ORDER BY create_date DESC 
    LIMIT 1
");
$stmtCheck->bind_param("i", $userId);
$stmtCheck->execute();
$resultStmtCheck = $stmtCheck->get_result();

// 
$assessment = $mysqli->prepare("SELECT id, is_pass FROM assessments WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$assessment->bind_param('i', $userId);
$assessment->execute();
$assessment->bind_result($assessmentId, $isPass);
$assessment->fetch();
$assessment->close();

if (!$assessmentId) {
    echo "Assessment not found.";
    exit();
}

$details = $mysqli->prepare("SELECT is_correct FROM assessment_details WHERE assessment_id = ?");
$details->bind_param('i', $assessmentId);
$details->execute();
$result = $details->get_result();

$total = 0;
$correct = 0;

while ($row = $result->fetch_assoc()) {
    $total++;
    if ($row['is_correct']) {
        $correct++;
    }
}
$details->close();

$percentage = $total > 0 ? ($correct / $total) * 100 : 0;
?>

<style>
    .circular-progress {
        --percentage: 0;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(<?= $isPass ? '#28a745' : '#dc3545' ?> calc(var(--percentage) * 1%),
                #e9ecef 0%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.25rem;
        color: #333;
    }
</style>

<div class="card shadow mx-auto p-4" style="max-width: 500px;">
    <div class="card-body text-center">
        <h3 class="card-title mb-3">Assessment Result</h3>

        <!-- Circular Progress -->
        <div class="d-flex justify-content-center my-4">
            <div class="circular-progress" style="--percentage: <?= round($percentage) ?>;">
                <span><?= round($percentage) ?>%</span>
            </div>
        </div>

        <!-- Progress bar -->
        <div class="progress my-3" style="height: 25px;">
            <div class="progress-bar <?= $isPass ? 'bg-success' : 'bg-danger' ?>"
                role="progressbar"
                style="width: <?= round($percentage) ?>%;"
                aria-valuenow="<?= round($percentage) ?>"
                aria-valuemin="0"
                aria-valuemax="100">
                <?= round($percentage) ?>%
            </div>
        </div>

        <?php
        if ($rowStmtCheck = $resultStmtCheck->fetch_assoc()) {
            if ($rowStmtCheck['is_pass'] == 1) {
                $createDate = new DateTime($rowStmtCheck['create_date']);
                $threeMonthsAgo = (new DateTime())->modify('-3 months');

                if ($createDate < $threeMonthsAgo) {
                    if ($rowStmtCheck['is_book_appointment'] == 0) {
                        // header('Location: dashboard.php?page=assessment');
                        // exit;
                    } else {
                        // header('Location: dashboard.php?page=assessment');
                        // exit;
                    }
                } else {
                    if ($rowStmtCheck['is_book_appointment'] != 0) {
                        // header('Location: dashboard.php?page=assessment');
                        // exit;
                    }
                }
            } else {
                // header('Location: dashboard.php?page=assessment');
                // exit;
            }
        } else {
            // header('Location: dashboard.php?page=assessment');
            // exit;
        }
        ?>

        <?php if ($isPass): ?>
            <p class="mt-3">
                🎉 <strong class="text-success">Congratulations!</strong> You passed the assessment.<br>
                <span>Date of pass: <strong><?= htmlspecialchars($rowStmtCheck['create_date']) ?></strong></span><br>
            </p>
            <a href="dashboard.php?page=appointment" class="btn btn-success mt-3">Book Appointment</a>
        <?php else: ?>
            ❌ <strong class="text-danger">Sorry!</strong> You did not pass. Try again!<br>
            <a href="dashboard.php?page=assessment" class="btn btn-primary mt-3">Try Again</a>
        <?php endif; ?>

        <a href="dashboard.php" class="btn btn-danger mt-3">Back to Dashboard</a>
    </div>
</div>