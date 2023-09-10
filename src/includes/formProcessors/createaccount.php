<?php
require_once __DIR__ . "/../standardReq.php";
require_once __DIR__ . "/../classes/User.php";
require_once __DIR__ . "/../classes/RequestedAccount.php";

if (!$currentUser->hasPerm("createaccount")) {
    exit("Sorry, you cannot create an account");
}

//Sanitize email and username
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$username = htmlspecialchars($_POST['username']);

if (!$nopassMode) {
    //Hash entered password (only present if nopassMode is disabled)
    $hashpass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
} else {
    //Validate email (only present if nopassMode is enabled)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo $GLOBALS['errorCreateAccountInvalidEmail'];
        exit();
    }
}

//Check if username contains spaces
if (str_contains($username, " ")) {
    echo $GLOBALS['errorCreateAccountHasSpaces'];
    exit();
}

//Check if username already exists
$user = new User($username);
if ($user->exists) {
    echo $GLOBALS['errorCreateAccountExists'];
    exit();
}

//Check if email already exists
$user = new User($_POST["email"], true);
if ($user->exists) {
    echo "An account with that email address already exists!<br><br>",
    "<a href='index.php?action=createaccount'>Try again</a>";
    exit();
}

//Create Account
if (!$nopassMode) {
    $query = $mysqli->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $query->bind_param("sss", $username, $hashpass, $email);
} else {
    $query = $mysqli->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
    $query->bind_param("ss", $username, $email);
}
$query->execute();

//Check for any requested accounts with the same email, and remove them
$reqAccount = new RequestedAccount($email, true);
if ($reqAccount->exists) {
    $reqAccount->deleteReq();
}

//Success message when not logged in
if ($currentUser->username === false) {
    echo "Your account has been created!<br><br>",
    "<a href='index.php'>Log in</a>";
} else {
    //Success message when logged in
    echo "The account \"<b>{$username}</b>\" has been created";
}
