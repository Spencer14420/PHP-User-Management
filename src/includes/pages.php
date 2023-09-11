<?php

require_once __DIR__ . "/../config/additionalPages.php";

//Demo page
$pages["demo"] = new Page("demo");
$pages["demo"]->setContent("Hi {$currentUser->username}, this is a demo page");

$pages["demo2"] = new Page("demo2");
$pages["demo2"]->setContent("Hi {$currentUser->username}, this is another demo page");
