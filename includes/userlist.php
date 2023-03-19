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
    echo "<b>" . $row['username'] . "</b>";
    if ($currentUser->hasPerm("renameusers")) {
        echo " <a href=index.php?action=rename&user={$row['username']}>(Rename)</a>";
    }
    if ($currentUser->hasPerm("deleteusers")) {
        echo " <a href=editusers.php?action=delete&user={$row['username']}>(Delete)</a>";
    }
    if ($currentUser->hasPerm("groupusers")) {
        echo " <a href=editusers.php?action=editgroups&user={$row['username']}>(Edit groups)</a>";
    }
    echo "<br><br>";
}
