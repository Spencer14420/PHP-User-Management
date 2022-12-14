<?php
require_once "auth.php";
require_once "mysql.php";
include_once "userHeader.php";

$query = $mysqli->prepare("SELECT username FROM users");
$query->execute();
$result = $query->get_result();
while ($row = $result->fetch_assoc()) {
    echo "<b>". $row['username'] ."</b>";
    if ($currentUser->hasPerm("renameusers")) {
        echo " <a href=editusers.php?action=rename&user={$row['username']}>(Rename)</a>";
    }
    if ($currentUser->hasPerm("deleteusers")) {
        echo " <a href=editusers.php?action=delete&user={$row['username']}>(Delete)</a>";
    }
    if ($currentUser->hasPerm("groupusers")) {
        echo " <a href=editusers.php?action=editgroups&user={$row['username']}>(Edit groups)</a>";
    }
    echo "<br><br>";
}
?>