<?php
echo "<form id='form' action='nopassLogin.php' method='POST'>",
    "<label for='code'>Code: </label>",
    "<input required type='text' id='code' name='code'><br><br>",
    "<input type='hidden' id='email' name='email' value='{$_POST['email']}'>",
    "</form>",
    "<button id='submitBtn'>Submit</button>";

    echo '<script>';
    echo 'document.querySelector("#submitBtn").addEventListener("click", () => document.querySelector("#form").submit());';
    echo 'document.addEventListener("keydown", function(event) {';
    echo 'if (event.keyCode === 13) {';
    echo 'document.querySelector("#form").submit();';
    echo '}';
    echo '});';
    echo '</script>';
?>