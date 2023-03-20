<?php

require_once __DIR__ . "/../standardReq.php";

//Create user object for user being edited
$selectedUser = new EditableUser($_GET['user']);

//Rename user
$selectedUser->renameUser($_POST['newname']);
