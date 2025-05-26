<?php
require_once 'connection.php';

if (!isset($_GET['id'])) {
    echo "No appointment ID provided.";
    exit;
}

$appointment_id = (int) $_GET['id'];

$stmt = $mysqli->prepare("
    SELECT 
        his.comment, 
        his.status, 
        his.created_at, 
        CONCAT(u.first_name, ' ', u.last_name) AS created_by
    FROM donation_appointment_status_history his
    JOIN user_profiles u ON his.created_by = u.user_id
    WHERE his.donation_appointment_id = ?
    ORDER BY his.created_at ASC
");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

$statuses = [];
while ($row = $result->fetch_assoc()) {
    $statuses[] = $row;
}

$all_steps = ['Pending', 'Reviewed', 'Completed'];

$status_to_step = [
    'Pending' => 'Pending',
    'Accepted' => 'Reviewed',
    'Rejected' => 'Reviewed',
    'Expired' => 'Reviewed',
    'Cancelled' => 'Reviewed',
    'Completed' => 'Completed',
];

$completed_steps = [];
$created_by_steps = [];
$reviewed_actual_status = null;

foreach ($statuses as $row) {
    $step = $status_to_step[$row['status']];
    $completed_steps[$step] = $row['created_at'];
    $created_by_steps[$step] = $row['created_by'];
    $comment_steps[$step] = $row['comment'];

    if ($step === 'Reviewed') {
        $reviewed_actual_status = $row['status'];
    }
}
?>

<style>
    .vertical-progress {
        position: relative;
        padding-left: 15px;
        margin: 40px 0;
        border-left: 3px solid #dee2e6;
    }

    .step {
        position: relative;
        padding: 10px 0 20px 30px;
    }

    .step::before {
        content: '';
        position: absolute;
        top: 18px;
        left: -16px;
        width: 12px;
        height: 12px;
        background-color: #dee2e6;
        border-radius: 50%;
        z-index: 1;
    }

    .step.active::before {
        background-color: rgb(97, 71, 0);
    }

    .step .icon {
        position: absolute;
        top: 8px;
        left: -40px;
        font-size: 20px;
        color: #6c757d;
    }

    .step.active .icon {
        color: #0d6efd;
    }

    .label {
        font-size: 16px;
        font-weight: 500;
    }

    .date {
        font-size: 13px;
        color: gray;
    }
</style>

<div class="container my-5">
    <h3 class="mb-4">Appointment Progress</h3>

    <div class="vertical-progress">
        <?php foreach ($all_steps as $step):
            $is_active = array_key_exists($step, $completed_steps);
            $display_label = $step;

            if ($step === 'Reviewed' && $is_active && $reviewed_actual_status) {
                $display_label = $reviewed_actual_status;
            }

            $icon = '📄';
            if ($step == 'Pending') {
                $icon = '🕒';
            } elseif ($step == 'Reviewed') {
                if ($reviewed_actual_status == 'Accepted') {
                    $icon = '✅';
                } elseif ($reviewed_actual_status == 'Rejected') {
                    $icon = '❌';
                } elseif ($reviewed_actual_status == 'Expired') {
                    $icon = '⏰';
                } elseif ($reviewed_actual_status == 'Cancelled') {
                    $icon = '🚫';
                }
            } elseif ($step == 'Completed') {
                $icon = '🩸';
            }
        ?>
            <div class="step <?= $is_active ? 'active' : '' ?>">
                <div class="icon"><?= $icon ?></div>
                <div class="label"><?= htmlspecialchars($display_label) ?></div>
                <div class="date">
                    <?= $is_active ? date('M d, Y H:i', strtotime($completed_steps[$step])) : '' ?>
                    <?= $is_active ? ' by ' . htmlspecialchars($created_by_steps[$step]) : '' ?>
                </div>
                <?php if ($is_active && !empty($comment_steps[$step])): ?>
                    <div class="text-muted" style="font-size: 14px;">
                        💬 <?= htmlspecialchars($comment_steps[$step]) ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="<?= ($_SESSION['role'] ?? '') === 'donor' ? 'dashboard.php?page=appointment_list' : 'dashboard.php?page=appointment_request' ?>" class="btn btn-secondary mt-4">
        Back to List
    </a>
</div>