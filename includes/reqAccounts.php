<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/mysql.php";
include_once __DIR__ . "/userHeader.php";

//Check for "view" permission
require_once __DIR__ . "/pageCheck.php";

//Check for "requestaccount-review" permission
if (!$currentUser->hasPerm("requestaccount-review")) {
    exit("You do not have permission to view this page");
}

$query = $mysqli->prepare("SELECT * FROM req_accounts");
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<b>{$row['name']}</b> ({$row['email']})";
    echo " (<a href='index.php?action=createaccount&username={$row['name']}&email={$row['email']}'>Create account</a>) (<a>Decline</a>)";
}
