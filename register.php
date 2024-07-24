<?php
include 'config.php';

$usernameErr = $emailErr = $passwordErr = "";
$username = $email = $password = $confirm_password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($_POST["username"]);
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    }

    if (empty($_POST["confirm_password"])) {
        $confirm_passwordErr = "Confirm password is required";
    } else {
        $confirm_password = test_input($_POST["confirm_password"]);
    }

    if ($password !== $confirm_password) {
        $passwordErr = "Passwords do not match";
    }

    if (empty($usernameErr) && empty($emailErr) && empty($passwordErr)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password_hash);

        if ($stmt->execute()) {
            header("Location: login.php");
        } else {
            echo "Error: " . $stmt->error;
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
    <title>Register</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Register</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Username: <input type="text" name="username" value="<?php echo $username;?>">
        <span class="error"><?php echo $usernameErr;?></span>
        <br><br>
        Email: <input type="text" name="email" value="<?php echo $email;?>">
        <span class="error"><?php echo $emailErr;?></span>
        <br><br>
        Password: <input type="password" name="password">
        <span class="error"><?php echo $passwordErr;?></span>
        <br><br>
        Confirm Password: <input type="password" name="confirm_password">
        <br><br>
        <input type="submit" name="submit" value="Register">
    </form>
</body>
</html>
