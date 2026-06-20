<?php
require_once 'paths.cfg.php';
require_once 'Config.php';
require_once 'Role.php';
class CoreView
{
    public $views;
    public $paths;

    public function __construct($controller)
    {
        $this->views = $controller->views;
        $this->paths = new Paths();
        if ($controller->is_ajax)
        {
            $this->display($controller->container,FALSE,FALSE);
            exit();
        }
        if ($controller->redirect)
        {
            $controller->createView();
            header("Location: ".$controller->redirect);
            exit();
        }

    }

    public function display($name,$volatile=FALSE,$load_request_data=TRUE)
    {
        $config = new Config(); 
        $role = new Role();
        $views = $this->views;
        if (is_array($this->views) && array_key_exists($name,$this->views))
        {
            $view = $this->views[$name];
        } else 
        {
            $view = array('path'=>'','data'=>array('name'=>$name,'views'=>$this->views));
            $view['path'] = $this->paths->app_path.'/app/view/error_missing.php';
        }
        
        
        //echo '<!--'.json_encode($_SESSION[PROJECT.'_containers']).' -->';
        if (array_key_exists('redirect',$view) && $view['redirect'])
        {
            header("Location: ".$view['redirect']);
            exit();
            
        } else if (array_key_exists('htm',$view) && $view['htm']) 
        {
            echo $view['htm'];
        }
        else if ( array_key_exists('path',$view) && ($view['path']))
        {
            if (file_exists($view['path']))
            {
                foreach($view['data'] as $field=>$value)
                {
                    ${$field}=$value;
                }
                $path = $view['path'];
                if ($load_request_data) $_REQUEST = $view['request_data'];
                if ($volatile) 
                {
                    if (isset($_SESSION[PROJECT.'_containers']) && (array_key_exists($name, $_SESSION[PROJECT.'_containers'])))
                    {
                        $_SESSION[PROJECT.'_containers'][$name]=array();
                    }
                }
                
                echo '<!-- '.json_encode($name).':'.$path.' -->';
                $view = $this;
                include $path;
            } else 
            {
                $data = $view;
                include $this->paths->app_path.'/app/view/error_not_found.php';
            }
        }
    } 
}
