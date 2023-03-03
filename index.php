<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
</head>
<body>
    <?php
    require_once "auth.php";
    require_once "mysql.php";
    require_once "systemSettings.php";
    include_once "userHeader.php";
    $loggedInUser = $currentUser->username;

    //Shown to logged out users
    if (!$loggedInUser) {

        if (isset($_GET['action'])) {
            if ($_GET['action'] === "createaccount") {
                include_once "forms/createaccountForm.php";
                exit();
            }
        }
        include_once "forms/loginForm.php";
    }
    ?>
</body>
</html>