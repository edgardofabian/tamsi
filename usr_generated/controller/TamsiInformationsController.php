<?php 

require_once 'TamsiSecurity.php';
require_once 'security.cfg.php';
require_once __DIR__.'/../../lib/vendor/autoload.php';
require_once 'Information.php';
require_once 'Next.php';
class TamsiInformationsController 
{
    public $views;
	
    public $security;
    
    public function __construct($views)
    {
        $this->views = $views;
        $this->security = new TamsiSecurity();
    }

    

    protected $requirements;
    
    protected function setRequirements($reqs)
    {
        $this->requirements = $reqs;
    }
    
    protected function validate()
    {
        $success=TRUE;
        $message='';
        if (isset($this->requirements))
        {
            foreach($this->requirements as $field=>$req)
            {
                if (in_array('required',$req))
                {
                    if (!((array_key_exists($field,$_REQUEST)) && trim($_REQUEST[$field]))) 
                    {
                        $success = FALSE;
                        $message.= "Missing required $field.\r\n";
                    }
                }
                if (in_array($field,$_POST))
                {
                    if (in_array('email',$req))
                    {
                        if (!filter_var(trim($_POST[$field]), FILTER_VALIDATE_EMAIL))
                        {
                            $success = FALSE;
                            $message.= 'Your $field:'.$_POST[$field]." appears to be invalid email.\r\n";
                        }
                    }
                    if (in_array('numeric',$req))
                    {
                        if (!is_numeric(trim($_POST[$field])))
                        {
                            $success = FALSE;
                            $message.= "$field is not numeric .\r\n";
                        }
                    }            
                }
            }
        }
        
        $response = array('message'=>$message,'success'=>$success);
        
        return $response;
    }
    
    protected function getPost($information)
    {
        
        if (array_key_exists("user_id",$_POST))  $information->setUserId($_POST["user_id"]);
        if (array_key_exists("name",$_POST))  $information->setName($_POST["name"]);
        if (array_key_exists("value",$_POST))  $information->setValue($_POST["value"]);
        if (array_key_exists("token",$_POST))  $information->setToken($_POST["token"]);
        if (array_key_exists("active",$_POST))  $information->setActive($_POST["active"]);
;
        return $information;
    }
        
    protected function getPostNew($information)
    {
        return $this->getPost($information);
    }
        
    public function create()
    {
        $response = $this->validate();
        if ($response['success'])
        {
            $information= new Information();
            $information = $this->getPostNew($information);
            $response = $this->save($information);
        }
        
        return $response;
    }

    
    protected function getInformation()
    {
        $id = $_REQUEST['id'];
		$information= new Information();
		$information = $information->find(array('id'=>$id));
        return $information;
    }
    
    protected function setDefaultIdRequirements()
    {
        if (!isset($this->requirements))
        {
            $this->requirements=array('id'=>array('required'));
        }
        if (!array_key_exists('id',$this->requirements))
        {
            $this->requirements = array_merge($this->requiremenst, array('id'=>array('required')));
        }
    }

    protected function setEditRequirements()
    {
        $this->setDefaultIdRequirements();
    }
    
    protected function setNewRequirements()
    {
        $this->requirements = array();
    }    
    
    protected function edit()
    {
        $response = $this->validate();
        if ($response['success']) 
        {
            $information = $this->getInformation();
            if (is_object($information) && ($information->getId()))
            {
                $information=$this->getPost($information);
                $response = $this->save($information);
            }
            else
            {
                $message='Cannot find $information! '.$_SESSION['tamsi_notice'];
                $success=false;
                $response = array('message'=>$message,'success'=>$success);
            }
        }
        return $response;
    }
    
    
    protected function remove($information)
    {
        if ($information->getId()>0)
        {
            if ($information->delete()==1)
            {
                
                $message='Successfully deleted '.$id;
                $success=true;
            } else
            {
                $message='SQL Error! '.$_SESSION['tamsi_notice'];
                $success=false;
            }
        } else
        {
            $message='Cannot find information with id='.$id;
            $success=false;            
        }
        $response = array('message'=>$message,'success'=>$success);
        return $response;
    }    

    public function delete()     
    {
        $response = $this->validate();
        if ($response['success']) 
        {
            $information = $this->getInformation();
            $response=$this->remove($information);
        }
        return $response;
    }
    
    public function newRestAction()
    {
        $this->setNewRequirements();
        $response = $this->create();
        if (array_key_exists('id',$response))
        {
            $next = new Next();
            $next->url = '?command=display_rest&path=Admin.dir/Informations.dir/Edit Information.hid.rest.php&id='.$response['id'];
            $next->container = 'div.page';
            $response['next']=$next;
        }
        $this->json_response($response);
    }    
    
    public function editRestAction()     
    {
        $this->setEditRequirements();
        $response = $this->edit();
        $this->json_response($response);
    }    

    public function deleteRestAction()     
    {
        $this->setDefaultIdRequirements();
        $response=$this->delete();
        $this->json_response($response);        
    }

    public function newAction()
    {
        $this->setNewRequirements();
        $response = $this->create();
        return $this->bool_response($response);
    }

    public function editAction()     
    {
        $this->setEditRequirements();
        $response = $this->edit();
        return $this->bool_response($response);
    }
    
    public function deleteAction()     
    {
        $this->setDefaultIdRequirements();
        $response = $this->delete();
        return $this->bool_response($response);
    }



    public function save($information)
    {
        if ($information->save()>0)
        {

           $message="Successfully saved ".$information->getName();
           $success=true;
           
        } else
        {
           $message='SQL Error! '.$_SESSION['tamsi_notice'];
           $success=false;
        }
        
        $response = array('message'=>$message,'success'=>$success,'id'=>$information->getId());    
        return $response;
    }

    public function deleteSelectedRestAction()
    {
        $ret = 0;
        $message = 'No id selected.';
        if (array_key_exists('delete_selected',$_POST))
        {
            $delete_selected = $_POST['delete_selected'];

            if (count($delete_selected)>0)
            {
                $information = new Information();
                $filter=array('id'=>$delete_selected);
                $ret = $information->deleteAll($filter);
                $message = $_SESSION['tamsi_notice'];
            }
        }
        $response = array('message'=>$message,'success'=>$ret);
        $this->json_response($response);
    }

    public function json_response($response)
    {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();           
    }
    
    public function bool_response($response)
    {
        $_SESSION['tamsi_notice']=$response['message'];
        return $response['success'];
    }
}

