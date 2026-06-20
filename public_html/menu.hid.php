<?php
if (!defined('PROJECT')) 
{
	header("Location: https://".$_SERVER['HTTP_HOST']);
	die();
}
$paths = new Paths();
require_once 'User.php';
$icons=array('admin'=>'<i class="fa fa-gear main_icon"></i> ','books'=>'<i class="fa fa-book main_icon"></i> ','library'=>'<i class="fa fa-book main_icon"></i> ');
$icons['contact us']='<i class="fa fa-map-marker"></i> ';   
$icons['team']='<i class="fa fa-users main_icon"></i> ';   


    /*$books = $this->controller->model->getMenuBranch('Books',$this->controller->model->getMenus());
    $books->setRoleRequired(array('no_login'));
    foreach($books->url as $i=>$bmenu)
    {
        $books->url[$i]->setRoleRequired(array('no_login'));
    }*/
    //$menus = $this->controller->model->removeMenu('Books');
    //$menus = $this->controller->model->addMenuAfter('Admin',$books,$menus);
    //$this->controller->model->setMenus($menus);
    //die(json_encode($books));

$hmenu = new MenuElement('Home','');
$hmenu->setAction('display_rest');
$hmenu->class="mx-";
$hmenu->attributes='mx-container="div.page"';
$hmenu->icon='<i class="fa fa-home main_icon"></i> ';
$hmenu->id='home';
$hmenu->url='?command=display_rest&path=home.pub.hid.php';
$hmenu->setRoleRequired(array('no_login'));
$this->controller->model->addMenu($hmenu);


if ($role->isUserLoggedIn())
{
	$user = new User();
	$user->get(array('filter'=>array('id'=>$_SESSION['user_id'])));

    $unames=explode(' ',$_SESSION['user_name']);
    $name='';
    if (count($unames)>1)
    {
        $name.=ucfirst($unames[0][0]);
    }
    $li=count($unames)-1;
    if ($li>0)
    {
        $name.=ucfirst($unames[$li][0]);
    }
    
    $user_menu=new MenuElement($name,'#');
    $icon = '<img class="main_icon" style="width:28px;border-radius:15px;border:1px solid gray" src="img/displaypng.php?id='.$_SESSION['user_id'].'&image='.$user->getImageIcon('').'&key='.urlencode($user->getSecurekey()).'"></img>';    
    $user_menu->icon=$icon;//'<i class="fa fa-user"></i> ';

    $profile=new MenuElement('Profile','');
    $profile->icon='<i class="fa fa-user main_icon"></i> ';
    $profile->attributes='mx-container="div.page"';
    $profile->action='display_rest';
    $profile->url = $paths->base_url.'/?command=display_rest&path=_01Admin.dir/Users.dir/Edit User.hid.rest.php&id='.$_SESSION['user_id'];
    $profile->class='mx-';

    $logout=new MenuElement('Logout',$paths->base_url.'/logout');
    $logout->icon='<i class="fa fa-sign-out main_icon"></i> ';
    $logout->action='';
    $logout->class='sitemap_menu';
    $user_menu->url=array($profile,$logout);
    
    $this->controller->model->addMenu($user_menu);

    $menus = $this->controller->model->getMenus();
    if ($role->isUserAnyOf(array('admin','developer','teacher')))
    {
    } else
    {
        $menus = $this->controller->model->removeMenu('Admin',$menus);
        
    }
    $this->controller->model->setMenus($menus);
    
} else
{
    $login_menu = new MenuElement('Login','');
    $login_menu->setAction('#');
    $login_menu->attributes='onClick="showLogin(event)"';
    $login_menu->icon='<i class="fa fa-sign-in main_icon"></i> ';
    $login_menu->id='login_button';
    $login_menu->url='';
    $login_menu->setRoleRequired(array('no_login'));
    $this->controller->model->addMenu($login_menu);
}

$this->controller->model->setMenuAttribute('Books','mx-next="'.$paths->base_url.'/?command=display_rest&path=book_editor.hid.pub.php"  mx-next_container="#book_editor"');

$this->controller->model->addIcons($icons);
$menu_level=1;
$main_menu=TRUE;



$this->createSiteMapFromMenu(array(),$main_menu,$menu_level,false,array()); 
$this->prepareAdminMenu($this->controller->model->getMenus());

$this->renderSitemap(TRUE);?>

