<?php
/** Main controller of tamsi framework 
 * Object contaiting controller functions for main tamsi actions. It passes to user controller when not in the scope of actions
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
 * @package Controller 
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
require_once 'TamsiController.php';

class Controller extends TamsiController
{
	public function __construct(Model $model)
	{
        parent::__construct($model);
	}
    
    public function displayInclude()
    {
        $base_path = $this->model->getBasePath();
        
        $path = $this->model->getPath();
        
        $config = new Config();

        if ($base_path)
        {
            //die($base_path.'/'.$path);
            $path=$base_path.'/'.$path;
        } 
        
        if (!$this->is_allowed_dir($path) && ((strpos($path,'.pub')===FALSE)))
        {
            $_SESSION['tamsi_notice']='Requires login to view!';
            return FALSE;
        } else
        {
            $raw = FALSE;
            if (array_key_exists('raw',$_REQUEST))
            {
                $raw = $_REQUEST['raw'];
            }
            if (!(strpos($path,EDITABLE_VIEW_PATHS)===FALSE) && !($raw))
            {
				include __DIR__.'/../view/page.php';
            }
            else
            {
                include $path;
            }
            return TRUE;
        }        
    }    
    
    public function generateLiAFromMenu($menu,$submenu_html='',$external=FALSE,$action_container='',$first_word_last_level=FALSE)
    {
        $id='';
        $htm='';
        if ($menu->id) $id='id="'.$menu->id.'"'; $id='id="'.strtolower(str_replace(' ','_',$menu->name)).'"'; 
        $role_class='';
        foreach($menu->getRoleRequired() as $rol)
        {
             $role_class.=' '.$rol;
        }
        if ($submenu_html)
        {
            if (strpos(strtolower($menu->name),'admin')===FALSE)
            {                                    
                $li_id='';
            } else
            {
                $li_id="id='admin'";
            }
            
            $htm="<li $li_id class='has_child $role_class'><a $id href='#' class='".$menu->class."  has_child sitemap_menu' ".$menu->attributes." >".$menu->icon." ".$menu->name." </a> $submenu_html</li>";
        }
        else
        {
            $container='';
            $href=$menu->url;
            $url='';       
            $menu_name=ucfirst($menu->name);
            $li_class='';
            if ($first_word_last_level)
            {
                $names = explode(' ',$menu_name);
                foreach($names as $nam)
                {
                    if ($nam) 
                    {
                        $menu_name=$nam;
                        break;
                    }
                }
            }
                 
            if ($menu->getAction()=='redirect')
            {
                if ($menu->getExternalLink() && $external)
                {
                    $li_class='external_link '.$menu->getExternalLink().' '.$external;
                }
                else if ($menu->getExternalLink()===FALSE)
                {
                    $li_class='';
                }
                $class=$menu->class;
                
            } else
            {
                $li_class='';
                
                if ($menu->getAction()==='display_rest')
                {
                    $class=$menu->class.' mx-';
                    if (!$action_container)
                    {
                        $container='div.page';
                    }
                    else $container=$action_container;
                    $href='#';
                    $url="mx-click='".$menu->url."'";
                } else
                {
                    $class=$menu->class;
                }
            }   
            $htm="<li class='$li_class $role_class' ><a $id class='sitemap_menu $class' mx-container='$container'  ".$menu->attributes." href='".$href."' $url > ".$menu->icon." $menu_name</a></li>";         
        }
        
        return $htm;
    }    
	
}
