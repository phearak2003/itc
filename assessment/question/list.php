<?php
include 'connection.php';

// Handle INSERT
if (isset($_POST['add'])) {
    $stmt = $mysqli->prepare("INSERT INTO assessment_questions (question_text, category_id, expected_answer, is_required, order_no) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisii", $_POST['question_text'], $_POST['category_id'], $_POST['expected_answer'], $_POST['is_required'], $_POST['order_no']);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?page=list_question");
    exit;
}

// Handle UPDATE
if (isset($_POST['update'])) {
    $stmt = $mysqli->prepare("UPDATE assessment_questions SET question_text=?, category_id=?, expected_answer=?, is_required=?, order_no=? WHERE id=?");
    $stmt->bind_param("sisiii", $_POST['question_text'], $_POST['category_id'], $_POST['expected_answer'], $_POST['is_required'], $_POST['order_no'], $_POST['id']);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?page=list_question");
    exit;
}

// Handle DELETE
if (isset($_POST['delete'])) {
    $stmt = $mysqli->prepare("DELETE FROM assessment_questions WHERE id=?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?page=list_question");
    exit;
}

// Fetch questions and categories
$questions = $mysqli->query("SELECT aq.*, qc.name AS category_name FROM assessment_questions aq JOIN question_categories qc ON aq.category_id = qc.id ORDER BY aq.order_no ASC");
$categories = $mysqli->query("SELECT * FROM question_categories");

function esc($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}
?>

<h2 class="text-center">Assessment Questions</h2>

<!-- Button to open the add hospital modal -->
<button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addModal">Add New Question</button>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="" method="POST">
            <div class="modal-header">
                <h5>Add Question</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="question_text" class="form-label">Question</label>
                    <textarea id="question_text" name="question_text" class="form-control" placeholder="Question" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="expected_answer" class="form-label">Expected Answer</label>
                    <input type="text" id="expected_answer" name="expected_answer" class="form-control" placeholder="Expected Answer">
                </div>

                <div class="mb-3">
                    <label for="order_no" class="form-label">Order Number</label>
                    <input type="number" id="order_no" name="order_no" class="form-control" placeholder="Order Number">
                </div>

                <div class="mb-3 form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="is_required" checked> Required
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" name="add" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Hospitals Table -->
<div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
        <h5>Hospital List</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Category</th>
                    <th>Expected Answer</th>
                    <th>Required</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($questions->num_rows > 0): ?>
                    <?php while ($q = $questions->fetch_assoc()): ?>
                        <tr>
                            <td><?= esc($q['id']) ?></td>
                            <td><?= esc($q['question_text']) ?></td>
                            <td><?= esc($q['category_name']) ?></td>
                            <td><?= esc($q['expected_answer']) ?></td>
                            <td><?= $q['is_required'] ? 'Yes' : 'No' ?></td>
                            <td><?= esc($q['order_no']) ?></td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal<?= $q['id'] ?>">View</button>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $q['id'] ?>">Edit</button>
                                <form method='POST' class='d-inline'>
                                    <input type='hidden' name='id' value='<?= $q['id'] ?>'>
                                    <button type='submit' name='delete' class='btn btn-danger btn-sm' onclick='return confirm("Are you sure?")'>Delete</button>
                                </form>
                            </td>
                        </tr>

                        <!-- View Modal -->
                        <div class="modal fade" id="viewModal<?= $q['id'] ?>" tabindex="-1" aria-labelledby="viewModalLabel<?= $q['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewModalLabel<?= $q['id'] ?>">View Question</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Question:</strong> <?= esc($q['question_text']) ?></p>
                                        <p><strong>Category:</strong> <?= esc($q['category_name']) ?></p>
                                        <p><strong>Expected Answer:</strong> <?= esc($q['expected_answer']) ?></p>
                                        <p><strong>Required:</strong> <?= $q['is_required'] ? 'Yes' : 'No' ?></p>
                                        <p><strong>Order No:</strong> <?= esc($q['order_no']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $q['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $q['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?= $q['id'] ?>">Edit Question</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="">
                                            <input type="hidden" name="id" value="<?= $q['id'] ?>">

                                            <div class="mb-3">
                                                <label for="question_text" class="form-label">Question Text</label>
                                                <textarea id="question_text" name="question_text" class="form-control" required><?= esc($q['question_text']) ?></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="category_id" class="form-label">Category</label>
                                                <select class="form-select" id="category_id" name="category_id" required>
                                                    <option value="">Select</option>
                                                    <?php foreach ($categories as $cat): ?>
                                                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $q['category_id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="expected_answer" class="form-label">Expected Answer</label>
                                                <input type="text" id="expected_answer" name="expected_answer" class="form-control" value="<?= esc($q['expected_answer']) ?>">
                                            </div>

                                            <div class="mb-3">
                                                <label for="order_no" class="form-label">Order No</label>
                                                <input type="number" id="order_no" name="order_no" class="form-control" value="<?= esc($q['order_no']) ?>">
                                            </div>

                                            <div class="mb-3 form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="is_required" value="1" <?= $q['is_required'] ? 'checked' : '' ?>> Required
                                                </label>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-warning" name="update">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>