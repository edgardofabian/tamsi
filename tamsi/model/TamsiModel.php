<?php
/** Main model of tamsi framework 
 * Object contaiting model objects for main tamsi framework. 
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
 * @package Tamsi
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
require_once 'MenuElement.php';
class TamsiModel
{
	const TYPE_TABPANE = 0;
	const TYPE_SIDEBAR = 1;
	const TYPE_POPUP = 2;
    const VERSION = "1.0";

    protected $types;
    protected $base_path; 		//base path of the url of the current running output   //
    protected $file_filters;    // an array of directory ignored by menu generation
	protected $file_pattern;   	// PCRE pattern for accepted file 
    protected $file_extensions; // file extensions of accepted file
    protected $id;        		// id of the html element
    protected $type;      		// could be tabpane, sidebar, fullpopup
    protected $ul_class;  		// injected class of menu ul
    protected $li_class;  		// injected class of menu li
	protected $menus;           // array of menu 
	protected $links;			// array of links
    protected $parents;			// array of links
	protected $url;		  	// destination URL for each menu
	protected $actions;			// specific action type per menu
    protected $image_filters;
	protected $data;
    protected $command;
    protected $path;
    protected $selected;
    protected $view_path;
    protected $introduction;
    public function __construct()
    {
        
        $this->types = array(self::TYPE_TABPANE=>'menu_tabpane',self::TYPE_SIDEBAR=>'menu_sidebar',self::TYPE_POPUP=>'menu_popup');
		$this->file_filters = array('.','..','img','lib','css','js','app','usr','.git','util','config','index.php','flat','_vti_bin','_vti_log','_vti_txt','_vti_cnt','_vti_cnf','_vti_pvt','cgi-bin','.Trash-1000','fonts','images','_private','index_all','get','tamsi','video','usr_generated','.htaccess','.htpasswds','.phalcon','getServer.php','getmyip.php','view','audio','video','pdf','sgf'); //default filter for menu
        $this->file_extensions = array("html","htm","phtml","shtm","seg","pvt",'php');
        $this->image_filters = array('png','jpg','jpeg','gif');
		$this->id = 'menu';
		$this->type = self::TYPE_TABPANE;
        $this->url ='';
        $this->ul_class = $this->types[self::TYPE_TABPANE];
        $this->li_class = '';
        $this->introduction = 'intro';
		$this->menus = array();
		$this->links = array();
        $this->parents = array();
        $this->selected = '1000';
	}

	public function addFileFilters($filter)
	{
		$this->dir_filters[] = $filter;
	}

    public function addFileExtension($ext)
    {
        $this->file_extensions[] = $ext;
    }	

	public function addLiClass($class)
    {
        $this->li_class=$this->li_class.' '.$class;
    }	
    
    public function addMenu($menu)
    {
        $this->menus[] = $menu;
    }

	public function addMenuAfter($name,$new_menu,$menus=array())
	{
        if (count($menus)==0) 
        {
            $menus=$this->menus;
            $update=1;
        }
                
        $len = 0;        
		foreach ($menus as $idx=>$menu)
		{
            $len = $len+1;
			if (strcasecmp(trim($menu->name),trim($name))==0)
			{
				$menu_s = array_slice($menus,0,$len,true);
                $menu_a = array_slice($menus,$len,count($menus)-$len+1,true);
                $menu_s[]=$new_menu;
                foreach($menu_a as $m)
                {
                    $menu_s[]=$m;
                }
                
                return $menu_s;
			}
			else if (is_array($menu->url))
			{                    
				$menus[$idx]->url=$this->addMenuAfter($name,$new_menu,$menu->url);
                return $menus;
			} 
			
		}		
		return $menus;
	} 

    public function addLink($menu)
    {
        $this->links[] = $menu;
    }
    public function addParent($parent)
    {
        $this->parents[] = $parent;
    }
    
	public function addUlClass($class)
    {
        $this->ul_class=$this->ul_class.' '.$class;
    }	




    public function getBasePath()
    {
		return $this->base_path;
	}


    public function getTitle()
    {
        $titles = explode("/",$this->getBasePath());
        $n=count($titles);
        $dir='';
        $dir = $titles[$n-1]; 
        $end = strpos($dir,".");
        $title = substr($dir,0,$end);
        return $title;
    }

    public function getCommand()
    {
        return $this->command;
    }

	public function getData()
	{
		//return $_SESSION['view_data'];
        return $this->data;
	}

	public function getFileFilters()
	{
		return $this->file_filters;
	}

	public function getFileExtensions()
	{
		return $this->file_extensions;
	}

    public function getHome()
    {
        
        return $_SERVER['PHP_SELF'].'/../';
    }

	public function getId()
	{
		return $this->id;
    }
    
    public function getImageFilters()
    {   
        return $this->image_filters;
    }

	public function getLiClass()
	{
		return $this->li_class;
	}	
    
    public function getMenus()
    {
        return $this->menus;
    }
    
    public function getLinks()
    {
        return $this->links;
    }
    
    public function getParents()
    {
        return $this->parents;
    }    
    
    public function getFirstMenu()
    {
		$menus = $this->menus;
		if ($menus)
		{
			foreach ($menus as $menu)
			{ 
				if (strpos($menu->getClass(),'hidden')>0)
				{
				} else
				return $menu;
			}
		} else
		return false;
	}
    
    public function getLastPage()
    {
		$menus = $this->menus;
		if ($menus)
		{
			$last_page = 0;
			foreach ($menus as $menu)
			{
				$last_page = $menu->getPage();
			}
			return $last_page;
		} else
		return false;
	}    
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function getViewPath()
    {
        return $this->view_path;
    }    
    
    public function getSelected()
    {
        return $this->selected;
    }

	public function getUlClass()
	{
		return $this->ul_class;
	}

	public function setBasePath($path)
    {
        $this->base_path = $path;
        return $this;
    }
    
    public function setCommand($cmd)
    {
        $this->command = $cmd;
        return $this;
    }

	public function setData($data)
	{
		//$_SESSION['view_data'] = $data;
        $this->data = $data;
		return $this;
	}


	public function setFileFilters($filters)
	{
		$this->file_filters = $filters;
        return $this;
	}	

    public function setFileExtensions($exts)
    {
        $this->file_extensions = $exts;
        return $this;
    }


	public function setId($id)
	{
		$this->id = $id;
        return $this;
	}

    public function setLiClass($class)
    {
        $this->li_class = $class;
        return $this;
    }

    public function setMenus($menus)
    {
        $this->menus = $menus;
        return $this;
    }
    
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    
    public function setViewPath($path)
    {
        $this->view_path = $path;
        return $this;
    }
    
    public function setSelected($sel)
    {
        $this->selected = $sel;
    }
    
	public function setType($type)
	{
		$this->type = $type;
        return $this;
	}


    public function setUlClass($class)
    {
        $this->ul_class = $class;
        return $this;
	}
	
	public function isDisplayData($ext)
	{
		return in_array($ext,$this->file_extensions);
	}
	
	public function isDisplayDataImage($ext)
	{
		return in_array($ext,$this->image_filters);
	}
    
    public function getSelectedMenu()
    {
        foreach ($this->menus as $menu)
        {
            if ($menu->getId()==$this->selected)
            {
                return $menu;
            }
        }
        return $this->menus[0];
    }

    public function sortByPage()
    {
        uasort($this->menus,array($this,'comparePage'));
    }

    public function comparePage($a, $b) 
    {
        $a_page = $a->getPage();
        $b_page = $b->getPage() ;
        
        if ($a_page == $b_page) 
        {
            return 0;
        } 
        else if ($a_page > $b_page)
        {
            return 1;
        } else
        {
            return 0;
        }
    }    
    
    public function addIcons($icons=array(),$menus=array())
    {
        $update=0;
        if (count($menus)==0) 
        {
            $menus=$this->menus;
            $update=1;
        }
        
		foreach ($menus as $i=>$menu)
		{
			if (array_key_exists(strtolower($menu->name),$icons))
			{
				$menu->icon = $icons[strtolower($menu->name)];
			}
			else if (is_array($menu->url))
			{                    
				$fmenus=$this->addIcons($icons,$menu->url);
                $menu->url = $fmenus;
			} 
            $menus[$i]=$menu;
		}	  
        if ($update) $this->menus=$menus;
        return $menus;
    }    

	public function setMenuBranch($name,$new_menu,$menus=array())
	{
        $update=0;
        if (count($menus)==0) 
        {
            $menus=$this->menus;
            $update=1;
        }
                
		foreach ($menus as $i=>$menu)
		{
			if (strcasecmp(trim($menu->name),trim($name))==0)
			{
				$menu[$i]=$new_menu;
			}
			else if (is_array($menu->url))
			{                    
				$fmenu=$this->setMenuBranch($name,$new_menu,$menu->url);
                $menu->url = $fmenu;
			} 
			
		}
        if ($update) $this->menus=$menus;		
		return $menus;
	} 

	public function getMenuBranch($name='',$menus=array())
	{
        if (count($menus)==0) 
        {
            $menus=$this->menus;
        }
                
		foreach ($menus as $menu)
		{
			if (strcasecmp(trim($menu->name),trim($name))==0)
			{
				return $menu;
			}
			else if (is_array($menu->url))
			{                    
				$fmenu=$this->getMenuBranch($name,$menu->url);
                if ($fmenu) return $fmenu;
			} 
			
		}		
		return array();
	} 

	public function removeMenu($name='',$menus=array())
	{
        if (count($menus)==0) 
        {
            $menus=$this->menus;
        }
                
		foreach ($menus as $i=>$menu)
		{
			if (strcasecmp(trim($menu->name),trim($name))==0)
			{
                unset($menus[$i]);
			}
			else if (is_array($menu->url))
			{                    
				$menu->url = $this->removeMenu($name,$menu->url);
			} 
			
		}		
		return $menus;
	} 


	public function setMenu($name,$new_menu,$menus=array())
	{
        return $this->setMenuBranch($name,$new_menu,$menus);
	} 

	public function getMenu($name,$menus=array())
	{
        return $this->getMenuBranch($name,$new_menu,$menus);
	}
    
	public function setMenuAttribute($name,$attr,$menus=array())
	{
        $update=0;
        if (count($menus)==0) 
        {
            $menus=$this->menus;
            $update=1;
        }
                
		foreach ($menus as $i=>$menu)
		{
			if (strcasecmp(trim($menu->name),trim($name))==0)
			{
				$menu->attributes.=' '.$attr;
			}
			if (is_array($menu->url))
			{                    
				$fmenu=$this->setAllMenuAttribute($attr,$menu->url);
                $menu->url = $fmenu;
			} 
			
		}
        if ($update) $this->menus=$menus;		
		return $menus;
	}     
    
	public function setAllMenuAttribute($attr,$menus=array())
	{
        $update=0;
        if (count($menus)==0) 
        {
            $menus=$this->menus;
            $update=1;
        }
                
		foreach ($menus as $i=>$menu)
		{
			$menu->attributes.=' '.$attr;
			
			if (is_array($menu->url))
			{                    
				$fmenu=$this->setAllMenuAttribute($attr,$menu->url);
                $menu->url = $fmenu;
			} 
			
		}
        if ($update) $this->menus=$menus;		
		return $menus;
	}    
    
}
