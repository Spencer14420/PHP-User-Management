<?php
require_once __DIR__ . "/Form.php";

//This class allows adds an addCSRF method,
//which adds a CSRF token to the form
//as a hidden input
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
