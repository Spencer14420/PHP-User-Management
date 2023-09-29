<?php

require_once __DIR__ . "/Form.php";

class Page
{
    public $name;
    private $includes = [];
    private $content = false;
    private $form = false;
    public $headerLink;

    function __construct($pageName, $headerLink = false)
    {
        $this->name = $pageName;
        $this->headerLink = $headerLink;
    }

    public function addInclude($file)
    {
        $this->includes[] = $file;
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
        //Includes all the specified files and echoes the content and the form
        if ($this->includes) {
            foreach ($this->includes as $include) {
                include_once $include;
            }
        }

        if ($this->content) {
            echo "$this->content<br><br>";
        }
        if ($this->form) {
            $this->form->echoForm();
        }
    }
}
