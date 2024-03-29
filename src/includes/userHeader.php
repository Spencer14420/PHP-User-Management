<?php
//Include on page to add "Logged in as [User] | Log Out" etc.
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/pages.php";
if ($currentUser->username !== false) {
    echo "Logged in as {$currentUser->username}";

    if ($currentUser->hasPerm("view")) {
        //Link to pages
        foreach ($pages as $page) {
            if ($page->headerLink) {
                echo " | <a href='index.php?page=$page->name'>$page->headerLink</a>";
            }
        }
        if ($currentUser->hasPerm("renameusers") or $currentUser->hasPerm("deleteusers") or $currentUser->hasPerm("groupusers")) {
            echo " | <a href='index.php?action=userlist'>Edit Users</a>";
        }
    }
    //Show "Create Account" link if createaccount permission is restricted to specific groups
    if (!in_array("createaccount", $perms["all"]) and $currentUser->hasPerm("createaccount")) {
        echo " | <a href='index.php?action=createaccount'>Create Account</a>";
    }
    //Requested accounts
    if ($currentUser->hasPerm("requestaccount-review")) {
        echo " | <a href='index.php?action=reviewrequested'>Requested Accounts</a>";
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
