<?php
    //Include on page to add "Logged in as [User] | Log Out" etc.
    require_once "auth.php";
    if ($currentUser->username !== false) {
        echo "Logged in as {$currentUser->username}";
        if ($currentUser->hasPerm("renameusers") OR $currentUser->hasPerm("deleteusers") OR $currentUser->hasPerm("groupusers")) {
            echo " | <a href='editusers.php'>Edit Users</a>";
        }
        echo " | <a href='logout.php'>Log out</a><br><br>";
    } else {
        echo "<a href='index.php'>Log in</a>";
        if ($currentUser->hasPerm("createaccount")) {
            echo " | <a href='index.php?action=createaccount'>Create account</a>";
        }
        echo "<br><br>";
    }
?>
