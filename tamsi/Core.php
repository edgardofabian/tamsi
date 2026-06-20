<?php

require_once 'CoreController.php';
require_once 'CoreView.php';
require_once 'User.php';
require_once 'Role.php';

class Core
{
    public $model;
    public $controller;
    public $view;        
    public function __construct($first_word_last_level=FALSE,$enable_external_links=TRUE,$include_menu=array(),$icons=array(),$additional_menus=array(),$enable_site_map=TRUE,$container='div.page',$enable_login=TRUE)
    {
        session_start();
        
        $this->controller = new Controller();
        $this->controller->receive();
        
        
        $this->view  = new View($this->controller); 
        
    }

}

