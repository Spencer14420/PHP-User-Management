<?php

require_once __DIR__ . "/standardReq.php";
require_once __DIR__ . "/classes/Page.php";

$pages = [];

//Demo page
$pages[0] = new Page("demo");
$pages[0]->setContent("Hi {$currentUser->username}, this is a demo page");

$pages[1] = new Page("demo2");
$pages[1]->setContent("Hi {$currentUser->username}, this is another demo page");
