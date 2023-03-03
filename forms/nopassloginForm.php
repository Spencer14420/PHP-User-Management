<?php

echo "<form id='form' action='nopassLogin.php?verify=1' method='POST'>",
    "<label for='email'>Email address: </label>",
    "<input required type='text' id='email' name='email'><br><br>",
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