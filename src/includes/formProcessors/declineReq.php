<?php

require_once __DIR__ . "/../standardReq.php";
require_once __DIR__ . "/../classes/RequestedAccount.php";

if (!isset($_POST['confirmed'])) {
    echo $GLOBALS['confirmBoxUnchecked'];
    exit();
}

//Create RequestedAccount object for request being declined
$selectedRequest = new RequestedAccount($_GET['user']);

//Delete request
$selectedRequest->deleteReq();
