<?php
require_once __DIR__ . "/TokenForm.php";
require_once __DIR__ . "/User.php";

//Adds an $associatedUser property, consisting of a User object
//This is useful for when the form content needs to be
//based on data associated with the user
class UserObjForm extends TokenForm
{
    public $associatedUser;

    function __construct($username)
    {
        $this->associatedUser = new User($username);
    }
}
