<?php 

require_once 'database.cfg.php';
require_once 'ModelBase.php';
require_once 'User.php'; 

class TicketProtected extends ModelBase 
{

    //@var int
    protected $id;

    //@var enum
    protected $status;

    //@var int
    protected $user_id_submitter;

    //@var text
    protected $description;

    //@var timestamp
    protected $created;

    public function getImage($folder='')
    {
        $paths = new Paths();
        $name = $this->generateImageName($folder);
        if (file_exists($paths->user_image.'tickets/'.strtolower($name).'.svg'))
        {
            $absolute_path = $paths->user_image.'tickets/'.strtolower($name).'.svg';
        }
        else
        {
            $absolute_path = $paths->user_image.'tickets/'.strtolower($name).'.png';
        }
        if (file_exists($absolute_path))
        {
            return $absolute_path;
        } else
        {
            return $paths->user_image.'tickets/'."none.png";
        }
    }
    public function getImageUrl($folder='')
    {
        $paths = new Paths();
        $name = $this->generateImageName($folder);
        if (file_exists($paths->user_image.'tickets/'.strtolower($name).'.svg'))
        {
            $absolute_path = $paths->user_image.'tickets/'.strtolower($name).'.svg';
        }
        else
        {
            $absolute_path = $paths->user_image.'tickets/'.strtolower($name).'.png';
        }
        $url = str_replace($paths->public,'',$absolute_path);
        return $url;
    }    
    public function getImageIcon($folder='')
    {
        $paths = new Paths();
        $name = $this->generateImageName($folder);
        $absolute_path = $paths->user_image.'tickets/'.strtolower($name).'_icon.png';
        if (file_exists($absolute_path))
        {
            return $absolute_path;
        } else
        {
            return $paths->user_image.'tickets/'."none_icon.png";
        }
    }
    public function getImageIconUrl($folder='')
    {
        $paths = new Paths();
        $name = $this->generateImageName($folder);
        $absolute_path = $paths->user_image.'tickets/'.strtolower($name).'_icon.png';
        $url = str_replace($paths->public,'',$absolute_path);
        return $url;
    }    
    
    public function generateImageName($folder)
    {
        $name = str_replace(',','_',str_replace(' ','',strip_tags($this->getId().'_tickets')));
        $name=strtolower($name).($folder?'_'.$folder:'');    
        return $name;
    }    
    public function setId($id)
    {
       $this->id=$id;
       return $this;
    }
    public function getId()
    {
       return $this->id;
    }

    public function setStatus($status)
    {
       $this->status=$status;
       return $this;
    }
    public function getStatus()
    {
       return $this->status;
    }

    public function setUserIdSubmitter($user_id_submitter)
    {
       $this->user_id_submitter=$user_id_submitter;
       return $this;
    }
    public function getUserIdSubmitter()
    {
       return $this->user_id_submitter;
    }

    public function getSubmitter()
    {
        $select = new User();
        $select->find(array('id'=>$this->user_id_submitter));
        if ($select->getId()>0)
        {
            return $select->getName();
        } else
        {
            return "not found!";
        }
    }
    public function setDescription($description)
    {
       $this->description=$description;
       return $this;
    }
    public function getDescription()
    {
       return $this->description;
    }

    public function setCreated($created)
    {
       $this->created=$created;
       return $this;
    }
    public function getCreated()
    {
       return $this->created;
    }

    public  function insert()
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return false;
        } 
        

        $status=(is_null($this->status)?"":$mysqli->real_escape_string($this->status));
        $user_id_submitter=intval(is_null($this->user_id_submitter)?0:$mysqli->real_escape_string($this->user_id_submitter));
        $description=(is_null($this->description)?"":$mysqli->real_escape_string($this->description));
        $sql="INSERT INTO tickets (status,user_id_submitter,description) VALUES ('$status','$user_id_submitter','$description');";
        $mysqli->query($sql);
        if ($mysqli->error)
        {
            $_SESSION['tamsi_notice'].="Errormessage: ".$mysqli->error."  \r\n";
            return false;
        } else
        {
            $this->id = $mysqli->insert_id;
        }
        $mysqli->close();
        return true;
        

    }
    public  function insertAll($tickets)
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return false;
        } 
        
        $sql="INSERT INTO tickets (status,user_id_submitter,description) VALUES ";
        
        $last = count($tickets);
        $i=0;
        foreach($tickets as $i=>$ticket)
        {
            $i=$i+1;

            $status=(is_null($ticket->status)?"":$mysqli->real_escape_string($ticket->status));
            $user_id_submitter=intval(is_null($ticket->user_id_submitter)?0:$mysqli->real_escape_string($ticket->user_id_submitter));
            $description=(is_null($ticket->description)?"":$mysqli->real_escape_string($ticket->description));

            
            if ($i<$last)
            {
                $sql.="('$status','$user_id_submitter','$description'), ";
            } else
            {
                $sql.="('$status','$user_id_submitter','$description'); ";
            }
        }
        $mysqli->query($sql);
        if ($mysqli->error)
        {
            $_SESSION['tamsi_notice'].="Errormessage: ".$mysqli->error."  \r\n";
            return false;
        } else
        {
            $this->affected_rows = $mysqli->affected_rows;
        }
        $mysqli->close();
        return true;
        

    }
    public  function insertUpdateAll($tickets,$fields_array=array('status','user_id_submitter','description'),$key='id')
    {
        return parent::insertUpdateAll($tickets,$fields_array,$key);
    }
    public  function update($data=array())
    {
        if ((count($data)>0) && (array_key_exists('id',$data) || ($this->id>0)))
        {
            return parent::update($data);
        } else
        {
            $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
            if ($mysqli->connect_errno) 
            {
                $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
                return false;
            } 
            
            $sql="UPDATE tickets SET ";        
            $updates = '';
            
            $status=(is_null($this->status)?"":$mysqli->real_escape_string($this->status));
            $user_id_submitter=intval(is_null($this->user_id_submitter)?0:$mysqli->real_escape_string($this->user_id_submitter));
            $description=(is_null($this->description)?"":$mysqli->real_escape_string($this->description));

            $sql.="status='$status',user_id_submitter='$user_id_submitter',description='$description' WHERE id='$this->id'";



            $mysqli->query($sql);
            if ($mysqli->error)
            {
                $_SESSION['tamsi_notice'].="Errormessage: ".$mysqli->error."  \r\n";
                return false;
            } 
            $mysqli->close();
            return true;
        }


    }
    public function __construct()
    {
        parent::__construct();
        $this->table = 'tickets';
        $this->column_types = json_decode('{"id":"int","status":"enum","user_id_submitter":"int","description":"text","created":"timestamp"}',TRUE);
        $this->columns = array_keys($this->column_types);

       return $this;
    }
 
    
     
    public function findAll($filter=array(),$orders=array(),$limit=100000,$page=1,$select=array(),$debug=false)
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return array();
        }   
        
        $sql = $this->generateFindAllSql($mysqli,$filter,$orders,$limit,$page,$select,$debug);
             
        
        $res = $mysqli->query($sql);
    
        $tickets = array();
        
        if ($mysqli->affected_rows>0)
        {
            while ($row = $res->fetch_array())
            {
                $ticket= new Ticket();
                foreach($row as $ticket_field=>$ticket_value)
                {
                    if (in_array($ticket_field,$ticket->columns))
                    {
                        $ticket->$ticket_field = $ticket_value;
                    }
                }
                $tickets[]=$ticket;
            }
        } else
        {

        }
        $mysqli->close();
        return $tickets;
    } 

    public function getName()
    { 
        return 'Ticket:'.$this->id;
    }

}