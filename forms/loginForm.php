<?php

echo "<form id='form' action='login.php' method='POST'>",
    "<label for='username'>Username: </label>",
    "<input required type='text' id='username' name='username'><br><br>",
    "<label for='pass'>Password: </label>",
    "<input required type='password' id='pass' name='pass'><br><br>",
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