<?php
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php?page=no_permission");
    exit;
}

require_once 'connection.php';

$message = '';
$alertClass = '';

// Handle Add
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (!empty($name)) {
        $stmt = $mysqli->prepare("INSERT INTO question_categories (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        $stmt->execute();
        $message = 'Category added!';
        $alertClass = 'alert-success';
        $stmt->close();
    } else {
        $message = 'Name is required.';
        $alertClass = 'alert-warning';
    }
}

// Handle Edit
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $stmt = $mysqli->prepare("UPDATE question_categories SET name=?, description=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $description, $id);
    $stmt->execute();
    $message = 'Category updated!';
    $alertClass = 'alert-info';
    $stmt->close();
}

// Handle Delete
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];
    $stmt = $mysqli->prepare("DELETE FROM question_categories WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $message = 'Category deleted!';
    $alertClass = 'alert-danger';
    $stmt->close();
}

// Fetch categories
$categories = [];
$result = $mysqli->query("SELECT * FROM question_categories ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
$mysqli->close();
?>

<h2 class="text-center">Category Management</h2>
<!-- Add Category Button and Modal -->
<button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addModal">Add New Category</button>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Center modal vertically -->
        <form method="POST" class="modal-content">
            <input type="hidden" name="action" value="add">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success">Add Category</button>
            </div>
        </form>
    </div>
</div>


<!-- Display Messages -->
<?php if ($message): ?>
    <div class="alert <?php echo $alertClass; ?> mt-3"><?php echo $message; ?></div>
<?php endif; ?>

<!-- Category List -->
<div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
        <h5>Category List</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th width="180">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= $no++ ?></td> <!-- Display row number -->
                        <td><?= htmlspecialchars($cat['name']) ?></td>
                        <td><?= htmlspecialchars($cat['description']) ?></td>
                        <td>
                            <!-- View Button with Icon -->
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#viewModal<?= $cat['id'] ?>">
                                <i class="bi bi-eye"></i> <!-- Eye Icon for View -->
                            </button>

                            <!-- Edit Button with Icon -->
                            <button class="btn btn-sm btn-warning" onclick="fillEditForm(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['name']) ?>', '<?= htmlspecialchars($cat['description']) ?>')">
                                <i class="bi bi-pencil"></i> <!-- Pencil Icon for Edit -->
                            </button>

                            <!-- Delete Button with Icon -->
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i> <!-- Trash Icon for Delete -->
                                </button>
                            </form>
                        </td>
                    </tr>
                    <!-- View Modal -->
                    <div class="modal fade" id="viewModal<?= $cat['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title">Category Detail</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>ID:</strong> <?= $cat['id'] ?></p>
                                    <p><strong>Name:</strong> <?= htmlspecialchars($cat['name']) ?></p>
                                    <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($cat['description'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="editId">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" id="editName" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="editDesc" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function fillEditForm(id, name, description) {
        document.getElementById('editId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editDesc').value = description;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    }
</script>