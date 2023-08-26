<?php

require_once __DIR__ . "/../standardReq.php";
require_once __DIR__ . "/../classes/EditableUser.php";

if (!filter_var($_POST['newEmail'], FILTER_VALIDATE_EMAIL)) {
    exit("Please enter a valid email address");
}

//Create user object for user being edited
$selectedUser = new EditableUser($_GET['user']);

//Rename user
$selectedUser->editEmail($_POST['newEmail']);
