<?php
//This is included in index.php whenever the user makes a POST request
require_once __DIR__ . "/standardReq.php";

function validateCSRF($sessName, $csrf)
{
    session_start();
    if ($_SESSION[$sessName] === $csrf) {
        return true;
    } else {
        return false;
    }
}

if (isset($_GET['action'])) {
    //Createaccount form
    if ($_GET['action'] === "createaccount") {
        require_once __DIR__ . "/formProcessors/createaccount.php";
        //Login forms
    } elseif ($_GET['action'] === "login") {
        if ($nopassMode) {
            //Nopass login form
            require_once __DIR__ . "/formProcessors/nopassLogin.php";
        } else {
            //Regular login form
            require_once __DIR__ . "/formProcessors/login.php";
        }
        //Request account form
    } elseif ($_GET['action'] === "requestaccount") {
        require_once __DIR__ . "/formProcessors/requestaccount.php";
        //Rename user forms
    } elseif (isset($_GET['user']) and $_GET['action'] === "rename") {
        if (validateCSRF("rename" . $_GET['user'], $_POST['csrf'])) {
            require_once __DIR__ . "/formProcessors/renameUser.php";
        }
    }
}
