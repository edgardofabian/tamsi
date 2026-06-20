<?php
    //die(dirname(__FILE__,4).'/events/config/paths.cfg.php');
    require_once dirname(__FILE__,4).'/school/config/paths.cfg.php';
    
    $paths = new Paths();
    set_include_path($paths->includes);
    require_once 'User.php';
    $absolute_path=$paths->base_url.'/uploads/image/none.png';
    $id=$_GET['id'];
    $user=new User();
    $user->find(array('id'=>$id));
    if ($id>0)
    {
		if ($user->getSecurekey()==$_REQUEST['key'])
		{
			$absolute_path = $_GET['image'];
		}
		if (!file_exists($absolute_path)) $absolute_path=$paths->base_url.'/uploads/image/none.png';
    }
	//die($absolute_path);
    //display image
    header("Content-type: image/png");
    readfile($absolute_path);
