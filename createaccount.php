<?php
require_once __DIR__."/config/mysql.php";
require_once __DIR__."/includes/auth.php";
require_once __DIR__."/config/systemSettings.php";

if (!$nopassMode) {
    $hashpass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
}

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

//Check if email already exists
$query = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
$query->bind_param("s", $_POST['email']);
$query->execute();
$result = $query->get_result();
if (mysqli_num_rows($result) > 0) {
    echo "An account with that email address already exists!<br><br>",
    "<a href='index.php?action=createaccount'>Try again</a>";
    exit();
}

if (!$nopassMode) {
    $query = $mysqli->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $query->bind_param("sss", $_POST['username'], $hashpass, $_POST['email']);
} else {
    $query = $mysqli->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
    $query->bind_param("ss", $_POST['username'], $_POST['email']);
}
$query->execute();
echo "Your account has been created!<br><br>",
"<a href='index.php'>Log in</a>"
?>