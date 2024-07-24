<?php
session_start();
require 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $mobile_number = $_POST['mobile_number'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, mobile_number, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $mobile_number, $hashed_password);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="register.php">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required><br>
            <label for="mobile_number">Mobile Number:</label>
            <input type="text" name="mobile_number" id="mobile_number" required><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required><br>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
