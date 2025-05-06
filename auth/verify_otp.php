<?php
include '../connection.php';
session_start();

if (!isset($_SESSION['reset_user'])) {
    header("Location: reset_request.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp = implode('', $_POST['otp']);
    $new_pass = $_POST['new_password'];
    $retype_pass = $_POST['retype_password'];
    $uid = $_SESSION['reset_user'];

    if ($new_pass !== $retype_pass) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $mysqli->prepare("SELECT otp_code, otp_expiry FROM password_resets WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $stmt->bind_result($db_otp, $otp_expiry);
        $stmt->fetch();
        $stmt->close();

        if ($otp === $db_otp && strtotime($otp_expiry) >= time()) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);

            $force_reset = 0;
            $active = 1;
            $update_user = $mysqli->prepare("UPDATE users SET password = ?, active = ?, force_reset = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $update_user->bind_param("siii", $hashed, $active, $force_reset, $uid);
            $update_user->execute();
            $update_user->close();

            $reset_attempts = $mysqli->prepare("UPDATE login_attempts SET failed_attempts = 0, last_failed_at = NULL WHERE user_id = ?");
            $reset_attempts->bind_param("i", $uid);
            $reset_attempts->execute();
            $reset_attempts->close();

            $delete_otp = $mysqli->prepare("DELETE FROM password_resets WHERE user_id = ?");
            $delete_otp->bind_param("i", $uid);
            $delete_otp->execute();
            $delete_otp->close();

            unset($_SESSION['reset_user']);

            header("Location: success_reset.php");
            exit;
        } else {
            $error = "Invalid or expired OTP code.";
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
        <form method="POST" class="p-4 border rounded bg-white shadow-sm my-5" style="max-width: 500px; margin: auto;">
            <h4 class="card-title mb-4 text-center">Reset Your Password</h4>

            <label class="form-label">OTP Code *</label>
            <div class="d-flex justify-content-between mb-3">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <input type="text" name="otp[]" maxlength="1" pattern="\d" class="form-control text-center me-1 otp-input" style="width: 50px;" required>
                <?php endfor; ?>
            </div>

            <hr>

            <div class="mb-3">
                <label for="new_password" class="form-label">New Password *</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
                <small id="passwordStrength" class="text-muted"></small>
                <ul id="passwordRules" class="small mt-1">
                    <li id="length" class="text-danger">At least 8 characters</li>
                    <li id="uppercase" class="text-danger">At least one uppercase letter</li>
                    <li id="lowercase" class="text-danger">At least one lowercase letter</li>
                    <li id="number" class="text-danger">At least one number</li>
                    <li id="special" class="text-danger">At least one special character (!@#$%^&*)</li>
                </ul>
            </div>

            <div class="mb-3">
                <label for="retype_password" class="form-label">Retype New Password *</label>
                <input type="password" name="retype_password" id="retype_password" class="form-control" required>
                <small id="matchStatus" class="text-danger d-none">Passwords do not match.</small>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <button type="submit" id="submitBtn" class="btn btn-primary w-100" disabled>Reset Password</button>
            
            <hr>

            <p class="text-center"><small>Back to <a href="login.php">Login</a></small></p>
        </form>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            const password = document.getElementById('new_password');
            const retype = document.getElementById('retype_password');
            const matchStatus = document.getElementById('matchStatus');
            const submitBtn = document.getElementById('submitBtn');

            const rules = {
                length: document.getElementById('length'),
                uppercase: document.getElementById('uppercase'),
                lowercase: document.getElementById('lowercase'),
                number: document.getElementById('number'),
                special: document.getElementById('special')
            };

            function validatePasswordStrength(pw) {
                const checks = {
                    length: pw.length >= 8,
                    uppercase: /[A-Z]/.test(pw),
                    lowercase: /[a-z]/.test(pw),
                    number: /[0-9]/.test(pw),
                    special: /[!@#$%^&*]/.test(pw)
                };

                let valid = true;
                for (let key in checks) {
                    if (checks[key]) {
                        rules[key].classList.remove('text-danger');
                        rules[key].classList.add('text-success');
                    } else {
                        rules[key].classList.add('text-danger');
                        rules[key].classList.remove('text-success');
                        valid = false;
                    }
                }

                return valid;
            }

            function checkFormValidity() {
                const strong = validatePasswordStrength(password.value);
                const match = password.value === retype.value && password.value !== "";

                matchStatus.classList.toggle('d-none', match);

                submitBtn.disabled = !(strong && match);
            }

            password.addEventListener('input', checkFormValidity);
            retype.addEventListener('input', checkFormValidity);

            document.querySelectorAll('.otp-input').forEach((input, i, arr) => {
                input.addEventListener('input', () => {
                    if (input.value && i < arr.length - 1) arr[i + 1].focus();
                });
            });
        </script>
    </div>
</body>