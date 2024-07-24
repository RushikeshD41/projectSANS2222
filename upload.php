<?php
session_start();
include 'config.php';

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["resume"])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["resume"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
        echo "Sorry, only PDF, DOC, and DOCX files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk && move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("UPDATE users SET resume = ? WHERE id = ?");
        $stmt->bind_param("si", $target_file, $_SESSION["id"]);
        if ($stmt->execute()) {
            echo "The file " . htmlspecialchars(basename($_FILES["resume"]["name"])) . " has been uploaded.";
        } else {
            echo "Error updating database.";
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Resume</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Upload Resume</h2>
    <form method="post" enctype="multipart/form-data">
        Select resume to upload:
        <input type="file" name="resume">
        <input type="submit" value="Upload Resume" name="submit">
    </form>
</body>
</html>
