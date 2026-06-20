<?php
/** Main view of tamsi framework 
 * Object contaiting view functions for main tamsi actions. It passes to user view when not in the scope of actions
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
 * @package TamsiView 
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
require_once 'Model.php';
require_once 'MenuElement.php';
require_once 'Role.php';
require_once 'Controller.php';
require_once 'generate.php';
require_once 'FileProperties.php';
require_once 'Security.php';
require_once 'CoreView.php';
class TamsiView extends CoreView
{
    public $controller;
    public $paths;
    public $admin_menus;
    public $security;
    public $css;
    public $jquery;
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
        $this->security = $this->controller->security;
        $this->paths = new Paths();
        $this->css=array($this->paths->base_url.'/css/menu.css',$this->paths->base_url.'/css/style.css');
        $this->jquery['js']=$this->paths->base_url.'/js/jquery-1.12.4.js';
        $this->jquery['js_ui']=$this->paths->base_url.'/js/jquery-ui.js';
        $this->jquery['js_ui_touch']=$this->paths->base_url.'/js/jquery.ui.touch-punch.min.js';
        $this->jquery['js_css']=$this->paths->base_url.'/js/jquery-ui.css';
        parent::__construct($controller);
    }
    
    public function addCss($path)
    {
        $this->css[]=$path;
    }
    
    public function linkCss($num)
    {
        foreach($this->css as $css_path)
        {
            echo '<link rel="stylesheet" type="text/css" href="'.$css_path.'?x='.$num.'"  />'."\r\n";
        }
    }
    

//<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    public function initializejQuery()
    {
        echo "\r\n".
'<script src="'.$this->jquery['js'].'"></script>
<script src="'.$this->jquery['js_ui'].'"></script>
<script type="text/javascript" src="'.$this->jquery['js_ui_touch'].'" ></script>
<link rel="stylesheet" href="'.$this->jquery['js_css'].'" />
<script type="text/javascript">
    function isMobile()
	{
		if (window.matchMedia("(max-width: 767px)").matches) 
		{
			return true;
		} else
		{
			return false;
		}
	}          
</script>';
    }    
    
    public function initializeCssAndJquery($num=0)
    {
        $this->linkCss($num);
        $this->initializejQuery();
    }
    
    public function floatTableHeaderOnScroll()
    {
		echo "
		<script type='text/javascript'>
		$(function() 
		{
			if ($('table.float_scroll > thead').length > 0)
			{
				var thead_top = $('table.float_scroll > thead').offset().top;
				$( window ).scroll( function()
				{
					if ((thead_top - $(window).scrollTop())<1)
					{
						
						if ($('table.float_scroll > thead.clone').length > 0)
						{
							$('table.float_scroll > thead.clone').css('display','');
						} else
						{
							jfloating_thead = $('table.float_scroll > thead').first();
							jclone_thead = $('table.float_scroll > thead').clone();
							jfloating_thead.addClass('float_thead');
							jclone_thead.addClass('clone');
							jfloating_thead.after(jclone_thead);
						}
						$('table.float_scroll > thead.float_thead').css('position','fixed');
						$('table.float_scroll > thead.float_thead').css('top','0');
						$('table.float_scroll > thead.float_thead').find('th').each( function(i)
						{
							$(this).width($('table.float_scroll > tbody > tr').find('td').eq(i).width());
						});
					} 
					if ((thead_top - $(window).scrollTop())>1)
					{
						
						$('table.float_scroll > thead.float_thead').css('position','');
						$('table.float_scroll > thead.float_thead').css('top','');
						$('table.float_scroll > thead.clone').css('display','none');
					}
					
				});        
			} 
		});
        </script>";
	}
    
    public function initializeExternalJquery()
    {
        echo '<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
                <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
                <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">';
    }   

    
    function generateLinks()
    {
        $cnt = 0;
		$htm = '<div class="important_links" style="text-align:center;horizontal-align:center;display:block">';
        foreach ($this->controller->model->getLinks() as $menu)
        {
			if ($menu->getClass())
			{
				$class="class='".$menu->getClass()."'";
			} else
			{
				$class='';
			}
			if (file_exists('img/'.$menu->getName().'.png'))
			{
				$src='img/'.strtolower($menu->getName()).'.png';
			} else
			if (file_exists('img/'.$menu->getName().'.gif'))
			{
				$src='img/'.strtolower($menu->getName()).'.gif';
			} else
			{
				$src='img/link.png';
			}
			$htm.='<div class="important_link" style="display:inline-block;padding: 5px 5px 5px 5px;width:150px"><a href="'.$this->controller->click($menu).'" style="display:inline-block"><img src="'.$src.'" style="round-corners;height:64px;display:block;margin-left:auto;margin-right:auto" /><label>'.$menu->getName().'</label></a></div>';
		}
		
		echo $htm;
		echo $_SESSION['important_links'];
		echo '</div>';
        echo $this->generateDivPopupBottomSticky();
	}
    
    function generateSimpleLinks()
    {
		echo $_SESSION['external_links'];
	}    
    
    function generateTitle($default='')
    {
        if ($_REQUEST['path'])
        {
            $path = $_REQUEST['path'];
        } else
        {
            $path = $default;
        }
        $dirs = explode('/',trim($path));
        $ndir = count($dirs);
        $dir = new FileProperties(trim($dirs[$ndir-1]));
        if ($dir->getName())
        {
            echo '<p class="title" >'.ucfirst($dir->getName()).'</p>';
        } else
        {
            echo '<p class="title" >'.$default.'</p>';
        }
    }
    
    public function generateLiAFromMenu($menu,$submenu_html='',$external=FALSE,$action_container='',$first_word_last_level=FALSE)
    {
        $id='';
        $htm='';
        if ($menu->id) $id='id="'.$menu->id.'"'; else $id='id="'.strtolower(str_replace(' ','_',$menu->name)).'"'; 
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
            
            $htm="<li $li_id class='has_child $role_class'><label $id   class='".$menu->class."  has_child sitemap_menu' ".$menu->attributes." >".$menu->icon." ".$menu->name." </label> $submenu_html</li>";
        }
        else
        {
            $container='';
            if (strlen($menu->url)>0)
            {
                $href=$menu->url;
            } else $href='javascript:void(0);';
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
                    $href='javascript:void(0);';
                    $url="mx-click='".$menu->url."'";
                    $htm="<li class='$li_class $role_class' ><label $id class='sitemap_menu $class' mx-container='$container'  ".$menu->attributes." href='".$href."' $url > ".$menu->icon." $menu_name</label></li>";
                } else
                {
                    $class=$menu->class;
                    
                    $htm="<li class='$li_class $role_class' ><a $id class='sitemap_menu $class' mx-container='$container'  ".$menu->attributes." href='".$href."' $url > ".$menu->icon." $menu_name</a></li>";
                }
            }   
                     
        }
        
        return $htm;
    }

    public function createSiteMapFromMenu($menus=array(),$main=true, $level=1,$external=false,$include_menu=array(),$container='div.page',$first_word_last_level=FALSE)
    {
        session_start();
        if ($main)
        {
            if ($_REQUEST && array_key_exists('clearcache',$_REQUEST))
            {
                if ($_REQUEST['clearcache'])
                {
                    unset($_SESSION['site_map']);
                }
            }
			if (isset($_SESSION['site_map']))
			{
				$site_map = $_SESSION['site_map'];
			} else
			{
				$site_map = '';
			}
		}
		else
		{
			$site_map =  '';
		}
        if (strlen($site_map)==0)
        {   //generate sitemap
            $important_links = '';
            $external_links = '';
            $links = '';
            $class_level = 'level_'.$level;
            if ($main)
            {
                $site_map_begin = '<ul id="site_map_content" class="links '.$class_level.'" >';
                if (count($menus)==0) 
                {
                    $menus=$this->controller->model->getMenus();
                }
            } else
            {
                $site_map_begin = '<ul class="links '.$class_level.'" >';
            }

            
            
            $level = $level+1;
            $site_map_content='';
            foreach ($menus as $menu)
            {
				
				if ($this->controller->role->isUserAnyOf($menu->role_required))
                {
					//echo $menu->name;
					if (is_array($menu->url))
					{
						if ((count($include_menu)==0) || (in_array(strtolower($menu->name),$include_menu)))
						{
							$subhtml=$this->createSiteMapFromMenu($menu->url,false,$level,$external,$include_menu,$container,$first_word_last_level);
							if ($subhtml)
							{
								$site_map_content.=$this->generateLiAFromMenu($menu,$subhtml,$external,$container,$first_word_last_level);
							}
						}
					} else
					if (is_string($menu->url))
					{
						if ((count($include_menu)==0) || (in_array(strtolower($menu->name),$include_menu)))
						{
							$site_map_content.=$this->generateLiAFromMenu($menu,'',$external,$container,$first_word_last_level);
						}
					}
				}
                
            }
            //die('menus='.count($menus));
			if ($links || $site_map_content)
			{
				$site_map.=$site_map_begin;
				$site_map.=$site_map_content;
				$site_map.= $links;
				$site_map.= "</ul>";
			}
            $important_links.=$external_links;
            
        } else
        {
            $site_map = $_SESSION['site_map'];
            $important_links = $_SESSION['important_links'];
            $external_links = $_SESSION['external_links'];
        }

        
		if ($main)
        {
            $_SESSION['site_map'] = $site_map;
            $_SESSION['important_links'] = $important_links;
            $_SESSION['external_links'] = $external_links;
            return $site_map;
        } else
        {
            return $site_map;
        }
	}   	   
    
    
    function renderSiteMap($mobile_only=false,$disable_mobile=false,$disable_desktop=false,$icon_expand='chevron-right',$icon_collapse='chevron-down',$user_menu=FALSE)
    {
        $htm="<button id='mobile_menu_close' type='button' style='display:none' class='button'  onclick='hideSitemap()' ><i class='fa fa-close'></i></button>";
        $htm.="<button id='mobile_menu_open' type='button' style='display:none' class='button'  onclick='showSitemap()' ><i class='fa fa-bars'></i></button>";

        $htm.="<div id='site_map' class='round-corners-bottom' style='display:none'>";
        
        $htm.=$_SESSION['site_map'];
        $htm.="</div>";
        $htm.='<!-- Renders Sitemap  -->
            <script type="text/javascript">        
            function enableSitemapControl()
            {
                $("#site_map_content").hide();
                $("#site_map").addClass("desktop_menu");  
                $("#site_map_content").show();
                $("#site_map").show();
                $("#site_map").find(".sitemap_menu").each( function()
                {
                    if ($(this).hasClass("has_child"))
                    {
                        $(this).children("i.fa-'.$icon_collapse.'").remove();
                        $(this).children("i.fa-'.$icon_expand.'").remove();
                        if ($(this).parent("li").hasClass("active"))
                        {
                            $(this).append($("<i class=\'fa fa-'.$icon_collapse.'\' style=\'font-size:12px\'></i>"));
                            $(this).parent("li").children("ul").show();
                        } else
                        {
                            $(this).append($("<i class=\'fa fa-'.$icon_expand.'\' style=\'font-size:12px\' ></i>"));
                            $(this).parent("li").children("ul").hide();
                        }                  
                        $(this).click( function()
                        {
                            $(this).children("i.fa-'.$icon_collapse.'").remove();
                            $(this).children("i.fa-'.$icon_expand.'").remove();                    
                            if ($(this).parent("li").hasClass("active"))
                            {
                                $(this).children("i.fa-'.$icon_collapse.'").remove();
                                $(this).append($("<i class=\'fa fa-'.$icon_expand.'\' style=\'font-size:12px\'></i>"));
                                $(this).parent("li").children("ul").hide();
                                $(this).parent("li").removeClass("active");
                                
                            } else
                            {
                                $(this).parent("li").parent("ul").children("li.active").find("i.fa-'.$icon_collapse.'").replaceWith("<i class=\'fa fa-'.$icon_expand.'\' style=\'font-size:12px\'></i>");
                                $(this).parent("li").parent("ul").children("li.active").children("ul").hide();
                                $(this).parent("li").parent("ul").children("li.active").removeClass("active");
                                $(this).parent("li").addClass("active");
                                $(this).children("i.fa-'.$icon_expand.'").remove();
                                $(this).append($("<i class=\'fa fa-'.$icon_collapse.'\' style=\'font-size:12px\' ></i>"));
                                $(this).parent("li").children("ul").show();
                            }
                        });
                    } else
                    {
                        $(this).click( function()
                        {
                            $(this).parent("li.active").children("ul").hide();
                            $(this).parent("li.active").removeClass("active");
                        });
                    }
                });
            }
            
            function resetSitemap()
            {
                initDesktopOrMobile();
                $("#site_map").find("li.active").each( function()
                {
                    $(this).find("i.fa-'.$icon_collapse.'").each( function()
                    {
                        $(this).removeClass("fa-'.$icon_collapse.'").addClass("fa-'.$icon_expand.'");
                    });
                    $(this).children("ul").hide();
                    $(this).removeClass("active");
                });           
			}    

			function hideSitemap()
			{
				$("#site_map").hide();
				$("#mobile_menu_close").hide();
				$("#mobile_menu_open").show();
			}

			function showSitemap()
			{
				$("#site_map").show();
				$("#mobile_menu_open").hide();
				$("#mobile_menu_close").show();
			}
            
            function initDesktopOrMobile()
            {
				if (isMobile()) 
				{
					$("#site_map").removeClass("desktop_menu");
					$("#site_map").addClass("mobile_menu");
                    $("#site_map").hide();
                    $("#mobile_menu_close").hide();
                    $("#mobile_menu_open").show();
				} else 
				{
					$("#site_map").removeClass("mobile_menu");
					$("#site_map").addClass("desktop_menu");		
                    $("#site_map").show();
                    $("#mobile_menu_open").hide();
                    $("#mobile_menu_close").hide();
				}				
            
            }


            $(document).ready(function()
            {
                '."\r\n";
				$htm.='
				enableSitemapControl();
				if ('.($mobile_only?0:1).') 
				{
					if (isMobile()) 
					{
						$("#site_map").show();
					} else 
					{
						$("#site_map").hide();					
					}
				} 
                initDesktopOrMobile();
				$( window ).resize(function() 
				{
					if ('.($mobile_only?0:1).') 
					{
						if (isMobile()) $("#site_map").show();
						else $("#site_map").hide();
					} 
                    initDesktopOrMobile();				
                });		 				
            });
            </script>';
        echo $htm;
    }
    
    public function createUlLiFromMenu($menus=array(),$level=1,$include=array(),$ul_class='menu')
    {
        $menu_level = 'level_'.$level;        
        $ul = '<ul  class="'.$ul_class.' '.$menu_level.'" >';

        $level = $level+1;
      
        foreach ($menus as $menu)
        {
            if (is_array($menu->url))
            {                                   
                if ((count($include)==0) || (in_array(strtolower($menu->name),$include)))
                {
                    $ul.='<li>'.$menu->name.$this->createUlLiFromMenu($menu->url,$level,$include).'</li>';
                }
            } else
            if (is_string($menu->url))
            {
                if ($menu->getAction()=='redirect')
                {
                    $ul.='<li><a  class="'.$menu->class.'" href="'.$menu->url.'" >'.$menu->icon.' '.$menu->name.'</a></li>';
                } else
                {
                    if (!$menu->container) $menu->container="div.page";
                    $ul.='<li><a type="button" class="mx- '.$menu->class.'" href="javascript:void(0);" mx-container="'.$menu->container.'"  mx-click="'.$menu->url.'" >'.$menu->icon.' '.$menu->name.'</a></li>';
                            
                }
            }
        }
        $ul.= "</ul>";
        return $ul;
	}    
    
    public function prepareAdminMenu($menus=array())
    {
        $this->admin_menus = $this->controller->model->getMenuBranch('admin',$menus);
        //die('admin:'.json_encode($menus));
    }

    public function renderAdminMenu($template='',$include=array())
    {
        if ($template)
        {
            $admin_menu = str_replace('%menu%',$this->createUlLiFromMenu($this->admin_menus->url,1,$include),str_replace('%name%',$this->admin_menus->getName(),$template));
        } else
        {
            $admin_menu = $this->admin_menus->getName().$this->createUlLiFromMenu($this->admin_menus->url,1,$include);
        }
        echo $admin_menu;
    }

    public function initializeSlider($name,$delay=5)
    {
        $htm='
        <link rel="stylesheet" type="text/css" href="'.$paths->base_url.'/js/slider/jquery.bxslider.css"  />
        <script type="text/javascript" src="'.$paths->base_url.'/js/slider/jquery.bxslider.min.js"></script>
        <script type="text/javascript">
        $(document).ready(function()
        {
            $('."'".$name."'".').bxSlider(
            {
                auto: true,
                autoControls: true,
                pause: '.$delay.'000
            });
        });
        
        </script>';
        echo $htm;
    } 
    
    function initializeDatetimePicker()
    {
        $htm='<script type="text/javascript">
                var timeout;
                function refreshDatePicker()
                {
                    $("body").find("input.date").each( function( index )
                    {
                        $(this).datepicker({changeMonth: true,changeYear: true, yearRange: "-100:+0", dateFormat: "yy-mm-dd"});
                    });
                    /*$("body").find("input.datetime").each( function( index )
                    {
                        $(this).datetimepicker();
                    });*/
                }
                $(document).ready(function() 
                {               
                    refreshDatePicker();
                });'."\r\n".'</script>';
            echo $htm;
    }

    
	public function generateDivPopupBottomSticky()
	{
		$script = "<script >
        
                    $.fn.popup = function() 
                    {
                        jLinks = $('<span class=".'"popup-open menu_open32 height_32"'."style=".'"display:block"'." ></span>');
                        $(this).before(jLinks);
                        
                        jHide = $('<span class=".'"popup-close hide32 height_32 "'."style=".'"display:none"'." ></span>');
                        $(this).after(jHide);
                        
                        $(this).addClass('footer');
                        
                        $(this).hide(400);
                        jDiv = $(this);
                        jLinks.click( function()
                        {
                            jLinks.css('display','none');
                            /*jDiv.show(400);*/
                            jDiv.css('display','block');
                            jHide.css('display','block');
                            
                        });
                        
                        jHide.click( function()
                        {
                            jHide.css('display','none');
                            /*jDiv.hide(400);*/
                            jDiv.css('display','none');
                            jLinks.css('display','block');
                            
                        });                        
                        
                    }
                    $(function() 
                    {
                        $('div.important_links').popup();
                    });
                   </script>";
        return $script;
    }

    public function generateTableOfContents()
    {
        $id = 'id="'.$this->controller->model->getId().'"';
        
        $ul_class = 'class="toc"'; 
        
        $htm = '<div class="toc" style="text-align:center;horizontal-align:center">';
        
        $htm.='<div class="toc_title" >Table of contents</div> ';
        $htm.='<table class="odd_even_row_alternate_color toc round-corners" style="width:60%;list-style:none; margin-left:auto; margin-right:auto"'.$id.' '.$ul_class.'>';

        $li_class = 'class="toc_elements"';
        $li_class_selected = 'class="toc selected"';
        
        $cnt = 0;;
        $htm.='<thead><tr><th>Title</th><th></th><th>Page</th></tr></thead>';
        $htm.='<tbody>';
        foreach ($this->controller->model->getMenus() as $menu)
        {
			if ($menu->getAction()!=MenuElement::ACTION_ADDPAGE)
			{
				$htm.='<tr>';
				$link = $path.$this->controller->click($menu);
				$htm .= '<td style="text-align:left"  id="li_'.$menu->getId().'" '.$li_class.'> <a  href="'.$link.'">';
				$htm .= $menu->getName().'</a></td><td style="text-align:right"'.' '.$li_class.'><span>.............................................................</span></td><td  '.$li_class.'><a href="'.$link.'">'.$menu->getPage().'</a></td></tr>';
			}
            $cnt = $cnt + 1;
        }
        $htm .= '</tbody></table></div>';
        echo $htm;
    }

    public function generateUserGreeting($edit_user_path='',$template='',$user_name='')
    {
        if (!$edit_user_path)
        {
            $edit_user_path = $this->paths->admin."/Users.dir/Edit User.hid.rest.php";
        }
        if ($this->controller->role->isUserLoggedIn()) //means logged in
        {
			if ($user_name) 
			{
				$name=$user_name;
			}
			else
			{
				$name = $_SESSION['user_name']; 
			}
			$close="<button id='hanger_greeting_close' style='border:0px solid white;background-color:transparent;margin-top:0.2em;vertical-align:middle'><i class='fa fa-close'></i></button>";
			if ($template)
			{
				$out1=str_replace('%close_button%',$close,$template);
				$out2=str_replace('%name%',$name,$out1);
				$out3=str_replace('%edit_user_path%',$edit_user_path,$out2);
				echo $out3;
			}
			else
			{
				echo "<div class='hanger_greeting' ><label class='user_greeting' ><a class='mx-' mx-container='div.page' mx-click='?command=display_rest&path=".$edit_user_path."&id=".$_SESSION['user_id']."&object=User' >".$name."</a></label> $close</div>";
			}
            echo '<script>$( function () { $("#hanger_greeting_close").click( function() { $("div.hanger_greeting").hide();}); });</script>';
        }
    }

	public function generatePage($name)
    {
        $this->display($name);
        
    }
    public function generateHomeButton($text_color,$height,$icon_only=false)
    {
		if ($height<32) $height=''; 
		if ($icon_only)
		{
			echo "<a style='color:$text_color;height:$height' class='home$height height_$height simple-button' href='".$this->controller->model->getHome()."?command=home&clearcache=zzzz'>&nbsp;</a>";
		} else
		{
			echo "<a style='color:$text_color;height:$height' class='home$height height_$height simple-button' href='".$this->controller->model->getHome()."?command=home&clearcache=zzzz'>Home</a>";
		}
    }

    public function getHomeButton($text_color,$height,$icon_only=false)
    {
        $html='';
		if ($height<32) $height=''; 
		if ($icon_only)
		{
			$html= "<a style='color:$text_color;height:$height' class='home$height height_$height simple-button' href='".$this->controller->model->getHome()."?command=home&clearcache=zzzz'>&nbsp;</a>";
		} else
		{
			$html= "<a style='color:$text_color;height:$height' class='home$height height_$height simple-button' href='".$this->controller->model->getHome()."?command=home&clearcache=zzzz'>Home</a>";
		}
        return $html;
    }

    public function generateLoginButton($text_color,$height,$icon_only=false)
    {
		if ($height<32) $height=''; 
        if (!$this->controller->role->isUserLoggedIn())
        {
			if ($icon_only)
			{
				echo "<a style='color:$text_color;height:$height' class='login$height height_$height simple-button' onclick='showLogin()' >&nbsp;</a>";
			} else
			{
				echo "<a style='color:$text_color;height:$height' class='login$height height_$height simple-button' onclick='showLogin()' >In</a>";
			}
        }
    }
    
    public function getLoginButton($text_color,$height,$icon_only=false)
    {
        $html='';
		if ($height<32) $height=''; 
        if (!$this->controller->role->isUserLoggedIn())
        {
			if ($icon_only)
			{
				$html= "<a style='color:$text_color;height:$height' class='login$height height_$height simple-button' onclick='showLogin()' >&nbsp;</a>";
			} else
			{
				$html= "<a style='color:$text_color;height:$height' class='login$height height_$height simple-button' onclick='showLogin()' >In</a>";
			}
        }
        return $html;
    }    
    
    public function generateSiteMapButton($text_color,$height,$icon_only=false)
    {
		if ($height<32) $height=''; 
		if ($icon_only)
		{
			echo "<a style='color:$text_color;height:$height' class='sitemap$height height_$height simple-button' onclick='showSiteMap()' >&nbsp;</a>";
		} else
		{
			echo "<a style='color:$text_color;height:$height' class='sitemap$height height_$height simple-button' onclick='showSiteMap()' >Map</a>";
		}
    }    

    public function generateLogoutButton($text_color,$height,$icon_only=false)
    {
		if ($height<32) $height=''; 
        if ($this->controller->role->isUserLoggedIn())
        {
			if ($icon_only)
			{
				echo "<a style='color:$text_color;height:$height' class='logout$height height_$height simple-button' href='?command=logout' >&nbsp;</a>";
			} else
			{
				echo "<a style='color:$text_color;height:$height' class='logout$height height_$height simple-button' href='?command=logout' >Out</a>";
			}
        }
    }
    
    public function getLogoutButton($text_color,$height,$icon_only=false)
    {
        $html='';
		if ($height<32) $height=''; 
        if ($this->controller->role->isUserLoggedIn())
        {
			if ($icon_only)
			{
				$html= "<a style='color:$text_color;height:$height' class='logout$height height_$height simple-button' href='?command=logout' >&nbsp;</a>";
			} else
			{
				$html= "<a style='color:$text_color;height:$height' class='logout$height height_$height simple-button' href='?command=logout' >Out</a>";
			}
        }
        return $html;
    }    
    
    public function generateRegisterButton($text_color,$height,$class='simple-button')
    {
        echo "<button style='vertical-align:center' class='register$height $class height_$height round-corners' onclick='showRegister()' >Register</button>";
    }  
    
    public function generateSearchButton($text_color,$height,$icon_only=false,$class='simple-button')
    {
		if ($icon_only)
		{
			echo "<a style='color:$text_color;height:$height' class='search$height $class round-corners height_$height ' onclick='showSearch()' >&nbsp;</a>";
		} else
		{
			echo "<a style='color:$text_color;height:$height' class='search$height simple-button round-corners height_$height ' onclick='showSearch()' >Search</a>";
		}
    }   
    
    public function getSearchButton($text_color,$height,$icon_only=false,$class='simple-button')
    {
        $html='';
		if ($icon_only)
		{
			$html= "<a style='color:$text_color;height:$height' class='search$height $class round-corners height_$height ' onclick='showSearch()' >&nbsp;</a>";
		} else
		{
			$html= "<a style='color:$text_color;height:$height' class='search$height simple-button round-corners height_$height ' onclick='showSearch()' >Search</a>";
		}
        return $html;
    }   
       

	public function getUserIcon($text_color,$edit_base_path)
	{
		if ($this->controller->role->isUserLoggedIn())
		{
			$id=$_SESSION['user_id'];
            $user = new User();
            $user->find(array('id'=>$id));
            $key=urlencode($user->getSecurekey());
            $image_path = $user->getImageIcon();
            $edit_user_path = $this->paths->admin."/Users.dir&path=Edit User.hid.rest.php";
            if (file_exists($image_path))
            {
				echo "<div class='user_icon' '><a class='mx-' mx-container='div.page' mx-click='?command=display_php&base_path=".$edit_user_path."&id=$id&object=User' href='?command=display_php&base_path=".$edit_user_path."&id=$id&object=User' ><img class='profile_icon' src='img/displaypng.php?image=$image_path&id=$id&key=$key'></img></a></div>";
			} else
			{
				$this->generateUserGreeting("$text_color");
			}
        }
	}
    
	public function getUserGreetingIcon($text_color,$edit_base_path)
	{
		if ($this->controller->role->isUserLoggedIn())
		{
			$id=$_SESSION['user_id'];
            $user = new User();
            $user->find(array('id'=>$id));
            $key=urlencode($user->getSecurekey());
            $image_path = $user->getImageIcon();
            $name = ucfirst($user->getFirstname()[0]).' '.ucfirst($user->getLastname());
            if (file_exists($image_path))
            {
				echo "<div class='user_icon' '><a class='user_greeting_icon' href='?command=display_php&base_path=$edit_base_path&path=Edit User.php&id=$id&object=User' ><img class='profile_icon' width='38' src='img/displaypng.php?image=$image_path&id=$id&key=$key'></img><label style='font-size:12px' class='user_greeting_label' >$name</label></a></div>";
			} else
			{
				$this->generateUserGreeting("$text_color");
			}
        }
	}    
    
    public function generateLoginForm($id='user_login',$check_field='email',$attributes="style='display:block'",$text_color='white',$div_class='login_form hanger_header',$registration='',$forgot_password_enabled=TRUE,$user_icon='',$password_icon='',$title="Security Check")
    {
        $name = '';
        $path = '';
        $page = '';
        $selected = '0';

        
                
		echo "<div id='$id'  class='$div_class' title='Security Check' style='display:none;' ".$attributes." >";
        echo '<div class="'.$div_class.'" ><label class="title" >'.$title.'</label><button type="button" class="user_login" onclick="hideLogin()" style=""><li class="fa fa-close"></li></button></div>';
		echo "<form id='login_form' action='".$this->paths->base_url."/login' class='form round-corners-5px' method='post' >";
		echo '<input type="hidden" name="'.$this->security->getLoggedOutCsrfName().'" value="'.$this->security->getLoggedOutCsrfValue().'" />';
		echo '<input id="target_url_id" type="hidden" class="target_url_class" name="target_url" value="" />';
        echo '<div class="login_form_input_div" style="white-space:nowrap">'.$user_icon.'<input id="user" type="text" style="position:relative;" class="user_name" required="required" name="'.$check_field.'" placeHolder="'.$check_field.'" /><a id="clear_field" href="#" style="color:gray"> <i class="fa fa-times"></i></a></div>';
		echo '<div id="password_container" class="login_form_input_div" style="white-space:nowrap">'.$password_icon.'<input id="password" type="password" style="position:relative;" class="password" required="required" name="password" placeHolder="password" /><a id="show_password" href="#" style="color:gray"> <i class="fa fa-eye"></i></a></div>';
        
        if ($forgot_password_enabled)
		{
			echo '<div class="login_form_button_div" ><button id="login" type="submit" class="button round-corners" value="login" ><li class="fa fa-sign-in"></li> Login</button> <button id="forgot_password" type="button" style="float:right" class="button round-corners " value="Forgot"><li class="fa fa-question"></li> Forgot</button>';
		} else
        {
            echo '<div class="login_form_button_div" ><button id="login" type="submit" class="button round-corners" value="login" ><li class="fa fa-sign-in"></li> Login</button>';
        }
		if ($registration)
		{
			echo '<button style="vertical-align:center;" class="button round-corners mx-" mx-container="div.page" mx-click="?command=display_rest&path='.$registration.'" value="Signup" type="button" mx-next="hideLogin()" ><li class="fa fa-user-plus"></li> Register</button>';
		}
        if (defined('ENABLE_TEST_DRIVE') && ENABLE_TEST_DRIVE)
        {
            echo '<a id="test_drive" style="vertical-align:center;float:right" class="" onclick="setTestDrive()" type="button"><li class="fa fa-user-plus"></li> Test Drive</a>';
            echo '<button id="send_test_drive" style="vertical-align:center;float:right;display:none" class="mx- button" mx-container="div.page" mx-click="?command=testDrive&object=User" type="button"><li class="fa fa-user-plus"></li> Send Test</button>';
        }
		echo '</div>';
        echo '<div class="external_logi">';
        if ((defined("GOOGLE_CLIENT_ID") && GOOGLE_CLIENT_ID) || (defined("FACEBOOK_CLIENT_ID") && FACEBOOK_CLIENT_ID))
        {
			echo '<h3>Or login through:</h3>';
		}
        if (defined("GOOGLE_CLIENT_ID") && GOOGLE_CLIENT_ID)
        {
            echo '<a style="vertical-align:center;" class="button round-corners " href="'.$this->paths->base_url.'/?command=googleLogin&object=User&google=redirect" ><li class="fa fa-google"></li> Google</a>';
        }
        if (defined("FACEBOOK_CLIENT_ID") && FACEBOOK_CLIENT_ID)
        {
            echo '<a style="vertical-align:center;" class="button round-corners " href="'.$this->paths->base_url.'/?command=facebookLogin&object=User&facebook=redirect" ><li class="fa fa-facebook"></li> Facebook</a>';
        }        
        echo '</div>';
        echo '</form>';
		echo '</div>';
		echo "
		<script type='text/javascript'>
		$(function() 
		{
            $('#forgot_password').click( function()
            {
                if ($('#password_container').is(':visible')) 
                {
                    $('#password_container').hide();
                    $('#login').hide();
                    $('#cancel').hide();";
        if (defined('ENABLE_TEST_DRIVE') && ENABLE_TEST_DRIVE)
        {
            echo "
                    $('#send_test_drive').hide();
                    $('#test_drive').hide();";
        }
        echo "
                    $(this).closest('form').find('label.title').html('Forgot Password');
                    $('#forgot_password').val('Send Reset Password');
                } else
                {
                    jForm = $(this).closest('form');
                    jForm.attr('action','?command=forgotPasswordSendLink&object=User');
                    jForm.submit();
                }
            });
            $('#show_password').click( function()
            {
				showPassword();
			});
            $('#clear_field').click( function()
            {
				$('#user').val('');
			});
            
		});
		function showPassword() 
        {
			var x = document.getElementById('password');
			if (x.type === 'password') 
			{
				x.type = 'text';
				setTimeout(showPassword,1500);
			} else 
			{
				x.type = 'password';
			}
		} 
		function showLogin()
		{
            $('#password_container').fadeIn('fast');
            $('#login').fadeIn('fast');
            $('#cancel').fadeIn('fast');
            $('#forgot_password').fadeIn('fast');";
        if (defined('ENABLE_TEST_DRIVE') && ENABLE_TEST_DRIVE)
        {
            echo "
                    $('#send_test_drive').hide();
                    $('#test_drive').fadeIn('fast');";
        }
        echo "
            $('#forgot_password').val('Forgot');
            $('#$id').show(300);
		}
        function showLoginAndGoto(url)
		{
            $('#password_container').fadeIn('fast');
            $('#login').fadeIn('fast');
            $('#cancel').fadeIn('fast');
            $('#target_url_id').val(encodeURI(url));
            $('#forgot_password').val('Forgot');
            $('#$id').show(300);
            //hideSiteMap();";
        if (defined('ENABLE_TEST_DRIVE') && ENABLE_TEST_DRIVE)
        {
            echo "
            $('#test_drive').show();
            $('#send_test_drive').hide();";
        }
        echo "
		}
        function hideLogin()
		{
			$( '#$id' ).hide(300);
		}";
        if (defined('ENABLE_TEST_DRIVE') && ENABLE_TEST_DRIVE)
        {
        echo "
        function setTestDrive()
        {
            alert('We will send to your email temporary credentials to try as ".ENABLE_TEST_DRIVE.". To test as patient simply book an appointment and follow your email.')
            $('#password_container').hide();
            $('#login').hide();
            $('#cancel').hide();
            $(this).closest('form').find('label.title').html('Test Driva as ".ENABLE_TEST_DRIVE."');
            $('#forgot_password').hide();
            $('#test_drive').hide();
            $('#send_test_drive').fadeIn('fast');
        }";
        }
		echo "</script>";
        if ($registration)
        {
            if (file_exists($registration))
            {
            }
            else
            {
                echo "
                <script type='text/javascript'>
                $(function() 
                {
                    alert('Registration is enabled but missing registration form (".$registration.")');
                });
                </script>
                ";
            }
        }
        
    }    


    public function generateNoticeForm($color='white',$class='',$attributes='',$notice_id='tamsi_notice')
    {
        echo "<div id='$notice_id' title='Notice' class='hanger $class' style='display:none;'  $attributes >";
        $this->display('#tamsi_notice',true);          
        echo '</div>';
        
        if ($this->views["#tamsi_notice"]["data"]["message"])
        {
            echo "
            <script type='text/javascript'>
            $(function() 
            {
                $('#$notice_id').show(300);
                setTimeout(function() { hideNotice(); },10000);
                $('#$notice_id').click( function()
				{
					hideNotice();
				});
                /*center($('#$notice_id'));*/
            }); ";
        } else
        {
            echo "
            <script type='text/javascript'>
            $(function() 
            {
            }); ";

        }
        echo "
        function showNotice()
        {
            $( '#$notice_id' ).show(300);
        }
        function hideNotice()
        {
            $( '#$notice_id' ).hide(400);
        }
        </script>";
    }
    
    public function generateLoginAndNotice($config=array())
    {
        $config_dflt=array('login'=>array('id'=>'user_login','check_field'=>'email','attributes'=>"style='display:block'",'color'=>'white','class'=>'login_form hanger_header','registration'=>'','forgot_password'=>TRUE,'user_icon'=>'','password_icon'=>'','title'=>"Security Check"),
                                           'notice'=>array('id'=>'tamsi_notice',$color='white','class'=>'','attributes'=>''));
        if (array_key_exists('login',$config))
        {
            foreach($config['login'] as $field=>$val)
            {
                $config_dflt['login'][$field]=$val;
            }
        }
        if (array_key_exists('notice',$config))
        {
            foreach($config['notice'] as $field=>$val)
            {
                $config_dflt['notice'][$field]=$val;
            }
        }  
        $login=$config_dflt['login'];
        $notice=$config_dflt['notice'];
        $this->generateLoginForm($login['id'],$login['check_field'],$login['attributes'],$login['color'],$login['class'],$login['registration'],$login['forgot_password'],$login['user_icon'],$login['password_icon'],$login['title']);
        $this->generateNoticeForm($notice['color'],$notice['class'],$notice['attributes'],$notice['id']);                                         
    }
        
    
    public function renderMenu($name,$level=0)
    {
        //die('<p>'.json_encode($this->controller->model->getMenus()).'</p>');
        $menus = $this->controller->model->getMenus();
        $menu = $this->controller->model->getMenuBranch($name,$menus);
        if ($level==0) $level=1;
        $menu_htm = $this->createUlLiFromMenu($menu->url,$level,array());
        echo $menu_htm;
    }
    
    public function generateCommandView()
    {
        $viewname = $_REQUEST['command'].$_REQUEST['object'].'.hid.php';
        if (file_exists("usr/view/$viewname"))
        {

			include ("usr/view/$viewname");
        }
    }   
    
    public function getController()
    {
        return $this->controller;
    }
    
    public function includeJs()
    {
		include __DIR__."/TamsiJavascript.php";
	}

    public function initializeScripts()
	{
		echo "\n".
"<script>
	var mathjax_enabled = ".($config['mathjax']?1:0).";
	var speak_enabled = ".($config['speak']?1:0).";
	var chosen_enabled = ".($config['chosen']?1:0).";
	var wysiwyg_enabled = ".($config['wysiwyg']?1:0).";
	var datetimepicker_enabled = ".($config['jquery']?1:0).";\n".'  			 
	$(document).ready( function()
	{
		if (isMobile())
		{
			$("body").addClass("mobile");
		} else
		{
			$("body").addClass("desktop");
		}
		loadActions("");
		$( window ).resize(function() 
		{
			if (isMobile())
			{
				$("body").removeClass("desktop");
				$("body").addClass("mobile");
			} else
			{
				$("body").removeClass("mobile");
				$("body").addClass("desktop");
			}
		});		 
     });
</script>';
		$this->includeJs();
    }


}
