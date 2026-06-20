<?php
if (file_exists(__DIR__.'/main.cfg.php'))
{
    include __DIR__.'/main.cfg.php';
}
require_once __DIR__.'/paths.cfg.php';



class Views
{

    public $views;
    public function __construct()
    {
        $paths = new Paths();
        $this->views = array();
        
        //define default view and data of view
        $this->views['#sitemap']=array('path'=>($paths->public).'/menu.hid.php','data'=>array());
        $this->views[MAIN_CONTAINER]=array('path'=>($paths->public).'/home.pub.hid.php','data'=>array());
        $this->views['#tamsi_notice']=array('path'=>($paths->public).'/notice.pub.hid.php','data'=>array());
        
    }
}
