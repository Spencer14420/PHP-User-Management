<?php
//This is included in index.php whenever the user makes a GET request
require_once __DIR__ . "/standardReq.php";
require_once __DIR__ . "/forms.php";


if (isset($_GET['action'])) {
    //Createaccount form
    if ($_GET['action'] === "createaccount") {
        if ($nopassMode) {
            $nopasscreateaccountForm->setAction("index.php?action=createaccount");
            $nopasscreateaccountForm->echoForm();
        } else {
            $createaccountForm->setAction("index.php?action=createaccount");
            $createaccountForm->echoForm();
        }
        //Login forms
    } elseif ($_GET['action'] === "login") {
        if ($nopassMode) {
            //Nopass login form
            $nopassloginForm->setAction("index.php?action=login&verify=1");
            $nopassloginForm->echoForm();
        } else {
            //Regular login form
            $loginForm->setAction("index.php?action=login");
            $loginForm->echoForm();
        }
        //User list
    } elseif ($_GET['action'] === "userlist") {
        include_once __DIR__ . "/userlist.php";
        //Request account form
    } elseif ($_GET['action'] === "requestaccount") {
        $requestAccountForm->setAction("index.php?action=requestaccount");
        $requestAccountForm->echoForm();
        //Renameuser form
    } elseif (isset($_GET['user']) and $_GET['action'] === "rename") {
        echo "Rename <b>{$_GET['user']}</b><br><br>";
        $renameUserForm->setAction("index.php?action=rename&user={$_GET['user']}");
        //addCSRF parameter is the name of the session variable
        //Below sets a unique $_SESSION variable name based on GET variables,
        //so multiple actions can be performed at the same time
        //without overwriting each others' tokens
        $renameUserForm->addCSRF($_GET['action'] . $_GET['user']);
        $renameUserForm->echoForm();
        //Edit groups form
    } elseif (isset($_GET['user']) and $_GET['action'] === "editgroups") {
        echo "Add or remove <b>{$_GET['user']}</b> from groups<br><br>";
        //Defined in forms.php
        renderEditGroupsForm($_GET['user']);
        //Delete user form
    } elseif (isset($_GET['user']) and $_GET['action'] === "delete") {
        echo "Delete <b>{$_GET['user']}</b><br><br>";
        $deleteUserForm->setAction("index.php?action=delete&user={$_GET['user']}");
        $deleteUserForm->addCSRF($_GET['action'] . $_GET['user']);
        $deleteUserForm->echoForm();
    } elseif (isset($_GET['user']) and $_GET['action'] === "undelete") {
        echo "Undelete <b>{$_GET['user']}</b><br><br>";
        $undeleteUserForm->setAction("index.php?action=undelete&user={$_GET['user']}");
        $undeleteUserForm->addCSRF($_GET['action'] . $_GET['user']);
        $undeleteUserForm->echoForm();
    }
}
