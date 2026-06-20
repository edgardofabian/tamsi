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
 * @link http://tamsi.docph.net
 */
require_once __DIR__.'/../config/database.cfg.php';
require_once __DIR__.'/../config/paths.cfg.php';
$paths = new Paths();
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
    $argv[] = 'generateController.php';
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
    print_r("generateController table_name login_register new_edit link_image\n");
    print_r("login_register, new_edit and link_image are optional\n");
    print_r("* login_register means include loginRestAction() and registerRestAction()\n");
    print_r("* link_image means include uploadImageAction()\n");

} else
{
    print_r("processing....\n");
    
    if (!file_exists("../usr/controller"))
    {
        mkdir(__DIR__."/../usr/controller");
    }
    if (!file_exists("../old/usr/controller"))
    {
        mkdir(__DIR__."/../old/usr/controller");
    }

    if (!file_exists("../usr_generated/controller"))
    {
        mkdir(__DIR__."/../usr_generated/controller");
    }
    if (!file_exists("../old/usr_generated/controller"))
    {
        mkdir(__DIR__."/../old/usr_generated/controller");
    }    
    
    $table = $argv[1];
	
    if (in_array('singular_table',$argv))
    {
        $table_element = $table;
        $users_table = 'user';
        $element_array = $table.'s';
    } else
    {
        $table_element = generateTableElementName($table);
        $users_table = 'users';
        $element_array = $table;
    }    
    
    $controller_name = getFunctionName(ucfirst($table));
    $class_name = getFunctionName($table_element);
    
    $mysqli = new mysqli($db_host,$db_user, $db_password, $db);
    if ($mysqli->connect_errno) 
    {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
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
    $primary_function_name =  getFunctionName($primary);
    if (in_array('disclaimer',$argv))
    {
        $header = file_get_contents('header.tem');
    } else
    {
        $header =  '';
    }

    $entity="<?php \r\n$header\r\n";
    $entity.="require_once 'TamsiSecurity.php';\nrequire_once 'security.cfg.php';\n";
    if (defined('GOOGLE_CLIENT_ID') || defined('FACEBOOK_CLIENT_ID'))
    {
        $entity.="require_once __DIR__.'/../../lib/vendor/autoload.php';\n";
    }
    if ($table===$users_table)
    {
		$entity.="require_once 'Configurations.php';\n";
	}

    $security_tem = file_get_contents('security.tem');
    $ctrl_temp=file_get_contents('controller.tem');
    
    
    if ((in_array('login_register',$argv)) || ($table===$users_table))
    {
        $login_register = file_get_contents('login_register.tem');
		if (defined('GOOGLE_CLIENT_ID'))
        {
            $google_login=file_get_contents('google_login.tem');
        }
        else $google_login='';
        $login_register=str_replace('[GOOGLE_LOGIN]',$google_login,$login_register);
        
        if (defined('FACEBOOK_CLIENT_ID'))
        {
            $facebook_login=file_get_contents('facebook_login.tem');
        }
        else $facebook_login='';
        $login_register=str_replace('[FACEBOOK_LOGIN]',$facebook_login,$login_register);
        
        
        $log_reg_tem = str_replace('[PRIMARY_FUNCTION]',$primary_function_name,$login_register);
        $ctrl_template = str_replace ('[LOGIN_REGISTER_LOGOUT]',$log_reg_tem,$ctrl_temp);
        $ctrl_template = str_replace ('[SECURITY]',$security_tem,$ctrl_template);
    } else
    {
        $ctrl_template = str_replace ('[LOGIN_REGISTER_LOGOUT]','',$ctrl_temp);
    }
    
    
    if (in_array('restful=false',$argv))
    {
        if ($table===$users_table)
        { 
            $new_save_tem = file_get_contents('user_edit.tem');
        } else
        {
            $new_save_tem = file_get_contents('new_edit.tem');
        }
        $ctrl_template = str_replace ('[NEW_EDIT]',$new_save_tem,$ctrl_template);
        $ctrl_template = str_replace ('[SECURITY]',$security_tem,$ctrl_template);
    } else
    {

        if ($table===$users_table)
        { 
            $new_save_tem = file_get_contents('user_edit_rest.tem')."\r\n".file_get_contents('user_edit.tem');
        } else
        {
            $new_save_tem = file_get_contents('new_edit_rest.tem')."\r\n".file_get_contents('new_edit.tem');
        }
        $ctrl_template = str_replace ('[NEW_EDIT]',$new_save_tem,$ctrl_template);
        $ctrl_template = str_replace ('[SECURITY]',$security_tem,$ctrl_template);
    } 
    

    
    $no_repeat_field = array();
    foreach ($argv as $arg)
    {
        if (!(strpos($arg,'NO_REPEAT')===false))
        {
            $equal = strpos($arg,'=');
            $no_repeat_field[] = substr($argv,$equal+1);
        }
    }
    $no_repeat='';
    if (count($no_repeat_field)>0)
    {
        $no_repeat = '    $old_'.$table_element.' = new '.getFunctionName($table_element).'();'."\r\n";
		$no_repeat.= '    $old_'.$table_element.' = $old_'.$table_element.'->find(array(';
        $i=0;
        foreach($no_repeat_field as $field) 
        {
            $i=$i+1;
            if ($i==count($no_repeat_field))
            {
                $no_repeat.='"'.$field.'"=>$'.$table_element.'->get'.getFunctionName($field).'()));'."\r\n";
            } else
            {
                $no_repeat.='"'.$field.'"=>$'.$table_element.'->get'.getFunctionName($field).'(),';
            }
        }
        
        $no_repeat.= 'if ($old_'.$table_element.'->getId()>0)'."\r\n".'        {';
			
		$no_repeat.='$message="User with the same email=".$'.user.'->getName()." already exist!"'."\r\n";
		$no_repeat.='$success=false;'."\r\n";
		$no_repeat.='} else'."\r\n";
    }
    

    $res->close();


	$sql ="SELECT column_name, data_type,character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' and TABLE_SCHEMA='$db';";

    
    $res = $mysqli->query($sql);

    $post_entity = "\r\n";
    $post_entity_new = "";
    $array_to_object = $post_entity;
    $column_image_tem=file_get_contents('column_image.tem');
    $column_images='';
    while ($row = $res->fetch_array()) 
    {
        $data_type = $row['data_type'];
        $data = $row['column_name'];
        if ($data === $primary)
        {
        } else
        {
			if (($data_type==='timestamp')|| (trim(strtolower($data))==='salt') || (trim(strtolower($data))==='password'))
			{
				//skip
			}
            else if (((trim(strtolower($data))==='created')||(trim(strtolower($data))==='modified')||(trim(strtolower($data))==='login')) && (($data_type==='datetime')))
            {          
				   if (strpos($post_entity,'$now=new DateTime()')===FALSE) $post_entity.='        $now=new DateTime();'."\r\n";
                   $post_entity.= '        $'.$table_element.'->set'.getFunctionName($data).'($now->format("Y-m-d H:i:s"));'."\r\n";
            } else
            if (strpos($data,'_ids')>0)
            {
                $idsx = strpos($data,'_ids');
    
                if ($idsx > 0)
                {
                    $post_entity.= '        if (array_key_exists("'.$data.'",$_POST))'."\n";
                    $post_entity.= '        {'."\n";
                    $post_entity.= '            $'.$data.'='.'$_POST["'.$data.'"];'."\n";
                    $post_entity.= '            $str_'.$data.'="" '.";\n";
                    $post_entity.= '            if (is_array($'.$data.'))'."\n";
                    $post_entity.= '            {'."\n";
                    $post_entity.= '                $str_'.$data.'=implode(",",$'.$data.');'."\n";
                    $post_entity.= '            }'."\n";
                    $post_entity.= '            $'.$table_element.'->set'.getFunctionName($data).'($str_'.$data.');'."\r\n";
                    $post_entity.= '        }'."\n";
                }  else  
                {  
                }        
            } else if (strpos($data,'_image'))
            { 
                $column_image=str_replace('[IMAGE_NAME]',getFunctionName($data),$column_image_tem);
                $column_image=str_replace('[IMAGE_FOLDER]',$data,$column_image);
                $column_image = str_replace('[TABLE_ELEMENT]',$table_element,$column_image);
                $column_image = str_replace('[TABLE]',$table,$column_image);
                $column_image = str_replace('[CLASS_NAME]',$class_name,$column_image);
                $column_images.=$column_image;
            } else if (($table=='users') && ($data=='role'))
            {
                $post_entity.= '        if (array_key_exists("'.$data.'",$_POST))'."\r\n";
                $post_entity.= "        {\r\n";
                $post_entity.= '            $role=0;'."\r\n";;
                $post_entity.= '            if (is_array($_POST["'.$data.'"]))'."\r\n";
                $post_entity.= "            {\r\n";
                $post_entity.= '               $rol = new Role();'."\r\n";
                $post_entity.= '               $roles = array();'."\r\n";
                $post_entity.= '               foreach($_POST["'.$data.'"] as $r)'."\r\n";
                $post_entity.= '               {'."\r\n";
                $post_entity.= '                   if ($r<$_SESSION["user_role"]) $roles[] = $r;'."\r\n";
                $post_entity.= '               }'."\r\n";
                $post_entity.= '               $role = $rol->arrayToIntegerEncoded($roles);'."\r\n";
                $post_entity.= "            }\r\n";
                $post_entity.= '            $'.$table_element.'->set'.getFunctionName($data).'($role);'."\r\n";
                $post_entity.= "        }\r\n";
                $entity.="require_once 'Role.php';\n";
            } else
            {
                $post_entity.= '        if (array_key_exists("'.$data.'",$_POST))  $'.$table_element.'->set'.getFunctionName($data).'('.'$_POST["'.$data.'"]);'."\r\n";
			}
            
		}
		$array_to_object.='                $'.$table_element.'->set'.getFunctionName($data).'('.'$row["'.$data.'"]);'."\r\n";
    }
    
    if ($post_entity_new)
    {
       $post_entity_new='        $now = new DateTime("NOW");'."\r\n".$post_entity_new;
    }
    
    if (in_array('link_image',$argv) || $column_images )
    {
        $entity.="require_once 'TamsiImage.php';\n";

        $upload_image_tem = file_get_contents('upload_image.tem')."\r\n".$column_images;
        $upload_image_tem = str_replace('[IMAGE_NAME]','uploadImage',$upload_image_tem);
        $upload_image_tem = str_replace('[IMAGE_FOLDER]','',$upload_image_tem);
        $ctrl_template = str_replace ('[UPLOAD_IMAGE]',$upload_image_tem,$ctrl_template);
    }
    else
    {
        $ctrl_template = str_replace ('[UPLOAD_IMAGE]','',$ctrl_template);
    }
    
    
    $array_to_array_object=$array_to_object.'                $'.$element_array.'[]=$'.$table_element.';';
        
    $ctrl_template = str_replace('[TABLE]',$table,$ctrl_template);
    $ctrl_template = str_replace('[TABLE_ELEMENT]',$table_element,$ctrl_template);
    $ctrl_template = str_replace('[CLASS_NAME]',$class_name,$ctrl_template);
    $ctrl_template = str_replace('[PRIMARY]',$primary,$ctrl_template);    
    $ctrl_template = str_replace('[CONTROLLER_NAME]',$controller_name,$ctrl_template);
    $ctrl_template = str_replace('[CONTAINER]','div.page',$ctrl_template);
    $ctrl_template = str_replace('[ADMIN_DIR]',$paths->admin,$ctrl_template);
    
    $ctrl_template = str_replace('[UNIT_TRANSFER]',$array_to_object,$ctrl_template);
    $ctrl_template = str_replace('[TABLE_TRANSFER]',$array_to_array_object,$ctrl_template);
    $ctrl_template = str_replace('[POST_ENTITY]',$post_entity,$ctrl_template);
    $ctrl_template = str_replace('[POST_ENTITY_NEW]',$post_entity.$post_entity_new,$ctrl_template);
    
    
    //die($post_entity.$post_entity_new);
    $ctrl_final = str_replace('[NO_REPEAT]',$no_repeat,$ctrl_template);
    $entity.=$ctrl_final."\r\n";
    
    if (file_exists("../usr_generated/controller/Tamsi$controller_name"."Controller.php"))
    {
        $now = new DateTime('NOW');
        print_r("Storing backup at ../old/usr_generated/controller/Tamsi$controller_name"."Controller".$now->format('Ymd_his').".bak"."\n");
        $backup = file_get_contents("../usr_generated/controller/Tamsi$controller_name"."Controller.php");
        file_put_contents("../old/usr_generated/controller/Tamsi$controller_name"."Controller".$now->format('Ymd_his').".bak",$backup);
    }    
    
    $entity_extended="<?php \r\n$header\r\n";
    $entity_extended.="require_once 'Tamsi".$controller_name."Controller.php';\r\n";
    $entity_extended.="class ".$controller_name."Controller extends Tamsi".$controller_name."Controller \r\n";
    $entity_extended.="{ \r\n}\r\n";
    if (file_exists("../usr/controller/$controller_name"."Controller.php"))
    {
    } else
    {
        print_r("Writing ../usr/controller/$controller_name"."Controller.php\n");
        file_put_contents("../usr/controller/$controller_name"."Controller.php",$entity_extended);        
    }
    
    file_put_contents("../usr_generated/controller/Tamsi$controller_name"."Controller.php",$entity);
    print_r("done! $table created at ../usr_generated/controller/Tamsi$controller_name"."Controller.php\n");
}



