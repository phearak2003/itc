<?php
session_start();
include '../connection.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("
        SELECT u.id, u.password, u.active, r.name AS role_name, u.force_reset
        FROM users u
        JOIN roles r ON u.role_id = r.id
        WHERE u.username = ? and u.password is not null
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password, $active, $role_name, $force_reset);
        $stmt->fetch();

        $attempt_stmt = $mysqli->prepare("SELECT failed_attempts, last_failed_at FROM login_attempts WHERE user_id = ?");
        $attempt_stmt->bind_param("i", $user_id);
        $attempt_stmt->execute();
        $attempt_stmt->bind_result($failed_attempts, $last_failed_at);
        $attempt_stmt->fetch();
        $attempt_stmt->close();

        if ($failed_attempts >= 3) {
            $error = "Your account is locked due to too many failed login attempts. Please contact the administrator.";
        } elseif (password_verify($password, $hashed_password)) {
            if ($active == 1) {
                $reset_stmt = $mysqli->prepare("UPDATE login_attempts SET failed_attempts = 0, last_failed_at = NULL WHERE user_id = ?");
                $reset_stmt->bind_param("i", $user_id);
                $reset_stmt->execute();

                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role_name;
                header("Location: ../dashboard.php");
                exit;
            } else {
                if ($force_reset == 1) {
                    $error = "You must reset your password before logging in.";
                } else {
                    $error = "Your account is inactive. Please contact the administrator.";
                }
            }
        } else {
            $failed_attempts++;
            $update_stmt = $mysqli->prepare("UPDATE login_attempts SET failed_attempts = ?, last_failed_at = NOW() WHERE user_id = ?");
            $update_stmt->bind_param("ii", $failed_attempts, $user_id);
            $update_stmt->execute();

            if ($failed_attempts >= 3) {
                $error = "Your account is now locked after 3 failed login attempts.";
            } else {
                $error = "Invalid username or password. Attempt $failed_attempts of 3.";
            }
        }
    } else {
        $error = "Invalid username or password.";
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
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-body my-5">
                        <h3 class="text-center text-danger mb-4">User Login</h3>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control" name="username" id="username" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <p class="text-end mb-3"><small>Forget password? <a href="reset_request.php">Reset</a></small></p>

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <button type="submit" class="btn btn-danger w-100">Login</button>

                            <hr>

                            <p class="text-center"><small>Don't have an account? <a href="register.php">Register</a></small></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            if (type === 'password') {
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            } else {
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            }
        });
    </script>
</body>