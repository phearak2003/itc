<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php?page=no_permission");
    exit;
}

include 'connection.php';

$roles_result = $mysqli->query("SELECT id, name FROM roles");

$selected_role = isset($_GET['role_id']) ? intval($_GET['role_id']) : 0;

if ($selected_role > 0) {
    $stmt = $mysqli->prepare("
        SELECT users.id, users.image_url, users.username, user_profiles.first_name, user_profiles.last_name, user_profiles.gender, users.active, roles.name AS role_name
        FROM users
        JOIN roles ON users.role_id = roles.id
        LEFT JOIN user_profiles ON users.id = user_profiles.user_id
        WHERE users.role_id = ?
    ");
    $stmt->bind_param("i", $selected_role);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $mysqli->query("
        SELECT users.id, users.image_url, users.username, user_profiles.first_name, user_profiles.last_name, user_profiles.gender, users.active, roles.name AS role_name
        FROM users
        JOIN roles ON users.role_id = roles.id
        LEFT JOIN user_profiles ON users.id = user_profiles.user_id
    ");
}
?>

<?php if (isset($_GET['error']) && $_GET['error']): ?>
    <div class="alert alert-warning"><?php echo htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

<h2 class="text-center">User Management</h2>

<a href="?page=add_user" class="btn btn-primary mb-4">Add New User</a>

<form method="GET" class="mb-3">
    <input type="hidden" name="page" value="user_management">
    <div class="input-group" style="max-width: 300px;">
        <label class="input-group-text">Filter by Role</label>
        <select name="role_id" class="form-select" onchange="this.form.submit()">
            <option value="0">All Roles</option>
            <?php while ($role = $roles_result->fetch_assoc()): ?>
                <option value="<?= $role['id'] ?>" <?= $selected_role == $role['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars(ucfirst($role['name'])) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
</form>

<!-- Hospitals Table -->
<div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
        <h5>User Management</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Profile</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $order = 1;
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $order++ ?></td>
                            <td>
                                <?php if (!empty($row['image_url'])): ?>
                                    <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="User Image" class="rounded-circle" width="35" height="35">
                                <?php else: ?>
                                    <span class="text-muted">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($row['first_name'])) . ' ' . htmlspecialchars(ucfirst($row['last_name'])) ?></td>
                            <td><?= htmlspecialchars(ucfirst($row['gender'])) ?></td>
                            <td><?= htmlspecialchars(ucfirst($row['role_name'])) ?></td>
                            <td>
                                <?php if ($row['active'] == 1): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="?page=edit_user&id=<?= $row['id'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="?page=delete_user&id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');" class="btn btn-danger btn-sm" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <a href="?page=view_user&id=<?= $row['id'] ?>" class="btn btn-primary btn-sm" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No users found for selected role.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>