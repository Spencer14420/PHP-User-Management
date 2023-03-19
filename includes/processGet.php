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
    }
}
