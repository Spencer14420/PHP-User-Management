<?php

require_once __DIR__ . "/../standardReq.php";
require_once __DIR__ . "/../classes/EditableUser.php";

if (!isset($_POST['confirmed'])) {
    echo $GLOBALS['errorConfirmBoxUnchecked'];
    exit();
}

//Create user object for user being edited
$selectedUser = new EditableUser($_GET['user']);

//Delete user
$selectedUser->undeleteUser();
