<?php
//Include on page to add "Logged in as [User] | Log Out" etc.
require_once __DIR__ . "/auth.php";
if ($currentUser->username !== false) {
    echo "Logged in as {$currentUser->username}";
    if ($currentUser->hasPerm("renameusers") or $currentUser->hasPerm("deleteusers") or $currentUser->hasPerm("groupusers")) {
        echo " | <a href='index.php?action=userlist'>Edit Users</a>";
    } elseif ($currentUser->hasPerm("view")) {
        echo " | <a href='index.php?action=userlist'>User List</a>";
    }
    //Show "Create Account" link if createaccount permission is restricted to specific groups
    if (!in_array("createaccount", $perms["all"]) and $currentUser->hasPerm("createaccount")) {
        echo " | <a href='index.php?action=createaccount'>Create Account</a>";
    }
    echo " | <a href='logout.php'>Log out</a><br><br>";
} else {
    echo "<a href='index.php?action=login'>Log in</a>";
    if ($currentUser->hasPerm("createaccount")) {
        echo " | <a href='index.php?action=createaccount'>Create account</a>";
    } elseif ($currentUser->hasPerm("requestaccount")) {
        echo " | <a href='index.php?action=requestaccount'>Request account</a>";
    }
    echo "<br><br>";
}
