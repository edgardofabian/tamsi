<?php 

require_once 'database.cfg.php';
require_once 'security.cfg.php';
require_once 'TamsiSecurity.php';

class ModelBase
{

    //@var int
    protected $id;
    public $columns;
    public $column_types;

    public $db;
    public $total_db_items;
    public $table;
    
    public $security;
    public $secure;
    
    public function __construct()
    {
        $this->id = 0;
        $this->db = new db();
        $this->total_db_items = -1;
        $this->secure= new Secure_config();
        $this->security= new TamsiSecurity();   

        return $this;
    }

    public function encrypt_values($data)
    {
        foreach($data as $key=>$value)
        {
            if (array_key_exists($key,$this->secure->encrypt_keys[$this->table]))
            {
                $data[$key]=$this->security->encrypt($value,$this->secure->encrypt_keys[$this->table][$key]);
            }
        }
        return $data;
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
    
    public  function insertUpdateAll($objects,$columns_array=array(),$key='id')
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return false;
        } 
        
        if (is_array($columns_array) && ($columns_array))
        {
        } else $columns_array = $this->columns;
                
        $last_field = count($columns_array) - 1;
        
        $field_list = '';
        $update_values = '';
        foreach($columns_array as $j=>$field)
        {
            if ($j<$last_field)
            {
                $field_list.="$field,";
                if ($field != $key) $update_values.="$field = VALUES($field),";
            } else
            {
                $field_list.="$field ";
                if ($field != $key) $update_values.="$field = VALUES($field);";
            }
        }
        
        
        $sql="INSERT INTO ".$this->table." ($field_list) VALUES ";
        
        $last = count($objects);
        $i=0;
        foreach($objects as $object)
        {
            $i=$i+1;
            $values = '';
            foreach($columns_array as $j=>$field)
            {
                $val='';
                if ($object->$field)
                {
                    $val=trim($mysqli->real_escape_string($object->$field));
                }
                if (array_key_exists($field,$this->column_types))
                {
                    if (($this->column_types[$field]=='int') || ($this->column_types[$field]=='tinyint')) $field_val=(int) $val;
                    else if ($this->column_types[$field]=='double') $field_val=(float) $val;
                    else if ($this->column_types[$field]=='float') $field_val=(int) $val;
                    else  $field_val=($val?"'$val'":"''");
                } else $field_val=($val?"'$val'":"''");
                if ($j<$last_field) $values.="$field_val,"; else $values.="$field_val";                
            }
            
            if ($i<$last)
            {
                $sql.="($values), ";
            } else
            {
                $sql.="($values) ";
            }
        }
        
        $sql.= "ON DUPLICATE KEY UPDATE ";
        
        
        $sql.=$update_values;
        
        $mysqli->query($sql);
        if ($mysqli->error)
        {
            $_SESSION['tamsi_notice']="Errormessage: ".$mysqli->error."  \r\n";
            return false;
        } else
        {
            $this->affected_rows = $mysqli->affected_rows;
        }
        $mysqli->close();
        return true;
    }
    
    public  function deleteAll($filter)
    {
        $ret=0;
        if (is_array($filter) && (count($filter)>0))
        {
            $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
            if ($mysqli->connect_errno) 
            {
                $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
                return false;
            } 

            foreach ($filter as $id=>$val)
            {
                if (($val)||(is_string($filter[$id]) && strlen($filter[$id]))) 
                {
                }
                else
                {
                    unset($filter[$id]);
                }
            }
     
            
            $sql="DELETE FROM ".$this->table." ";

            $filter_sql = '';
            $len=count($filter);
            if ($len>0)
            {
                foreach($filter as $key=>$val)
                {
                    $operator = '=';
                    if (is_array($val))
                    {
                        $field="$key";
                        $esc_val = array();
                        foreach($val as $va)
                        {
                            if (is_string($va))
                            {
                                $esc_val[]=$mysqli->real_escape_string($va);
                            } else $esc_val[]=$va;
                        }
                        $value = "('".implode("','",$esc_val)."')";
                        $operator = 'IN';
                    } else if (is_numeric($key) || empty($key) || (!isset($key))) 
                    {
                        $operator = '';
                        $value="$val";
                        $field='';
                    } else if (property_exists($this,$key))
                    {
                        $field=$key;
                        $value = "'".$mysqli->real_escape_string($val)."'";
                    } else
                    {
                        $operator = '';
                        $field=$key;
                        $value = "'$val'";
                    }
                    
                    if (!$filter_sql)
                    {
                        $filter_sql.="WHERE $field $operator $value ";
                    } else
                    {
                        $filter_sql.="AND $field $operator $value ";
                    } 

                }
                $sql.=' '.$filter_sql;
                $mysqli->query($sql);
                
                $ret = $mysqli->affected_rows;    
                if ($mysqli->error)
                {
                    $_SESSION['tamsi_notice'].="Errormessage: ".$mysqli->error."  \r\n";
                    return FALSE;
                } 
                $mysqli->close();
            } 
            
        }
        if ($ret==0) $_SESSION['tamsi_notice'].="No item was deleted: ".$sql."  \r\n";
        return $ret;
    } 
    
    public  function update($data=array())
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return false;
        } 
        
        $sql="UPDATE ".$this->table." SET ";        
        $updates = '';
        $where = '';
        
        
        if (property_exists($this->secure,'encrypt_key') && array_key_exists($this->table,$this->secure->encrypt_key))
        {
            $data = $this->encrypt_values($data);
        }
        
        foreach($data as $key=>$value)
        {
            if ($key=='id')
            {
                $where="WHERE $key='$value';";
            } else
            {
                if ($updates)
                {
                    $updates.= ", $key='$value' ";
                } else
                {
                    $updates.= " $key='$value' ";
                }
            }
        }
        if ($where)
        {
        } else
        {
            $where="WHERE id='".$this->id."';";
        }
        $sql.=$updates.$where;

        $mysqli->query($sql);
        if ($mysqli->error)
        {
            $_SESSION['tamsi_notice'].="Errormessage: ".$mysqli->error."  \r\n";
            return false;
        } 
        $mysqli->close();
        return true;        
    }
    public  function save()
    {
        if  ($this->id)
        {
            return $this->update();
        } else
        {
            return $this->insert();
        }
    }

    public  function delete()
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return false;
        } 
        
        $sql="DELETE FROM ".$this->table." WHERE id='$this->id'";
        $mysqli->query($sql);
        if ($mysqli->error)
        {
            $_SESSION['tamsi_notice'].="Errormessage: ".$mysqli->error."  \r\n";
            return false;
        } 
        $mysqli->close();
        return true;
        

    }

    
    public function find($filter,$orders=array(),$select=array(),$debug=false)
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return $this;
        }
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
                $field="$key";
                $esc_val = array();
                foreach($val as $va)
                {
                    foreach($val as $va)
                    {
                        if (is_string($va))
                        {
                            $esc_val[]=$mysqli->real_escape_string($va);
                        } else $esc_val[]=$va;
                    }
                }
                $value = "('".implode("','",$esc_val)."')";
                $operator = 'IN';
            } else if (is_numeric($key) || empty($key) || (!isset($key))) 
            {
                $operator = '';
                $value="$val";
                $field='';
            } else if (property_exists($this,$key))
            {
                $field=$key;
                if (is_string($val))
                {
                    $value = "'".$mysqli->real_escape_string($val)."'";
                } else $value = "'$val'";
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
        
        
        if ($debug) die($sql);
        
        $res = $mysqli->query($sql);
    
        if ($mysqli->affected_rows>0)
        {
            $row = $res->fetch_array();
            $decrypt_fields = array();
            if (array_key_exists($this->table,$this->secure->encrypt_keys))
            {
                $decrypt_fields = $this->secure->encrypt_keys[$this->table];
            }
            foreach($row as $field=>$value)
            {
                if (in_array($field,$this->columns))
                {
                    if (array_key_exists($field,$decrypt_fields))
                    {
                        $this->$field = $this->security->decrypt($value,$decrypt_fields[$field]);
                    } else $this->$field = $value;
                }
            }
        } else
        {
            
        }
        $res->free_result();
        $mysqli->close();
        return $this;
    }   
    
     
    public function generateFindAllSql($mysqli,$filter=array(),$orders=array(),$limit=10000000,$page=1,$select=array(),$debug=false)
    {
        
        $sel_str='*';
        if ($select)
        {
            $sel_str=implode(',',$select);
        }
        $sql="SELECT ".$sel_str." from ".$this->table." ";        
        
        $i=1;
        if ($page > 0)
        {
            $offset = ($page - 1)*$limit;
        } else
        {
            $offset = 0;
        }
        foreach ($filter as $id=>$val)
        {
            if (($val)||(is_string($filter[$id]) && strlen($filter[$id]))) 
            {
            }
            else
            {
                unset($filter[$id]);
            }
        }
        foreach ($orders as $id=>$val)
        {
            if (is_string($orders[$id]) && strlen($orders[$id]))
            {
            }
            else
            {
                unset($orders[$id]);
            }
        }        
        $len=count($filter);
        if ($len>0)
        {
            foreach($filter as $key=>$val)
            {
                $operator = '=';
                if (is_array($val))
                {
                    $field="$key";
                    $esc_val = array();
                    foreach($val as $va)
                    {
                        if (is_string($va))
                        {
                            $esc_val[]=$mysqli->real_escape_string($va);
                        } else $esc_val[]=$va;
                    }
                    $value = "('".implode("','",$esc_val)."')";
                    $operator = 'IN';
                } else if (is_numeric($key) || empty($key) || (!isset($key))) 
                {
                    $operator = '';
                    $value="$val";
                    $field='';
                } else if (property_exists($this,$key))
                {
                    if (strpos($key,'s_ids')===FALSE)
                    {
                        $field=$key;
                        $value = "'".$mysqli->real_escape_string($val)."'";
                    } else
                    {
                        $field='';
                        $operator='';
                        $value =  "($key LIKE '$val') OR ($key LIKE '$val,%') OR ($key LIKE '%,$val') OR ($key LIKE '%,$val,%') "; 
                    }
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
        } else
		{
			$sql.="WHERE `id` > 0 ";
		}
        $i=1;
        $olen=0;
        if (is_array($orders))
        {
        	$olen=count($orders);
        }
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
        if ($limit>0)
        {
            $sql.=" LIMIT $limit OFFSET $offset";
        }
        
        
        $sql.=";";
        
        if ($debug) die($sql);
        
        return $sql;
        
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
    
        $subjects = array();
        
        if ($mysqli->affected_rows>0)
        {
            while ($row = $res->fetch_object())
            {
                $subjects[]=$row;
            }
        } else
        {

        }
        
        $res->free_result();
        $mysqli->close();
        return $subjects;
    } 

    
    public function get($param=array('filter'=>array(),'select'=>array(),'sort'=>array()))
    {
        $filter=array();
        if (array_key_exists('filter',$param))
        {
            $filter = $param['filter'];
        }
        $select=array();
        if (array_key_exists('select',$param))
        {
            $select=$param['select'];
        }
        $sort=array();
        if (array_key_exists('sort',$param))
        {
            $sort=$param['sort'];
        }
        $limit=1;
        $page=0;
        $debug=false;
        if (array_key_exists('debug',$param))
        {
            $debug=$param['debug'];
        }
        $this->find($filter,$sort,$select,$debug);
        return $this;
    }    

    public function getAll($param=array('filter'=>array(),'select'=>array(),'sort'=>array(),'limit'=>0,'page'=>0))
    {
        $filter=array();
        if (array_key_exists('filter',$param))
        {
            $filter = $param['filter'];
        }
        $select=array();
        if (array_key_exists('select',$param))
        {
            $select=$param['select'];
        }
        $sort=array();
        if (array_key_exists('sort',$param))
        {
            $sort=$param['sort'];
        }
        $limit=0;
        if (array_key_exists('limit',$param))
        {
            $limit=$param['limit'];
        }
        $page=0;
        if (array_key_exists('page',$param))
        {
            $page=$param['page'];
        }
        $debug=false;
        if (array_key_exists('debug',$param))
        {
            $debug=$param['debug'];
        }
        return $this->findAll($filter,$sort,$limit,$page,$select,$debug);
    }   


    public function getPageCount($filter=array(),$orders=array(),$item_per_page=100,$clear=true,$max=10000)
    {
        $id = 'refi_clean_row_pages';
        if (($_SESSION[$id]) && (!$clear))
        {
            return $_SESSION[$id]; 
        } else
        {
            $res_count = 0;
            $not_done = 1;
            $loop = 0;
            if (((int)$max)==0) $max=10000;
            for($not_done=1;$not_done;)
            {
                $offset = $loop*$max;
                if ($not_done)
                {
                    $res = $this->getCount($filter,$max,$offset);
                    if ($res>0)
                    {
                        $res_count = $res_count + $res;
                    }
                    if ($res<$max)
                    {
                        $not_done = 0; //done already
                    }
                }
                $loop = $loop+1;
             }
            
            if ($res_count>0)
            {
               $pages = ceil($res_count/$item_per_page);
            
               if ($pages<1) $pages = 1;
            
               $_SESSION[$id] = $pages;
            }
            else
            {
               if ($pages<1) $pages = 1;
            
               $_SESSION[$id] = $pages;
            }
            
            $this->total_db_items = $res_count;
            
            return ($pages);
        }        
        
    } 
    
    public function getTotalDbCount($filter=array(),$orders=array(),$item_per_page=100,$clear=true,$max=10000)
    {
        
        if ($this->total_db_items>=0)
        {
			$count = $this->total_db_items;
			$this->total_db_items=-1;
            return $count; 
        } else
        {
            $res_count = 0;
            $not_done = 1;
            $loop = 0;
            if (((int)$max)==0) $max=10000;
            for($not_done=1;$not_done;)
            {
                $offset = $loop*$max;
                if ($not_done)
                {
                    $res = $this->getCount($filter,$max,$offset);
                    if ($res>0)
                    {
                        $res_count = $res_count + $res;
                    }
                    if ($res<$max)
                    {
                        $not_done = 0; //done already
                    }
                }
                $loop = $loop+1;
             }
            
            $this->total_db_items = $res_count;
            
            return ($this->total_db_items);
        }        
        
    }
        
    public function getCount($filter=array(),$limit=100000,$offset=0)
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return 0;
        }
                
        $sql="SELECT count(id) from ".$this->table." ";
 

        foreach ($filter as $id=>$val)
        {
            if ((is_array($val)) || strlen($val))
            {
            }
            else
            {
                unset($filter[$id]);
            }
        }

        $len=count($filter);
        if ($len>0)
        {
            $i=1;
            foreach($filter as $key=>$val)
            {
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
        } else
		{
			$sql.="WHERE `id` > 0 ";
		}
 
        $sql.=" LIMIT $limit OFFSET $offset";
 
        $res = $mysqli->query($sql);
        
        if ($res)
        {
            $row = $res->fetch_array();
            return $row["count(id)"];
        }
        $mysqli->close();        
        return 0;
        
    }
    
    function getOptionsAndSelected($selected=array(),$filter=array())
    {
        $objs=$this->findAll($filter);
        $data_names = array();
        $options = '';
        foreach ($objs as $option)
        {
            $name = $option->getName();
            $oid = $option->getId();
            if ($oid)
            {
                if (in_array($oid,$selected))
                {
                    $options.="<option selected='selected' value='$oid' >$name</option>\n";

                    $data_names[] = $name;
                } else
                {
                    $options.="<option value='$oid' >$name</option>\n";

                }
            }
        }     
        return (object) array('options'=>$options,'selected'=>implode(', ',$data_names));
    }
    
    function query($sql,$return_mysqli_param=array())// $return_mysqli_param=array('result','affected_rows','last_id')
    {
        $mysqli = new mysqli($this->db->host,$this->db->user, $this->db->password, $this->db->name);
        if ($mysqli->connect_errno) 
        {
            $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
            return FALSE;
        }    
    
        $res = $mysqli->query($sql);
        
        
        if (count($return_mysqli_param)>0)
        {
            $ret = array();
            foreach($return_mysqli_param as $field)
            {
                if ($field == 'result') $ret['result']=$res;
                else if (property_exists($mysqli, $field))
                {
                    $ret[$field]=$mysqli->$field;
                } else
                {
                    $ret[$field]='Uknown property';
                }
            }
        
        } else $ret = $res;
        return $ret;
    }    

    public function getName()
    { 
        return 'ModelBase'.$this->id;
    }

}
