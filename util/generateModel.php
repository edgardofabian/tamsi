
<?php
/** Main model of tamsi framework 
 * Object contaiting model objects for main tamsi framework. 
 * 
 * Copyright (C) 20014-2020 Edgardo Fabian <edgardo.fabian@gmail.com> 
 * LICENSE: This program is free software; you can redistribute it and/or 
 * modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation; either version 3 
 * of the License, or (at your option) any later version. 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU General Public License for more details. 
 * You should have received a copy of the GNU General Public License 
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;. 
 * @package Tamsi
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.gahum.tech
 */
ini_set('display_errors', 'On');
//error_reporting(E_ALL ^ E_WARNING); 
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING); 
require_once __DIR__.'/../config/database.cfg.php';
require_once __DIR__.'/../config/security.cfg.php';
$secure=new Secure_config();
if (file_exists(__DIR__.'/../tamsi/generate.php'))
{
    require_once __DIR__.'/../tamsi/generate.php';
} else
{
    require_once __DIR__.'/../tamsi/tamsi/generate.php';
}
if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
{
    $argv = array();
    $argv[] = 'generateModel.php';
    $argv[] = $_POST['table'];
    $argc = 2;
    if ($_POST['link_image'])
    {
        $argv[] = 'link_image';
        $argc = $argc+1;
    }
    if ($_POST['singular_table'])
    {
        $argv[] = 'singular_table';
        $argc = $argc+1;
    }       
    if ($_POST['login_register'])
    {
        $argv[] = 'login_register';
        $argc = $argc+1;
    }
    if ($_POST['no_repeat'])
    {
        $argv[] = 'no_repeat='.$_POST['no_repeat'];
        $argc = $argc+1;
    }
    if ($_POST['force_not_restful'])
    {
        $argv[] = 'restful=false';
        $argc = $argc+1;
    }    
}
//die(json_encode($argv));
if ($argc<2) 
{
    print_r("Insufficient parameters\n");
    print_r("generateModel table_name < link_image singular_table login_register no_repeat force_not_restful \n");
} else
{
    print_r("processing....\n");
    
    if (!file_exists("../usr/model"))
    {
        mkdir(__DIR__."/../usr/model");
    }
    if (!file_exists("../old/usr/model"))
    {
        mkdir(__DIR__."/../old/usr/model");
    }

    if (!file_exists("../usr_generated/model"))
    {
        mkdir(__DIR__."/../usr_generated/model");
    }
    if (!file_exists("../old/usr_generated/model"))
    {
        mkdir(__DIR__."/../old/usr_generated/model");
    }
    
    $link_image = false;
    if (in_array('link_image',$argv))
    {
        $link_image = true;
    } else
    {
        $link_image = false;
    }
    $table = $argv[1];
    if (in_array('singular_table',$argv))
    {
        $table_element = $table;
        $element_array = $table.'s';
    } else
    {
        $table_element = generateTableElementName($table);
        $element_array = $table;
    }
	
    $controller_name = getFunctionName($table);
    $class_name = getFunctionName($table_element);
    
    $mysqli = new mysqli($db_host,$db_user, $db_password, $db);
    if ($mysqli->connect_errno) 
    {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    }
    $tables = getTableNames($mysqli);
    $column_types = array();
    $html_exclude=array();
    foreach ($argv as $arg)
    {
        if (strpos($arg,'html_exclude')===false)
        { //use default exclusion array
            $html_exclude = array('status','gender','password','email','salt');
        } else
        {
            $equal=strpos($arg,'=');
            $excludes = substr($arg,$equal+1);
            $html_exclude = array_merge(array('password','email','salt'),explode(',',$excludes));
        }
    }

    //get  primary key
    $sql = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY';";

    $res = $mysqli->query($sql);
    $primary = 'id';
    if ($mysqli->affected_rows>0)
    {
        $row = $res->fetch_array();
        $primary = $row['Column_name'];
    } else
    {
        print_r("No primary key");
        die();
    }

    $sql ="SELECT column_name, data_type,character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' and TABLE_SCHEMA='$db';";

    if (in_array('disclaimer',$argv))
    {
        $header = file_get_contents('header.tem');
    } else
    {
        $header =  '';
    }

    $entity="<?php \r\n$header\r\nrequire_once 'database.cfg.php';\n";
    $entity.="require_once 'ModelBase.php';\r\n";
    if (array_key_exists($table,$secure->encrypt_keys))
    {
        $entity.="require_once 'security.cfg.php';\r\n";
        $entity.="require_once 'TamsiSecurity.php';\r\n";
    }
    $entity.="[REQUIRE]\r\n";
    $entity.="class $class_name".'Protected'." extends ModelBase \n{\n\n";
    $entity_public = "<?php \r\n$header\r\nrequire_once 'database.cfg.php';\n[REQUIRE]\r\nclass $class_name".'Public'." extends ModelBase \n{\n\n";
    


    $entity_extended="<?php \r\n$header\r\nrequire_once 'database.cfg.php';\nrequire_once '".$class_name."Protected.php';\nclass $class_name extends $class_name".'Protected'."\r\n{\r\n\n";
    $entity_extended.="\r\n}\r\n\n";
    $res = $mysqli->query($sql);
    $getset='';
    $getforeign='';
    $find_template=file_get_contents('find.tem');
    $find_temp = str_replace('[ELEMENT_ARRAY]',$element_array,str_replace('[TABLE]',$table,$find_template));
    $find = str_replace('[TABLE_ELEMENT]',$table_element,$find_temp);
    //$find = str_replace('[CLASS_NAME]',$class_name,$findx);
    $insert_start="    public  function insert()\n    {\n";
    $insert='        $sql="INSERT INTO '.$table.' (';
    $columns='';
    $values="VALUES (";
    $update_start = "    public  function update(".'$data=array()'.")\n    {\n";
    
    $update_inside = file_get_contents('update_inside.tem');
    $update = "\r\n".'            $sql.="';
    $object_copy="\n";
    $new_object="\n";
    $construct = '';
    $escaped = '';
    $getname = false;
    $require = '';
    $fill_foreign = '';
    $fill_foreign_one = '';
    while ($row = $res->fetch_array()) 
    {
        $data_type = $row['data_type'];
        $data = $row['column_name'];

        if (!(strpos(strtolower($data_type),'char')===FALSE) || (strtolower($data_type)=='text'))
        {   //it is a string or password
            if (!(strpos(strtolower($data),"password")===FALSE))
            {
                $type="password";
            } else 
            {
                if (strpos($data,'_ids')>0)
                {
                    $foreign_alias = getForeignAliasName($data);
                    $foreign_array = generateTableNameFromElement(getForeignTableElement($data));
                    if (in_array($foreign_array, $tables))
                    {
                        $type="select_multiple";
                        if ($foreign_alias)
                        {
                            $list_head = $foreign_alias;
                        } else $list_head = $foreign_array;
                    } else
                    {
                        $type="text";
                    }
                } else
                {
                    if (($row['character_maximum_length']>128) || (strtolower($data_type)=='text'))
                    {
                        $type="textarea";
                    } else
                    {
                        $type="text";
                    }
                }
            }
        } else if ((!(strpos(strtolower($data_type),'int')===FALSE)) || (!(strpos(strtolower($data_type),'float')===FALSE))|| (!(strpos(strtolower($data_type),'double')===FALSE)) || (!(strpos(strtolower($data_type),'decimal')===FALSE)))
        {   //it is a number
            if ((strpos($data,'_id')>0) || (strpos($data,'_id_')>0))
            {
                $foreign_alias = getForeignAliasName($data);
                $foreign_unit = getForeignTableElement($data);
                
                if (trim($foreign_unit) === 'type')
                {
                    $type="select_type";
                    if ($foreign_alias)
                    {
                        $list_head = $foreign_alias;
                    } else $list_head = 'type data';
                } else
                {
                    $foreign_name = generateTableNameFromElement($foreign_unit);
                    //if (strpos('severity',$foreign_name)>=0) die('foreign='.$foreign_name);
                    if (in_array($foreign_name, $tables))
                    {
                        $type="select";
                        if ($foreign_alias)
                        {
                            $list_head = $foreign_alias;
                        } else $list_head = $foreign_name;
                    } else
                    {
                        $type="number";
                    }
                }
                if ((!(strpos(strtolower($data_type),'float')===FALSE))|| (!(strpos(strtolower($data_type),'double')===FALSE)) || (!(strpos(strtolower($data_type),'decimal')===FALSE)))
                {
                    $type="float";
                }
                //echo "\r\n".$data.":".$foreign_unit.":".$foreign_name.':'."$type\r\n";
                $filter_ids_array.='    $'.$data."_lst=array();\r\n";
                $filterlist[]=$data;
                $fill_filters.='    $'.$data.'=$item->get'.getFunctionName($data)."();\r\n";
                $fill_filters.='    $'.$data."_lst[$".$data.']=$'.$data.";\r\n";
            }
            else
            {
                $type="number";
            }
        } else if ((!(strpos(strtolower($data_type),'datetime')===FALSE)) || (!(strpos(strtolower($data_type),'timestamp')===FALSE)))
        {   //it is a datetime
            $type="datetime";
        } else if (!(strpos(strtolower($data_type),'date')===FALSE))
        {   //it is a date
            $type="date";
        } else if (!(strpos(strtolower($data_type),'time')===FALSE))
        {   //it is a time
            $type="time";
        } else if (!(strpos(strtolower($data_type),'enum')===FALSE))
        {   //it is a select or radio buttons
            $type="radio";
            $options = getEnumOptions($mysqli,$table, $data);
        } else if (($data==$primary) || (!(strpos(strtolower($data_type),'decimal')===FALSE)) || (!(strpos(strtolower($data_type),'float')===FALSE)))
        {
            $type = "number";
        } else 
        {
            $type = $data_type;
        }
        
        $column_types[$data]=$data_type;
        
        $entity.="    //@var $data_type\n";
        $entity.="    protected ".'$'."$data;\n\n";
        
        $entity_public.="    //@var $data_type\n";
        $entity_public.="    public ".'$'."$data;\n\n";
        
        $foreign_table_element = getForeignTableElement($data);
        /*if (strpos($data,'_ids')>0)
        {
            die($data.':'.$foreign_table_element);
        }*/
        $foreign_alias_name = getForeignAliasName($data);
        if ($foreign_table_element)
        {
			$entity_public.="    //@var varchar\n";
			if ($foreign_alias_name)
			{
				
			} else
			{
				$foreign_alias_name=$foreign_table_element;			
			}
			$entity_public.="    public ".'$'.$foreign_alias_name.";\n\n";
			$getforeign_tem = file_get_contents('get_foreign_element.tem');
			$getforeign1 = str_replace('[DATA]',$data,$getforeign_tem);
			$getforeign2 = str_replace('[FOREIGN_TABLE_ELEMENT]',$foreign_table_element,$getforeign1);
            
			$foreign_class_name = getFunctionName($foreign_table_element);
			$getforeign3 = str_replace('[FOREIGN_CLASS_NAME]',$foreign_class_name,$getforeign2);
			$getforeign4 = str_replace('[FOREIGN_NAME]',$foreign_alias_name,$getforeign3);
			$getforeign.= str_replace('[FOREIGN_FUNCTION_NAME]',getFunctionName($foreign_alias_name),$getforeign4);
            if ($foreign_class_name==='Type')
            {
                ;
            } else
            {
                if (strpos($require,$foreign_class_name.".php';"))
                {
                    //already added
                }
                else
                {
                    $require.="require_once '".$foreign_class_name.".php'; \r\n";
                }
            }
			$fill_foreign.='                $'."$table_element".'->get'.getFunctionName($foreign_alias_name)."();\r\n";
			$fill_foreign_one.='            '.'$this'.'->get'.getFunctionName($foreign_alias_name)."();\r\n";
		}
        $security='';
        if (array_key_exists($table,$secure->encrypt_keys))
        {
            $security='       $this->secure= new Secure_config();'."\n";
            $security.='       $this->security= new TamsiSecurity();'."\n";
        }
        $find=str_replace('[SECURITY]',$security,$find);
        
        $getset.="    public function set".getFunctionName($data)."($".$data.")\n"; 
        $getset.="    {\n       ".'$this->'."$data=".'$'."$data;\n       return ".'$this;'."\n    }\n";
        $getset.="    public function get".getFunctionName($data)."()\n"; 
        if (getFunctionName($data)=='Name')
        {
            $getname=true;
        }
        
        $getset.="    {\n       return ".'$this->'."$data;\n    }\n\n";
        
        
        if ($type === "select_multiple")
        {
            $foreign_alias = getForeignAliasName($data);
            
            $foreign_table = getForeignTableElement($data);

            if ($foreign_alias)
            {
                $foreign_object = $foreign_alias;
            } else
            {
                $foreign_object = getForeignTableElement($data);
            }             
            
            $getset.="    public function get".getFunctionName($foreign_object)."()\n"; 
            $getset.="    {\n";
            $getset.='        $sids = explode(",",$this->'.$data.');'."\n";
            $getset.='        $index = 0;'."\n";
            $getset.='        $ret = "";'."\n";
            $getset.='        foreach ($sids as $sid)'."\n";
            $getset.='        {'."\n";
            $getset.='            if (trim($sid))'."\n";
            $getset.='            {'."\n";
            $getset.='                $index = $index + 1;'."\n";
            $getset.='                $select = new '.getFunctionName($foreign_table).'();'."\n"; 
            $getset.='                $select->find(array('."'id'".'=>$sid));'."\n";
            $getset.='                if ($select->getId()>0)'."\n";
            $getset.='                {'."\n";
            $getset.='                    $item_name=$select->getName();'."\r\n";
            $getset.='                } else'."\n";
            $getset.='                {'."\n";
            $getset.='                    $item_name = "None";'."\n";
            $getset.='                }'."\n";            
            $getset.='                if ($ret)'."\r\n";
            $getset.='                {'."\r\n";
            $getset.='                    $ret.=", ".$item_name;'."\n";
            $getset.='                } else'."\r\n";
            $getset.='                {'."\r\n";
			$getset.='                    $ret.=$item_name;'."\n";
			$getset.='                }'."\r\n";
            $getset.='            }'."\n";
            $getset.="        }\n";
            $getset.='        return $ret;'."\n";
            $getset.="    }\n";
            
        } else
        if ($type === "select")
        {
            $foreign_alias = getForeignAliasName($data);
            
            $foreign_table = getForeignTableElement($data);

            if ($foreign_alias)
            {
                $foreign_object = $foreign_alias;
            } else
            {
                $foreign_object = getForeignTableElement($data);
            }             
            
            $getset.="    public function get".getFunctionName($foreign_object)."()\n"; 
            $getset.="    {\n";
            $getset.='        $select = new '.getFunctionName($foreign_table).'();'."\n";
            $getset.='        $select->find(array('."'id'".'=>$this->'.$data.'));'."\n";
            $getset.='        if ($select->getId()>0)'."\n";
            $getset.='        {'."\n";
            $getset.='            return $select->getName();'."\n";
            $getset.='        } else'."\n";
            $getset.='        {'."\n";
            $getset.='            return "not found!";'."\n";
            $getset.='        }'."\n";
            $getset.="    }\n";
            
        } else
        if ($type === "select_type")
        {
            $getset.="    public function getTypeObjectName()\n"; 
            $getset.="    {\n";
            $getset.='        $object_name="";'."\n";                
            $getset.='        if ($this->getType())'."\n";
            $getset.='        {'."\n";
            $getset.='            $type = $this->getType();'."\n";
            $getset.='            $data_array = preg_split("'.'/[\s|@|._]+/'.'", $type);'."\n";
            $getset.='            $camels =array();'."\n";
            $getset.='            foreach ($data_array as $data_str)'."\n";
            $getset.='            {'."\n";
            $getset.='                $camels[] = ucfirst(strtolower($data_str));'."\n";
            $getset.='            }'."\n";
            $getset.='            $object_name=implode("", $camels);'."\n";                
            $getset.='        }'."\n";
            $getset.='        return $object_name;'."\n";
            $getset.="    }\n";
            
            $getset.="    public function getTypeObject()\n"; 
            $getset.="    {\n";
            $getset.='        if ($this->getType())'."\n";
            $getset.='        {'."\n";
            $getset.='            $type_obj = $this->getTypeObjectName();'."\n";
            $getset.='            require_once $type_obj.".php";'."\n";
            $getset.='            $object = new $type_obj();'."\n";
            $getset.='            if ($object)'."\n";
            $getset.='            {'."\n";
            $getset.='                $object->find(array('."'id'".'=>$this->'.$data.'));'."\n";
            $getset.='                if ($object->getId()>0)'."\n";
            $getset.='                {'."\n";
            $getset.='                    return $object->getName();'."\n";
            $getset.='                } else'."\n";
            $getset.='                {'."\n";
            $getset.='                    return "not found!";'."\n";
            $getset.='                }'."\n";
            $getset.='            } else'."\n";
            $getset.='            {'."\n";
            $getset.='                return "Pls specify type first";'."\n";
            $getset.='            }'."\n";
            $getset.='        } else '."\n";
            $getset.='        {'."\n";
            $getset.='            return "Pls define a type";'."\n";
            $getset.='        }'."\n";
            $getset.="    }\n";
        }        
        
        if (($data==$primary) || ((trim(strtolower($data))==='created') && (($data_type==='timestamp')||($data_type==='datetime'))) )
        {
            ; //exclude
        } else if ((trim(strtolower($data))==='modified') && ($data_type==='datetime'))
        {
            $columns.="$data,";
            $escaped.= '            $now = new DateTime("NOW");'."\r\n";
            $escaped.= '            $'.$data.'=$mysqli->real_escape_string($now->format("Y-m-d H:i:s")'.");\n";
            $values.="'".'$'.$data."'".','; 
            $update.="$data='".'$'.$data."'".',';
        } else
        {
            $columns.="$data,";
            if (($type=='text') || ($type=='textarea') || ($type=="select_multiple"))
            {
                if (array_key_exists($table,$secure->encrypt_keys) && array_key_exists($data,$secure->encrypt_keys[$table]))
                {
                    $escaped.= '            $'.$data.'=$this->security->encrypt($this->'.$data.',$this->secure->encrypt_keys["'.$table.'"]["'.$data.'"])'.";\n";
                } else
                {
                    $escaped.= '            $'.$data.'=(is_null($this->'.$data.')?"":$mysqli->real_escape_string($this->'.$data."));\n";
                }
            }
            else if ($type=='datetime')
            {
                if (array_key_exists($table,$secure->encrypt_keys) && array_key_exists($data,$secure->encrypt_keys[$table]))
                {
                    $escaped.= '            $'.$data.'=$this->security->encrypt_datetime($this->'.$data.',$this->secure->encrypt_keys["'.$table.'"]["'.$data.'"])'.";\n";
                } else
                {
                    $escaped.= '            $'.$data.'=(is_null($this->'.$data.')?"":$mysqli->real_escape_string($this->'.$data."));\n";
                }
            }
            else if ($type=='date')
            {
                if (array_key_exists($table,$secure->encrypt_keys) && array_key_exists($data,$secure->encrypt_keys[$table]))
                {
                    $escaped.= '            $'.$data.'=$this->security->encrypt_date($this->'.$data.',$this->secure->encrypt_keys["'.$table.'"]["'.$data.'"])'.";\n";
                } else
                {
                    $escaped.= '            $'.$data.'=(is_null($this->'.$data.')?"":$mysqli->real_escape_string($this->'.$data."));\n";
                }
            }
            else if ($type=='float')
            {
                $escaped.= '            $'.$data.'=floatval(is_null($this->'.$data.')?0:$mysqli->real_escape_string($this->'.$data."));\n";
            }
            else if ($type=='number')
            {
                $escaped.= '            $'.$data.'=intval(is_null($this->'.$data.')?0:$mysqli->real_escape_string($this->'.$data."));\n";
            }
            else
            {
				if ((strpos($data,'_id')>0) || (strpos($data,'_id_')>0))
				{
					$escaped.= '            $'.$data.'=intval(is_null($this->'.$data.')?0:$mysqli->real_escape_string($this->'.$data."));\n";
				} else
				{
					$escaped.= '            $'.$data.'=(is_null($this->'.$data.')?"":$mysqli->real_escape_string($this->'.$data."));\n";
				}
            }
            $values.="'".'$'.$data."'".','; 
            $update.="$data='".'$'.$data."'".',';
        }
        if (array_key_exists($table,$secure->encrypt_keys) && array_key_exists($data,$secure->encrypt_keys[$table]))
        {
            if (($type=='text') || ($type=='textarea'))
            {        
                $object_copy.='            $this->'.$data.'=$this->security->decrypt($row['."'$data'],".'$this->secure->encrypt_keys["'.$table.'"]["'.$data.'"]);'."\n";
                $new_object.='                $'."$table_element->".$data.'=$this->security->decrypt($row['."'$data'],".'$this->secure->encrypt_keys["'.$table.'"]["'.$data.'"]);'."\n";
            }
            else if (($type=='datetime'))
            {
                $object_copy.='            $this->'.$data.'=$this->security->decrypt_datetime($row['."'$data'],".'$this->secure->encrypt_keys["'.$table.'"]["'.$data.'"]);'."\n";
                $new_object.='                $'."$table_element->".$data.'=$this->security->decrypt_datetime($row['."'$data'],".'$this->secure->encrypt_keys["'.$table.'"]["'.$data.'"]);'."\n";
            }
            else if (($type=='date'))
            {
                $object_copy.='            $this->'.$data.'=$this->security->decrypt_date($row['."'$data'],".'$this->secure->encrypt_keys["'.$table.'"]["'.$data.'"]);'."\n";
                $new_object.='                $'."$table_element->".$data.'=$this->security->decrypt_date($row['."'$data'],".'$this->secure->encrypt_keys["'.$table.'"]["'.$data.'"]);'."\n";
            }            
            else
            {
                $object_copy.='            $this->'.$data.'=$row['."'$data'];\n";
                $new_object.='                $'."$table_element->".$data.'=$row['."'$data'];\n";
            }
        } else
        {
            $object_copy.='            $this->'.$data.'=$row['."'$data'];\n";
            $new_object.='                $'."$table_element->".$data.'=$row['."'$data'];\n";            
        }
    }
    $res->close();
    
    $entity = str_replace('[REQUIRE]',$require,$entity);
    $entity_public = str_replace('[REQUIRE]',$require,$entity_public);
    $function_getname = "    public function getName()\r\n";
    $function_getname.= "    { \r\n        return '$class_name:'.".'$this->id'.";\r\n    }";
    
    $now = new DateTime('NOW');
    $datetime_stamp = $now->format('Ymd_his');
    
    if (file_exists("../usr_generated/model/$class_name"."Protected.php"))
    {
        
        
        $backup = file_get_contents("../usr_generated/model/$class_name"."Protected.php");
        /*if (!$getname)
        {
            $start_getname = strpos($backup,'    public function getName()');
            $end_getname = strpos($backup,'}',$start_getname);
            if ($start_getname && $end_getname)
            {
                $function_getname = substr($backup,$start_getname,$end_getname-$start_getname+1); 
            }
        }*/
        
        file_put_contents("../old/usr_generated/model/$class_name".'Protected'.$datetime_stamp.".bak",$backup);
        print_r("Saving backup at ../old/usr_generated/model/$class_name".'Protected'.$datetime_stamp.".bak\r\n");
        
    }
    
    if (file_exists("../usr_generated/model/$class_name"."Public.php"))
    {
        
        
        $backup = file_get_contents("../usr_generated/model/$class_name"."Public.php");
        if (!$getname)
        {
            $start_getname = strpos($backup,'    public function getName()');
            $end_getname = strpos($backup,'}',$start_getname);
            if ($start_getname && $end_getname)
            {
                $function_getname = substr($backup,$start_getname,$end_getname-$start_getname+1); 
            }
        }
        
        file_put_contents("../old/usr_generated/model/$class_name".'Public'.$datetime_stamp.".bak",$backup);
        print_r("Saving backup at ../old/usr_generated/model/$class_name".'Public'.$datetime_stamp.".bak\r\n");
    }
    
    if (file_exists("../usr/model/$class_name".".php"))
    {
        ;
    } else
    {
        file_put_contents("../usr/model/$class_name".".php",$entity_extended);
        print_r("done! Extended $table created at ../usr/model/$class_name".".php\n");    
    }
    
    $insert.=$columns;
    
    $ic_pos=strrpos(',',$insert);
    $vc_pos=strrpos(',',$values);
    $uc_pos=strrpos(',',$update);

    /*$entity.=$construct;
    $entity_public.=$construct;*/
    
    if ($link_image)
    {
        $get_image_tem = file_get_contents('getimage.tem');
        $get_image1 = str_replace('[TABLE]',$table,$get_image_tem);
        $get_image2 = str_replace('[TABLE_ELEMENT]',$table_element,$get_image1);
        $get_image = str_replace('[CLASS_NAME]',$class_name,$get_image2);
        $entity.=$get_image;
    }
    $sql_open = file_get_contents('sql_open.tem');
    $entity.=$getset;
    $entity_public.=$getforeign;
    
    $entity_process=$insert_start.$sql_open."\r\n".str_replace("            $","        $",$escaped).substr($insert,0,$ic_pos-1).') '.substr($values,0,$vc_pos-1).');"'.";\n";
    
    $insert_all = file_get_contents('insert_all.tem');
    
    $escaped_insert_all = str_replace('this',$table_element,$escaped);
    $fc_pos=strrpos(',',$columns);
    
    $values_insert_all = str_replace('VALUES ','',$values);
    
    $insert_all = str_replace('[ESCAPED_INSERT_ALL]',$escaped_insert_all,$insert_all);
    $insert_all = str_replace('[VALUES]',substr($values_insert_all,0,$vc_pos-1).')',$insert_all);
    $insert_all = str_replace('[FIELDS]',substr($columns,0,$fc_pos-1),$insert_all);
    
    $sql_close = file_get_contents('sql_close.tem');
    $sql_close_insert = file_get_contents('sql_close_insert.tem');
    $entity_process.=$sql_close_insert."\n    }\n";
    
    //$entity_process.=$insert_all;
    
    $columns_array = substr($columns,0,$fc_pos-1);
    $columns_array = "'".str_replace(",","','",$columns_array)."'";
    
    $insert_update_all = file_get_contents('insert_update_all.tem');
    
    $insert_all.=$insert_update_all;
    
    //$delete_all = file_get_contents('delete_all.tem');
    
    
    //$all = $insert_all.$insert_update_all.$delete_all;
    
    $all = str_replace('[TABLE]',$table,$insert_all);
    $all = str_replace('[TABLE_ELEMENT]',$table_element,$all);
    $all = str_replace('[FIELDS_ARRAY]',$columns_array,$all);

    
    
    $entity_process.=$all;
    
    $update_all = str_replace('[ALL_UPDATES]',$escaped.substr($update,0,$uc_pos-1)." WHERE $primary='".'$this->'.$primary."'".'"'.";\n",$update_inside);
    
    $update_all = str_replace('[TABLE]',$table,$update_all);
    $update_all = str_replace('[TABLE_ELEMENT]',$table_element,$update_all);
    $update_all = str_replace('[FIELDS_ARRAY]',$columns_array,$update_all);
    
    $entity_process.=$update_start.$update_all;
    $entity_process.="\n    }\n";
    /*
    $entity_process.="    public  function save()\n    {\n        ";
    $entity_process.='if  ($this->'.$primary.")\n        {\n";
    $entity_process.='            return $this->update();'."\n        } else\n        {\n";
    $entity_process.='            return $this->insert();'."\n        }\n    }\n\n";
    $entity_process.="    public  function delete()\n    {\n";
    $entity_process.=$sql_open;
    $entity_process.='        $sql="DELETE FROM '.$table.' WHERE '.$primary."='".'$this->'.$primary."'".'"'.";\n";
    $entity_process.=$sql_close."\n    }\n";
    */
    $entity_process_public = $entity_process;
    $find = str_replace('[OBJECT_COPY]',$object_copy,$find);
    $find = str_replace('[NEW_OBJECT]',$new_object,$find);
    $find = str_replace('[CLASS_NAME]',$class_name,$find);
    $entity_process.=$find."\r\n";
    
    $find_final_public = str_replace('[OBJECT_COPY]',$object_copy.$fill_foreign_one,$find);
    $find_final_public1 = str_replace('[NEW_OBJECT]',$new_object.$fill_foreign,$find_final_public);
    $find_final_public2 = str_replace('[CLASS_NAME]',$class_name.'Public',$find_final_public1);
    $entity_process_public.=$find_final_public2."\r\n";
    if (!$getname)
    {
        $entity_process.=$function_getname."\r\n";
        $entity_process_public.=$function_getname."\r\n";
    }
    $entity_process.="\r\n}";
    $entity_process_public.="\r\n}";
    
    $entity.=$entity_process;
    
    $col_types_json = json_encode($column_types);
    $entity = str_replace('[COLUMN_TYPES]',$col_types_json,$entity);
    $entity_public.= $entity_process_public;
    
    file_put_contents("../usr_generated/model/$class_name"."Protected.php",$entity);
    print_r("done! Public $table created at ../usr_generated/model/$class_name"."Protected.php\n");
    
    file_put_contents("../usr_generated/model/$class_name"."Public.php",$entity_public);
    print_r("done! Public $table created at ../usr_generated/model/$class_name"."Public.php\n");
}

