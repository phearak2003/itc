<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../connection.php';
include __DIR__ . '/../telegram/send.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role_name = 'donor';
    $image_url = null;

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $dob = trim($_POST['dob'] ?? null);
    $gender = $_POST['gender'] ?? null;
    $blood_type = $_POST['blood_type'] ?? null;

    $check_stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $error = "Username already exists.";
    } else {
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $img_tmp = $_FILES['image']['tmp_name'];
            $img_name = basename($_FILES['image']['name']);
            $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($img_ext, $allowed)) {
                $new_name = uniqid('profile_', true) . '.' . $img_ext;
                $upload_dir = 'uploads/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $image_url = $upload_dir . $new_name;
                move_uploaded_file($img_tmp, $image_url);
            } else {
                $error = "Invalid image file type.";
            }
        } else {
            $error = "Image upload failed.";
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
                $hashed = password_hash($password, PASSWORD_DEFAULT);

                $active = 1;
                $force_reset = 0;

                $insert_stmt = $mysqli->prepare("INSERT INTO users (username, password, active, role_id, image_url, force_reset) VALUES (?, ?, ?, ?, ?, ?)");
                $insert_stmt->bind_param("ssiisi", $username, $hashed, $active, $role_id, $image_url, $force_reset);

                if ($insert_stmt->execute()) {
                    $user_id = $insert_stmt->insert_id;

                    $profile_stmt = $mysqli->prepare("INSERT INTO user_profiles (user_id, first_name, last_name, dob, gender, blood_type) VALUES (?, ?, ?, ?, ?, ?)");
                    $profile_stmt->bind_param("isssss", $user_id, $first_name, $last_name, $dob, $gender, $blood_type);
                    $profile_stmt->execute();
                    $profile_stmt->close();

                    $telegram_chat_id = isset($_POST['telegram_chat_id']) ? trim($_POST['telegram_chat_id']) : null;
                    $contact_stmt = $mysqli->prepare("INSERT INTO user_contacts (user_id, telegram_chat_id) VALUES (?, ?)");
                    $contact_stmt->bind_param("is", $user_id, $telegram_chat_id);
                    $contact_stmt->execute();

                    if ($telegram_chat_id) {
                        $text = "Welcome!\nNow you can login with your own account.";
                        sendTelegramMessage($telegram_chat_id, $text);
                    }

                    $login_stmt = $mysqli->prepare("INSERT INTO login_attempts (user_id) VALUES (?)");
                    $login_stmt->bind_param("i", $user_id);
                    $login_stmt->execute();

                    header("Location: success_register.php");
                    exit;
                } else {
                    $error = "Failed to register user.";
                }
            }
        }
    }
}
?>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <style>
        .form-control:focus {
            outline: none !important;
            box-shadow: none !important;
        }
    </style>
    <div class="container">
        <form method="POST" enctype="multipart/form-data" class="p-4 border rounded bg-white shadow-sm my-5" style="max-width: 600px; margin: auto;">
            <h3 class="mb-4 text-center text-danger">Register</h3>

            <div class="row mb-3">
                <div class="col-12 text-center">
                    <img id="preview" src="../uploads/assets/default-user.png" alt="Image Preview" class="img-thumbnail rounded-circle mb-2" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="image" class="form-label">Profile Image *</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)" required>
                </div>

                <div class="col-md-6">
                    <label for="username" class="form-label">Username *</label>
                    <input type="text" class="form-control" id="username" name="username" required oninput="updateFilename()">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name">
                </div>
                <div class="col-md-6">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" id="gender" name="gender">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="blood_type" class="form-label">Blood Type</label>
                    <select class="form-select" id="blood_type" name="blood_type" required>
                        <option value="">Select</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label">Password *</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="telegram_chat_id" class="form-label">Telegram Chat ID *</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="telegram_chat_id" name="telegram_chat_id" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="testTelegram()">Test</button>
                    </div>
                </div>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <button type="submit" class="btn btn-danger w-100">Register</button>

            <hr>

            <p class="text-center"><small>Already have account? <a href="login.php">Login</a></small></p>
        </form>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
</body>