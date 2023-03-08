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

    //Require "renameusers", "deleteusers" or "groupusers
    if (!$currentUser->hasPerm("renameusers") or !$currentUser->hasPerm("deleteusers") or !$currentUser->hasPerm("groupusers")) {
        exit("You do not have permission to view this page");
    }

    //---Form scripts--->

    //Rename
    if (isset($_POST['csrf']) and isset($_POST['newname']) and isset($_GET['action']) and isset($_GET['user'])) {
        //Validate token
        if ($_POST['csrf'] === $_SESSION["rename" . $_GET['user']]) {
            //Check if username is taken
            $query = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
            $query->bind_param("s", $_POST['newname']);
            $query->execute();
            $result = $query->get_result();
            if (mysqli_num_rows($result) > 0) {
                exit("<b>Error</b>: An account with the username \"<b>{$_POST['newname']}</b>\" already exists");
            }

            //Rename user
            if ($currentUser->hasPerm("renameusers")) {
                $query = $mysqli->prepare("UPDATE users SET username = ? WHERE username = ?");
                $query->bind_param("ss", $_POST['newname'], $_GET['user']);
                $query->execute();
                echo "<b>Success</b><br><br><b>{$_GET['user']}</b> has been renamed to <b>{$_POST['newname']}</b>";
                exit();
            }
        }
    }

    //Groups
    if (isset($_POST['csrf']) and isset($_GET['action']) and isset($_GET['user'])) {
        //Validate token
        if ($_POST['csrf'] === $_SESSION["editgroups" . $_GET['user']]) {
            exit();
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
        $selectedUser->setUserid($_GET['user'], $mysqli);
        $selectedUser->setGroups($mysqli);


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