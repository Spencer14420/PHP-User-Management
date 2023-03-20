<?php

require_once __DIR__ . "/../standardReq.php";
require_once __DIR__ . "/../../config/defaultPerms.php";

//Create user object for user being edited
$selectedUser = new User();
$selectedUser->setUsername($_GET['user']);
$selectedUser->setUserid($selectedUser->username);

//Create array of selected groups
$selectedGroups = [];
foreach ($perms as $key => $perm) {
    if ($key !== "all" and $_POST[$key] == true) {
        $selectedGroups[] = $key;
    }
}

//Change groups
$selectedUser->changeGroups($selectedGroups);
