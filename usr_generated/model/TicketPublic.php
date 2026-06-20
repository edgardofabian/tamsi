<?php 

require_once 'database.cfg.php';
require_once 'User.php'; 

class TicketPublic extends ModelBase 
{

    //@var int
    public $id;

    //@var enum
    public $status;

    //@var int
    public $user_id_submitter;

    //@var varchar
    public $submitter;

    //@var text
    public $description;

    //@var timestamp
    public $created;

    public function getSubmitter()
    {
        require_once 'User.php';
        $user_id_submitter=$this->user_id_submitter;
		$user = new User(); 
        $user->find(array('id'=>$user_id_submitter));
        if ($user->getId()>0)
        {
            $this->submitter = $user->getName();
        } else
        {
            $this->submitter = "";
        }
        return $this->submitter;
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
        $this->column_types = json_decode('[COLUMN_TYPES]',TRUE);
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