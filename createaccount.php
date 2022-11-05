<?php
require_once "mysql.php";
require_once "auth.php";

$hashpass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

//Check if user has "createaccount" perm
if (!$currentUser->hasPerm("createaccount")) {
    echo "Sorry, you cannot create an account<br><br>",
    "<a href='index.php'>Go back</a>";
    exit();
}

//Check if username already exists
$query = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
$query->bind_param("s", $_POST['username']);
$query->execute();
$result = $query->get_result();
if (mysqli_num_rows($result) > 0) {
    echo "That username already exists!<br><br>",
    "<a href='index.php?action=createaccount'>Try again</a>";
    exit();
}

$query = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$query->bind_param("ss", $_POST['username'], $hashpass);
$query->execute();
echo "Your account has been created!<br><br>",
"<a href='index.php'>Log in</a>"
?>