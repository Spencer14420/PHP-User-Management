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
    require_once "auth.php";
    require_once "mysql.php";
    include_once "userHeader.php";
    session_start();
    $loggedInUser = $currentUser->username;

    //Require "renameusers", "deleteusers" or "groupusers
    if (!$currentUser->hasPerm("renameusers") OR !$currentUser->hasPerm("deleteusers") OR !$currentUser->hasPerm("groupusers")) {
        exit("You do not have permission to view this page");
    }

    //Process rename form
    if (isset($_POST['csrf']) AND isset($_POST['newname']) AND isset($_GET['action']) AND isset($_GET['user'])) {
        //Validate token
        if ($_POST['csrf'] === $_SESSION["rename" . $_GET['user']]) {
            //Rename user
            $query = $mysqli->prepare("UPDATE users SET username = ? WHERE username = ?");
            $query->bind_param("ss", $_POST['newname'], $_GET['user']);
            $query->execute();
            echo "<b>Success</b><br><br><b>{$_GET['user']}</b> has been renamed to <b>{$_POST['newname']}</b>";
            exit();
        }
    }

    if (!isset($_GET['action'])) {
        //List usernames and controls
        $query = $mysqli->prepare("SELECT username FROM users");
        $query->execute();
        $result = $query->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<b>". $row['username'] ."</b>";
            if ($currentUser->hasPerm("renameusers")) {
                echo " <a href=editusers.php?action=rename&user={$row['username']}>(Rename)</a>";
            }
            if ($currentUser->hasPerm("deleteusers")) {
                echo " <a href=editusers.php?action=delete&user={$row['username']}>(Delete)</a>";
            }
            if ($currentUser->hasPerm("groupusers")) {
                echo " <a href=editusers.php?action=editgroups&user={$row['username']}>(Edit groups)</a>";
            }
            echo "<br><br>";
        }
        exit();
    }

    //Generate CSRF token and store it in session
    $csrf = hash("sha256", random_bytes(256));

    //Unique $_SESSION variable name based on GET variables,
    //so multiple actions can be performed at the same time
    //without overwriting each others' tokens
    $_SESSION[$_GET['action'] . $_GET['user']] = $csrf;

    //Rename interface
    if ($_GET['action'] === "rename") {
        echo "Rename <b>{$_GET['user']}</b><br><br>";
        echo "<form action='editusers.php?action=rename&user={$_GET['user']}' method='POST'>";
        echo '<label for="newname">New username: </label>';
        echo '<input required type="text" id="newname" name="newname">';
        echo '<input type="hidden" id="csrf" name="csrf" value="'.$_SESSION[$_GET['action'] . $_GET['user']].'"><br>';
        echo '<input type="submit">';
        echo '</form>';
        
    }

    //Delete interface
    if ($_GET['action'] === "delete") {
        echo "Delete <b>{$_GET['user']}</b>";
    }

    //Groups interface
    if ($_GET['action'] === "editgroups") {
        echo "Add or remove <b>{$_GET['user']}</b> from groups";
    }
    ?>

</body>
</html>