<?php
$servername = "localhost";
$username = "webuser";
$password = "your_password";
$dbname = "web_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
