<?php
//This is included in index.php whenever the user makes a GET request
require_once __DIR__ . "/standardReq.php";
require_once __DIR__ . "/forms.php";
require_once __DIR__ . "/pages.php";

if (isset($_GET['page'])) {

    $selectedPage = htmlspecialchars($_GET['page']);

    //Check for view permission
    require_once __DIR__ . "/pageCheck.php";

    //Create an array of pages names
    $pageNames = array_map(
        function ($page) {
            return $page->name;
        },
        $pages
    );

    //Check if the page value is valid
    if (in_array($selectedPage, $pageNames)) {
        //Get the index of the specified page
        $pageId = array_search($selectedPage, $pageNames);

        //Try to echo page content
        try {
            $pages[$pageId]->echoContent();
        } catch (Exception $e) {
            //Display error message
            echo $GLOBALS['errorGeneric'];
        }
    } else {
        echo $GLOBALS['errorPageNotFound'];
    }
} elseif (isset($_GET['action'])) {

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
        //Request account form
    } elseif ($_GET['action'] === "requestaccount") {
        $requestAccountForm->setAction("index.php?action=requestaccount");
        $requestAccountForm->echoForm();
    } else {
        //Check for "view" permission (to protect the actions below)
        require_once __DIR__ . "/pageCheck.php";
    }

    //Actions that require the "view" permission are listed below 

    //User list
    if ($_GET['action'] === "userlist") {
        include_once __DIR__ . "/userlist.php";
        //Requested accounts panel
    } elseif ($_GET['action'] === "reviewrequested") {
        include_once __DIR__ . "/reqAccounts.php";
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
            echo $GLOBALS['errorUserDoesNotExist'];
        }
        //Renameuser form
    } elseif (isset($_GET['user']) and $_GET['action'] === "editemail") {
        $user = new User($_GET['user']);
        if ($user->exists) {
            echo "Edit <b>$user->formattedUsername's</b> email address<br><br>";
            echo "Current email address: <b>$user->sanitizedEmail</b><br><br>";
            $editEmailForm->setAction("index.php?action=editemail&user=$user->username");
            //addCSRF parameter is the name of the session variable
            //Below sets a unique $_SESSION variable name based on GET variables,
            //so multiple actions can be performed at the same time
            //without overwriting each others' tokens
            $editEmailForm->addCSRF($_GET['action'] . $user->username);
            $editEmailForm->echoForm();
        } else {
            echo $GLOBALS['errorUserDoesNotExist'];
        }
        //Edit groups form
    } elseif (isset($_GET['user']) and $_GET['action'] === "editgroups") {
        $user = new User($_GET['user']);
        if ($user->exists) {
            echo "Add or remove <b>$user->formattedUsername</b> from groups<br><br>";
            //Defined in forms.php
            renderEditGroupsForm($user->username);
        } else {
            echo $GLOBALS['errorUserDoesNotExist'];
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
            echo $GLOBALS['errorUserDoesNotExist'];
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
            echo $GLOBALS['errorUserDoesNotExist'];
        }
    } elseif (isset($_GET['user']) and $_GET['action'] === "declinereq") {
        include_once __DIR__ . "/classes/RequestedAccount.php";
        $request = new RequestedAccount($_GET['user']);
        if ($request->exists) {
            echo "Decline <b>$request->sanitizedUsername</b> ($request->sanitizedEmail)<br><br>";
            $declineReqAcc->setAction("index.php?action=declinereq&user=$request->username");
            $declineReqAcc->addCSRF($_GET['action'] . $request->username);
            $declineReqAcc->echoForm();
        } else {
            echo $GLOBALS['errorNoRequestExists'];
        }
    }
}
