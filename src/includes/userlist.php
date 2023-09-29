<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/mysql.php";
include_once __DIR__ . "/userHeader.php";

$query = $mysqli->prepare("SELECT username FROM users");
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {

    if (isset($_GET['user'])) {
        $user = new User($_GET['user']);
    } else {
        $user = new User($row['username']);
    }
    $deleteAction = ($user->deleted) ? "undelete" : "delete";

    //Hide the user if it is deleted,
    //and the viewer does not have the undeleteusers perm
    if ($user->deleted and !$currentUser->hasPerm("undeleteusers")) {
        continue;
    }

    echo "<b>{$user->formattedUsername}</b>";
    echo ($user->email) ? " ($user->sanitizedEmail)" : "";

    if ($currentUser->hasPerm("renameusers")) {
        echo " <a href=index.php?action=rename&user=$user->sanitizedUsername>(Rename)</a>";
    }
    if ($currentUser->hasPerm("{$deleteAction}users")) {
        echo " <a href=index.php?action=$deleteAction&user=$user->sanitizedUsername>(" . ucfirst($deleteAction) . ")</a>";
    }
    if ($currentUser->hasPerm("groupusers")) {
        echo " <a href=index.php?action=editgroups&user=$user->sanitizedUsername>(Edit groups)</a>";
    }
    if ($currentUser->hasPerm("editemail")) {
        echo " <a href=index.php?action=editemail&user=$user->sanitizedUsername>(Edit email)</a>";
    }
    echo "<br><br>";

    //Only run once if a user is specified in the URL
    if (isset($_GET['user'])) {
        break;
    }
}
