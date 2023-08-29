<?php
require_once __DIR__ . "/../standardReq.php";
require_once __DIR__ . "/../classes/User.php";
require_once __DIR__ . "/../classes/RequestedAccount.php";

if (!$currentUser->hasPerm("createaccount")) {
    exit("Sorry, you cannot create an account");
}

if (!$nopassMode) {
    //Hash entered password (only present if nopassMode is disabled)
    $hashpass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
} else {
    //Validate email (only present if nopassMode is enabled)
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        echo "Please enter a valid email address<br><br>",
        "<a href='index.php?action=createaccount'>Try again</a>";
        exit();
    }
}

//Check if user has "createaccount" perm
if (!$currentUser->hasPerm("createaccount")) {
    echo "Sorry, you cannot create an account<br><br>",
    "<a href='index.php'>Go back</a>";
    exit();
}

//Check if username already exists
$user = new User($_POST['username']);
if ($user->exists) {
    echo "That username already exists!<br><br>",
    "<a href='index.php?action=createaccount'>Try again</a>";
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
    $query->bind_param("sss", $_POST['username'], $hashpass, $_POST['email']);
} else {
    $query = $mysqli->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
    $query->bind_param("ss", $_POST['username'], $_POST['email']);
}
$query->execute();

//Check for any requested accounts with the same email, and remove them
$reqAccount = new RequestedAccount($_POST["email"], true);
if ($reqAccount->exists) {
    $reqAccount->deleteReq();
}

//Success message when not logged in
if ($currentUser->username === false) {
    echo "Your account has been created!<br><br>",
    "<a href='index.php'>Log in</a>";
} else {
    //Success message when logged in
    echo "The account \"<b>{$_POST['username']}</b>\" has been created";
}
