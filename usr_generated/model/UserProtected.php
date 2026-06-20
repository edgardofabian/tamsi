<?php 

require_once 'database.cfg.php';
require_once 'ModelBase.php';

class UserProtected extends ModelBase 
{

    //@var int
    protected $id;

    //@var varchar
    protected $firstname;

    //@var varchar
    protected $lastname;

    //@var varchar
    protected $email;

    //@var varchar
    protected $profile;

    //@var timestamp
    protected $created;

    //@var datetime
    protected $modified;

    //@var datetime
    protected $login;

    //@var int
    protected $role;

    //@var varchar
    protected $salt;

    //@var varchar
    protected $password;

    //@var varchar
    protected $ip;

    //@var varchar
    protected $updateip;

    //@var varchar
    protected $loginip;

    //@var varchar
    protected $resetkey;

    //@var varchar
    protected $securekey;

    public function getImage($folder='')
    {
        $paths = new Paths();
        $name = $this->generateImageName($folder);
        if (file_exists($paths->user_image.'users/'.strtolower($name).'.svg'))
        {
            $absolute_path = $paths->user_image.'users/'.strtolower($name).'.svg';
        }
        else
        {
            $absolute_path = $paths->user_image.'users/'.strtolower($name).'.png';
        }
        if (file_exists($absolute_path))
        {
            return $absolute_path;
        } else
        {
            return $paths->user_image.'users/'."none.png";
        }
    }
    public function getImageUrl($folder='')
    {
        $paths = new Paths();
        $name = $this->generateImageName($folder);
        if (file_exists($paths->user_image.'users/'.strtolower($name).'.svg'))
        {
            $absolute_path = $paths->user_image.'users/'.strtolower($name).'.svg';
        }
        else
        {
            $absolute_path = $paths->user_image.'users/'.strtolower($name).'.png';
        }
        $url = str_replace($paths->public,'',$absolute_path);
        return $url;
    }    
    public function getImageIcon($folder='')
    {
        $paths = new Paths();
        $name = $this->generateImageName($folder);
        $absolute_path = $paths->user_image.'users/'.strtolower($name).'_icon.png';
        if (file_exists($absolute_path))
        {
            return $absolute_path;
        } else
        {
            return $paths->user_image.'users/'."none_icon.png";
        }
    }
    public function getImageIconUrl($folder='')
    {
        $paths = new Paths();
        $name = $this->generateImageName($folder);
        $absolute_path = $paths->user_image.'users/'.strtolower($name).'_icon.png';
        $url = str_replace($paths->public,'',$absolute_path);
        return $url;
    }    
    
    public function generateImageName($folder)
    {
        $name = str_replace(',','_',str_replace(' ','',strip_tags($this->getId().'_users')));
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

    public function setFirstname($firstname)
    {
       $this->firstname=$firstname;
       return $this;
    }
    public function getFirstname()
    {
       return $this->firstname;
    }

    public function setLastname($lastname)
    {
       $this->lastname=$lastname;
       return $this;
    }
    public function getLastname()
    {
       return $this->lastname;
    }

    public function setEmail($email)
    {
       $this->email=$email;
       return $this;
    }
    public function getEmail()
    {
       return $this->email;
    }

    public function setProfile($profile)
    {
       $this->profile=$profile;
       return $this;
    }
    public function getProfile()
    {
       return $this->profile;
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

    public function setModified($modified)
    {
       $this->modified=$modified;
       return $this;
    }
    public function getModified()
    {
       return $this->modified;
    }

    public function setLogin($login)
    {
       $this->login=$login;
       return $this;
    }
    public function getLogin()
    {
       return $this->login;
    }

    public function setRole($role)
    {
       $this->role=$role;
       return $this;
    }
    public function getRole()
    {
       return $this->role;
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

    public function setPassword($password)
    {
       $this->password=$password;
       return $this;
    }
    public function getPassword()
    {
       return $this->password;
    }

    public function setIp($ip)
    {
       $this->ip=$ip;
       return $this;
    }
    public function getIp()
    {
       return $this->ip;
    }

    public function setUpdateip($updateip)
    {
       $this->updateip=$updateip;
       return $this;
    }
    public function getUpdateip()
    {
       return $this->updateip;
    }

    public function setLoginip($loginip)
    {
       $this->loginip=$loginip;
       return $this;
    }
    public function getLoginip()
    {
       return $this->loginip;
    }

    public function setResetkey($resetkey)
    {
       $this->resetkey=$resetkey;
       return $this;
    }
    public function getResetkey()
    {
       return $this->resetkey;
    }

    public function setSecurekey($securekey)
    {
       $this->securekey=$securekey;
       return $this;
    }
    public function getSecurekey()
    {
       return $this->securekey;
    }

    public  function insert()
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return false;
        } 
        

        $firstname=(is_null($this->firstname)?"":$mysqli->real_escape_string($this->firstname));
        $lastname=(is_null($this->lastname)?"":$mysqli->real_escape_string($this->lastname));
        $email=(is_null($this->email)?"":$mysqli->real_escape_string($this->email));
        $profile=(is_null($this->profile)?"":$mysqli->real_escape_string($this->profile));
        $now = new DateTime("NOW");
        $modified=$mysqli->real_escape_string($now->format("Y-m-d H:i:s"));
        $login=(is_null($this->login)?"":$mysqli->real_escape_string($this->login));
        $role=intval(is_null($this->role)?0:$mysqli->real_escape_string($this->role));
        $salt=(is_null($this->salt)?"":$mysqli->real_escape_string($this->salt));
        $password=(is_null($this->password)?"":$mysqli->real_escape_string($this->password));
        $ip=(is_null($this->ip)?"":$mysqli->real_escape_string($this->ip));
        $updateip=(is_null($this->updateip)?"":$mysqli->real_escape_string($this->updateip));
        $loginip=(is_null($this->loginip)?"":$mysqli->real_escape_string($this->loginip));
        $resetkey=(is_null($this->resetkey)?"":$mysqli->real_escape_string($this->resetkey));
        $securekey=(is_null($this->securekey)?"":$mysqli->real_escape_string($this->securekey));
        $sql="INSERT INTO users (firstname,lastname,email,profile,modified,login,role,salt,password,ip,updateip,loginip,resetkey,securekey) VALUES ('$firstname','$lastname','$email','$profile','$modified','$login','$role','$salt','$password','$ip','$updateip','$loginip','$resetkey','$securekey');";
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
    public  function insertAll($users)
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return false;
        } 
        
        $sql="INSERT INTO users (firstname,lastname,email,profile,modified,login,role,salt,password,ip,updateip,loginip,resetkey,securekey) VALUES ";
        
        $last = count($users);
        $i=0;
        foreach($users as $i=>$user)
        {
            $i=$i+1;

            $firstname=(is_null($user->firstname)?"":$mysqli->real_escape_string($user->firstname));
            $lastname=(is_null($user->lastname)?"":$mysqli->real_escape_string($user->lastname));
            $email=(is_null($user->email)?"":$mysqli->real_escape_string($user->email));
            $profile=(is_null($user->profile)?"":$mysqli->real_escape_string($user->profile));
            $now = new DateTime("NOW");
            $modified=$mysqli->real_escape_string($now->format("Y-m-d H:i:s"));
            $login=(is_null($user->login)?"":$mysqli->real_escape_string($user->login));
            $role=intval(is_null($user->role)?0:$mysqli->real_escape_string($user->role));
            $salt=(is_null($user->salt)?"":$mysqli->real_escape_string($user->salt));
            $password=(is_null($user->password)?"":$mysqli->real_escape_string($user->password));
            $ip=(is_null($user->ip)?"":$mysqli->real_escape_string($user->ip));
            $updateip=(is_null($user->updateip)?"":$mysqli->real_escape_string($user->updateip));
            $loginip=(is_null($user->loginip)?"":$mysqli->real_escape_string($user->loginip));
            $resetkey=(is_null($user->resetkey)?"":$mysqli->real_escape_string($user->resetkey));
            $securekey=(is_null($user->securekey)?"":$mysqli->real_escape_string($user->securekey));

            
            if ($i<$last)
            {
                $sql.="('$firstname','$lastname','$email','$profile','$modified','$login','$role','$salt','$password','$ip','$updateip','$loginip','$resetkey','$securekey'), ";
            } else
            {
                $sql.="('$firstname','$lastname','$email','$profile','$modified','$login','$role','$salt','$password','$ip','$updateip','$loginip','$resetkey','$securekey'); ";
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
    public  function insertUpdateAll($users,$fields_array=array('firstname','lastname','email','profile','modified','login','role','salt','password','ip','updateip','loginip','resetkey','securekey'),$key='id')
    {
        return parent::insertUpdateAll($users,$fields_array,$key);
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
            
            $sql="UPDATE users SET ";        
            $updates = '';
            
            $firstname=(is_null($this->firstname)?"":$mysqli->real_escape_string($this->firstname));
            $lastname=(is_null($this->lastname)?"":$mysqli->real_escape_string($this->lastname));
            $email=(is_null($this->email)?"":$mysqli->real_escape_string($this->email));
            $profile=(is_null($this->profile)?"":$mysqli->real_escape_string($this->profile));
            $now = new DateTime("NOW");
            $modified=$mysqli->real_escape_string($now->format("Y-m-d H:i:s"));
            $login=(is_null($this->login)?"":$mysqli->real_escape_string($this->login));
            $role=intval(is_null($this->role)?0:$mysqli->real_escape_string($this->role));
            $salt=(is_null($this->salt)?"":$mysqli->real_escape_string($this->salt));
            $password=(is_null($this->password)?"":$mysqli->real_escape_string($this->password));
            $ip=(is_null($this->ip)?"":$mysqli->real_escape_string($this->ip));
            $updateip=(is_null($this->updateip)?"":$mysqli->real_escape_string($this->updateip));
            $loginip=(is_null($this->loginip)?"":$mysqli->real_escape_string($this->loginip));
            $resetkey=(is_null($this->resetkey)?"":$mysqli->real_escape_string($this->resetkey));
            $securekey=(is_null($this->securekey)?"":$mysqli->real_escape_string($this->securekey));

            $sql.="firstname='$firstname',lastname='$lastname',email='$email',profile='$profile',modified='$modified',login='$login',role='$role',salt='$salt',password='$password',ip='$ip',updateip='$updateip',loginip='$loginip',resetkey='$resetkey',securekey='$securekey' WHERE id='$this->id'";



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
        $this->table = 'users';
        $this->column_types = json_decode('{"id":"int","firstname":"varchar","lastname":"varchar","email":"varchar","profile":"varchar","created":"timestamp","modified":"datetime","login":"datetime","role":"int","salt":"varchar","password":"varchar","ip":"varchar","updateip":"varchar","loginip":"varchar","resetkey":"varchar","securekey":"varchar"}',TRUE);
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
    
        $users = array();
        
        if ($mysqli->affected_rows>0)
        {
            while ($row = $res->fetch_array())
            {
                $user= new User();
                foreach($row as $user_field=>$user_value)
                {
                    if (in_array($user_field,$user->columns))
                    {
                        $user->$user_field = $user_value;
                    }
                }
                $users[]=$user;
            }
        } else
        {

        }
        $mysqli->close();
        return $users;
    } 

    public function getName()
    { 
        return 'User:'.$this->id;
    }

}