<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';
$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT username, profile_picture, resume FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $profile_picture, $resume);
$stmt->fetch();
$stmt->close();

// Handle password change
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($current_password, $hashed_password)) {
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_hashed_password, $user_id);
        if ($stmt->execute()) {
            $success = "Password changed successfully.";
        } else {
            $error = "Error: " . $stmt->
