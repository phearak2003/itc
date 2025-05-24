<?php
function sendOtp($username)
{
    include '../connection.php';
    include __DIR__ . '/../telegram/send.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
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
                    $_SESSION['reset_username'] = $username;
                    header("Location: verify_otp.php");
                    return '';
                } else {
                    return "No Telegram chat ID found for this user.";
                }
            } else {
                return "Telegram contact not found for this user.";
            }
        } else {
            return "User not found.";
        }
    } else {
        http_response_code(400);
        return "Invalid request.";
    }
}
