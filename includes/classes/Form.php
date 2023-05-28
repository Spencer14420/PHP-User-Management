<?php
require_once __DIR__ . "/../auth.php";
require_once __DIR__ . "/../../config/defaultPerms.php";

class Form
{
    private $inputs = [];
    private $action;
    private $hasPermission = true;
    private $permError = "";
    private $hiddenInputs = [];

    //If the input is a checkbox,
    //$input[3] (required) is used to set the "checked" parameter
    public function addInput($type, $name, $question, $required, $filled)
    {
        $this->inputs[] = [$type, $name, $question, $required, $filled];
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
                    <input $required type='$input[0]' id='$input[1]' name='$input[1]' value='$input[4]'><br><br>
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
