<?php 

require_once 'database.cfg.php';
require_once 'InformationProtected.php';
class Information extends InformationProtected
{
/*    public function find($filter,$orders=array(),$select=array(),$debug = false)
    {
        $sel_str='*';
        if (is_array($select) && (count($select)>1))
        {
            $sel_str=implode(',',$select);
        }
        $sql="SELECT ".$sel_str." from ".$this->table." ";    
        $i=1;
        $len=count($filter);
        foreach($filter as $key=>$val)
        {
            $value='';
            $operator = '=';
            if (is_array($val))
            {
                $field="`$key`";
                $value = "('".implode("','",$val)."')";
                $operator = 'IN';
            } else if (is_numeric($key) || empty($key) || (!isset($key))) 
            {
                $operator = '';
                $value="$val";
                $field='';
            } else if (property_exists($this,$key))
            {
                $field=$key;
                $value = "'$val'";
            } else
            {
                $operator = '';
                $field=$key;
                $value = "'$val'";
            }
            
            if ($i==1)
            {
                $sql.="WHERE $field $operator $value ";
            } else
            {
                $sql.="AND $field $operator $value ";
            } 

            $i=$i+1;
        }
        $olen=count($orders);
        if ($olen>0)
        {
            $sql.="ORDER BY ";
            foreach($orders as $key=>$value)
            {
                if ($i<$olen)
                {
                    $sql.="$key $value, ";
                } else
                {
                    $sql.="$key $value ";
                }
                $i=$i+1;
            }
        }        
        
        $sql.=" LIMIT 1 ;";
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['maya_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return $this;
        }
        
        $res = $mysqli->query($sql);
    
        if ($mysqli->affected_rows>0)
        {
            $row = $res->fetch_array();
            
            $this->id=$row['id'];
            $this->user_id=$row['user_id'];
            $this->name=$row['name'];
            $this->value=$this->security->decrypt($row['value'],$this->secure->encrypt_keys["informations"]["value"]);
            $this->token=$this->security->decrypt($row['token'],$this->secure->encrypt_keys["informations"]["token"]);
            //die('decrypted='.$this->value.'<br>raw:'.$row['value'].'<br>using: '.$this->secure->encrypt_keys["informations"]["value"]);
            $this->salt=$row['salt'];
            $this->active=$row['active'];
            $this->created=$row['created'];

        } else
        {
            
        }
        $mysqli->close();
        return $this;
    }   
    public function getToken()
    {
        if ($this->salt)
        {
            return $this->security->decrypt($this->token,$this->salt);
        }
        else
        {
            return $this->token;
        }
    }*/
}

