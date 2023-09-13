<?php

$mysqli = new mysqli("localhost", "root", "root", "phpuser");

$GLOBALS['mysqli'] = $mysqli;

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
