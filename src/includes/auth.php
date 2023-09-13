<?php
require_once __DIR__ . "/../config/mysql.php";
require_once __DIR__ . "/classes/User.php";

$currentUser = new User(false); //Default User object for when the user is logged out

//Checks if the user is logged in
//and then overwrites the default User object
function auth()
{
    global $mysqli;
    global $currentUser;

    //Check if token cookie is set
    if (!isset($_COOKIE['sp-token'])) {
        return;
    }

    //Check if token matches anything in the database
    $hashtoken = hash("sha256", $_COOKIE['sp-token']);
    $query = $mysqli->prepare("SELECT username FROM users WHERE token = ?");
    $query->bind_param("s", $hashtoken);
    $query->execute();
    $result = $query->get_result();
    if (mysqli_num_rows($result) === 0) {
        return;
    }

    $username = $result->fetch_assoc()['username'];
    $currentUser = new User($username);

    $GLOBALS['currentUser'] = $currentUser;

    if ($currentUser->deleted) {
        require_once __DIR__ . "/../logout.php";
    }
}

auth();
