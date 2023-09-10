<?php
require_once __DIR__ . "/../standardReq.php";
require_once __DIR__ . "/../forms.php";
require_once __DIR__ . "/../classes/User.php";

$user = new User($_POST["email"], true);

//Verify email
if (isset($_GET['verify'])) {
    if ($_GET['verify'] == 1) {
        //Generate random code
        $chars = "0123456789";
        $code = substr(str_shuffle($chars), 0, 5);

        //Enter code form
        $entercodeForm->setAction("index.php?action=login");
        $entercodeForm->addHiddenInput("email", $_POST["email"]);
        $entercodeForm->echoForm();

        //Don't modify the database or email the user
        //if the accound doesn't exist or is deleted
        if (!$user->exists or $user->deleted) {
            exit();
        }

        //Add code to database
        $hashcode = password_hash($code, PASSWORD_DEFAULT);
        $query = $mysqli->prepare("UPDATE users SET password = ? WHERE email = ?");
        $query->bind_param("ss", $hashcode, $_POST['email']);
        $query->execute();

        //Email code to user
        $headers = "From: {$sysEmail}";
        $message = "Your code: {$code}";
        mail($_POST['email'], "Your code for {$domain}", $message, $headers);
    }
} else {
    $query = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param("s", $_POST['email']);
    $query->execute();
    $result = $query->get_result();
    $correctCode = $result->fetch_assoc()['password'];

    //Check if password/username are incorrect
    if (!password_verify($_POST['code'], $correctCode)) {
        //If the code is wrong, reset the code and prompt the user to try signing in again
        $query = $mysqli->prepare("UPDATE users SET password = '0' WHERE email = ?");
        $query->bind_param("s", $_POST['email']);
        $query->execute();

        echo $GLOBALS['errorWrongCode'];
        exit();
    }

    //Generate token and store it as a cookie
    $token = hash("sha256", random_bytes(265));
    setcookie("sp-token", $token, 0, "/", $domain);

    //Store hashed token in database
    $hashtoken = hash("sha256", $token);
    $query = $mysqli->prepare("UPDATE users SET token = '{$hashtoken}' WHERE email = ?");
    $query->bind_param("s", $_POST['email']);
    $query->execute();

    //Delete code from database, so it can only be used once
    $query = $mysqli->prepare("UPDATE users SET password = '0' WHERE email = ?");
    $query->bind_param("s", $_POST['email']);
    $query->execute();

    //Redirect back to index
    header("Location: index.php");
    exit();
}
