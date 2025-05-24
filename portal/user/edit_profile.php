<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: dashboard.php?page=no_permission");
    exit;
}
include 'connection.php';
include __DIR__ . '/../telegram/send.php';

if (isset($_GET['id'])) {
    $user_id = (int) $_GET['id'];
    $user_stmt = $mysqli->prepare("SELECT u.username, u.image_url, up.first_name, up.last_name, up.dob, up.gender, up.blood_type, uc.telegram_chat_id, r.name as role_name, u.force_reset FROM users u JOIN user_profiles up ON u.id = up.user_id LEFT JOIN user_contacts uc ON u.id = uc.user_id JOIN roles r ON u.role_id = r.id WHERE u.id = ?");
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_stmt->store_result();
    $user_stmt->bind_result($username, $image_url, $first_name, $last_name, $dob, $gender, $blood_type, $telegram_chat_id, $role_name, $force_reset);
    $user_stmt->fetch();
    $user_stmt->close();

    if (!$username) {
        die('User not found.');
    }

    if ($force_reset == 1) {
        die('<div class="alert alert-danger">This user has been forcefully reset. Please let user reset their password before be able to edit.</div><br><a class="btn btn-primary px-4" href="dashboard.php?page=user_management">Back</a>');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role_name = trim($_POST['role_name']);

    if ($role_name !== 'admin') {
        $stmt = $mysqli->prepare("SELECT r.name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($current_role);
        if ($stmt->fetch()) {
            $role_name = $current_role;
        }
        $stmt->close();
    }

    if (isset($_GET['id'])) {
        $user_id = (int) $_GET['id'];
        $user_stmt = $mysqli->prepare("SELECT u.image_url AS old_image_url FROM users u JOIN user_profiles up ON u.id = up.user_id LEFT JOIN user_contacts uc ON u.id = uc.user_id JOIN roles r ON u.role_id = r.id WHERE u.id = ?");
        $user_stmt->bind_param("i", $user_id);
        $user_stmt->execute();
        $user_stmt->store_result();
        $user_stmt->bind_result($old_image_url);
        $user_stmt->fetch();
        $user_stmt->close();

        if (!$username) {
            die('User not found.');
        }
    }

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $dob = trim($_POST['dob'] ?? null);
    $gender = $_POST['gender'] ?? null;
    $blood_type = $_POST['blood_type'] ?? null;
    $telegram_chat_id = trim($_POST['telegram_chat_id'] ?? '');

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img_tmp = $_FILES['image']['tmp_name'];
        $img_name = basename($_FILES['image']['name']);
        $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($img_ext, $allowed)) {
            $new_name = uniqid('profile_', true) . '.' . $img_ext;
            $upload_dir = __DIR__ . '/../uploads/profiles/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            move_uploaded_file($img_tmp, $upload_dir . $new_name);
            $image_url = $upload_dir . $new_name;
        } else {
            $error = "Invalid image file type.";
        }
    }

    if (empty($error)) {
        $role_stmt = $mysqli->prepare("SELECT id FROM roles WHERE name = ?");
        $role_stmt->bind_param("s", $role_name);
        $role_stmt->execute();
        $role_stmt->bind_result($role_id);
        $role_stmt->fetch();
        $role_stmt->close();

        if (empty($role_id)) {
            $error = "Invalid role selected.";
        } else {
            $image_url = "uploads/profiles/" . $new_name;

            $update_stmt = $mysqli->prepare("UPDATE users SET role_id = ?, image_url = ? WHERE username = ?");
            $update_stmt->bind_param("iss", $role_id, $image_url, $username);

            if ($old_image_url && $old_image_url !== 'uploads/assets/default-user.png' && file_exists($old_image_url)) {
                unlink($old_image_url);
            }

            if ($update_stmt->execute()) {
                $profile_stmt = $mysqli->prepare("UPDATE user_profiles SET first_name = ?, last_name = ?, dob = ?, gender = ?, blood_type = ? WHERE user_id = ?");
                $profile_stmt->bind_param("sssssi", $first_name, $last_name, $dob, $gender, $blood_type, $user_id);
                $profile_stmt->execute();
                $profile_stmt->close();

                $contact_stmt = $mysqli->prepare("UPDATE user_contacts SET telegram_chat_id = ? WHERE user_id = ?");
                $contact_stmt->bind_param("si", $telegram_chat_id, $user_id);
                $contact_stmt->execute();

                $login_stmt = $mysqli->prepare("UPDATE login_attempts SET user_id = ? WHERE user_id = ?");
                $login_stmt->bind_param("ii", $user_id, $user_id);
                $login_stmt->execute();

                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

                if ($_SESSION['user_id'] === $id) {
                    header("Location: dashboard.php?page=user_profile");
                    exit;
                } else {
                    header("Location: dashboard.php?page=user_management");
                    exit;
                }
            } else {
                $error = "Failed to update user.";
            }
        }
    }
}

$roles = $mysqli->query("SELECT * FROM roles");
?>

<div class="container">
    <form method="POST" enctype="multipart/form-data" class="p-4 border rounded bg-white shadow-sm my-5" style="margin: auto; max-width: 600px;">
        <h3 class="mb-4 text-center text-danger">Edit Profile</h3>

        <div class="row mb-3">
            <div class="col-12 text-center">
                <img id="preview" src="<?= $image_url ? $image_url : 'uploads/assets/default-user.png' ?>" alt="Image Preview" class="img-thumbnail rounded-circle mb-2" style="width: 150px; height: 150px; object-fit: cover;">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="image" class="form-label">Profile Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
            </div>

            <div class="col-md-6">
                <label for="username" class="form-label">Username *</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($username) ?>" disabled>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($first_name) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($last_name) ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" id="gender" name="gender" required>
                    <option value="">Select</option>
                    <option value="male" <?= $gender == 'male' ? 'selected' : '' ?>>Male</option>
                    <option value="female" <?= $gender == 'female' ? 'selected' : '' ?>>Female</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob" value="<?= htmlspecialchars($dob) ?>">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="blood_type" class="form-label">Blood Type</label>
                <select class="form-select" id="blood_type" name="blood_type" required>
                    <option value="">Select</option>
                    <option value="A+" <?= $blood_type == 'A+' ? 'selected' : '' ?>>A+</option>
                    <option value="A-" <?= $blood_type == 'A-' ? 'selected' : '' ?>>A-</option>
                    <option value="B+" <?= $blood_type == 'B+' ? 'selected' : '' ?>>B+</option>
                    <option value="B-" <?= $blood_type == 'B-' ? 'selected' : '' ?>>B-</option>
                    <option value="AB+" <?= $blood_type == 'AB+' ? 'selected' : '' ?>>AB+</option>
                    <option value="AB-" <?= $blood_type == 'AB-' ? 'selected' : '' ?>>AB-</option>
                    <option value="O+" <?= $blood_type == 'O+' ? 'selected' : '' ?>>O+</option>
                    <option value="O-" <?= $blood_type == 'O-' ? 'selected' : '' ?>>O-</option>
                </select>
            </div>
            <div class="col-md-6">
                <label>Role</label>
                <select class="form-select" name="role_name" <?= $_SESSION['user_id'] == $user_id ? 'disabled' : '' ?> required>
                    <option value="">Select Role</option>
                    <?php while ($role = $roles->fetch_assoc()) { ?>
                        <option value="<?= $role['name'] ?>" <?= $role['name'] == $role_name ? 'selected' : '' ?>><?= $role['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="telegram_chat_id" class="form-label">Telegram Chat ID *</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="telegram_chat_id" name="telegram_chat_id" value="<?= htmlspecialchars($telegram_chat_id) ?>" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="testTelegram()">Test</button>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col d-flex justify-content-between">
                <a class="btn btn-primary w-50 me-2" href="dashboard.php?page=user_profile">Back</a>
                <button type="submit" class="btn btn-danger w-50">Save Changes</button>
            </div>
        </div>
    </form>
</div>

<script>
    function testTelegram() {
        const chatId = document.getElementById("telegram_chat_id").value;
        if (!chatId) {
            alert("Please enter a Telegram Chat ID.");
            return;
        }

        const formData = new FormData();
        formData.append("chat_id", chatId);
        formData.append("text", "Hello, this is a test message from Blood Donation Website!");

        fetch("telegram/send.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(msg => alert("✅ " + msg))
            .catch(err => {
                console.error(err);
                alert("❌ Error sending message.");
            });
    }

    function previewImage(event) {
        const reader = new FileReader();
        const output = document.getElementById('preview');
        const file = event.target.files[0];

        if (!file) {
            output.src = 'uploads/assets/default-user.png';
            return;
        }

        reader.onload = function() {
            output.src = reader.result;
        };
        reader.readAsDataURL(file);

        updateFilename();
    }

    function updateFilename() {
        const username = document.getElementById('username').value.trim();
        const imageInput = document.getElementById('image');
        const file = imageInput.files[0];

        if (file && username) {
            const timestamp = Date.now();
            const extension = file.name.split('.').pop();
            const newName = `${username}_${timestamp}.${extension}`;

            const dataTransfer = new DataTransfer();
            const renamedFile = new File([file], newName, {
                type: file.type
            });
            dataTransfer.items.add(renamedFile);
            imageInput.files = dataTransfer.files;
        }
    }
</script>