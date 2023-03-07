<?php
require_once "mysql.php";
require_once "systemSettings.php";

//Check if username exists
$query = $mysqli->prepare("SELECT password FROM users WHERE username = ?");
$query->bind_param("s", $_POST['username']);
$query->execute();
$result = $query->get_result();
if (mysqli_num_rows($result) === 0) {
    exit("Username or password are incorrect!");
}

$correctPass = $result->fetch_assoc()['password'];

//Check if password/username are incorrect
if (!password_verify($_POST['pass'], $correctPass)) {
    exit("Username or password are incorrect!");
}

//Generate token and store it as a cookie
$token = hash("sha256", random_bytes(265));
setcookie("sp-token", $token, 0, "/", $domain);

//Store hashed token in database
$hashtoken = hash("sha256", $token);
$query = $mysqli->prepare("UPDATE users SET token = '{$hashtoken}' WHERE username = ?");
$query->bind_param("s", $_POST['username']);
$query->execute();

//Redirect back to index
header("Location: index.php");
exit();
