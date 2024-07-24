<?php
session_start();
include 'config.php';

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$passwordErr = $new_passwordErr = $confirm_passwordErr = "";
$password = $new_password = $confirm_password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["password"])) {
        $passwordErr = "Current password is required";
    } else {
        $password = test_input($_POST["password"]);
    }

    if (empty($_POST["new_password"])) {
        $new_passwordErr = "New password is required";
    } else {
        $new_password = test_input($_POST["new_password"]);
    }

    if (empty($_POST["confirm_password"])) {
        $confirm_passwordErr = "Confirm password is required";
    } else {
        $confirm_password = test_input($_POST["confirm_password"]);
    }

    if ($new_password !== $confirm_password) {
        $confirm_passwordErr = "Passwords do not match";
    }

    if (empty($passwordErr) && empty($new_passwordErr) && empty($confirm_passwordErr)) {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION["id"]);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $new_password_hash, $_SESSION["id"]);
            if ($stmt->execute()) {
                echo "Password updated successfully.";
            } else {
                echo "Error updating password.";
            }
        } else {
            $passwordErr = "Incorrect current password.";
        }
    }
}

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Change Password</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Current Password: <input type="password" name="password">
        <span class="error"><?php echo $passwordErr;?></span>
        <br><br>
        New Password: <input type="password" name="new_password">
        <span class="error"><?php echo $new_passwordErr;?></span>
        <br><br>
        Confirm New Password: <input type="password" name="confirm_password">
        <span class="error"><?php echo $confirm_passwordErr;?></span>
        <br><br>
        <input type="submit" name="submit" value="Change Password">
    </form>
</body>
</html>
