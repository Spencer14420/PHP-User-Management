<?php
require_once __DIR__ . "/../standardReq.php";

//Check if user has "requestaccount" perm
if (!$currentUser->hasPerm("requestaccount")) {
    echo $GLOBALS['errorNoRequestaccountPerm'];
    exit();
}

//Sanitize requested email and username
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$username = htmlspecialchars($_POST['name']);

//Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo $GLOBALS['errorReqAccountInvalidEmail'];
    exit();
}

//Remove spaces from requested username
$username = str_replace(' ', '', $username);

//Check if email already exists
$query = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();
if (mysqli_num_rows($result) > 0) {
    echo $GLOBALS['errorReqAccountEmailExists'];
    exit();
}

//Add request to the req_accounts table
$query = $mysqli->prepare("INSERT INTO req_accounts (email, name) VALUES (?, ?)");
$query->bind_param("ss", $email, $username);
$query->execute();
echo $GLOBALS['reqAccountSuccess'];

//Send email notification
$headers = "From: {$sysEmail}";
$message = "Someone (probably you) has requested an account on $domain.\n\nYou will recieve an email if the request has been accepted.";
mail($email, "Your request has been recieved", $message, $headers);
