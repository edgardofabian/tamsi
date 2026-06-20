<?php 

require_once 'database.cfg.php';
require_once 'ModelBase.php';
require_once 'Ticket.php'; 
require_once 'User.php'; 

class TicketDiscussionProtected extends ModelBase 
{

    //@var int
    protected $id;

    //@var int
    protected $ticket_id;

    //@var int
    protected $user_id;

    //@var text
    protected $message;

    //@var timestamp
    protected $created;

    public function setId($id)
    {
       $this->id=$id;
       return $this;
    }
    public function getId()
    {
       return $this->id;
    }

    public function setTicketId($ticket_id)
    {
       $this->ticket_id=$ticket_id;
       return $this;
    }
    public function getTicketId()
    {
       return $this->ticket_id;
    }

    public function getTicket()
    {
        $select = new Ticket();
        $select->find(array('id'=>$this->ticket_id));
        if ($select->getId()>0)
        {
            return $select->getName();
        } else
        {
            return "not found!";
        }
    }
    public function setUserId($user_id)
    {
       $this->user_id=$user_id;
       return $this;
    }
    public function getUserId()
    {
       return $this->user_id;
    }

    public function getUser()
    {
        $select = new User();
        $select->find(array('id'=>$this->user_id));
        if ($select->getId()>0)
        {
            return $select->getName();
        } else
        {
            return "not found!";
        }
    }
    public function setMessage($message)
    {
       $this->message=$message;
       return $this;
    }
    public function getMessage()
    {
       return $this->message;
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
        

        $ticket_id=intval(is_null($this->ticket_id)?0:$mysqli->real_escape_string($this->ticket_id));
        $user_id=intval(is_null($this->user_id)?0:$mysqli->real_escape_string($this->user_id));
        $message=(is_null($this->message)?"":$mysqli->real_escape_string($this->message));
        $sql="INSERT INTO ticket_discussions (ticket_id,user_id,message) VALUES ('$ticket_id','$user_id','$message');";
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
    public  function insertAll($ticket_discussions)
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return false;
        } 
        
        $sql="INSERT INTO ticket_discussions (ticket_id,user_id,message) VALUES ";
        
        $last = count($ticket_discussions);
        $i=0;
        foreach($ticket_discussions as $i=>$ticket_discussion)
        {
            $i=$i+1;

            $ticket_id=intval(is_null($ticket_discussion->ticket_id)?0:$mysqli->real_escape_string($ticket_discussion->ticket_id));
            $user_id=intval(is_null($ticket_discussion->user_id)?0:$mysqli->real_escape_string($ticket_discussion->user_id));
            $message=(is_null($ticket_discussion->message)?"":$mysqli->real_escape_string($ticket_discussion->message));

            
            if ($i<$last)
            {
                $sql.="('$ticket_id','$user_id','$message'), ";
            } else
            {
                $sql.="('$ticket_id','$user_id','$message'); ";
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
    public  function insertUpdateAll($ticket_discussions,$fields_array=array('ticket_id','user_id','message'),$key='id')
    {
        return parent::insertUpdateAll($ticket_discussions,$fields_array,$key);
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
            
            $sql="UPDATE ticket_discussions SET ";        
            $updates = '';
            
            $ticket_id=intval(is_null($this->ticket_id)?0:$mysqli->real_escape_string($this->ticket_id));
            $user_id=intval(is_null($this->user_id)?0:$mysqli->real_escape_string($this->user_id));
            $message=(is_null($this->message)?"":$mysqli->real_escape_string($this->message));

            $sql.="ticket_id='$ticket_id',user_id='$user_id',message='$message' WHERE id='$this->id'";



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
        $this->table = 'ticket_discussions';
        $this->column_types = json_decode('{"id":"int","ticket_id":"int","user_id":"int","message":"text","created":"timestamp"}',TRUE);
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
    
        $ticket_discussions = array();
        
        if ($mysqli->affected_rows>0)
        {
            while ($row = $res->fetch_array())
            {
                $ticket_discussion= new TicketDiscussion();
                foreach($row as $ticket_discussion_field=>$ticket_discussion_value)
                {
                    if (in_array($ticket_discussion_field,$ticket_discussion->columns))
                    {
                        $ticket_discussion->$ticket_discussion_field = $ticket_discussion_value;
                    }
                }
                $ticket_discussions[]=$ticket_discussion;
            }
        } else
        {

        }
        $mysqli->close();
        return $ticket_discussions;
    } 

    public function getName()
    { 
        return 'TicketDiscussion:'.$this->id;
    }

}