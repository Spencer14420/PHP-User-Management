<?php

//Create Account Messages

$GLOBALS['errorCreateAccountInvalidEmail'] = <<<CONTENT
    Please enter a valid email address<br><br>
    <a href='index.php?action=createaccount'>Try again</a>
CONTENT;


$GLOBALS['errorCreateAccountHasSpaces'] = <<<CONTENT
    Sorry, usernames cannot contain spaces<br>
    <a href='index.php?action=createaccount'>Go back</a>
CONTENT;

$GLOBALS['errorCreateAccountExists'] = <<<CONTENT
    That username already exists!<br><br>
    <a href='index.php?action=createaccount'>Try again</a>
CONTENT;
