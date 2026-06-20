<?php 

require_once 'TamsiSecurity.php';
require_once 'security.cfg.php';
require_once __DIR__.'/../../lib/vendor/autoload.php';
require_once 'TamsiImage.php';
require_once 'Ticket.php';
require_once 'Next.php';
class TamsiTicketsController 
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
    
    protected function getPost($ticket)
    {
        
        if (array_key_exists("status",$_POST))  $ticket->setStatus($_POST["status"]);
        if (array_key_exists("user_id_submitter",$_POST))  $ticket->setUserIdSubmitter($_POST["user_id_submitter"]);
        if (array_key_exists("description",$_POST))  $ticket->setDescription($_POST["description"]);
;
        return $ticket;
    }
        
    protected function getPostNew($ticket)
    {
        return $this->getPost($ticket);
    }
        
    public function create()
    {
        $response = $this->validate();
        if ($response['success'])
        {
            $ticket= new Ticket();
            $ticket = $this->getPostNew($ticket);
            $response = $this->save($ticket);
        }
        
        return $response;
    }

    
    protected function getTicket()
    {
        $id = $_REQUEST['id'];
		$ticket= new Ticket();
		$ticket = $ticket->find(array('id'=>$id));
        return $ticket;
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
            $ticket = $this->getTicket();
            if (is_object($ticket) && ($ticket->getId()))
            {
                $ticket=$this->getPost($ticket);
                $response = $this->save($ticket);
            }
            else
            {
                $message='Cannot find $ticket! '.$_SESSION['tamsi_notice'];
                $success=false;
                $response = array('message'=>$message,'success'=>$success);
            }
        }
        return $response;
    }
    
    
    protected function remove($ticket)
    {
        if ($ticket->getId()>0)
        {
            if ($ticket->delete()==1)
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
            $message='Cannot find ticket with id='.$id;
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
            $ticket = $this->getTicket();
            $response=$this->remove($ticket);
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
            $next->url = '?command=display_rest&path=Admin.dir/Tickets.dir/Edit Ticket.hid.rest.php&id='.$response['id'];
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

    public function uploadImageAction()
    {
		$ticket = $this->getTicket();;
        $this->uploadImage($ticket);
        if (isset($_POST['redirect']) && $_POST['redirect'])
        {
            $url = $_POST['redirect'];
            header('Location: '.$url);
            exit();
        }        
    }
    
    public function generateImageName($ticket,$folder)
    {
        $name = str_replace(',','_',str_replace(' ','',strip_tags($ticket->getId().'_tickets')));
        $name=strtolower($name).($folder?'_'.$folder:'');    
        return $name;
    }
    
    protected function uploadImage($ticket,$folder='',$force_png=TRUE,$max_width=480)
    {
        $current_image = $ticket->getImage($folder);
        if (!(strpos($current_image,'none.png')>0))  unlink($current_image);
        if (!$folder) 
        {
            $current_icon_image = $ticket->getImageIcon($folder);
            if (!(strpos($current_icon_image,'none_icon.png')>0))  unlink($current_icon_image);
        }
        $filename = $this->generateImageName($ticket,$folder);
        
        $paths = new Paths();
        $ext = strtolower(strrchr($_FILES["file"]["name"], '.'));
        $image = new TamsiImage();
        $image->setFilename($filename.$ext);
        $image->setPath($paths->user_image."tickets/");
        
        if ($image->save())
        {
            $method='';
            if ($max_width && ($image->getWidth() > $max_width))
            {
                $image->setWidth(480);
                $method='force_width';
            }
            if (($ext!='.png')&&($force_png)&&($ext!='.svg')||($method)) //allow only png and svg
            {
                $image->resize($method); //all image must be stored as png
                $ext='.png';
            }
            if (!$folder) $this->createIcon($image,$filename);
            $_SESSION['tamsi_notice'].='Successfully uploaded image';
            return $filename.$ext;
        } else
        {
            $_SESSION['tamsi_notice'].='Cannot save image!';
            return false;
        }
        
    }
    
    protected function createIcon($image,$name)
    {
        $image->createIcon('64','64',$name.'_icon.png');    
    }



    public function save($ticket)
    {
        if ($ticket->save()>0)
        {

           $message="Successfully saved ".$ticket->getName();
           $success=true;
           
        } else
        {
           $message='SQL Error! '.$_SESSION['tamsi_notice'];
           $success=false;
        }
        
        $response = array('message'=>$message,'success'=>$success,'id'=>$ticket->getId());    
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
                $ticket = new Ticket();
                $filter=array('id'=>$delete_selected);
                $ret = $ticket->deleteAll($filter);
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

