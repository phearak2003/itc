<?php
ob_start();
require 'connection.php';

// 
$userId = (int)$_SESSION['user_id'];

if (!$userId) {
    header('Location: login.php');
    exit();
}

// Step 1: Fetch latest assessment for the user
$stmtCheck = $mysqli->prepare("
    SELECT * FROM assessments 
    WHERE user_id = ? 
    ORDER BY create_date DESC 
    LIMIT 1
");
$stmtCheck->bind_param("i", $userId);
$stmtCheck->execute();
$resultStmtCheck = $stmtCheck->get_result();

if ($rowStmtCheck = $resultStmtCheck->fetch_assoc()) {
    // Step 2: Check if passed
    if ($rowStmtCheck['is_pass'] == 1) {
        // Step 3: Check if older than 3 months
        $createDate = new DateTime($rowStmtCheck['create_date']);
        $threeMonthsAgo = (new DateTime())->modify('-3 months');

        if ($createDate < $threeMonthsAgo) {
            // Step 4: Check if not yet booked
            if ($rowStmtCheck['is_book_appointment'] == 0) {
                // echo "Assessment is passed, expired, and not booked yet.";
                header('Location: dashboard.php?page=assessment_result');
                exit;
            } else {
                // echo "Assessment is passed and expired, but already booked.";
            }
        } else {
            // echo "Assessment is passed and still valid.";
            header('Location: dashboard.php?page=assessment_result');
            exit;
        }
    } else {
        // echo "Assessment not passed.";
    }
} else {
    // echo "No assessment found.";
}



// 
$categoriesQuery = $mysqli->query("SELECT * FROM question_categories");
$categories = [];

while ($category = $categoriesQuery->fetch_assoc()) {
    $catId = $category['id'];
    $questionsQuery = $mysqli->query("SELECT * FROM assessment_questions WHERE category_id = $catId ORDER BY order_no ASC");
    $questions = [];

    while ($q = $questionsQuery->fetch_assoc()) {
        $questions[] = $q;
    }

    $category['questions'] = $questions;
    $categories[] = $category;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];

    $mysqli->begin_transaction();

    try {
        // Insert the assessment record
        $stmt = $mysqli->prepare("INSERT INTO assessments (user_id, is_pass, is_book_appointment) VALUES (?, ?, ?)");
        $isPass = 0;
        $isBookAppointment = 0;
        $stmt->bind_param('iii', $userId, $isPass, $isBookAppointment);
        $stmt->execute();
        $assessmentId = $mysqli->insert_id;

        $allAnswersCorrect = true;

        foreach ($_POST['answers'] as $questionId => $answer) {
            $stmt = $mysqli->prepare("SELECT expected_answer, is_required FROM assessment_questions WHERE id = ?");
            $stmt->bind_param('i', $questionId);
            $stmt->execute();
            $stmt->bind_result($expectedAnswer, $isRequired);
            $stmt->fetch();
            $stmt->close();

            $isCorrect = ($answer === $expectedAnswer) ? 1 : 0;

            if ($isRequired) {
                $totalRequired++;
                if ($isCorrect) {
                    $correctRequired++;
                }
            }

            $stmt = $mysqli->prepare("INSERT INTO assessment_details (assessment_id, question_id, answer, is_correct) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('iisi', $assessmentId, $questionId, $answer, $isCorrect);
            $stmt->execute();
        }

        $percentage = $totalRequired > 0 ? ($correctRequired / $totalRequired) * 100 : 0;
        $isPass = $percentage >= 60 ? 1 : 0;

        // Update the assessment record with pass status
        $stmt = $mysqli->prepare("UPDATE assessments SET is_pass = ? WHERE id = ?");
        $stmt->bind_param('ii', $isPass, $assessmentId);
        $stmt->execute();
        $mysqli->commit();

        header('Location: assessment_result.php');
        exit();
    } catch (Exception $e) {
        $mysqli->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>

<style>
    #stepProgress .nav-link {
        cursor: pointer;
        border-radius: 50px;
        margin: 0 4px;
    }

    #stepProgress .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
    }
</style>

<div class="assessment-container my-5" style="max-width: 800px; margin: 0 auto;">
    <form method="POST" action="" id="assessmentForm">
        <div class="d-flex justify-content-between mb-4">
            <button type="button" id="prevBtn" class="btn btn-secondary" onclick="nextStep(-1)" disabled>Previous</button>
            <ul class="nav nav-pills justify-content-center" id="stepProgress">
                <?php foreach ($categories as $index => $category): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $index === 0 ? 'active' : '' ?>"
                            href="#"
                            data-step="<?= $index ?>"
                            data-label="<?= ($index + 1) . '. ' . htmlspecialchars($category['name']) ?>">
                            <?= $index === 0 ? ($index + 1) . '. ' . htmlspecialchars($category['name']) : ($index + 1) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button type="button" id="nextBtn" class="btn btn-primary" onclick="nextStep(1)">Next</button>
            <button type="submit" id="submitBtn" class="btn btn-success">Submit Assessment</button>
        </div>

        <div id="step-container">
            <?php $questionNumber = 1; ?>
            <?php foreach ($categories as $index => $category): ?>
                <div class="step" <?= $index > 0 ? 'style="display:none;"' : '' ?>>
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <?= htmlspecialchars($category['name']) ?>
                        </div>
                        <div class="card-body">
                            <?php foreach ($category['questions'] as $question): ?>
                                <div class="mb-3">
                                    <p>
                                        <strong><?= $questionNumber++ ?>. </strong> <?= htmlspecialchars($question['question_text']) ?>
                                        <span class="text-danger"><?= $question['is_required'] ? ' *' : ' (Optional)' ?></span>
                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm ms-2 clear-answer"
                                            data-question="answers[<?= $question['id'] ?>]">
                                            <i class="bi bi-eraser"></i>
                                        </button>
                                    </p>

                                    <?php
                                    $yesId = "yes_" . $question['id'];
                                    $noId = "no_" . $question['id'];
                                    ?>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input"
                                            type="radio"
                                            id="<?= $yesId ?>"
                                            name="answers[<?= $question['id'] ?>]"
                                            value="Yes"
                                            <?= $question['is_required'] ? 'required' : '' ?>>
                                        <label class="form-check-label" for="<?= $yesId ?>">Yes</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input"
                                            type="radio"
                                            id="<?= $noId ?>"
                                            name="answers[<?= $question['id'] ?>]"
                                            value="No">
                                        <label class="form-check-label" for="<?= $noId ?>">No</label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll('#stepProgress .nav-link').forEach((tab, i, allTabs) => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();

            const targetStep = parseInt(this.dataset.step);

            // Only validate if moving forward
            if (targetStep > currentStep && !validateStep(currentStep)) {
                alert("Please answer all required questions before moving to another step.");
                return;
            }

            allTabs.forEach(link => {
                link.classList.remove('active');
                link.textContent = link.dataset.step !== undefined ?
                    parseInt(link.dataset.step) + 1 :
                    link.textContent;
            });

            this.classList.add('active');
            this.textContent = this.dataset.label;

            currentStep = targetStep;
            showStep(currentStep);
        });
    });

    // Extend showStep to update progress labels
    function showStep(step) {
        console.log('Current Step:', step); // Debugging line
        steps.forEach((el, idx) => {
            el.style.display = idx === step ? 'block' : 'none';
        });

        document.querySelectorAll('#stepProgress .nav-link').forEach((link, idx) => {
            if (idx === step) {
                link.classList.add('active');
                link.textContent = link.dataset.label;
            } else {
                link.classList.remove('active');
                link.textContent = idx + 1;
            }
        });

        prevBtn.disabled = step === 0;
        nextBtn.style.display = (step === steps.length - 1) ? 'none' : 'inline-block';
        submitBtn.style.display = (step === steps.length - 1) ? 'block' : 'none';
    }

    // Clearing answers
    document.querySelectorAll('.clear-answer').forEach(button => {
        button.addEventListener('click', function() {
            const name = this.getAttribute('data-question');
            document.querySelectorAll(`input[name="${name}"]`).forEach(input => {
                input.checked = false;
            });
        });
    });

    // Multi-step form navigation
    let currentStep = 0;
    const steps = document.querySelectorAll('.step');
    const stepLinks = document.querySelectorAll('#stepProgress .nav-link');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    function showStep(step) {
        steps.forEach((el, idx) => {
            el.style.display = idx === step ? 'block' : 'none';
        });

        document.querySelectorAll('#stepProgress .nav-link').forEach((link, idx) => {
            if (idx === step) {
                link.classList.add('active');
                link.textContent = link.dataset.label;
            } else {
                link.classList.remove('active');
                link.textContent = idx + 1;
            }
        });

        prevBtn.disabled = step === 0;
        nextBtn.style.display = (step === steps.length - 1) ? 'none' : 'inline-block';
        submitBtn.style.display = (step === steps.length - 1) ? 'block' : 'none';
    }

    function validateStep(stepIndex) {
        const step = steps[stepIndex];
        const radios = step.querySelectorAll('input[required]');
        const requiredNames = [...new Set([...radios].map(r => r.name))];
        return requiredNames.every(name =>
            step.querySelector(`input[name="${name}"]:checked`)
        );
    }

    function nextStep(direction) {
        if (direction === 1 && !validateStep(currentStep)) {
            alert("Please answer all required questions before proceeding.");
            return;
        }

        currentStep += direction;
        showStep(currentStep);
    }

    // Enable clicking on step indicators
    stepLinks.forEach((link, index) => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            if (index === currentStep) return;

            // If moving forward, validate current step
            if (index > currentStep && !validateStep(currentStep)) {
                alert("Please complete all required questions before proceeding.");
                return;
            }

            currentStep = index;
            showStep(currentStep);
        });
    });

    // Final form validation on submit
    document.getElementById('assessmentForm').addEventListener('submit', function(e) {
        const allRequired = [...document.querySelectorAll('input[required]')];
        const requiredNames = [...new Set(allRequired.map(r => r.name))];

        let valid = true;
        requiredNames.forEach(name => {
            if (!document.querySelector(`input[name="${name}"]:checked`)) {
                valid = false;
            }
        });

        if (!valid) {
            e.preventDefault();
            alert("Please answer all required questions before submitting.");
        } else {
            // Optional: You can show a confirmation or submission status here
            alert('Form is ready to submit!');
        }
    });

    showStep(currentStep);
</script>