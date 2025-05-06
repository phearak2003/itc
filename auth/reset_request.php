<?php
include '../connection.php';
include __DIR__ . '/../telegram/send.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);

    $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();

        $contact_stmt = $mysqli->prepare("SELECT telegram_chat_id FROM user_contacts WHERE user_id = ?");
        $contact_stmt->bind_param("i", $user_id);
        $contact_stmt->execute();
        $contact_stmt->store_result();

        if ($contact_stmt->num_rows === 1) {
            $contact_stmt->bind_result($chat_id);
            $contact_stmt->fetch();
            $contact_stmt->close();

            if (!empty($chat_id)) {
                $delete_otp = $mysqli->prepare("DELETE FROM password_resets WHERE user_id = ?");
                $delete_otp->bind_param("i", $user_id);
                $delete_otp->execute();
                $delete_otp->close();

                $otp = rand(100000, 999999);
                $expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));

                $reset_stmt = $mysqli->prepare("INSERT INTO password_resets (user_id, otp_code, otp_expiry) VALUES (?, ?, ?)");
                $reset_stmt->bind_param("iss", $user_id, $otp, $expiry);
                $reset_stmt->execute();
                $reset_stmt->close();

                $message = "Your OTP code is: $otp (valid for 5 minutes)";
                sendTelegramMessage($chat_id, $message);

                $_SESSION['reset_user'] = $user_id;
                header("Location: verify_otp.php");
                exit;
            } else {
                $error = "No Telegram chat ID found for this user.";
            }
        } else {
            $error = "Telegram contact not found for this user.";
        }
    } else {
        $error = "User not found.";
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
            <h4 class="mb-3">Request OTP</h4>
            <div class="mb-3">
                <label for="username" class="form-label">Username *</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary w-100">Send OTP via Telegram</button>

            <hr>

            <p class="text-center"><small>Back to <a href="login.php">Login</a></small></p>
        </form>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>