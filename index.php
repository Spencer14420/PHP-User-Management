<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
</head>
<body>
    <script>let action;</script>

    <?php
    require_once "auth.php";
    require_once "mysql.php";
    include_once "userHeader.php";
    $loggedInUser = $currentUser->username;

    //Shown to logged out users
    if (!$loggedInUser) {
        $action = "login.php";

        if (isset($_GET['action'])) {
            if ($_GET['action'] === "createaccount") {
                if ($currentUser->hasPerm("createaccount")) {
                    echo "<form id='form' action='createaccount.php' method='POST'>";
                } else {
                    echo "Sorry, you cannot create an account";
                    exit();
                }
            }
        }
        else {
            if ($currentUser->hasPerm("createaccount")) {
            }
            echo "<form id='form' action='login.php' method='POST'>";
        }
        
        echo "<label for='username'>Username: </label>",
                "<input required type='text' id='username' name='username'><br><br>",
                "<label for='pass'>Password: </label>",
                "<input required type='password' id='pass' name='pass'><br><br>";

        if (isset($_GET['action']) and $currentUser->hasPerm("createaccount")) {
            if ($_GET['action'] === "createaccount") {
                $action = "createaccount.php";
                echo "<label for='pass2'>Confirm password: </label>",
                    "<input required type='password' id='pass2' name='pass2'><br><br>",
                    "<script>action = 'createaccount'</script>";
            }
        }
        echo "</form>",
        "<button id='submitBtn'>Submit</button>";

        //Script to confirm if password and confirm password match
        echo '<script>';
        echo 'const valInput = () => {';
        echo 'if (action === "createaccount") {';
        echo 'if (document.querySelector("#pass").value === document.querySelector("#pass2").value) {';
        echo 'document.querySelector("#form").submit();';
        echo '} else {';
        echo 'alert("Passwords do not match");';
        echo '}';
        echo '} else {';
        echo 'document.querySelector("#form").submit();';
        echo '}';
        echo '};';
        echo 'document.querySelector("#submitBtn").addEventListener("click", valInput);';
        echo 'document.addEventListener("keydown", function(event) {';
        echo 'if (event.keyCode === 13) {';
        echo 'valInput();';
        echo '}';
        echo '});';
        echo '</script>';
    }
    ?>
</body>
</html>