<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/defaultPerms.php"; //Needed by renderEditGroupsForm();
require_once __DIR__ . "/classes/Form.php";
require_once __DIR__ . "/classes/TokenForm.php";
require_once __DIR__ . "/classes/UserObjForm.php";

//Login form
$loginForm = new Form();
$loginForm->addInput("text", "username", "Username", true, "");
$loginForm->addInput("password", "pass", "Password", true, "");

//nopass-mode login form
$nopassloginForm = new Form();
$nopassloginForm->addInput("text", "email", "Email address", true, "");

//Enter code form
$entercodeForm = new Form();
$entercodeForm->addInput("text", "code", "Code", true, "");

//Create account form
$createaccountForm = new Form();
$createaccountForm->addInput("text", "username", "Username", true, "");
$createaccountForm->addInput("text", "email", "Email address", true, "");
$createaccountForm->addInput("password", "pass", "Password", true, "");
$createaccountForm->setPerm($currentUser->hasPerm("createaccount"));
$createaccountForm->setPermError("Sorry, you cannot create an account");

//Nopass-mode create account form
$nopasscreateaccountForm = new Form();
$nopasscreateaccountForm->addInput("text", "username", "Username", true, (isset($_GET["username"])) ? $_GET["username"] : "");
$nopasscreateaccountForm->addInput("text", "email", "Email address", true, (isset($_GET["email"])) ? $_GET["email"] : "");
$nopasscreateaccountForm->setPerm($currentUser->hasPerm("createaccount"));
$nopasscreateaccountForm->setPermError("Sorry, you cannot create an account");

//Request account form
$requestAccountForm = new Form();
$requestAccountForm->addInput("text", "email", "Email address", true, "");
$requestAccountForm->addInput("text", "name", "Your name", true, "");
$requestAccountForm->setPerm($currentUser->hasPerm("requestaccount"));
$requestAccountForm->setPermError("Sorry, you cannot request an account");

//Rename user form
$renameUserForm = new TokenForm();
$renameUserForm->addInput("text", "newname", "New username", true, "");
$renameUserForm->setPerm($currentUser->hasPerm("renameusers"));
$renameUserForm->setPermError("Sorry, you cannot rename users");

//Edit groups form, called by processGet.php
function renderEditGroupsForm($username)
{
    if (isset($_GET['action']) and isset($_GET['user'])) {
        $editGroupsForm = new UserObjForm($username);
        global $perms; //Defined in defaultPerms.php
        global $currentUser;

        //Adds a checkbox for each group defined in the $perms array, other than "all"
        foreach ($perms as $group => $perm) {
            if ($group !== "all") {
                $editGroupsForm->addInput(
                    "checkbox",
                    $group,
                    $group,
                    $editGroupsForm->associatedUser->inGroup($group),
                    ""
                );
            }
        }
        $editGroupsForm->setAction("index.php?action=editgroups&user={$_GET['user']}");
        //addCSRF parameter is the name of the session variable
        //Below sets a unique $_SESSION variable name based on GET variables,
        //so multiple actions can be performed at the same time
        //without overwriting each others' tokens
        $editGroupsForm->addCSRF($_GET['action'] . $_GET['user']);
        $editGroupsForm->setPerm($currentUser->hasPerm("groupusers"));
        $editGroupsForm->setPermError("Sorry, you cannot add or remove users from groups");
        $editGroupsForm->echoForm();
    }
}

//Delete user form
$deleteUserForm = new TokenForm();
$deleteUserForm->addInput("checkbox", "confirmed", "Check this box to confirm", false, "");
$deleteUserForm->setPerm($currentUser->hasPerm("deleteusers"));
$deleteUserForm->setPermError("Sorry, you cannot delete users");

//Undelete user form
$undeleteUserForm = new TokenForm();
$undeleteUserForm->addInput("checkbox", "confirmed", "Check this box to confirm", false, "");
$undeleteUserForm->setPerm($currentUser->hasPerm("undeleteusers"));
$undeleteUserForm->setPermError("Sorry, you cannot undelete users");
