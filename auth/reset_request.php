<?php
include '../connection.php';
include __DIR__ . '/../telegram/otp.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $error = sendOtp($username);
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