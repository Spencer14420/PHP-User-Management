<?php

require_once __DIR__ . "/../standardReq.php";
require_once __DIR__ . "/../classes/EditableUser.php";

if (!isset($_POST['confirmed'])) {
    exit("You must check the box to confirm");
}

//Create user object for user being edited
$selectedUser = new EditableUser($_GET['user']);

//Delete user
$selectedUser->undeleteUser();
