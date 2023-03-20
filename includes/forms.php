<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/defaultPerms.php";

class Form
{
    private $inputs = [];
    private $action;
    private $hasPermission = true;
    private $permError = "";
    private $hiddenInputs = [];

    //If the input is a checkbox,
    //$input[3] (required) is used to set the "checked" parameter
    public function addInput($type, $name, $question, $required)
    {
        $this->inputs[] = [$type, $name, $question, $required];
    }


    public function setAction($action)
    {
        $this->action = $action;
    }

    //$perm argument should be $currentUser->hasPerm("permission")
    public function setPerm($perm)
    {
        $this->hasPermission = $perm;
    }

    public function setPermError($errMesssage)
    {
        $this->permError = $errMesssage;
    }

    public function addHiddenInput($name, $value)
    {
        $this->hiddenInputs[] = [$name, $value];
    }

    public function echoForm()
    {
        if ($this->hasPermission) {
            $method = 'POST';
            echo "<form id='form' action='$this->action' method='$method'>";

            //Inputs
            foreach ($this->inputs as $input) {
                //Form type is $input[0], name and id are $input[1],
                //question is $input[2], and required is $input[3]

                if ($input[0] === "checkbox" and $input[3] === true) {
                    $required = "checked";
                } else {
                    $required = $input[3] ? "required" : "";
                }

                $str = <<<INPUT
                    <label for='$input[1]'>$input[2]: </label>
                    <input $required type='$input[0]' id='$input[1]' name='$input[1]'><br><br>
                INPUT;
                echo $str;
            }

            //Hidden inputs
            foreach ($this->hiddenInputs as $hiddenInput) {
                echo "<input type='hidden' id='$hiddenInput[0]' name='$hiddenInput[0]' value='$hiddenInput[1]'>";
            }

            $str = <<<END
                <input type='submit'>
                </form>
                <script>
                    document.addEventListener("keydown", function(event) {
                        if (event.keyCode === 13) {
                            document.querySelector("#form").submit();
                        }
                    });
                </script>
            END;
            echo $str;
        } else {
            echo $this->permError;
        }
    }
}

//Adds a method to add a hidden CSRF token input
class TokenForm extends Form
{
    public function addCSRF($sessName)
    {
        session_start();
        $csrf = hash("sha256", random_bytes(256));
        $_SESSION[$sessName] = $csrf;
        $this->addHiddenInput("csrf", $csrf);
    }
}

//Allows the user to set a username associated with the form
//The username is then converted into a User object
class UserObjForm extends TokenForm
{
    public User $associatedUser;

    function __construct($username)
    {
        $this->associatedUser = new User;
        $this->associatedUser->setUsername($username);
        $this->associatedUser->setUserid($username);
        $this->associatedUser->setGroups();
    }
}

//Forms defined below

//Login form
$loginForm = new Form();
$loginForm->addInput("text", "username", "Username", true);
$loginForm->addInput("password", "pass", "Password", true);

//nopass-mode login form
$nopassloginForm = new Form();
$nopassloginForm->addInput("text", "email", "Email address", true);

//Enter code form
$entercodeForm = new Form();
$entercodeForm->addInput("text", "code", "Code", true);

//Create account form
$createaccountForm = new Form();
$createaccountForm->addInput("text", "username", "Username", true);
$createaccountForm->addInput("text", "email", "Email address", true);
$createaccountForm->addInput("password", "pass", "Password", true);
$createaccountForm->setPerm($currentUser->hasPerm("createaccount"));
$createaccountForm->setPermError("Sorry, you cannot create an account");

//Nopass-mode create account form
$nopasscreateaccountForm = new Form();
$nopasscreateaccountForm->addInput("text", "username", "Username", true);
$nopasscreateaccountForm->addInput("text", "email", "Email address", true);
$nopasscreateaccountForm->setPerm($currentUser->hasPerm("createaccount"));
$nopasscreateaccountForm->setPermError("Sorry, you cannot create an account");

//Request account form
$requestAccountForm = new Form();
$requestAccountForm->addInput("text", "email", "Email address", true);
$requestAccountForm->addInput("text", "name", "Your name", true);
$requestAccountForm->setPerm($currentUser->hasPerm("requestaccount"));
$requestAccountForm->setPermError("Sorry, you cannot request an account");

//Rename user form
$renameUserForm = new TokenForm();
$renameUserForm->addInput("text", "newname", "New username", true);
$renameUserForm->setPerm($currentUser->hasPerm("renameusers"));
$renameUserForm->setPermError("Sorry, you cannot rename users");

//Edit groups form, called by processGet.php
function renderEditGroupsForm($username)
{
    if (isset($_GET['action']) and isset($_GET['user'])) {
        $editGroupsForm = new UserObjForm($username);
        global $perms; //Defined in defaultPerms.php

        //Adds a checkbox for each group defined in the $perms array, other than "all"
        foreach ($perms as $group => $perm) {
            if ($group !== "all") {
                $editGroupsForm->addInput(
                    "checkbox",
                    $group,
                    $group,
                    $editGroupsForm->associatedUser->inGroup($group)
                );
            }
        }
        $editGroupsForm->setAction("index.php?action=editgroups&user={$_GET['user']}");
        //addCSRF parameter is the name of the session variable
        //Below sets a unique $_SESSION variable name based on GET variables,
        //so multiple actions can be performed at the same time
        //without overwriting each others' tokens
        $editGroupsForm->addCSRF($_GET['action'] . $_GET['user']);
        $editGroupsForm->echoForm();
    }
}
