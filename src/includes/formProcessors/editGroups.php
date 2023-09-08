<?php

require_once __DIR__ . "/../standardReq.php";
require_once __DIR__ . "/../../config/defaultPerms.php";
require_once __DIR__ . "/../classes/EditableUser.php";

//Create user object for user being edited
$selectedUser = new EditableUser($_GET['user']);

//Create array of selected groups
$selectedGroups = [];
foreach ($perms as $key => $perm) {
    if (($key !== "all" or $key !== "user") and $_POST[$key] == true) {
        $selectedGroups[] = $key;
    }
}

//Change groups
$selectedUser->changeGroups($selectedGroups);
