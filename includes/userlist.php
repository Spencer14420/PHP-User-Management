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

    //Hide the user if it is deleted,
    //and the viewer does not have the undeleteusers perm
    if ($user->deleted and !$currentUser->hasPerm("undeleteusers")) {
        continue;
    }

    echo "<b>{$user->formattedUsername}</b>";
    echo ($user->email) ? " ($user->sanitizedEmail)" : "";

    if ($currentUser->hasPerm("renameusers")) {
        echo " <a href=index.php?action=rename&user=$$user->sanitizedUsername>(Rename)</a>";
    }
    if ($currentUser->hasPerm("{$deleteAction}users")) {
        echo " <a href=index.php?action=$deleteAction&user=$$user->sanitizedUsername>(" . ucfirst($deleteAction) . ")</a>";
    }
    if ($currentUser->hasPerm("groupusers")) {
        echo " <a href=index.php?action=editgroups&user=$$user->sanitizedUsername>(Edit groups)</a>";
    }
    if ($currentUser->hasPerm("editemail")) {
        echo " <a href=index.php?action=editemail&user=$$user->sanitizedUsername>(Edit email)</a>";
    }
    echo "<br><br>";
}
