<?php
require 'connection.php';

$assessmentId = isset($_GET['assessment_id']) ? (int)$_GET['assessment_id'] : 0;

// Fetch assessment metadata
$assessmentInfoStmt = $mysqli->prepare("SELECT create_date, is_pass, is_book_appointment FROM assessments WHERE id = ?");
$assessmentInfoStmt->bind_param("i", $assessmentId);
$assessmentInfoStmt->execute();
$assessmentInfoResult = $assessmentInfoStmt->get_result();

$assessmentDate = null;
$isPassed = null;
$isBooked = null;

if ($row = $assessmentInfoResult->fetch_assoc()) {
    $assessmentDate = date('F j, Y, g:i a', strtotime($row['create_date']));
    $isPassed = $row['is_pass'] ? 'Yes' : 'No';
    $isBooked = $row['is_book_appointment'] ? 'Yes' : 'No';
}

// Fetch assessment details
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
    echo '<h2>Assessment Details</h2>';

    echo '<div class="row my-2">';
    echo '<div class="col-3 d-flex">';
    echo '<a class="btn btn-primary w-25" href="dashboard.php?page=assessment_history">Back</a>';
    echo '<a href="dashboard.php?page=appointment" class="btn btn-success mx-3">Book Appointment</a>';
    echo ' </div>';
    echo ' </div>';

    

    if ($assessmentDate !== null) {
        echo '<div class="mb-3">';
        echo '<strong>Assessment Date:</strong> ' . $assessmentDate . '<br>';
        echo '<strong>Passed:</strong> <span class="badge bg-' . ($isPassed === 'Yes' ? 'success' : 'danger') . '">' . $isPassed . '</span><br>';
        echo '<strong>Booked Appointment:</strong> <span class="badge bg-' . ($isBooked === 'Yes' ? 'primary' : 'secondary') . '">' . $isBooked . '</span>';
        echo '</div>';
    }

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
