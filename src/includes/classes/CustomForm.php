<?php

require_once __DIR__ . "/Form.php";

class CustomForm extends Form
{
    public $name;

    function __construct($name)
    {
        $this->name = $name;
        $this->setAction("index.php?action=cfsubmit&form=$this->name");
    }
}
