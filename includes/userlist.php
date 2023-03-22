<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/mysql.php";
include_once __DIR__ . "/userHeader.php";

//Check for "view" permission
require_once __DIR__ . "/pageCheck.php";

$query = $mysqli->prepare("SELECT username FROM users");
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {

    $user = new User($row['username']);
    $deleteAction = ($user->deleted) ? "undelete" : "delete";

    echo "<b>{$user->formattedUsername}</b>";
    if ($currentUser->hasPerm("renameusers")) {
        echo " <a href=index.php?action=rename&user=$user->username>(Rename)</a>";
    }
    if ($currentUser->hasPerm("{$deleteAction}users")) {
        echo " <a href=index.php?action=$deleteAction&user=$user->username>(" . ucfirst($deleteAction) . ")</a>";
    }
    if ($currentUser->hasPerm("groupusers")) {
        echo " <a href=index.php?action=editgroups&user=$user->username>(Edit groups)</a>";
    }
    echo "<br><br>";
}
