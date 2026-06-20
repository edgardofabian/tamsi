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
 * @package CoreController 
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
require_once 'database.cfg.php';
require_once 'FileProperties.php';
require_once 'Role.php';

require_once 'paths.cfg.php';
require_once 'Config.php';
require_once 'User.php';
require_once 'Security.php';
require_once 'Controller.php';
require_once 'generate.php';

require_once 'views.cfg.php';

class CoreController
{
	public $model;
    protected $config_paths;
    public $role;
    public $security;
    public $user;
    public $container;
    public $command;
    public $path;
    public $base_path;
    public $views;
    public $is_ajax;
    public $is_refresh;
    public $redirect;
    public $request_data;
    public $usersController;
    
	public function __construct()
	{
        $this->config_paths = new Paths();
        $this->role = new Role();
        
        $this->user = new User();
        
        $this->security = new Security();
        $this->command = 'home';
        $this->is_ajax = FALSE;
        $this->is_refresh = FALSE;
        $this->views = array();
        $this->container = MAIN_CONTAINER;
        $views_config = new Views();
        $this->views = $views_config->views;
        
        $this->restoreViews();
        
        //die(json_encode($this->views));
        
        $this->usersController = new UsersController($this->views);
        
        $this->redirect = '';
	}
	
	public function updateViews($views)
	{
		foreach($views as $vname=>$view)
		{
			foreach($view as $field=>$value)
			{
				$this->views[$vname][$field]=$value;
			}
		}
        $this->saveViews();
	}
    
    public function saveViews()
    {
        $views = $this->views;
        foreach($views as $vname=>$view)
        {
            $data = $view['data'];
            if (is_array($data))
            {
                foreach($data as $name=>$item)
                {
                    if (is_object($item))
                    {
                        if (method_exists($item,'getId')) //db item
                        {
                            $data[$name]=array('type'=>'obj_db','object'=>get_class($item),'id'=>$item->getId());
                            //if ($name=='task') die($data[$name]);
                        } else if (method_exists($item,'renderId')) //view item
                        {
                            $properties=array();
                            foreach($item as $key=>$value)
                            {
                                if (is_object($value))
                                {
                                    if (method_exists($value,'getId')) //db item
                                    {
                                        $properties[$key]==array('type'=>'obj_db','object'=>get_class($value),'id'=>$value->getId());
                                    } else 
                                    {
                                        $properties[$key]=array('type'=>'obj','object'=>get_class($value),'item'=>$value);
                                    }
                                } else if (is_array($value))
                                {
                                    $val_1=false;
                                    foreach($value as $k=>$v)
                                    {
                                        $val_1 = $v;
                                        break;
                                    }
                                    if (is_object($val_1) && method_exists($val_1,'getId'))
                                    {
                                        $ids = array();
                                        foreach($value as $val_obj)
                                        {
                                            $ids[] = $val_obj->getId();
                                        }
                                    }
                                    $properties[$key]=array('type'=>'objs_db','object'=>get_class($val_1),'ids'=>$ids);
                                } else
                                {
                                    $properties[$key]=$value;
                                }
                            }
                            $data[$name]=array('type'=>'obj_view','object'=>get_class($item),'properties'=>$properties);
                        } else
                        {
                            $data[$name]=array('type'=>'obj','object'=>get_class($item),'item'=>$item);
                        }
                        
                    } else if (is_array($item))
                    {
                        if (count($item)>0)
                        {
                            $item_1=false;
                            foreach($item as $k=>$v)
                            {
                                $item_1 = $v;
                                break;
                            }
                            if (is_object($item_1))
                            {
                                if (method_exists($item_1,'getId'))
                                {
                                    $ids = array();
                                    foreach($item as $item_obj)
                                    {
                                        $ids[] = $item_obj->getId();
                                    }
                                    $data[$name]=array('type'=>'objs_db','object'=>get_class($item_1),'ids'=>$ids);
                                } else
                                {
                                    $data[$name]=array('type'=>'objs_array','object'=>get_class($item_1),'items'=>$item);
                                }
                            }
                            else 
                            {
                                $data[$name]=array('type'=>'array','items'=>$item);
                            }
                        } else 
                        {
                            $data[$name]=array('type'=>'array','items'=>array());
                        }
                        
                    } else
                    {
                        $data[$name]=$item;
                    }
                }
            } 
            $view['data']=json_encode($data);
            $views[$vname]=$view;
        }
        $_SESSION[PROJECT.'_containers']=$views;
    }
    
    public function restoreViews()
    {
        if (isset($_SESSION[PROJECT.'_containers']) && ($_SESSION[PROJECT.'_containers']) )
        {
            $views = $_SESSION[PROJECT.'_containers'];
            foreach($views as $vname=>$view)
            {
                $data = array();
                if (array_key_exists('data',$view))
                {
                    $data = json_decode($view['data'],TRUE);
                }
                if (is_array($data))
                {
                    foreach($data as $name=>$item)
                    {
                        if (is_array($item))
                        {
                            if ($item['type']=='obj_db')
                            {
                                $obj_name = $item['object'];
                                require_once $obj_name.'.php';
                                $obj = new $obj_name();
                                if ($item['id'])
                                {
                                    $obj->get(array('filter'=>array('id'=>$item['id'])));
                                }
                                $data[$name]=$obj;
                            } else if ($item['type']=='objs_db')
                            {
                                $obj_name = $item['object'];
                                require_once $obj_name.'.php';
                                $obj = new $obj_name();
                                $objs = array();
                                if ($item['ids'])
                                {
                                    $objs = $obj->getAll(array('filter'=>array('id'=>$item['ids'])));
                                }
                                $data[$name]=$objs;
                                
                            } else if ($item['type']=='obj_view')
                            {
                                
                                $obj_name = $item['object'];
                                require_once $obj_name.'.php';
                                $view_obj = new $obj_name();
                                $properties=$item['properties'];
                                foreach($properties as $key=>$value)
                                {
                                    if ($value['type']=='obj_db')
                                    {
                                        $obj_name = $value['object'];
                                        require_once $obj_name.'.php';
                                        $obj = new $obj_name();
                                        if ($value['id'])
                                        {
                                            $obj->get(array('filter'=>array('id'=>$value['id'])));
                                        }
                                        $view_obj->$key=$obj;
                                    } else if ($value['type']=='objs_db')
                                    {
                                        $obj_name = $item['object'];
                                        require_once $obj_name.'.php';
                                        $obj = new $obj_name();
                                        $objs = array();
                                        if ($value['ids'])
                                        {
                                            $objs = $obj->getAll(array('filter'=>array('id'=>$value['ids'])));
                                        }
                                        $view_obj->$key=$objs;
                                        
                                    } else if ($value['type']=='obj')
                                    {
                                        
                                        $obj_name = $value['object'];
                                        require_once $obj_name.'.php';
                                        $obj = new $obj_name();
                                        foreach($value['item'] as $k=>$v)
                                        {
                                            $obj->$k = $v;
                                        }
                                        $view_obj->$key=$obj;
                                    }
                                    else
                                    {
                                        $view_obj->key=$value;
                                    }
                                }
                                $data[$name]=$view_obj;
                            } else if ($item['type']=='obj')
                            {
                                
                                $obj_name = $item['object'];
                                require_once $obj_name.'.php';
                                $obj = new $obj_name();
                                foreach($item['item'] as $key=>$value)
                                {
                                    $obj->$key = $value;
                                }
                                $data[$name]=$obj;
                            } else $data[$name]=$item;
                            
                        } else $data[$name]=$item;
                    }
                } 
                $view['data']=$data;
                $views[$vname]=$view;
            }
            $this->views = $views;  
        }
              
    }
    
    public function createView()
    {
        $path = $this->path;
        if ($this->base_path)
        {
            //die($base_path.'/'.$path);
            $path=$this->base_path.'/'.$this->path;
        } 
        if ($this->path && $this->container)
        {        
			if (!$this->is_allowed_dir($path))
			{
				$this->views['#tamsi_notice']=array('message'=>$path.': Requires login to view!');
				$this->saveViews();
				return FALSE;
			} else
			{
				if (array_key_exists($this->container,$this->views))
				{
                    $this->views[$this->container]['htm']='';
					$this->views[$this->container]['path']=$path;
                    $this->views[$this->container]['request_data']=$this->request_data;
				} else $this->views[$this->container] = array('path'=>$path,'data'=>array(),'request_data'=>$this->request_data);
				$this->saveViews();
				return TRUE;
			}        
		} else return FALSE;
    }        
    
    public function action($command)
    {
        if ((isset($_REQUEST['clearcache']))||(isset($_REQUEST['clear'])))
        {
            $views_config = new Views();
            $this->views=$views_config->views;       
            $this->command='home';
            $_SESSION['redirect_after_login']='';
            unset($_SESSION[PROJECT.'_containers']);
            unset($_SESSION['site_map']);
            $this->usersController->logoutAction();
            header("Location:?command=home");
            exit();            
        } 
 
        if (( $_SERVER['REQUEST_METHOD'] == 'POST' ) || ($command == 'logout' ) || ($command == 'display_php' ) || ($command == 'home' ) ) 
        {
            if (($command) && ($command!='home')) 
            {
                
            }
            else
            {
                $_SESSION['tamsi_notice']='';
                $this->base_path='';
                $this->path='';
            }
            //die($command);

            
            if (isset($_REQUEST['selected']))
            {
                $selected = $_REQUEST['selected'];
            } else
            {
                $selected = 0;
            }
            
            if ($command!='login') $_SESSION['redirect_after_login']='';

            if ($command == 'renderSiteMap')
            {
                unset($_SESSION['site_map']);
            }
            else if ($command=='home')
            {
                $views_config = new Views();
                $this->views=$views_config->views;   
                $this->createView();                  
            } else if ($command=='display_rest')
            {
				//die('here');
                if (((!$_SESSION['user_id']) && (strpos($this->path,'.pub')===FALSE)) || ($_SESSION['user_id'] && (!$this->security->checkCsrf())))
                {
                    $this->role->showUnauthorizedMessage();
                    echo $this->path;
                    exit();
                }
                $this->createView();
            } else if (($command=='display_php') || ($command=='redirect')) 
            {
                if ((!$_SESSION['user_id'])&& (strpos($this->path,'.pub')===FALSE))
                {
                    if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
                    {
                        $this->role->showUnauthorizedMessage();
                        exit();
                    } else
                    {
                        $this->views['#tamsi_notice']=array('message'=>'Requires login to view!<script>$( function() { showLogin(); });</script>');
                        if ($command=='display_php') //links provider prior to login
                        {
                            $_SESSION['redirect_after_login']='https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                        }
                        $this->base_path='';
                        $this->path='';
                        $this->command='home';                        
                    }
                }
                $this->createView();
                
            } else if ($command === 'login')
            {
                if (!$this->security->checkLoggedOutCsrf())
                {
                    $this->views['#tamsi_notice']=array('htm'=>'Security Error! Please submit only through this site!');
                }
                $_SESSION['site_map']='';
                $view = $this->usersController->loginAction('email');  
                $this->updateViews($view);
                /*if ($view) 
                {
                    if (array_key_exists('target_url',$_REQUEST) && $_REQUEST['target_url'])
                    {
                        $this->path=$_REQUEST['target_url'];
                    } else
                    {
                        $this->path=$paths->home;
                    }
                    if ($_SESSION['redirect_after_login'])
                    {
                        $this->createView();
                        header("Location: ".$_SESSION['redirect_after_login']);
                        $_SESSION['redirect_after_login']='';
                        exit;
                    }
                    $this->command='display_php';
                }*/
                //die(json_encode($this->views));
            } else if ($command === 'forgotPasswordSendLink')
            {
                if (!$this->security->checkLoggedOutCsrf())
                {
                    $this->views['#tamsi_notice']=array('htm'=>'Security Error! Please submit only through this site!');
                    $this->createView();
                    header("Location: ?command=home");
                    exit();
                }
                $user = $this->usersController->forgotPasswordSendLinkAction();            
            } else if (($command === 'register')||($command === 'registerRest'))
            {
                $_SESSION['site_map']='';
                if (!$this->security->checkLoggedOutCsrf())
                {
                    $this->views['#tamsi_notice']=array('htm'=>'Security Error! Please submit only through this site!');
                    $this->createView();
                    header("Location: ?command=home");
                    exit();
                }
                $user = $this->usersController->registerRestAction();            
            } else if ($command === 'logout')
            {
                $_SESSION['site_map']=''; //clear site map
                $view = $this->usersController->logoutAction();   
                $this->updateViews($view);        
            } else if ($_REQUEST['object'])
            {
                $objectName = $_REQUEST['object'];
                $objectController = generateTableNameFromElement($objectName).'Controller';
                require_once $objectController.'.php';
                
                
                
                if (class_exists($objectController))
                {
                    $object = new  $objectController($this->views);
                
                    if (isset($_REQUEST['EXTERNAL_API']) && ($_REQUEST['EXTERNAL_API']))
                    {
                        $commandAction =  $command.'ExternalApi';
                    }
                    else
                    {
                        if (!$this->security->checkCsrf())
                        {
                            if (array_key_exists('HTTP_X_REQUESTED_WITH',$_SERVER) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest')
                            {
                                $this->createView();
                                $response = array('message'=>'Please login. Your session has timed out!<script>$( function() { showLogin(); });</script>','success'=>false);
                                header('Content-Type: application/json');
                                echo json_encode($response);
                                exit();
                            }
                            else
                            {
                                $this->views['#tamsi_notice']=array('htm'=>'Please login. Your session has timed out!<script>$( function() { showLogin(); });</script>');
                                $this->createView();
                                header("Location:?command=home");
                                exit();
                            }
                        }
                        $commandAction = $_REQUEST['command'].'Action';
                        
                    }
                    if (method_exists($object,$commandAction))
                    {
                        $views = $object->$commandAction();
                        $this->updateViews($views);
                    } else
                    {
                       $response = array('message'=>"Method Unsupported!",'success'=>false);
                       header('Content-Type: application/json');
                       echo json_encode($response);
                       exit();
                    }

                } else
                {
                   $response = array('message'=>"Object does not exist!",'success'=>false);
                   header('Content-Type: application/json');
                   echo json_encode($response);
                   exit();
                }
            }
        } else
        {
            $success = false;
            $this->command='home';
            if ((array_key_exists('command',$_GET) && $_GET['command']) && (array_key_exists('object',$_GET) && $_GET['object']))
            {
                
                $objectName = $_REQUEST['object'];
                $objectController = generateTableNameFromElement($objectName).'Controller';
                require_once $objectController.'.php';
                
                if (class_exists($objectController))
                {
                    $object = new  $objectController($this->views);
                        
                        
                    $commandAction = strip_tags(trim($_GET['command'])).'ActionGet';
                    
                    if (method_exists($object,$commandAction))
                    {
                        $success = true;
                        $this->command='get';
                        $views=$object->$commandAction();
                        $this->updateViews($views);
                        if (strpos(strtolower($_GET['command']),'login'))
                        {
                            if ($_SESSION['user_id']>0) 
                            {
                                if ($_SESSION['redirect_after_login'])
                                {
                                    header("Location: ".$_SESSION['redirect_after_login']);
                                    $_SESSION['redirect_after_login']='';
                                    exit;
                                }
                            }
                        }
                    } else
                    {
                        if ($this->role->isUserLoggedIn())
                        {
                            $commandAction = strip_tags(trim($_GET['command'])).'Action';
                    
                            if (method_exists($object,$commandAction))
                            {
                                $success = true;
                                $this->command='get';
                                //$views=$object->$commandAction();
                                //$this->updateViews($views);
                            }
                        }
                        $this->views['#tamsi_notice']=array('htm'=>'Method unsupported!');
                        $_SESSION['redirect_after_login']='';
                    }
                }
                
            }
            else if (($_SESSION['user_id']) || ($this->model->getPath() && (!(strpos($this->model->getPath(),'.pub')===FALSE))))
            {
                $this->path=($_SESSION['current_path']);
                $this->command=('display_php');
                $_SESSION['redirect_after_login']='';
                //echo '<p>Here</p>';
            }                
            else
            {                
                $_SESSION['tamsi_notice']='';
                $_SESSION['redirect_after_login']='https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                $this->base_path=('');
                $this->path=('');
                $this->command=('home');            
            }
        }
        $this->request_data = $_REQUEST;
        $this->createView();        
        //die(json_encode($this->views));
    }
   
   
    public function receive()
    {
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
		{
			$this->is_ajax=TRUE;
		} else if (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0')
		{
			$this->is_refresh=TRUE;
		} 
            
        if (isset($_REQUEST['command']))
        {
            $this->command=($_REQUEST['command']);
        } else
        {
            $this->command='';
        }
        
        
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
			if ((isset($_POST['mx-container'])) && (isset($_POST['mx-form'])))
			{
                $this->container = $_POST['mx-container'];
				$data=$_POST;
				$url='';
/*				if (isset($_SERVER['REQUEST_URI'])) 
				{
					$uri=$_SERVER['REQUEST_URI'];
					$url=substr($uri,strpos($uri,'?'));
				}*/
                if (array_key_exists($this->container,$this->views))
                {
                    $this->views[$this->container]['data']=$data;
                } else $this->views[$this->container]=array('path'=>'','data'=>$data);
			}
		} 

        if (isset($_REQUEST['path']))
        {
            $this->path=($_REQUEST['path']);
            $path = $_REQUEST['path'];
            $file_info=new FileProperties($path);
        } else
        {
            $this->path=("");
            $file_info=new FileProperties("");
        }        
        
        if (isset($_REQUEST['base_path']))
        {
            $this->base_path=($_REQUEST['base_path']);
        } else
        {
            $this->base_path=("");
        }        
        
        $this->action($this->command);
    }
    
    
    public function is_allowed_dir($path)
    {
        if ($path)
        {
            $realpath = realpath($path);
        } else
        {
            $realpath = '';
        }   
        
        $base_path= dirname($realpath);
        
		$public = realpath($this->config_paths->public);
        
        if (strpos($base_path,$public)===FALSE) 
        {
			return FALSE; 
		}
        if ((strpos($realpath,'.pub')===FALSE) && (!array_key_exists('user_id',$_SESSION) || !($_SESSION['user_id']>0))) 
        {
			return FALSE;
		}
        return TRUE;       
    }
    
    public function downloadAction()
    {
      require_once 'Role.php'; 
      $allowed=array("student","admin","teacher");
      $role = new Role();
      if ($role->isUserAnyOf($allowed))
      {
         $filename = $_REQUEST['file'];
         // ensure the file exists
         if(isset($filename) && file_exists($filename)){

           // Read the file some.zip
           $file = file_get_contents($filename);
           $names = explode("/",$filename);
           $name = $names[count($names)-1];
           // Set headers to serve the file for download
           header("Content-type: application/octet-stream");
           header("Content-Disposition: attachment; filename=\"$name\""); // name file here
           header('Content-Length: ' . strlen($file)); // length of the file

           // echo the file
           echo $file;
         } else
         {
            echo "file not found";
         }
      }
      else
      {
         echo "<h1>Unauthorized! user_id=".$_SESSION['user_id']."</h1>";
      }   
       
    }

    static function sort_objects_by_rank($a, $b) 
    {
        if ($a->getRank() == $b->getRank())
        { 
            return 0 ; 
        }
        return ($b->getRank() < $a->getRank()) ? -1 : 1;
    }
    

}

?>
