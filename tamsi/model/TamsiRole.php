<?php
/** Object for roles of tamsi framework 
 * Object contaiting elements of a tamsi framework role intended to be hardcoded values.
 * This page is intended to be edited by programmers to fit their needs
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
 * @package TamsiRole
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
require_once 'security.cfg.php';
class TamsiRole
{
	const ADMIN = 0x0F;   //highest role
	const WRITER = 0x04;  //Can edit page
	const UNVERIFIED_USER = 0x00;	// returns to the orginal script
	const USER = 0x01;  		// Verified User

    protected $rolenames;
    protected $security;
    protected $guest;
    protected $admin;
    protected $user;
    public function __construct()
    {
        $this->rolenames = array(0x00=>'guest',0x01=>'user',0x08=>'admin');
        $this->security = new Secure_config();
        $this->loadRoles();
    }
    
    public function getCurrentUser()
    {
        return $_SESSION['user_id'];
    }
    
    public function loadRoles()
    {
        foreach($this->rolenames as $val=>$name)
        {
            $this->$name=$val;
        }
    }

	
	public function isUserAdmin($role)
    {
        if (($role & self::ADMIN)==self::ADMIN)
        {
			return true;
		} else
		{
			return false;
		}
    }	
    
    public function getValueFromName($role_name)
    {
        $name = strtolower($role_name);
        return array_search($name,$this->rolenames);
    }
    
    public function getValueFromArrayNames($roles)
    {
        $role=0;
        foreach ($roles as $role_name)
        {
            $name = strtolower($role_name);
            $role=$role+array_search($name,$this->rolenames);
        }
        return $role;
    }    
    
    public function getValueFromArray($roles)
    {
        $role=0;
        foreach ($roles as $value)
        {
            $role=$role+$value;
        }
        return $role;
    }        

	public function isUserLoggedIn()
	{
		if (($_SESSION['user_id']>0) && (strcmp($_SESSION['user_domain'],$this->security->domain)==0))
		{
			return true;
		} else
		{
			return false;
		} 
	}

	public function isUserAuthorized($required_role)
	{
		if (($_SESSION['user_id']>0) && (($_SESSION['user_role'] & $required_role )>0) && (strcmp($_SESSION['user_domain'],$this->security->domain)==0))
		{
			return true;
		} else
		{
			return false;
		} 
	}
	
	public function isUserAuthorizedAs($role)
	{
		$required_role = $this->getValueFromName($role);
		if (($_SESSION['user_id']>0) && (($_SESSION['user_role'] & $required_role )>0) && (strcmp($_SESSION['user_domain'],$this->security->domain)==0))
		{
			return true;
		} else
		{
			return false;
		} 
	}
	
	public function isUserAnyOf($roles=array())
	{
        if (in_array('no_login',$roles))
        {
            return true;
        }
        else if (in_array('all',$roles))
        {
            if (($_SESSION['user_id']>0))
			{
				return true;
			}
        }
        $role_vals=array();
		foreach ($roles as $role)
		{
			$required_role = $this->getValueFromName($role);
			$role_vals[$role]=$required_role;
            if (($_SESSION['user_id']>0) && (($_SESSION['user_role'] & $required_role )>0) && (strcmp($_SESSION['user_domain'],$this->security->domain)==0))
			{
				return true;
                //die($_SESSION['user_role'].':'.$_SESSION['user_domain'].':'.$this->security->domain.'<br>'.json_encode($role_vals));
			}
            
		}
        
		return false;
	}
    
	public function hasRole($user_role,$rolename)
	{
		$required_role = $this->getValueFromName($rolename);
		if ((($user_role & $required_role )>0))
		{
			return true;
		} else
		{
			return false;
		} 
	}    
    
    public function getRoleName($role)
    {
        $rolename='';
        $i=0;
        foreach ($this->rolenames as $key=>$name)
        {
            if (($key & $role)>0) 
            {
                $i = $i+1;
                if ($i==1)
                {
                    $rolename.=$name;
                } else
                {
                    $rolename.=', '.$name;
                }
            }
        }
        return $rolename;
    }
    
    public function getRoleNames()
    {
        return $this->rolenames;
    }    
    
    public function getAllowedRoles($allowed)
    {
		$allowed_roles='';
		$i=0;
		foreach ($allowed as $rolename)
		{
			$i=$i+1;
			if ($i==1)
			{
				$allowed_roles=$rolename;
			} else
			if ($i<count($allowed))
			{
				$allowed_roles.=', '.$rolename;
			} else
			{
				$allowed_roles.=' and '.$rolename;
			}
		}
		return $allowed_roles;
	}
    
    public function arrayToIntegerEncoded($role_array=array())
    {
        $role=0;
        foreach($role_array as $val)
        {
            $role = $role | $val;
        }
        return $role;
    }
    
    public function showUnauthorizedMessage($rolenames=array(),$path='',$additional_message='')
    {
        $url = str_replace("display_rest","display_php",urldecode($path));
        
        if ($_SESSION['user_id']==0)
        {
            echo "<div class='no_login_message'><span  >Please <a style='color: gray; display: inline;cursor:pointer' onclick='showLoginAndGoto(\"".$url."\")'>login</a> to view this page </span></div>";
            echo "<script> $(function() { setTimeout(function(){ showLoginAndGoto(\"".$url."\"); }, 2000); });</script>";
        } else
        {
            
            echo "<div class='no_rights_message'><span>This page is only accessible to  ".$this->getAllowedRoles($rolenames).'<br>'.$additional_message." </span></div>";
        }
    }
    
	public function getAllValuesWithRole($name)
    {
        $vals=array();
        for($i=0;$i<=15;$i++)
        {
            if ($i & array_search($name,$this->rolenames))
            {
                $vals[] = $i;
            }
        }
        return $vals;
    }
    
        
}

