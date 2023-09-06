<?php
require_once __DIR__ . "/config/systemSettings.php";
setcookie('sp-token', '', 1, "/", $domain, true, true);
header("Location: index.php");
