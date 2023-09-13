<?php

require_once __DIR__ . "/../includes/classes/Page.php";
require_once __DIR__ . "/additionalForms.php";

$pages = [];

$pages["example"] = new Page("example");
$pages["example"]->setContent("This is an example page");
$pages["example"]->setForm($demoForm);
$pages["example"]->addInclude(__DIR__ . "/exampleInclude.php");
