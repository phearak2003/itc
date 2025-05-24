<?php
include 'connection.php';

// Handle INSERT
if (isset($_POST['add'])) {
    $is_required = $_POST['is_required'] == 'on' ? 1 : 0;
    $stmt = $mysqli->prepare("INSERT INTO assessment_questions (question_text, category_id, expected_answer, is_required, order_no) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisii", $_POST['question_text'], $_POST['category_id'], $_POST['expected_answer'], $is_required, $_POST['order_no']);
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

function esc($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

// Pagination setup
$limit = 10;
$page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$pageName = isset($_GET['page']) ? $_GET['page'] : 'list_question';
$currentPage = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$offset = ($currentPage - 1) * $limit;

// Search logic
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchSQL = $search ? "WHERE aq.question_text LIKE ?" : "";

// Get total records for pagination
if ($search) {
    $countStmt = $mysqli->prepare("SELECT COUNT(*) FROM assessment_questions aq $searchSQL");
    $like = "%" . $search . "%";
    $countStmt->bind_param("s", $like);
} else {
    $countStmt = $mysqli->prepare("SELECT COUNT(*) FROM assessment_questions aq");
}
$countStmt->execute();
$countStmt->bind_result($totalRows);
$countStmt->fetch();
$countStmt->close();

$totalPages = ceil($totalRows / $limit);

// Fetch paginated questions
if ($search) {
    $querySQL = "SELECT aq.*, qc.name AS category_name 
                 FROM assessment_questions aq 
                 JOIN question_categories qc ON aq.category_id = qc.id 
                 $searchSQL 
                 ORDER BY qc.name ASC, aq.order_no ASC 
                 LIMIT ?, ?";
    $queryStmt = $mysqli->prepare($querySQL);
    // MySQLi requires integers for LIMIT params
    $queryStmt->bind_param("sii", $like, $offset, $limit);
} else {
    $querySQL = "SELECT aq.*, qc.name AS category_name 
                 FROM assessment_questions aq 
                 JOIN question_categories qc ON aq.category_id = qc.id 
                 ORDER BY qc.name ASC, aq.order_no ASC 
                 LIMIT ?, ?";
    $queryStmt = $mysqli->prepare($querySQL);
    $queryStmt->bind_param("ii", $offset, $limit);
}

$queryStmt->execute();
$questions = $queryStmt->get_result();
?>

<h2 class="text-center">Assessment Questions</h2>

<!-- Search and Add -->
<div class="d-flex justify-content-between mb-3">
    <form method="GET" class="d-flex">
        <input type="hidden" name="page" value="list_question">
        <input type="hidden" name="p" value="<?= intval($page) ?>">
        <input type="text" name="search" class="form-control me-2" placeholder="Search question..." value="<?= esc($search) ?>">
        <button class="btn btn-outline-primary" type="submit">Search</button>
    </form>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New Question</button>
</div>

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
                    <select class="form-select" id="expected_answer" name="expected_answer" required>
                        <option value="">Select</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
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
<div class="card">
    <div class="card-header bg-secondary text-white">
        <h5>Question List</h5>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
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
                <?php $no = $offset + 1; ?>
                <?php if ($questions->num_rows > 0): ?>
                    <?php while ($q = $questions->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($q['question_text']) ?></td>
                            <td><?= esc($q['category_name']) ?></td>
                            <td><?= esc($q['expected_answer']) ?></td>
                            <td><?= $q['is_required'] ? 'Yes' : 'No' ?></td>
                            <td><?= esc($q['order_no']) ?></td>
                            <td>
                                <!-- Actions -->
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal<?= $q['id'] ?>"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $q['id'] ?>"><i class="bi bi-pencil"></i></button>
                                <form method='POST' class='d-inline'>
                                    <input type='hidden' name='id' value='<?= $q['id'] ?>'>
                                    <button type='submit' name='delete' class='btn btn-danger btn-sm' onclick='return confirm("Are you sure?")'>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr><!-- View Modal -->
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
                                                <select class="form-select" id="expected_answer" name="expected_answer" required>
                                                    <option value="">Select</option>
                                                    <option value="Yes" <?= ($q['expected_answer'] === 'Yes') ? 'selected' : '' ?>>Yes</option>
                                                    <option value="No" <?= ($q['expected_answer'] === 'No') ? 'selected' : '' ?>>No</option>
                                                </select>
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

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <nav class="mt-3">
        <ul class="pagination justify-content-center">

            <!-- Prev button -->
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=list_question&p=<?= max(1, $page - 1) ?>&search=<?= urlencode($search) ?>" tabindex="-1">Previous</a>
            </li>

            <!-- Page numbers -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=list_question&p=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <!-- Next button -->
            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=list_question&p=<?= min($totalPages, $page + 1) ?>&search=<?= urlencode($search) ?>">Next</a>
            </li>

        </ul>
    </nav>
<?php endif; ?>