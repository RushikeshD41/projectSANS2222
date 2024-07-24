<?php
include 'config.php';
session_start();

$usernameErr = $passwordErr = "";
$username = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($_POST["username"]);
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    }

    if (empty($usernameErr) && empty($passwordErr)) {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        if ($stmt->num_rows == 1 && password_verify($password, $hashed_password)) {
            $_SESSION["id"] = $id;
            $_SESSION["username"] = $username;
            header("Location: index.php");
        } else {
            $passwordErr = "Invalid username or password";
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
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Username: <input type="text" name="username" value="<?php echo $username;?>">
        <span class="error"><?php echo $usernameErr;?></span>
        <br><br>
        Password: <input type="password" name="password">
        <span class="error"><?php echo $passwordErr;?></span>
        <br><br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>
