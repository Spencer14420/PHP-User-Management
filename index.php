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
    require_once __DIR__."/includes/auth.php";
    require_once __DIR__."/config/mysql.php";
    require_once __DIR__."/config/systemSettings.php";
    include_once __DIR__."/includes/userHeader.php";
    include_once __DIR__."/includes/forms.php";

    //Createaccount form
    if (isset($_GET['action'])) {
        if ($_GET['action'] === "createaccount") {
            if ($nopassMode) {
                $nopasscreateaccountForm->setAction("createaccount.php");
                $nopasscreateaccountForm->echoForm();
            } else {
                $createaccountForm->setAction("createaccount.php");
                $createaccountForm->echoForm();
            }
        } elseif ($_GET['action'] === "login") {
            if ($nopassMode) {
                //Nopass login form
                $nopassloginForm->setAction("nopassLogin.php?verify=1");
                $nopassloginForm->echoForm();
            } else {
                //Regular login form
                $loginForm->setAction("login.php");
                $loginForm->echoForm();
            }
        }
    }

    ?>
</body>
</html>