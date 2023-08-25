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
        $user = new User($_GET['user']);
        if ($user->exists) {
            echo "Rename <b>$user->formattedUsername</b><br><br>";
            $renameUserForm->setAction("index.php?action=rename&user=$user->username");
            //addCSRF parameter is the name of the session variable
            //Below sets a unique $_SESSION variable name based on GET variables,
            //so multiple actions can be performed at the same time
            //without overwriting each others' tokens
            $renameUserForm->addCSRF($_GET['action'] . $user->username);
            $renameUserForm->echoForm();
        } else {
            echo "The user you have selected does not exist";
        }
        //Edit groups form
    } elseif (isset($_GET['user']) and $_GET['action'] === "editgroups") {
        $user = new User($_GET['user']);
        if ($user->exists) {
            echo "Add or remove <b>$user->formattedUsername</b> from groups<br><br>";
            //Defined in forms.php
            renderEditGroupsForm($user->username);
        } else {
            echo "The user you have selected does not exist";
        }
        //Delete user form
    } elseif (isset($_GET['user']) and $_GET['action'] === "delete") {
        $user = new User($_GET['user']);
        if ($user->exists) {
            echo "Delete <b>$user->formattedUsername</b><br><br>";
            $deleteUserForm->setAction("index.php?action=delete&user=$user->username");
            $deleteUserForm->addCSRF($_GET['action'] . $user->username);
            $deleteUserForm->echoForm();
        } else {
            echo "The user you have selected does not exist";
        }
        //Undelete user form
    } elseif (isset($_GET['user']) and $_GET['action'] === "undelete") {
        $user = new User($_GET['user']);
        if ($user->exists) {
            echo "Undelete <b>$user->formattedUsername</b><br><br>";
            $undeleteUserForm->setAction("index.php?action=undelete&user=$user->username");
            $undeleteUserForm->addCSRF($_GET['action'] . $user->username);
            $undeleteUserForm->echoForm();
        } else {
            echo "The user you have selected does not exist";
        }
    }
}
