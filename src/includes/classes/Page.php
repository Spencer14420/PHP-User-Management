<?php

require_once __DIR__ . "/Form.php";

class Page
{
    public $name;
    private $content = false;
    private $form = false;

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
        if ($this->content) {
            echo "$this->content<br><br>";
        }
        if ($this->form) {
            $this->form->echoForm();
        }
    }
}
