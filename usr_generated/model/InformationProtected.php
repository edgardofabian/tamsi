<?php 

require_once 'database.cfg.php';
require_once 'ModelBase.php';
require_once 'security.cfg.php';
require_once 'TamsiSecurity.php';
require_once 'User.php'; 

class InformationProtected extends ModelBase 
{

    //@var int
    protected $id;

    //@var int
    protected $user_id;

    //@var varchar
    protected $name;

    //@var text
    protected $value;

    //@var varchar
    protected $token;

    //@var varchar
    protected $salt;

    //@var tinyint
    protected $active;

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
    public function setName($name)
    {
       $this->name=$name;
       return $this;
    }
    public function getName()
    {
       return $this->name;
    }

    public function setValue($value)
    {
       $this->value=$value;
       return $this;
    }
    public function getValue()
    {
       return $this->value;
    }

    public function setToken($token)
    {
       $this->token=$token;
       return $this;
    }
    public function getToken()
    {
       return $this->token;
    }

    public function setSalt($salt)
    {
       $this->salt=$salt;
       return $this;
    }
    public function getSalt()
    {
       return $this->salt;
    }

    public function setActive($active)
    {
       $this->active=$active;
       return $this;
    }
    public function getActive()
    {
       return $this->active;
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
        

        $user_id=intval(is_null($this->user_id)?0:$mysqli->real_escape_string($this->user_id));
        $name=(is_null($this->name)?"":$mysqli->real_escape_string($this->name));
        $value=$this->security->encrypt($this->value,$this->secure->encrypt_keys["informations"]["value"]);
        $token=$this->security->encrypt($this->token,$this->secure->encrypt_keys["informations"]["token"]);
        $salt=(is_null($this->salt)?"":$mysqli->real_escape_string($this->salt));
        $active=intval(is_null($this->active)?0:$mysqli->real_escape_string($this->active));
        $sql="INSERT INTO informations (user_id,name,value,token,salt,active) VALUES ('$user_id','$name','$value','$token','$salt','$active');";
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
    public  function insertAll($informations)
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return false;
        } 
        
        $sql="INSERT INTO informations (user_id,name,value,token,salt,active) VALUES ";
        
        $last = count($informations);
        $i=0;
        foreach($informations as $i=>$information)
        {
            $i=$i+1;

            $user_id=intval(is_null($information->user_id)?0:$mysqli->real_escape_string($information->user_id));
            $name=(is_null($information->name)?"":$mysqli->real_escape_string($information->name));
            $value=$information->security->encrypt($information->value,$information->secure->encrypt_keys["informations"]["value"]);
            $token=$information->security->encrypt($information->token,$information->secure->encrypt_keys["informations"]["token"]);
            $salt=(is_null($information->salt)?"":$mysqli->real_escape_string($information->salt));
            $active=intval(is_null($information->active)?0:$mysqli->real_escape_string($information->active));

            
            if ($i<$last)
            {
                $sql.="('$user_id','$name','$value','$token','$salt','$active'), ";
            } else
            {
                $sql.="('$user_id','$name','$value','$token','$salt','$active'); ";
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
    public  function insertUpdateAll($informations,$fields_array=array('user_id','name','value','token','salt','active'),$key='id')
    {
        return parent::insertUpdateAll($informations,$fields_array,$key);
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
            
            $sql="UPDATE informations SET ";        
            $updates = '';
            
            $user_id=intval(is_null($this->user_id)?0:$mysqli->real_escape_string($this->user_id));
            $name=(is_null($this->name)?"":$mysqli->real_escape_string($this->name));
            $value=$this->security->encrypt($this->value,$this->secure->encrypt_keys["informations"]["value"]);
            $token=$this->security->encrypt($this->token,$this->secure->encrypt_keys["informations"]["token"]);
            $salt=(is_null($this->salt)?"":$mysqli->real_escape_string($this->salt));
            $active=intval(is_null($this->active)?0:$mysqli->real_escape_string($this->active));

            $sql.="user_id='$user_id',name='$name',value='$value',token='$token',salt='$salt',active='$active' WHERE id='$this->id'";



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
        $this->table = 'informations';
        $this->column_types = json_decode('{"id":"int","user_id":"int","name":"varchar","value":"text","token":"varchar","salt":"varchar","active":"tinyint","created":"timestamp"}',TRUE);
        $this->columns = array_keys($this->column_types);
       $this->secure= new Secure_config();
       $this->security= new TamsiSecurity();

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
    
        $informations = array();
        
        if ($mysqli->affected_rows>0)
        {
            while ($row = $res->fetch_array())
            {
                $information= new Information();
                foreach($row as $information_field=>$information_value)
                {
                    if (in_array($information_field,$information->columns))
                    {
                        $information->$information_field = $information_value;
                    }
                }
                $informations[]=$information;
            }
        } else
        {

        }
        $mysqli->close();
        return $informations;
    } 


}