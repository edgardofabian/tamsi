<?php 

require_once 'database.cfg.php';

class ConfigurationPublic extends ModelBase 
{

    //@var int
    public $id;

    //@var varchar
    public $name;

    //@var varchar
    public $value;

    //@var text
    public $description;

    public  function insert()
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return false;
        } 
        

        $name=(is_null($this->name)?"":$mysqli->real_escape_string($this->name));
        $value=(is_null($this->value)?"":$mysqli->real_escape_string($this->value));
        $description=(is_null($this->description)?"":$mysqli->real_escape_string($this->description));
        $sql="INSERT INTO configurations (name,value,description) VALUES ('$name','$value','$description');";
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
    public  function insertAll($configurations)
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return false;
        } 
        
        $sql="INSERT INTO configurations (name,value,description) VALUES ";
        
        $last = count($configurations);
        $i=0;
        foreach($configurations as $i=>$configuration)
        {
            $i=$i+1;

            $name=(is_null($configuration->name)?"":$mysqli->real_escape_string($configuration->name));
            $value=(is_null($configuration->value)?"":$mysqli->real_escape_string($configuration->value));
            $description=(is_null($configuration->description)?"":$mysqli->real_escape_string($configuration->description));

            
            if ($i<$last)
            {
                $sql.="('$name','$value','$description'), ";
            } else
            {
                $sql.="('$name','$value','$description'); ";
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
    public  function insertUpdateAll($configurations,$fields_array=array('name','value','description'),$key='id')
    {
        return parent::insertUpdateAll($configurations,$fields_array,$key);
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
            
            $sql="UPDATE configurations SET ";        
            $updates = '';
            
            $name=(is_null($this->name)?"":$mysqli->real_escape_string($this->name));
            $value=(is_null($this->value)?"":$mysqli->real_escape_string($this->value));
            $description=(is_null($this->description)?"":$mysqli->real_escape_string($this->description));

            $sql.="name='$name',value='$value',description='$description' WHERE id='$this->id'";



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
        $this->table = 'configurations';
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
    
        $configurations = array();
        
        if ($mysqli->affected_rows>0)
        {
            while ($row = $res->fetch_array())
            {
                $configuration= new Configuration();
                foreach($row as $configuration_field=>$configuration_value)
                {
                    if (in_array($configuration_field,$configuration->columns))
                    {
                        $configuration->$configuration_field = $configuration_value;
                    }
                }
                $configurations[]=$configuration;
            }
        } else
        {

        }
        $mysqli->close();
        return $configurations;
    } 


}