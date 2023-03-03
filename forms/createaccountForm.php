<?php

require_once "auth.php";

if ($currentUser->hasPerm("createaccount")) {
    echo "<form id='form' action='createaccount.php' method='POST'>",
        "<label for='username'>Username: </label>",
        "<input required type='text' id='username' name='username'><br><br>",
        "<label for='email'>Email address: </label>",
        "<input required type='text' id='email' name='email'><br><br>",
        "<label for='pass'>Password: </label>",
        "<input required type='password' id='pass' name='pass'><br><br>",
        "<label for='pass2'>Confirm password: </label>",
        "<input required type='password' id='pass2' name='pass2'><br><br>",
        "</form>",
        "<button id='submitBtn'>Submit</button>";

        //Script to confirm if password and confirm password match
        echo '<script>';
        echo 'const valInput = () => {';
        echo 'if (document.querySelector("#pass").value === document.querySelector("#pass2").value) {';
        echo 'document.querySelector("#form").submit();';
        echo '} else {';
        echo 'alert("Passwords do not match");';
        echo '}';
        echo '';
        echo '};';
        echo '';
        echo 'document.querySelector("#submitBtn").addEventListener("click", valInput);';
        echo 'document.addEventListener("keydown", function(event) {';
        echo 'if (event.keyCode === 13) {';
        echo 'valInput();';
        echo '}';
        echo '});';
        echo '</script>';
} else {
    echo "Sorry, you cannot create an account";
    exit();
}



?>