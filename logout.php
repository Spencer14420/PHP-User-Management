<?php
require_once "systemSettings.php";
setcookie('sp-token', '', 1, "/", $domain);
header("Location: index.php");
