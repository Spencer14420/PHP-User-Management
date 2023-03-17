<?php

require_once __DIR__ . "/auth.php";

class Form
{
    private $inputs = [];
    private $get = false;
    private $action;
    private $hasPermission = true;
    private $permError = "";
    private $hiddenInputs = [];

    public function addInput($type, $name, $question, $required)
    {
        $this->inputs[] = [$type, $name, $question, $required];
    }

    public function setToGet()
    {
        $this->get = true;
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
            $method = $this->get ? 'GET' : 'POST';
            echo "<form id='form' action='$this->action' method='$method'>";

            //Inputs
            foreach ($this->inputs as $input) {
                //Form type is $input[0], name and id are $input[1],
                //question is $input[2], and required is $input[3]
                $required = $input[3] ? "required" : "";
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
