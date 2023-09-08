<?php
require_once __DIR__ . "/../standardReq.php";
require_once __DIR__ . "/../classes/User.php";

//Check if username does not exist or is deleted
$user = new User($_POST['username']);
if (!$user->exists or $user->deleted) {
    echo "Username or password are incorrect!<br><br>";
    exit("<a href='index.php?action=login'>Try again</a>");
}

$correctPass = $result->fetch_assoc()['password'];

//Check if password/username are incorrect
if (!password_verify($_POST['pass'], $correctPass)) {
    echo "Username or password are incorrect!<br><br>";
    exit("<a href='index.php?action=login'>Try again</a>");
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
