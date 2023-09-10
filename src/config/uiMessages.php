<?php

//General Messages

$GLOBALS['errorConfirmBoxUnchecked'] = <<<CONTENT
    You must check the box to confirm
CONTENT;

$GLOBALS['errorInvalidEmail'] = <<<CONTENT
    Please enter a valid email address
CONTENT;

$GLOBALS['errorEmailExists'] = <<<CONTENT
    An account with that email address already exists!
CONTENT;

$GLOBALS['errorCannotView'] = <<<CONTENT
    Sorry, you do not have permission to view this page
CONTENT;

$GLOBALS['errorUserDoesNotExist'] = <<<CONTENT
    The user you have selected does not exist
CONTENT;

//Create Account Messages

$GLOBALS['errorNoCreataccountPerm'] = <<<CONTENT
    Sorry, you cannot create an account
CONTENT;

$GLOBALS['errorCreateAccountInvalidEmail'] = <<<CONTENT
    {$GLOBALS['errorInvalidEmail']}<br><br>
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

$GLOBALS['errorCreateAccountEmailExists'] = <<<CONTENT
    {$GLOBALS['errorEmailExists']}<br><br>
    <a href='index.php?action=createaccount'>Try again</a>
CONTENT;

$GLOBALS['createAccountSuccess'] = <<<CONTENT
    Your account has been created!<br><br>
    <a href='index.php'>Log in</a>
CONTENT;

//Login Messages

$GLOBALS['errorWrongUserPass'] = <<<CONTENT
    Username or password are incorrect!<br><br>
    <a href='index.php?action=login'>Try again</a>
CONTENT;

$GLOBALS['errorWrongCode'] = <<<CONTENT
    The code you entered is incorrect<br><br>
    <a href='index.php?action=login'>Try again</a>
CONTENT;

//Request Account Messages

$GLOBALS['errorNoRequestaccountPerm'] = <<<CONTENT
    Sorry, you cannot request an account
CONTENT;

$GLOBALS['errorReqAccountInvalidEmail'] = <<<CONTENT
    {$GLOBALS['errorInvalidEmail']}<br><br>
    <a href='index.php?action=requestaccount'>Try again</a>
CONTENT;

$GLOBALS['errorReqAccountEmailExists'] = <<<CONTENT
    {$GLOBALS['errorInvalidEmail']}<br><br>
    <a href='index.php?action=requestaccount'>Try again</a>
CONTENT;

$GLOBALS['reqAccountSuccess'] = <<<CONTENT
    Your request has been filed. You will recieve an email if your request is accepted
CONTENT;


//Rename Messages

$GLOBALS['errorNoRenameusersPerm'] = <<<CONTENT
    Sorry, you cannot rename users
CONTENT;

//Edit Email Messages

$GLOBALS['errorNoEditemailPerm'] = <<<CONTENT
    Sorry, you cannot edit a user's email address
CONTENT;

//Edit Groups Messages

$GLOBALS['errorNoGroupusersPerm'] = <<<CONTENT
    Sorry, you cannot add or remove users from groups
CONTENT;

//Delete Users Messages

$GLOBALS['errorNoDeleteusersPerm'] = <<<CONTENT
    Sorry, you cannot delete users
CONTENT;

//Undelete Users Messages

$GLOBALS['errorNoUndeleteusersPerm'] = <<<CONTENT
    Sorry, you cannot undelete users
CONTENT;

//Review Requests Messages

$GLOBALS['errorNoRequestaccount-reviewPerm'] = <<<CONTENT
    Sorry, you cannot review account requests
CONTENT;

$GLOBALS['errorNoRequestExists'] = <<<CONTENT
    An account with the username you have selected has not been requested
CONTENT;
