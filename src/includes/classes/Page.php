<?php

require_once __DIR__ . "/Form.php";

class Page
{
    public $name;
    private $content;
    private $form;

    function __construct($pageName)
    {
        $this->name = $pageName;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    public function echoContent()
    {
        //Echoes the content and then form
        echo "$this->content<br><br>";
        $this->form->echoForm();
    }
}
