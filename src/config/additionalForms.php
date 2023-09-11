<?php

require_once __DIR__ . "/../includes/classes/CustomForm.php";

$demoForm = new CustomForm("demoForm");
$demoForm->addInput("text", "demoInput", "Demo Input", true, "");
