<?php
require_once __DIR__ . "/../mysql.php";

$input = $_POST['demoInput'];

$query = $mysqli->prepare("INSERT INTO demo_form (input) values (?)");
$query->bind_param("s", $input);
$query->execute();

echo "Your answer was submitted. Thank you.";
