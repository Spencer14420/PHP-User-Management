<?php
    //Require on page to only allow users with "view" permission
    require_once "auth.php";
    if (!$currentUser->hasPerm("view")) {
        exit("You do not have permission to view this page");
    }
?>