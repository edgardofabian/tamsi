<?php
require_once 'Role.php';
$role=new Role();
$allowed=array('admin','supervisor','developer','client');
if ($role->isUserAnyOf(array('admin')))
{
}
else
{
	$edit_remove_status=true;
    $edit_remove_gender=true; 	
    $edit_remove_custom=true;
    $edit_remove_login=true; 	
    $edit_remove_loginip=true; 	
    $edit_remove_updateip=true; 	
    $edit_remove_resetkey=true; 	
    $edit_remove_securekey=true;
}
echo '<h3 class="heading">Edit User</h3>';
include $config->paths->app_path.'/usr/view/users/edit_user.rest.php';

