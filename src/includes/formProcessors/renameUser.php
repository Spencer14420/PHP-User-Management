<?php

require_once __DIR__ . "/../standardReq.php";
require_once __DIR__ . "/../classes/EditableUser.php";

//Create user object for user being edited
$selectedUser = new EditableUser($_GET['user']);

//Rename user
$selectedUser->renameUser($_POST['newname']);
