<?php

class Page
{
    public $name;
    public $content;

    function __construct($pageName)
    {
        $this->name = $pageName;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function echoContent()
    {
        echo $this->content;
    }
}
