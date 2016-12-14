<?php
require_once "app/core/template.php";
require_once "app/core/templateexception.php";

class View
{
    public function __construct(){
    }

    public function generate($content_view, $template_view, $data = null)
    {
        if (file_exists('app/views/'.$template_view))
        {
            ob_start ();
            include 'app/views/'.$template_view;
            ob_end_flush();
        }
    }
} 