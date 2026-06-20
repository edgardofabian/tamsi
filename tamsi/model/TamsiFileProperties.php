<?php
/** Object for program file identification of tamsi framework 
 * Object contaiting functions to segreggate the information of files contained within the tamsi framework website
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
 * @package FileProperties
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
require_once 'MenuElement.php';
require_once 'Role.php';

class TamsiFileProperties
{
    protected $name;
    protected $extension;
    protected $order;
    protected $role_required;
    protected $hidden;
    protected $rest;
    protected $link;
    protected $unique_name;
    
    public function __construct($path)
    {
        $this->link=false;
        $this->unique_name = '';
        if (strpos($path,'/')>0)
		{ 
			$filenames = explode('/',$path);
			$num = count($filenames)-1;
            
			$file = $filenames[$num];
		} else 
        {
            $file = $path;
        }
        
        $ext_pos = strrpos($file,".")+1;
		if ($ext_pos<strlen($file))
		{
			$this->extension =strtolower(substr($file,$ext_pos));
		} else 
        {
            $this->extension = '';
        }
        $this->role_required=array('all');
        $segments = explode('.',$file);
        $this->hidden = false;
        $this->rest = false;
        if (count($segments)>0)
        {
            if (!(strpos($file,'www.')===FALSE))
            {
                $this->name = $file;
                $this->link = true;
            } else if (!(strpos($file,'http.')===FALSE))
            {
                $this->name = str_replace('http.','',$file);
                $this->link = true;
            } else if (!(strpos($file,'https.')===FALSE))
            {
                $this->name = str_replace('https.','',$file);
                $this->link = true;
            } else if (!(strpos($file,'.svg')===FALSE))
            {
                $this->name = $segments[0];
                $this->link = false;
            } else if ((!(strpos($file,'.pub.')===FALSE)) || (!(strpos($file,'.dir')===FALSE)))
            {
                $this->name = $segments[0];
                $this->role_required=array('no_login');
            } else
            {
                $this->name = $segments[0];
            }
            $this->name = str_replace('__','/',$this->name);
            
            $this->name=preg_replace('/^_[0-9][0-9]/','',$this->name);
            $this->name = str_replace('_',' ',$this->name);
            
            for($i=1;$i<count($segments);$i++)
            {
                $str = $segments[$i];
                if ($i==1)
                {
                    if (is_numeric($str))
                    {
                        $this->order =$str;
                    }
                }
                $start=strpos($str,'(');
                $end=strpos($str,')');
                if (!($start===false) && ($end>$start))
                {
					$roles = explode(',',substr($str,$start+1,$end-$start-1));
					if ($roles[0])
					{
						$this->role_required = $roles;
					}
                } 
                if (strpos($str,'hid')===0)
                {
                    $this->hidden = true;
                } 
                if (strpos($str,'rest')===0)
                {
                    $this->rest = true;
                }
                /*if (!(strpos(strtolower($this->name),'admin')===FALSE))
                {
                    $this->role_required[] = 'admin';
				}*/
                if ($i==(count($segments)-1))
                {
                    $this->extension = $str;
                }
            }
        } else
        {
            $this->name=$file;
            $this->extension='';
            $this->defined_page='';
            $this->role_required=array();
        }
                
    }

	public function getExtension()
	{
		return $this->extension;
	}	
    
    public function getRoleRequired()
	{
		return $this->role_required;
	}	

	public function getOrder()
	{
		return $this->order;
	}	
    
    public function getName()
    {
        return $this->name;
    }

    
    public function getHidden()
    {
        return $this->hidden;
    }   
    
    public function getRest()
    {
        return $this->rest;
    }    
    
    public function isDisplayDir($file)
	{
		if (strpos(strtolower($file),'.dir'))
		{
			return true;
		} else if (strpos(strtolower($file),'.pvt'))
		{
			return true;
		} else if (strpos(strtolower($file),'.tamsi'))
		{
			return true;
		} else if (strpos(strtolower($file),'.page'))
		{
			return true;
		} else
		{
			return false;
		}
	}
    
    public function isLink()
    {
        return $this->link;
    }
    
	public function getLinkPath($filename)
	{
        $file = str_replace('__','/',$filename);
		
		if (!(strpos($file,'www.')===false))
		{ 
			return "http://".$file;
		} else if (!(strpos($file,'http.')===false))
		{ 
			return str_replace('http.',"http://",$file);
		} else if (!(strpos($file,'https.')===false))
		{ 
			return str_replace('https.',"https://",$file);
		} 
	}    
}
