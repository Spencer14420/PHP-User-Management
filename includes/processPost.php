<?php
//This is included in index.php whenever the user makes a GET request
require_once __DIR__ . "/standardReq.php";

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
    }
}
