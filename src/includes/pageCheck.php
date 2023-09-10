<?php
//Require on page to only allow users with "view" permission
require_once __DIR__ . "/auth.php";
if (!$currentUser->hasPerm("view")) {
    echo $GLOBALS['errorCannotView'];
    exit();
}
