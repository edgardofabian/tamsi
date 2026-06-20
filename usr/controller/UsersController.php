<?php 
/**  
 * [DESCRIPTION]
 * 
 * Copyright (C) 20014-2020 Edgardo Fabian <edgardo.fabian@gahum.com> 
 * LICENSE: This program is free software; you can redistribute it and/or 
 * modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation; either version 3 
 * of the License, or (at your option) any later version. 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU General Public License for more details. 
 * You should have received a copy of the GNU General Public License 
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;. 
 * @package tamsi
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */

require_once 'TamsiUsersController.php';
class UsersController extends TamsiUsersController 
{ 
    public function assumePersonAction()
    {
        if ($_POST)
        {
            return $this->assumePersonActionGet();
        }
        else
        {
            $_SESSION['tamsi_notice']='Invalid Action';
        }
        return FALSE;
    }
    
	public function assumePersonActionGet()
    {
        if (array_key_exists('new_user_id',$_REQUEST) && $_REQUEST['new_user_id'])
        {
            $role = new Role();
            
            if ($role->isUserAuthorizedAs('admin'))
            {
				$id=$_REQUEST['new_user_id'];
				$user = new User();
				$user->find(array('id'=>$id));
				
                $_SESSION['user_id_orig']=$_SESSION['user_id'];
                $_SESSION['user_role_orig']=$_SESSION['user_role'];
                $_SESSION['user_name_orig']=$_SESSION['user_name'];
                $_SESSION['user_email_orig']=$_SESSION['user_email'];
                
                $_SESSION['user_id']=$user->getId();
                $_SESSION['user_name']=$user->getFirstname().' '.$user->getLastname();
                $_SESSION['user_email']=$user->getEmail();
                $_SESSION['user_role']=$user->getRole();
                $_SESSION['tamsi_notice']="You have switch to ".$user->getName().".";
                $_SESSION['list_appointments']=array();
                return TRUE;
            }
            else
            {
                $_SESSION['tamsi_notice']='Unauthorized';
            }
        }
        else
        {
            $_SESSION['tamsi_notice']='Invalid Action';
        }
        return FALSE;
    }    
    
    public function resumeOriginalRoleAction()
    {
        if ($_POST)
        {
            return $this->resumeOriginalRoleActionGet();
        }
        else
        {
            $_SESSION['tamsi_notice']='Invalid Action';
        }
        return FALSE;
    }
    public function resumeOriginalRoleActionGet()
    {
        $role = new Role();
        
        if ($role->hasRole($_SESSION['user_role_orig'],'admin') )
        {                
            $_SESSION['user_id']=$_SESSION['user_id_orig'];
            $_SESSION['user_name']=$_SESSION['user_name_orig'];
            $_SESSION['user_role']=$_SESSION['user_role_orig'];
            $_SESSION['user_email']=$_SESSION['user_email_orig'];
            
            
            $_SESSION['user_id_orig']=0;
            $_SESSION['user_role_orig']=0;
            $_SESSION['user_name_orig']='';
            $_SESSION['user_email_orig']=0;
            
            $_SESSION['tamsi_notice']=$_SESSION['user_name']." original role as ".$role->getRoleName($_SESSION['user_role'])." has been restored.";
            $_SESSION['list_appointments']=array();
            return TRUE;
        }
        else
        {
            $_SESSION['tamsi_notice']='Unauthorized';
        }
        return FALSE;
    }    
}
