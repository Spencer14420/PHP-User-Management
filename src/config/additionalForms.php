<?php

require_once __DIR__ . "/../includes/classes/Form.php";

$demoForm = new Form();
$demoForm->addInput("text", "demoInput", "Demo Input", true, "");
