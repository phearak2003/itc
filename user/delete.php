<?php
require 'connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php?page=no_permission");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard.php?page=user_management&error=Invalid user ID.");
    exit;
}

$user_id = (int)$_GET['id'];

if ($_SESSION['user_id'] === $user_id) {
    header("Location: dashboard.php?page=user_management&error=You cannot delete your own account.");
    exit;
}

$getImage = $mysqli->prepare("SELECT image_url FROM users WHERE id = ?");
$getImage->bind_param("i", $user_id);
$getImage->execute();
$getImage->bind_result($image_url);
$getImage->fetch();
$getImage->close();

if ($image_url && $image_url !== 'uploads/assets/default-user.png' && file_exists($image_url)) {
    unlink($image_url);
}

$deleteContacts = $mysqli->prepare("DELETE FROM user_contacts WHERE user_id = ?");
$deleteContacts->bind_param("i", $user_id);
$deleteContacts->execute();
$deleteContacts->close();

$deleteProfile = $mysqli->prepare("DELETE FROM user_profiles WHERE user_id = ?");
$deleteProfile->bind_param("i", $user_id);
$deleteProfile->execute();
$deleteProfile->close();

$deleteUser = $mysqli->prepare("DELETE FROM users WHERE id = ?");
$deleteUser->bind_param("i", $user_id);
$deleteUser->execute();

if ($deleteUser->affected_rows > 0) {
    $deleteUser->close();
    header("Location: dashboard.php?page=user_management&error=User deleted successfully");
    exit;
} else {
    $deleteUser->close();
    header("Location: dashboard.php?page=user_management&error=Failed to delete user or user does not exist.");
    exit;
}
