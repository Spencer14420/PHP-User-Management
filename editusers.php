<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Users</title>
</head>

<body>
    <?php
    require_once __DIR__ . "/includes/standardReq.php";

    session_start();
    $loggedInUser = $currentUser->username;

    //---Form scripts--->

    //Rename
    if (isset($_POST['csrf']) and isset($_GET['action']) and $_GET['action'] == "rename") {
        //Validate token
        if ($_POST['csrf'] === $_SESSION["rename" . $_GET['user']]) {
            //Create user object for user being edited
            $selectedUser = new User();
            $selectedUser->setUsername($_GET['user']);

            //Rename user
            $selectedUser->renameUser($_POST['newname']);
        }
    }

    //Groups
    if (isset($_POST['csrf']) and isset($_GET['action']) and $_GET['action'] == "editgroups") {
        //Validate token
        if ($_POST['csrf'] === $_SESSION["editgroups" . $_GET['user']]) {
            require_once __DIR__ . "/config/defaultPerms.php";

            //Create user object for user being edited
            $selectedUser = new User();
            $selectedUser->setUsername($_GET['user']);
            $selectedUser->setUserid($selectedUser->username);

            //Create array of selected groups
            $selectedGroups = [];
            foreach ($perms as $key => $perm) {
                if ($key !== "all" and $_POST[$key] == true) {
                    $selectedGroups[] = $key;
                }
            }

            //Change groups
            $selectedUser->changeGroups($selectedGroups);
        }
    }

    //<--- ---

    if (!isset($_GET['action'])) {
        //List usernames and controls
        include_once __DIR__ . "/includes/userlist.php";
        exit();
    }

    //Generate CSRF token and store it in session
    $csrf = hash("sha256", random_bytes(256));

    //Unique $_SESSION variable name based on GET variables,
    //so multiple actions can be performed at the same time
    //without overwriting each others' tokens
    $_SESSION[$_GET['action'] . $_GET['user']] = $csrf;

    //---Edit interfaces-->

    //Rename
    if ($_GET['action'] === "rename") {
        echo "Rename <b>{$_GET['user']}</b><br><br>";
        echo "<form action='editusers.php?action=rename&user={$_GET['user']}' method='POST'>";
        echo '<label for="newname">New username: </label>';
        echo '<input required type="text" id="newname" name="newname">';
        echo '<input type="hidden" id="csrf" name="csrf" value="' . $_SESSION[$_GET['action'] . $_GET['user']] . '"><br>';
        echo '<input type="submit">';
        echo '</form>';
    }

    //Delete
    if ($_GET['action'] === "delete") {
        echo "Delete <b>{$_GET['user']}</b>";
    }

    //Groups
    if ($_GET['action'] === "editgroups") {
        require_once __DIR__ . "/config/defaultPerms.php";

        //Create user object for user being edited
        $selectedUser = new User();
        $selectedUser->setUserid($_GET['user']);
        $selectedUser->setGroups();


        echo "Add or remove <b>{$_GET['user']}</b> from groups<br><br>";
        echo "<form action='editusers.php?action=editgroups&user={$_GET['user']}' method='POST'>";

        //List group checkboxes
        foreach ($perms as $key => $perm) {
            if ($key !== "all") {
                echo "<input type='checkbox' id='{$key}' name='{$key}' value='true' ";
                //Check the box if user is already in group
                if ($selectedUser->inGroup($key)) {
                    echo "checked";
                }
                echo "><label for='{$key}'>{$key}</label><br>";
            }
        }
        echo "<input type='hidden' id='csrf' name='csrf' value='{$_SESSION[$_GET['action'] .$_GET['user']]}'>";
        echo "<input type='submit'>";
        echo "</form>";
    }

    //<--- ---
    ?>

</body>

</html>