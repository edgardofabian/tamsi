<?php
/** Object for menu of tamsi framework 
 * Object contaiting elements of a tamsi framework menu
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
 * @package MenuElement 
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
class TamsiMenuElement
{
    public $url;		        // base location where the action will jump
    public $action; 			// The type of action to perform when invoked
    public $name;    		// an array of directory ignored by menu generation    
    public $class;   		// class of html element 
    public $id;        		// id of the html element
    public $role_required;   // required role in order to view this page
    public $external_link;
	public $attributes;
    public $icon;
    
    public function __construct($name,$url)
    {
        $this->url = $url;
        $this->action = 'display_rest';
        $this->name = $name;
        $this->class = '';
        $this->attributes = '';
        $this->id = '';
        $this->icon = '';
        $this->external_link=false;
        $this->role_required = array('all');
	}

	public function addClass($class)
    {
        $this->class=$this->class.' '.$class;
    }	

    
	public function getAction()
	{
		return $this->action;
	}

    public function getClass()
    {
		return $this->class;
	}
	



	public function getId()
	{
		return $this->id;
    }

	public function getName()
	{
		return $this->name;
	}


        
    public function getRoleRequired()
    {
		return $this->role_required;
	}
    

	public function setAction($action)
	{
		$this->action=$action;
        return $this;
	}

	public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }
    
	public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

	public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

	public function setRoleRequired($role)
	{
		$this->role_required = $role;
		return $this;
	}
    
    public function setExternalLink($link)
    {
        $this->external_link=$link;
    }

    public function getUrl()
    {
        return $this->url;
    }
    
    public function getExternalLink()
    {
        return $this->external_link;
    }
}
