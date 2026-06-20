<?php
/**  
 * Used to generate view from database tables
 * 
 * Copyright (C) 20014-2020 Edgardo Fabian <edgardo.fabian@gahum.com> 
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
 * @package genereView
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
require_once __DIR__.'/../config/security.cfg.php';
if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
{
    $argv = array();
    $argv[] = 'generateView.php';
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
if ($argc<2) 
{
    print_r("Insufficient parameters\n");
    print_r("generateView table_name [sort:colname1,colname2] [filter:colname1,colname2,colname3] \n");
    exit;
} 

require_once __DIR__.'/../config/database.cfg.php';
require_once __DIR__.'/../config/paths.cfg.php';
$paths = new Paths();
set_include_path($paths->includes);

require_once 'generate.php';


print_r("processing....\n");

/*$subpath = $argv[2];
if ($subpath)
{
    $model->setBasePath("../$subpath/");
} else $model->setBasePath("../");*/

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

$editPath = "name=edit$table";

$render_array = array();


//check for filter options
$filteritems = preg_grep("/filter:/",$argv);
$filterlist = array();
$columns = array();
$filter_ids_array = '';
$filter_ids_array_public='';
$fill_filters = '';

$object_list_values='';
/*if (count($filteritems)>0)
{
    $key_filter = key($filteritems);
    $filterlist=explode(',',substr(trim($filteritems[$key_filter]),7));
    
    foreach ($filterlist as $filter)
    {
        $foreign_name = getForeignTableElement($filter);
    }
} */ 


$back_button = file_get_contents('back_button.tem');
$sort_preprocess = str_replace('[TABLE_NAME]',$table,file_get_contents('sort_preprocess.tem'));
$sort_filter_form_start = "<form id='".'<?php echo $'.$table_element.'_view->form_list;?>'."' <?php echo ".'$'.$table_element.'_view->list_form_attributes;?>'.' class="form list" action="<?php echo $'.$table_element.'_view->action;?>" method="POST">'."\r\n";
$sort_filter_form_start.= "<input type='hidden' name='".$table."_container' value='".'<?php echo $'.$table_element.'_view->container;?>'."' />\r\n";

$mysqli = new mysqli($db_host,$db_user, $db_password, $db);
if ($mysqli->connect_errno) 
{
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$tables = getTableNames($mysqli);

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
$res->close();
$foreign = array();
//get foreign keys
$sql = "SELECT
        `column_name`, 
        `referenced_table_schema` AS foreign_db, 
        `referenced_table_name` AS foreign_table, 
        `referenced_column_name`  AS foreign_column 
        FROM
        `information_schema`.`KEY_COLUMN_USAGE`
        WHERE
        `constraint_schema` = SCHEMA()
        AND
        `table_name` = 'your-table-name-here'
        AND
        `referenced_column_name` IS NOT NULL
        ORDER BY
        `column_name`;";

$res = $mysqli->query($sql);
if ($mysqli->affected_rows>0)
{
    while ($row = $res->fetch_array()) 
    {   
        $foreign[$row['column_name']]=$row['foreign_table'];
    }
} else
{
    print_r("No foreign key....\n");
}
$res->close();



$headers = getColumNames($table,$db,$mysqli);

//$current_headers = getCurrentListHeader($table);

$current_headers = $headers;

//remove encrypted columns from list columns
$secure = new Secure_config();

$visible_headers = (array) $current_headers;

if (property_exists($secure,'encrypt_keys') && is_array($secure->encrypt_keys) && array_key_exists($table,$secure->encrypt_keys))
{
    foreach($secure->encrypt_keys[$table] as $field=>$value)
    {
        if (in_array($field,$visible_headers))
        {
            unset($visible_headers[array_search($field,$visible_headers)]);
        }
    }
}

$default_list_headers = array();
foreach($visible_headers as $header)
{
    $default_list_headers[]=$header;
}


$sql ="SELECT column_name, data_type, column_default, character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' and TABLE_SCHEMA='$db';";

$res = $mysqli->query($sql);

/*$view_object_head = "<?php\r\nrequire_once 'TamsiSecurity.php';\r\n";
$view_object_head.= "require_once 'paths.cfg.php';\r\n";
$view_object_head.= "require_once 'Configurations.php';\r\n";*/
$view_object_head = "<?php\r\nrequire_once 'ViewBase.php';\r\n";
$view_object_head.= "require_once '$class_name.php';\r\n";
$view_object_start= "class ".$class_name."ViewBase extends ViewBase \n";
$view_object_start.= "{ \r\n    public ".'$id;'."\r\n";
$view_object_start.= "    public ".'$security;'."\r\n";
$view_object_start.= "    public ".'$current_user;'."\r\n";
$view_object_start.= "    public ".'$columns;'."\r\n";
$view_object_start.= "    public ".'$headers;'."\r\n";
$view_object_start.= "    public ".'$title;'."\r\n";
$view_object_start.= "    public ".'$list_form_attributes;'."\r\n";
$view_object_start.= "    public ".'$custom_html_list;'."\r\n";
$view_object_start.= "    public ".'$command;'."\r\n";
$view_object_start.= "    public ".'$list_controls;'."\r\n";
$view_object_start.= "    public ".'$form_list;'."\r\n";
$view_object_start.= "    public ".'$action;'."\r\n";
$view_object_start.= "    public ".'$list_delete_button_url;'."\r\n";
$view_object_start.= "    public ".'$list_delete_button_next_url;'."\r\n";
$view_object_start.= "    public ".'$list_delete_selected_url;'."\r\n";
$view_object_start.= "    public ".'$enable_paginator;'."\r\n";
$view_object_start.= "    public ".'$status_lst;'."\r\n";
$view_object_start.= "    public ".'$search_texts;'."\r\n";
$view_object_start.= "    public ".'$items_per_page;'."\r\n";
$view_object_start.= "    public ".'$total_db_items;'."\r\n";
$view_object_start.= "    public ".'$table_only_paginator;'."\r\n";

$view_object_start.= "    public ".'$field_header;'."\r\n";
$view_object_start.= "    public ".'$field_value_header;'."\r\n";
$view_object_start.= "    public ".'$form_attributes;'."\r\n";
$view_object_start.= "    public ".'$button_name;'."\r\n";
$view_object_start.= "    public ".'$button_url;'."\r\n";
$view_object_start.= "    public ".'$button_next_url;'."\r\n";
$view_object_start.= "    public ".'$delete_button_url;'."\r\n";
$view_object_start.= "    public ".'$delete_button_next_url;'."\r\n";
$view_object_start.= "    public ".'$usr;'."\r\n";



$htm = "<table c.yrlass='odd_even_row_alternate_color full_width data' >\n";
$thead_edit = "<thead><th>Field</th><th>Data <?php if (".'$role->isUserAnyOf($allowed_edit)'.") {?><button type='button' id='toggle_edit' class='button mx-'  mx-click='toggleEdit()'><li class='fa fa-pencil'></li> Enable Edit</button> <?php }?></th></thead>\n";
$thead_new = "<thead><th>Field</th><th>Data</th></thead>\n";
$tbody = "<tbody>\n";
$tbody_new = "<tbody>\n";
//$th_list = "<thead><tr><th>#</th><th>Actions</th>\n";
$theaders = array();
$theaders['#'] = array('label'=>"#",'sort'=>0,'class'=>'','attributes'=>array());
$theaders['action']=array('label'=>"Action",'sort'=>0,'class'=>'','attributes'=>array());
if (in_array('link_image',$argv)) $theaders['icon']=array('label'=>"Icon",'sort'=>0,'class'=>'','attributes'=>array());;
//$tf_list='<tr><th></th><th><button id="clear_search_and_sort" type="button" class="button"><i class="fa fa-times"></i> <span style="color:#DDDDDD;text-shadow:none"><i class="fa fa-sort"><i class="fa fa-search"></span></button></th>';
$tfilters = array();
$tfilters['#']='<th></th>';
$tfilters['action']='<th><button id="clear_search_and_sort" type="button" class="button"><i class="fa fa-times"></i> <span style="color:#DDDDDD;text-shadow:none"><i class="fa fa-sort"><i class="fa fa-search"></span></button></th>';

$tr_list = '<tr class="id_<?php echo $'.$table_element.'->getId();?>" ><td><?php echo $i ;?></td><td><?php $'.$table_element.'_view->renderActions($'.$table_element.',"",$role->isUserAnyOf($allowed_edit));?>'."</td>";
$foreign_require='';
$default_filters='';
$view_object_list_header='';
$view_object_list_filter='';
$view_object_list_data='';
$view_object='';
$column_images='';
$new_column_images='';
$edit_column_images='';
$object_list__values='';
$distinct_columns='';
$register_defaults='';
$register_columns=array('firstname','lastname','email','password');
$field_props = '';
$edit_props = '';
$new_props = '';
$view_object_comment='';

$fill_filters_tem = str_replace('[TABLE_ELEMENT]',$table_element,file_get_contents('fill_filters.tem'));
$fill_filters_tem = str_replace('[TABLE]',$table,$fill_filters_tem);
$edit_columns = array();
$new_columns = array();

while ($row = $res->fetch_array()) 
{
    $data_type = $row['data_type'];
    $data = $row['column_name'];
    $columns[] = $data;
    if (!strpos('_id',$data)===false)
    {
        if ($default_filters)
        {
            $default_filters.=','.$data;
        } else
        {
            $default_filters=$data;
        }
    }
    $type = '';
    $options = array();
    $list_head = $data;
    $foreign_name = '';
    $element='';
    $element_new='';
    $element_ops='';
    
    if (!(strpos(strtolower($data_type),'char')===FALSE) || (strtolower($data_type)==='text'))
    {   //it is a string or password
		$options = array();
        if (!(strpos(strtolower($data),"password")===FALSE))
        {
            $type="password";
        } else 
        {
            if (strpos($data,'_ids')>0)
            {
                $foreign_alias = getForeignAliasName($data);

                $foreign_array = generateTableNameFromElement(getForeignTableElement($data));
                //die('foreign:'.$foreign_array);
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
                //die('type:'.$type);
                //$filter_ids_array_public.='    public $'.$data."_lst;\r\n";
                /*$filter_ids_array.='        $this->'.$data."_lst=array();\r\n";
                $filterlist[]=$data;
                $fill_filters.='        $'.$data.'=$item->get'.getFunctionName($data)."();\r\n";
                $fill_filters.='        $this->'.$data."_lst[$".$data.']=$'.$data.";\r\n";   
                *              */
                $distinct_columns.='    public $distinct_'.$data."_lst;\r\n"; 
                $fill_filters.=str_replace('[DISTINCT_FIELD]',$data,$fill_filters_tem);
                

            } else if (strpos($data,'_image')>0)
            {
                $type='image';
                $column_image = file_get_contents('render_upload_image.tem');
                $column_image =str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$column_image);
                $column_image =str_replace('[DATA]',$data,$column_image);
                $column_image =str_replace('[TABLE_ELEMENT]',$table_element,$column_image);
                $column_image =str_replace('[VIEW_OBJECT]',$table_element.'_view',$column_image);
                $column_image =str_replace('[IMAGE_NAME]',getCapitalizedNiceName($data),$column_image);
                $column_images.=$column_image;
                /*$new_column_images.='<?php if (!isset($new_remove_'.$data.')) '."\n";
                $new_column_images.='{ '."\n";
                $new_column_images.='   echo "<h5>'.getCapitalizedNiceName($data).'</h5>";'."\n";
                $new_column_images.='   if (!isset($new_redirect_'.$data.')) $new_redirect_'.$data.'="?command=display_php&base_path=".$_REQUEST["base_path"]."&path=".$_REQUEST["path"]."&id=".$'.$table_element.'_view->'.$table_element.'->getId();'."\n";
                $new_column_images.='   $'.$table_element.'_view->renderUpload'.getFunctionName($data).'($'.$table_element.'_view->'.$table_element.',TRUE,$new_redirect_'.$data.');'."\r\n";
                $new_column_images.='} '."?>\n";*/
                $edit_column_images.='<?php if (!isset($edit_remove_'.$data.')) '."\n";
                $edit_column_images.='{ '."\n";
                $edit_column_images.='   echo "<h5>'.getCapitalizedNiceName($data).'</h5>";'."\n";
                $edit_column_images.='   if (!isset($edit_redirect_'.$data.')) $edit_redirect_'.$data.'="?command=display_php&base_path=".$_REQUEST["base_path"]."&path=".$_REQUEST["path"]."&id=".$'.$table_element.'_view->'.$table_element.'->getId();'."\n";
                $edit_column_images.='   $'.$table_element.'_view->renderUpload'.getFunctionName($data).'($'.$table_element.'_view->'.$table_element.',TRUE,$edit_redirect_'.$data.',"",$role->isUserAnyOf($allowed_edit));'."\r\n";
                $edit_column_images.='} '."?>\n";
            }
            else
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
        if (!in_array($data,$register_columns))
        {
			$register_defaults.="<input type='hidden' name='$data' value='' />\n";
		}
    } else if (!(strpos(strtolower($data_type),'tinyint')===FALSE))
    {
		$type="boolean";
	}	else if ((!(strpos(strtolower($data_type),'int')===FALSE)) || (!(strpos(strtolower($data_type),'float')===FALSE))|| (!(strpos(strtolower($data_type),'double')===FALSE)))
    {   //it is a number
        if (count($foreign)>0)
        {
            if (array_key_exists($data,$foreign))
            {
                $type="select";
                $table = $foreign[$data];
                
            } else
            {
                $type="number";
            }
        }  else if ((strpos($data,'_id')>0) || (strpos($data,'_id_')>0))
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
            
            /*$filter_ids_array_public.='    public $'.$data."_lst;\r\n";
            $filter_ids_array.='        $this->'.$data."_lst=array();\r\n";
            $filterlist[]=$data;
            $fill_filters.='        $'.$data.'=$item->get'.getFunctionName($data)."();\r\n";
            $fill_filters.='        $this->'.$data."_lst[$".$data.']=$'.$data.";\r\n";*/
            $distinct_columns.='    public $distinct_'.$data."_lst;\r\n";
            $fill_filters.=str_replace('[DISTINCT_FIELD]',$data,$fill_filters_tem);
        }
        else
        {
            $type="number";
        }
        if (!in_array($data,$register_columns))
        {
			$register_defaults.="<input type='hidden' name='$data' value='0' />\n";
		}

    } else if ((!(strpos(strtolower($data_type),'datetime')===FALSE)) || (!(strpos(strtolower($data_type),'timestamp')===FALSE)))
    {   //it is a datetime
        $type="datetime";
        if (!in_array($data,$register_columns))
        {
			$register_defaults.="<input type='hidden' name='$data' value='2000-01-01 00:00:00' />\n";
		}

    } else if (!(strpos(strtolower($data_type),'date')===FALSE))
    {   //it is a date
        $type="date";
        if (!in_array($data,$register_columns))
        {
			$register_defaults.="<input type='hidden' name='$data' value='2000-01-01' />\n";
		}

    } else if (!(strpos(strtolower($data_type),'time')===FALSE))
    {   //it is a time
        $type="time";
        if (!in_array($data,$register_columns))
        {
			$register_defaults.="<input type='hidden' name='$data' value='00:00:00' />\n";
		}

    } else if (!(strpos(strtolower($data_type),'enum')===FALSE))
    {   //it is a select or radio buttons
        $type="enum";
        $options = getEnumOptions($mysqli,$table,$data);
        
        $default_option = '';
        foreach($options as $idx=>$opt)
        {
            $default_option = $opt;
            break;
        }
        $fill_filters_tem1=str_replace('$'.$table_element."->query('SELECT DISTINCT [DISTINCT_FIELD] from $table')","array('".implode("','",$options)."')",$fill_filters_tem);
        $distinct_columns.='    public $distinct_'.$data."_lst;\r\n";
        $fill_filters_tem1=str_replace('[DISTINCT_FIELD]',$data,$fill_filters_tem1);
        $fill_filters.=str_replace("['".$data."']",'',$fill_filters_tem1);
        if (!in_array($data,$register_columns))
        {
			if ($row['column_default']) $register_defaults.="<input type='hidden' name='$data' value='".$row['column_default']."' />\n";
			else $register_defaults.="<input type='hidden' name='$data' value='".$default_option."' />\n";
		}
      
    } 
    $edit_action = ''; 
    $label = $data;
    $list_select='';
    //edit properties
    $field_props.='        $this->columns["'.$data.'"]=array(\'type\'=>"'.$type.'",\'label\'=>"'.getFunctionName($data).'",\'mode\'=>"list",\'attributes\'=>array(),\'option_filters\'=>array());'."\n"; 
    $edit_props.='        $this->columns["'.$data.'"][\'mode\']="edit";'."\n"; 
    /*$edit_props.='        $this->edit_'.$data.'=true;// allow '.$data.' input to transition from lock to edit mode'."\n";
    $edit_props.='        $this->readonly_'.$data.'=false;// set the '.$data.' as read only'."\n";
    $edit_props.='        $this->hidden_'.$data.'=false;// set the '.$data.' as hidden'."\n";
    $edit_props.='        $this->'.$data.'_attributes=array();// attributes such as id, style etc that will be added to the '.$data.' input'."\n";
    $edit_props.='        $this->'.$data.'_option_filters=array();// filters for selection of dropdown options'."\n";*/
    //$edit_props.='        $this->edit_remove_'.$data.'=false;// remove the '.$data.' input from the edit form'."\n";
    
    //new properties
    $new_props.='        $this->columns["'.$data.'"][\'mode\']="new";'."\n"; 
    /*$new_props.='        $this->edit_'.$data.'=false;'."\n";
    $new_props.='        $this->readonly_'.$data.'=false;// set the '.$data.' as read only'."\n";
    $new_props.='        $this->hidden_'.$data.'=false;// set the '.$data.' as hidden'."\n";
    $new_props.='        $this->'.$data.'_attributes=array();// attributes such as id, style etc that will be added to the '.$data.' input'."\n";
    $new_props.='        $this->'.$data.'_option_filters=array();// filters for selection of dropdown options'."\n";*/
    //$new_props.='        $this->new_remove_'.$data.'=false;// remove the '.$data.' input from the new form'."\n";

    if ($type === "enum")
    {
		$element_ops=file_get_contents(__DIR__.'/view_render_enum.tem');
		$element_ops=str_replace('[ENUM_CHOICES]',convertArrayToString($options),$element_ops);
		$element_ops=str_replace('[DATA]',$data,$element_ops);
		$element_ops=str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$element_ops);
		$element_ops=str_replace('[CUSTOM]','',$element_ops);
        $element_ops=str_replace('[TABLE_ELEMENT]',$table_element,$element_ops);
        
        $view_object.=$element_ops;   
        $view_object_comment.="/*\r\n".$element_ops."\r\n*/\r\n";
        $element.='<?php'."\n";
        //$element.='    $edit_'.$data.'=true;'."\n";
        $edit_props.='        $this->columns["'.$data.'"]["attributes"]["class"]="chosen";'."\n";
        $new_props.='        $this->columns["'.$data.'"]["attributes"]["class"]="chosen";'."\n";
        //$element.='    $'.$data.'_attributes=array("class"=>"chosen");'."\n";
        $element.='    $'.$table_element.'_view->render'.getFunctionName($data).'Enum();'."\n";
        $element.='?>'."\n";
        $element_new.='<?php'."\n";
        //$element_new.='    $'.$data.'_attributes=array("class"=>"chosen");'."\n";
        $element_new.= '    $'.$table_element.'_view->render'.getFunctionName($data).'Enum();'."\n";
        $element_new.='?>'."\n";
    } else
    if ($type === "select_multiple")
    {
        $foreign_alias = getForeignAliasName($data);        
        $foreign_table = getForeignTableElement($data);
        if (!$foreign_alias) $foreign_alias=$foreign_table;
        $foreign_require.= "require_once '".getFunctionName($foreign_table).".php';\n";
        
        $element_ops=file_get_contents(__DIR__.'/view_render_select_multiple.tem');
        $element_ops=str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$element_ops);
        $element_ops=str_replace('[FOREIGN_TABLE_FUNCTION_NAME]',getFunctionName($foreign_table),$element_ops);
        $element_ops=str_replace('[FOREIGN_ALIAS_FUNCTION_NAME]',getFunctionName($foreign_alias),$element_ops);
        $element_ops=str_replace('[TABLE_ELEMENT]',$table_element,$element_ops);
        $element_ops=str_replace('[DATA]',$data,$element_ops);
        $element_ops=str_replace('[FOREIGN_ALIAS]',$foreign_alias,$element_ops);
        $element_ops=str_replace('[CUSTOM]',"multiple=''",$element_ops);

        $view_object.=$element_ops;
        $view_object_comment.="/*\r\n".$element_ops."\r\n*/\r\n";

        $element.='<?php'."\n";
        //$element.='    $edit_'.$data.'=true;'."\n";
        $edit_props.='        $this->columns["'.$data.'"]["attributes"]["class"]="chosen";'."\n";
        $new_props.='        $this->columns["'.$data.'"]["attributes"]["class"]="chosen";'."\n";
        $element.='    $'.$table_element.'_view->render'.getFunctionName($data).'Select();'."\n";
        $element.='?>'."\n";
        $element_new.='<?php'."\n";
        $element_new.= '    $'.$table_element.'_view->render'.getFunctionName($data).'Select();'."\n";
        $element_new.='?>'."\n";
        
        if ($foreign_alias)
        {
            $label = $foreign_alias;
        } else
        {
            $label = getForeignTableElement($data);
        }             
        
        $list_select =' echo $'.$table_element.'->get'.getFunctionName($label)."()";
        $edit_action = '<button type="button" mx-container="div.page" class="mx- button" mx-click="?command=display_rest&base_path='.$paths->admin.'/'.getFunctionName($foreign_table).'s.dir&path=Edit_'.getFunctionName($foreign_table).'.hid.rest.php&id=<?php echo $oid;?>" href="#"><li class="fa fa-pencil"></li> '.$label.'</button>';
    } else
    if ($type === "select")
    {
        $foreign_alias = getForeignAliasName($data);
        $element = "";
        $element_new = "";
        $foreign_table = getForeignTableElement($data);
        if (!$foreign_alias) $foreign_alias=$foreign_table;
        $foreign_require.= "require_once '".getFunctionName($foreign_table).".php';\n";
        
        $element_ops=file_get_contents(__DIR__.'/view_render_select.tem');
        $element_ops=str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$element_ops);
        $element_ops=str_replace('[FOREIGN_TABLE_FUNCTION_NAME]',getFunctionName($foreign_table),$element_ops);
        $element_ops=str_replace('[FOREIGN_ALIAS_FUNCTION_NAME]',getFunctionName($foreign_alias),$element_ops);
        $element_ops=str_replace('[TABLE_ELEMENT]',$table_element,$element_ops);
        $element_ops=str_replace('[DATA]',$data,$element_ops);
        $element_ops=str_replace('[FOREIGN_ALIAS]',$foreign_alias,$element_ops);
        $element_ops=str_replace('[CUSTOM]','',$element_ops);
        

        $view_object.=$element_ops;
        
        $view_object_comment.="/*\r\n".$element_ops."\r\n*/\r\n";

        $element.='<?php'."\n";
        // $element.='    $edit_'.$data.'=true;'."\n";
        $edit_props.='        $this->columns["'.$data.'"]["attributes"]["class"]="chosen";'."\n";
        $new_props.='        $this->columns["'.$data.'"]["attributes"]["class"]="chosen";'."\n";
        $element.='    $'.$table_element.'_view->render'.getFunctionName($data).'Select();'."\n";
        $element.='?>'."\n";
        $element_new.='<?php'."\n";
        $element_new.= '    $'.$table_element.'_view->render'.getFunctionName($data).'Select();'."\n";
        $element_new.='?>'."\n";
        if ($foreign_alias)
        {
            $label = $foreign_alias;
        } else
        {
            $label = getForeignTableElement($data);
        }            
        
        $list_select =' echo $'.$table_element.'->get'.getFunctionName($label)."()";
        $edit_action = '<button type="button" mx-container="div.page" class="mx- button" mx-click="?command=display_rest&base_path='.$paths->admin.'/'.getFunctionName($foreign_table).'s.dir&path=Edit_'.getFunctionName($foreign_table).'.hid.rest.php&id=<?php echo $oid;?>" href="#"><li class="fa fa-pencil"></li> '.$label.'</button>';
    } else
    if ($type === "select_type")
    {
        $foreign_alias = getForeignAliasName($data);

        $element_ops='';

        $element_ops=file_get_contents(__DIR__.'/view_render_select_type.tem');
        $element_ops=str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$element_ops);
        $element_ops=str_replace('[FOREIGN_TABLE_FUNCTION_NAME]',getFunctionName($foreign_table),$element_ops);
        $element_ops=str_replace('[FOREIGN_ALIAS_FUNCTION_NAME]',getFunctionName($foreign_alias),$element_ops);
        $element_ops=str_replace('[TABLE_ELEMENT]',$table_element,$element_ops);
        $element_ops=str_replace('[DATA]',$data,$element_ops);
        $element_ops=str_replace('[FOREIGN_ALIAS]',$foreign_alias,$element_ops);
        $element_ops=str_replace('[CUSTOM]','',$element_ops);
        
        $view_object.=$element_ops;
        
        $view_object_comment.="/*\r\n".$element_ops."\r\n*/\r\n";

        $element.='<?php'."\n";
        //$element.='    $edit_'.$data.'=true;'."\n";
        $edit_props.='        $this->columns["'.$data.'"]["attributes"]["class"]="chosen";'."\n";
        $new_props.='        $this->columns["'.$data.'"]["attributes"]["class"]="chosen";'."\n";
        $element.='    $'.$table_element.'_view->render'.getFunctionName($data).'Select();'."\n";
        $element.='?>'."\n";
        $element_new.='<?php'."\n";
        $element_new.= '    $'.$table_element.'_view->render'.getFunctionName($data).'Select();'."\n";
        $element_new.='?>'."\n";

        if ($foreign_alias)
        {
            $label = $foreign_alias;
        } else
        {
            $label = '<?PHP echo $'.$table_element.'->getType(); ?>';
        }            
        
        $list_select =' echo $'.$table_element.'->getTypeObject()';
    } else
    {
        if ($type==='datetime')
        {
            if ((trim(strtolower($data))==='created') && (($data_type==='datetime') || ($data_type==='timestamp')))
            {
                $element_ops=file_get_contents(__DIR__.'/view_render_text.tem');
                $element_ops=str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$element_ops);
                $element_ops=str_replace('[TABLE_ELEMENT]',$table_element,$element_ops);
                $element_ops=str_replace('[DATA]',$data,$element_ops);
                $element_ops=str_replace('[CUSTOM_CLASS]','date',$element_ops);
                $view_object.=$element_ops;
                $view_object_comment.="/*\r\n".$element_ops."\r\n*/\r\n";
                
 /*               $element = '<?php echo $'.$table_element."_view->".$table_element.'->get'.getFunctionName($data)."();?>";
                $element_new = '<?php echo $'.$table_element."_view->".$table_element.'->get'.getFunctionName($data)."();?>";*/
            } else if ((trim(strtolower($data))==='modified') && (($data_type==='datetime')))
            {
                $element_ops=file_get_contents(__DIR__.'/view_render_text.tem');
                $element_ops=str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$element_ops);
                $element_ops=str_replace('[TABLE_ELEMENT]',$table_element,$element_ops);
                $element_ops=str_replace('[DATA]',$data,$element_ops);
                $element_ops=str_replace('[CUSTOM_CLASS]','date',$element_ops);
                $view_object.=$element_ops;
                $view_object_comment.="/*\r\n".$element_ops."\r\n*/\r\n";
                /*
                $element = '<?php $now = new DateTime("now");'."\n";
                $element.= 'echo $'.$table_element."_view->".$table_element.'->get'.getFunctionName($data)."();\n";
                $element.= 'echo \'<input type="hidden" value="\'.$now->format("Y-m-d H:i:s").\'" >\';'."?>\n";
                $element_new = $element;*/

            } else
            {
               
                $element_ops=file_get_contents(__DIR__.'/view_render_text.tem');
                $element_ops=str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$element_ops);
                $element_ops=str_replace('[TABLE_ELEMENT]',$table_element,$element_ops);
                $element_ops=str_replace('[DATA]',$data,$element_ops);
                $element_ops=str_replace('[CUSTOM_CLASS]','datetime',$element_ops);
                
                $view_object.=$element_ops;
                $view_object_comment.="/*\r\n".$element_ops."\r\n*/\r\n";
 /*               $element.='<?php'."\n";
                //$element.='    $edit_'.$data.'=true;'."\n";
                $element.='    if (!isset($'.$table_element.'_view->'.$data.'_attributes["placeholder"])) '."\n";
                $element.='    { '."\n";
                $element.='        $'.$table_element.'_view->'.$data.'_attributes=array("placeholder"=>"'.getCapitalizedNiceName($data).'");'."\n";
                $element.='    } '."\n";
                $element.='    $'.$table_element.'_view->render'.getFunctionName($data).'($'.$table_element.'_view->'.$table_element.',isset($edit_'.$data.'),isset($readonly_'.$data.'),isset($hidden_'.$data.'),$'.$data.'_attributes);'."\n";
                $element.='?>'."\n";
                $element_new.='<?php'."\n";
                $element_new.='    if (!isset($'.$table_element.'_view->'.$data.'_attributes["placeholder"])) '."\n";
                $element_new.='    { '."\n";
                $element_new.='        $'.$table_element.'_view->'.$data.'_attributes=array("placeholder"=>"'.getCapitalizedNiceName($data).'");'."\n";
                $element_new.='    } '."\n";
                $element_new.= '    $'.$table_element.'_view->render'.getFunctionName($data).'($'.$table_element.'_view->'.$table_element.',isset($edit_'.$data.'),isset($readonly_'.$data.'),isset($hidden_'.$data.'),$'.$data.'_attributes);'."\n";
                $element_new.='?>'."\n";             */   
            }
        } else if ($type==='date')
        {
            if ((trim(strtolower($data))==='created') && (($data_type==='date') || ($data_type==='timestamp')))
            {
                $element_ops=file_get_contents(__DIR__.'/view_render_text.tem');
                $element_ops=str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$element_ops);
                $element_ops=str_replace('[TABLE_ELEMENT]',$table_element,$element_ops);
                $element_ops=str_replace('[DATA]',$data,$element_ops);
                $element_ops=str_replace('[CUSTOM_CLASS]','date',$element_ops);
                
                $view_object.=$element_ops;
                $view_object_comment.="/*\r\n".$element_ops."\r\n*/\r\n";
                /*
                $element = '<?php echo $'.$table_element."_view->".$table_element.'->get'.getFunctionName($data)."();?>";
                $element_new = '<?php echo $'.$table_element."_view->".$table_element.'->get'.getFunctionName($data)."();?>";*/
            } else if ((trim(strtolower($data))==='modified') && (($data_type==='date') || ($data_type==='timestamp')))
            {
                $element_ops=file_get_contents(__DIR__.'/view_render_text.tem');
                $element_ops=str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$element_ops);
                $element_ops=str_replace('[TABLE_ELEMENT]',$table_element,$element_ops);
                $element_ops=str_replace('[DATA]',$data,$element_ops);
                $element_ops=str_replace('[CUSTOM_CLASS]','date',$element_ops);
                
                $view_object.=$element_ops;
                $view_object_comment.="/*\r\n".$element_ops."\r\n*/\r\n";                
                
               /* $element = '<?php $now = new DateTime("now");'."\n";
                $element.= 'echo $'.$table_element."_view->".$table_element.'->get'.getFunctionName($data)."();\n";
                $element.= 'echo \'<input type="hidden" value="\'.$now->format("Y-m-d").\'" >\';'."?>\n";
                $element_new = $element;*/

            } else
            {
                /*$element = "<span class='view_input'><?php echo ".'$'.$table_element."_view->".$table_element.'->get'.getFunctionName($data)."();?></span><input type='text' class='date edit_input' name='$data' value='<?php echo $".$table_element."_view->".$table_element.'->get'.getFunctionName($data)."();?>' ></input>";
                $element_new = "<input type='text' class='date' name='$data' value='<?php echo $".$table_element."_view->".$table_element.'->get'.getFunctionName($data)."();?>' ></input>";*/
                $element_ops=file_get_contents(__DIR__.'/view_render_text.tem');
                $element_ops=str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$element_ops);
                $element_ops=str_replace('[TABLE_ELEMENT]',$table_element,$element_ops);
                $element_ops=str_replace('[DATA]',$data,$element_ops);
                $element_ops=str_replace('[CUSTOM_CLASS]','date',$element_ops);
                
                $view_object.=$element_ops;
                $view_object_comment.="/*\r\n".$element_ops."\r\n*/\r\n";
/*                $element.='<?php'."\n";
                //$element.='    $edit_'.$data.'=true;'."\n";
                $element.='    if (!isset($'.$table_element.'_view->'.$data.'_attributes["placeholder"])) '."\n";
                $element.='    { '."\n";
                $element.='        $'.$table_element.'_view->'.$data.'_attributes=array("placeholder"=>"'.getCapitalizedNiceName($data).'");'."\n";
                $element.='    } '."\n";
                $element.='    $'.$table_element.'_view->render'.getFunctionName($data).'($'.$table_element.'_view->'.$table_element.',isset($edit_'.$data.'),isset($readonly_'.$data.'),isset($hidden_'.$data.'),$'.$data.'_attributes);'."\n";
                $element.='?>'."\n";
                $element_new.='<?php'."\n";
                $element_new.='    if (!isset($'.$table_element.'_view->'.$data.'_attributes["placeholder"])) '."\n";
                $element_new.='    { '."\n";
                $element_new.='        $'.$table_element.'_view->'.$data.'_attributes=array("placeholder"=>"'.getCapitalizedNiceName($data).'");'."\n";
                $element_new.='    } '."\n";
                $element_new.= '    $'.$table_element.'_view->render'.getFunctionName($data).'($'.$table_element.'_view->'.$table_element.',isset($edit_'.$data.'),isset($readonly_'.$data.'),isset($hidden_'.$data.'),$'.$data.'_attributes);'."\n";
                $element_new.='?>'."\n";                   */
            }
        }
        else if ($type==='textarea')
        {
            $element_ops=file_get_contents(__DIR__.'/view_render_textarea.tem');
            $element_ops=str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$element_ops);
            $element_ops=str_replace('[TABLE_ELEMENT]',$table_element,$element_ops);
            $element_ops=str_replace('[DATA]',$data,$element_ops);
            $element_ops=str_replace('[CUSTOM_CLASS]','',$element_ops);
            
            $view_object.=$element_ops;
            $view_object_comment.="/*\r\n".$element_ops."\r\n*/\r\n";
/*            $element.='<?php'."\n";
            //$element.='    $edit_'.$data.'=true;'."\n";
            $edit_props.='//        $this->'.$data.'_attributes["class"]="jwysiwyg";'."\n";
            $element.='    if (!isset($'.$table_element.'_view->'.$data.'_attributes["placeholder"])) '."\n";
            $element.='    { '."\n";
            $element.='        $'.$table_element.'_view->'.$data.'_attributes=array("placeholder"=>"'.getCapitalizedNiceName($data).'");'."\n";
            $element.='    } '."\n";
            $element.='    $'.$table_element.'_view->render'.getFunctionName($data).'Textarea($'.$table_element.'_view->'.$table_element.',isset($edit_'.$data.'),isset($readonly_'.$data.'),isset($hidden_'.$data.'),$'.$data.'_attributes);'."\n";
            $element.='?>'."\n";
            $element_new.='<?php'."\n";
            $element_new.='    if (!isset($'.$table_element.'_view->'.$data.'_attributes["placeholder"])) '."\n";
            $element_new.='    { '."\n";
            $element_new.='        $'.$table_element.'_view->'.$data.'_attributes=array("placeholder"=>"'.getCapitalizedNiceName($data).'");'."\n";
            $element_new.='    } '."\n";
        
            $element_new.= '    $'.$table_element.'_view->render'.getFunctionName($data).'Textarea($'.$table_element.'_view->'.$table_element.',isset($edit_'.$data.'),isset($readonly_'.$data.'),isset($hidden_'.$data.'),$'.$data.'_attributes);'."\n";
            $element_new.='?>'."\n";                 */
        } 
        else if (($type==='number') && ($table_element==='user') && ($data==='role'))
        {
            $foreign_require.= "require_once 'Role.php';\n";
            
            $element_ops=file_get_contents(__DIR__.'/view_render_user_role.tem');
            $element_ops=str_replace('[TABLE_ELEMENT]',$table_element,$element_ops);
            $element_ops=str_replace('[DATA]',$data,$element_ops);
            $element_ops=str_replace('[CUSTOM_CLASS]','',$element_ops);
            
            $view_object.=$element_ops;
            $view_object_comment.="/*\r\n".$element_ops."\r\n*/\r\n";
            
 /*           $element.='<?php'."\n";
            //$element.='    $edit_'.$data.'=true;'."\n";
            $element.='    if (!isset($'.$table_element.'_view->'.$data.'_attributes["placeholder"])) '."\n";
            $element.='    { '."\n";
            $element.='        $'.$table_element.'_view->'.$data.'_attributes=array("placeholder"=>"'.getCapitalizedNiceName($data).'");'."\n";
            $element.='    } '."\n";
            $element.='    $'.$table_element.'_view->render'.getFunctionName($data).'Value($'.$table_element.'_view->'.$table_element.',isset($edit_'.$data.'),isset($readonly_'.$data.'),isset($hidden_'.$data.'),$'.$data.'_attributes);'."\n";
            $element.='?>'."\n";
            $element_new.='<?php'."\n";
            $element_new.='    if (!isset($'.$table_element.'_view->'.$data.'_attributes["placeholder"])) '."\n";
            $element_new.='    { '."\n";
            $element_new.='        $'.$table_element.'_view->'.$data.'_attributes=array("placeholder"=>"'.getCapitalizedNiceName($data).'");'."\n";
            $element_new.='    } '."\n";
            
            $element_new.= '    $'.$table_element.'_view->render'.getFunctionName($data).'Value($'.$table_element.'_view->'.$table_element.',isset($edit_'.$data.'),isset($readonly_'.$data.'),isset($hidden_'.$data.'),$'.$data.'_attributes);'."\n";
            $element_new.='?>'."\n"; */
            
        } else if (($type==='password') && ($table_element==='user'))
        {
            ; //skip passwords
        } else if (($type=='image'))
        {
 /*           $element.='<?php'."\n";
            //$element.='    $edit_'.$data.'=true;'."\n";
            $element.='    if (!isset($'.$table_element.'_view->'.$data.'_attributes["placeholder"])) '."\n";
            $element.='    { '."\n";
            $element.='        $'.$table_element.'_view->'.$data.'_attributes=array("placeholder"=>"'.getCapitalizedNiceName($data).'");'."\n";
            $element.='    } '."\n";              
            $element.='    $'.$table_element.'_view->render'.getFunctionName($data).'($'.$table_element.'_view->'.$table_element.',$'.$data.'_attributes);'."\n";
            $element.='?>'."\n";
            $element_new.='<?php'."\n";
            $element_new.='    if (!isset($'.$table_element.'_view->'.$data.'_attributes["placeholder"])) '."\n";
            $element_new.='    { '."\n";
            $element_new.='        $'.$table_element.'_view->'.$data.'_attributes=array("placeholder"=>"'.getCapitalizedNiceName($data).'");'."\n";
            $element_new.='    } '."\n";
          
            $element_new.='    $'.$table_element.'_view->render'.getFunctionName($data).'($'.$table_element.'_view->'.$table_element.',$'.$data.'_attributes);'."\n";
            $element_new.='?>'."\n";             */
        }
        else
        {                                
                $element_ops=file_get_contents(__DIR__.'/view_render_text.tem');
                $element_ops=str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$element_ops);
                $element_ops=str_replace('[TABLE_ELEMENT]',$table_element,$element_ops);
                $element_ops=str_replace('[DATA]',$data,$element_ops);
                $element_ops=str_replace('[CUSTOM_CLASS]','',$element_ops);
                
                $view_object.=$element_ops;
                $view_object_comment.="/*\r\n".$element_ops."\r\n*/\r\n";
/*                $element.='<?php'."\n";
                //$element.='    $edit_'.$data.'=true;'."\n";
                $element.='    if (!isset($'.$table_element.'_view->'.$data.'_attributes["placeholder"])) '."\n";
                $element.='    { '."\n";
                $element.='        $'.$table_element.'_view->'.$data.'_attributes=array("placeholder"=>"'.getCapitalizedNiceName($data).'");'."\n";
                $element.='    } '."\n";
                $element.='    $'.$table_element.'_view->render'.getFunctionName($data).'();'."\n";
                $element.='?>'."\n";
                $element_new.='<?php'."\n";
                $element_new.='    if (!isset($'.$table_element.'_view->'.$data.'_attributes["placeholder"])) '."\n";
                $element_new.='    { '."\n";
                $element_new.='        $'.$table_element.'_view->'.$data.'_attributes=array("placeholder"=>"'.getCapitalizedNiceName($data).'");'."\n";
                $element_new.='    } '."\n";
                   
                $element_new.= '    $'.$table_element.'_view->render'.getFunctionName($data).'($'.$table_element.'_view->'.$table_element.',isset($edit_'.$data.'),isset($readonly_'.$data.'),isset($hidden_'.$data.'),$'.$data.'_attributes);'."\n";
                $element_new.='?>'."\n";         */     
        }
    }
    if ($data === $primary)
    {
        $element = "<input type='$type' style='display:none' name='$data' value='<?php echo ".'$'.$table_element.'_view->'.$table_element.'->get'.getFunctionName($data)."();?>' ></input>".'<?php echo $'.$table_element.'_view->'.$table_element.'->get'.getFunctionName($data).'();?>';
        $tbody.="<tr id='tr_$data' ><td class='label'>".getCapitalizedNiceName($label)."</td><td id='td_$data' >$element</td></tr>\n";
        $tr_list.="<?php if (in_array('$data',".'$headers'.")) { ?><td><button type='button' class='mx- ' mx-container='<?php echo ".'$'.$table_element.'_view->container'.";?>' mx-click='?command=display_rest&base_path=".$paths->admin.'/'.$controller_name.'.dir&path=Edit_'.getFunctionName($table_element).'.hid.rest.php&id=<?php echo $'.$table_element.'->getId();?>&object='.$class_name."' ><i class='fa fa-edit'></i><?php echo ".'$'.$table_element."->getId();?></button></td> <?php }?>\n";
        if (in_array('link_image',$argv))
        {
            $tr_list.='<?php if (in_array("icon",$headers)) {?>';
            $tr_list.='<td><button type="button" class="mx- button" mx-container="<?php echo $'.$table_element.'_view->container;?>" mx-click="?command=display_rest&path=<?php echo $'.$table_element.'_view->paths->admin;?>/'.getFunctionName($table).'.dir/Edit_'.getFunctionName($table_element).'.hid.rest.php&id=<?php echo $'.$table_element."->getId();?>&object=User".'"'." ><img class='profile_icon' src='img/displaypng.php?id=<?php echo ".'$id;?>&image=<?php echo $'.$table_element.'->getImageIcon();?>&key=<?php echo urlencode($current_user->getSecurekey());?>'."' /></button></td>\n";
            $tr_list.='<?php }?>';
        }

    } else
    {
        
        if ($type==="password")
        {
            ;
        } else
        {
            $tbody.='<?php'."\n"; 
            $tbody.='if (!($'.$table_element.'_view->edit_remove_'.$data.'))'."\n";
            $tbody.='{?>'."\n";
            $tbody.="<tr id='tr_$data'><td class='label'>".getCapitalizedNiceName($label)."</td><td id='td_$data'>$element</td></tr>\n";
            $tbody.='<?php'."\n"; 
            $tbody.='} ?>'."\n";

            $tbody_new.='<?php'."\n"; 
            $tbody_new.='if (!($'.$table_element.'_view->new_remove_'.$data.'))'."\n";
            $tbody_new.='{?>'."\n";
            $tbody_new.="<tr id='tr_$data'><td class='label'>".getCapitalizedNiceName($label)."</td><td id='td_$data'>$element_new</td></tr>\n";
            $tbody_new.='<?php'."\n";
            $tbody_new.='} ?>'."\n";
        }
        if ( (($current_headers) && in_array($data,$current_headers)) || (count($current_headers)==0))
        {
            if ($list_select)
            {
                $object_list_value=file_get_contents('view_object_list_value.tem');
                $object_list_value=str_replace('[GET_VALUE]',$list_select,$object_list_value);
                $object_list_value=str_replace('[DATA]',$data,$object_list_value);
                $object_list_value=str_replace('[TABLE_ELEMENT]',$table_element,$object_list_value);
                $object_list_values.=$object_list_value;
                $tr_list.="\n<?php if (in_array('$data',".'$'."headers)) { ?>\n".'	<td><?php $'.$table_element.'_view->get'.getFunctionName($data).'($'.$table_element.");?></td> <?php }?>";
            } 

            
            else
            {
                if (($type==='number') && ($table_element==='user') && ($data==='role'))
                {
                    $object_list_value=file_get_contents('view_object_list_value.tem');
                    $get_rolename= '$role = new Role();'."\n";
                    $get_rolename.= 'echo $role->getRoleName($'.$table_element.'->get'.getFunctionName($data)."())";
                    $object_list_value=str_replace('[GET_VALUE]',$get_rolename,$object_list_value);
                    $object_list_value=str_replace('[DATA]',$data,$object_list_value);
                    $object_list_value=str_replace('[TABLE_ELEMENT]',$table_element,$object_list_value);
                    $object_list_values.=$object_list_value;
                    $tr_list.="\n<?php if (in_array('$data',".'$'."headers)) { ?>\n".'	<td><?php $'.$table_element.'_view->get'.getFunctionName($data).'($'.$table_element.");?></td> <?php }?>";
                } else
                {
                    $object_list_value=file_get_contents('view_object_list_value.tem');
                    $object_list_value=str_replace('[GET_VALUE]','echo $'.$table_element.'->get'.getFunctionName($data)."()",$object_list_value);
                    $object_list_value=str_replace('[DATA]',$data,$object_list_value);
                    $object_list_value=str_replace('[TABLE_ELEMENT]',$table_element,$object_list_value);
                    $object_list_values.=$object_list_value;
                    $tr_list.="\n<?php if (in_array('$data',".'$'."headers)) { ?>\n".'	<td><?php $'.$table_element.'_view->get'.getFunctionName($data).'($'.$table_element.");?></td> <?php }?>";
                }
            }
        }
    }
    //if ($data=='users_ids_access_lists') die($list_head.' vs '.getForeignAliasName($data).'<br>'.json_encode($current_headers));
    if ( ($current_headers) && (in_array($data,$current_headers)))
    {
    //create sort header
//    if (count($filterlist) || count($sortlist))
//    {
//        if (in_array($data, $sortlist))
//        {
            $sort_td_header_tem = file_get_contents('sort_td_header.tem');
            $sort_td_header_filled = str_replace('[SORT_FIELD]',$data,$sort_td_header_tem);
            //$th_list.=$sort_td_header_filled;
            
            if (in_array($data,$default_list_headers))
            {
                /*$th_list.="<?php if (in_array('$data',".'$headers'.")) { ?> <th id='header_$data'  field='$data'><a class='toggle_sort fa fa-sort' href='#'> ".getCapitalizedNiceName($list_head)."</a> $sort_td_header_filled</th> <?php } ?>\r\n";*/
                $theaders[$data]=array('label'=>getCapitalizedNiceName($list_head),'sort'=>1,'search'=>1,'class'=>'','attributes'=>array());
            }
            else
            {
                /*$th_list.="<?php if (in_array('$data',".'$headers'.")) { ?> <th id='header_$data'  field='$data'> ".getCapitalizedNiceName($list_head)."<?php } ?>\r\n";*/
                $theaders[$data]=array('label'=>getCapitalizedNiceName($list_head),'sort'=>'0','search'=>'0','class'=>'','attributes'=>array());
            }
//        } else
//        {
//			   $th_list.="<th>$list_head</th>";
//        }
//        if (in_array($data, $filterlist))
//        {
			
            $foreign_field_name = getForeignTableElement($data);
            $foreign_field_function_name = getFunctionName($foreign_field_name);
            
            
            
            if ($foreign_field_name)
            {
                
                if ($foreign_field_name === 'type')
                {
                    $names = getColumNames(generateTableNameFromElement($foreign_field_name),$db,$mysqli);
                    $filter_td_header = file_get_contents('filter_td_blank_header.tem');
                    $filter_td_header = str_replace('[FILTER_FIELD]',$data,$filter_td_header);
                    $filter_td_header = str_replace('[FILTER_FIELD_FUNCTION_NAME]',getFunctionName($data),$filter_td_header);
                    $filter_td_header = str_replace('[FOREIGN_FIELD_NAME]',$foreign_field_name,$filter_td_header);
                    $filter_td_header = str_replace('[FOREIGN_FIELD_FUNCTION_NAME]',$foreign_field_function_name,$filter_td_header);
                } else
                {
                
                    $names = getColumNames(generateTableNameFromElement($foreign_field_name),$db,$mysqli);
                    $filter_td_header = file_get_contents('filter_td_header.tem');
                    $filter_td_header = str_replace('[FILTER_FIELD]',$data,$filter_td_header);
                    $filter_td_header = str_replace('[FILTER_FIELD_FUNCTION_NAME]',getFunctionName($data),$filter_td_header);
                    $filter_td_header = str_replace('[FOREIGN_FIELD_NAME]',$foreign_field_name,$filter_td_header);
                    $filter_td_header = str_replace('[FOREIGN_FIELD_FUNCTION_NAME]',$foreign_field_function_name,$filter_td_header);
                    $filter_sort_field = 'idx';
                    if (in_array('name',$names))
                    {
                        $filter_sort_field = 'name';
                    } else 
                    {
                        foreach ($names as $name)
                        {
                            if (!(strpos($name,'name')===false))
                            {
                                $filter_sort_field = $name;
                            } 
                        }
                        if ($filter_sort_field==='idx')
                        {
                            foreach ($names as $name)
                            {
                                if (!(strpos($name,'name')===false))
                                {
                                    $filter_sort_field = $name;
                                }                         
                            }                    
                        }
                        if ($filter_sort_field==='idx')
                        {
                            foreach ($names as $name)
                            {
                                if (!(strpos($name,'date')===false))
                                {
                                    $filter_sort_field = $name;
                                }                         
                            }
                        }
                        if ($filter_sort_field==='idx')
                        {
                            $filter_sort_field='id';
                        }
                    }
                    
                    
                }
                $filter_td_header = str_replace('[FILTER_SORT_FIELD]',$filter_sort_field,$filter_td_header);
                $view_object.=$filter_td_header;

            } else if (($data=='role') && ($table_element=='user'))
			{
				$filter_td_header = file_get_contents('filter_td_role_header.tem');
                $filter_td_header = str_replace('[FILTER_FIELD]',$data,$filter_td_header);
				$view_object.=$filter_td_header;

				
			} 
            else if ($type=='enum')
			{
				$filter_td_header = file_get_contents('filter_td_enum_header.tem');
                $filter_td_header = str_replace('[FILTER_FIELD]',$data,$filter_td_header);
                $filter_td_header = str_replace('[FILTER_FIELD_FUNCTION_NAME]',getFunctionName($data),$filter_td_header);
                $filter_td_header = str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$filter_td_header);
                $filter_td_header = str_replace('[TABLE_ELEMENT]',$table_element,$filter_td_header);
                $filter_td_header = str_replace('[ENUM_CHOICES]',convertArrayToString($options),$filter_td_header);
                
				$view_object.=$filter_td_header;

			} 
            else if ($type=='boolean')
			{
				$filter_td_header = file_get_contents('filter_td_boolean_header.tem');
                $filter_td_header = str_replace('[FILTER_FIELD]',$data,$filter_td_header);
                $filter_td_header = str_replace('[FILTER_FIELD_FUNCTION_NAME]',getFunctionName($data),$filter_td_header);
                $filter_td_header = str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$filter_td_header);
                $filter_td_header = str_replace('[TABLE_ELEMENT]',$table_element,$filter_td_header);
                
				$view_object.=$filter_td_header;

			}else if (($type=='text') || ($type=='textarea') || ($type=='date') || ($type=='number'))
            {
				$filter_td_header = file_get_contents('filter_td_text_header.tem');
                $filter_td_header = str_replace('[FILTER_FIELD]',$data,$filter_td_header);
                $filter_td_header = str_replace('[FILTER_FIELD_FUNCTION_NAME]',getFunctionName($data),$filter_td_header);
                $filter_td_header = str_replace('[DATA_FUNCTION_NAME]',getFunctionName($data),$filter_td_header);
                $filter_td_header = str_replace('[TABLE_ELEMENT]',$table_element,$filter_td_header);
                $filter_td_header = str_replace('[FILTER_TYPE]',$type,$filter_td_header);
                
				$view_object.=$filter_td_header;
			} else
            {
				$filter_td_header = file_get_contents('filter_td_blank_header.tem');
                $filter_td_header = str_replace('[FILTER_FIELD]',$data,$filter_td_header);
                $filter_td_header = str_replace('[FILTER_FIELD_FUNCTION_NAME]',getFunctionName($data),$filter_td_header);
                
				$view_object.=$filter_td_header;
            }
//    }
    
        if ($list_head === 'id')
        {
            if (in_array('link_image',$argv))
            {
/*                $th_list.="<?php if (in_array('icon',\$headers)) echo '<th>Icon</th>';?>\n";
                $tf_list.="<?php if (in_array('icon',\$headers)) echo '<th></th>';?>\n";*/
            }
        }
    }
}

$filter_preprocess = str_replace('[TABLE_NAME]',$table,file_get_contents('filter_preprocess.tem'));

if ( ($current_headers) && (in_array('role',$current_headers)) && ($table_element=='user'))
{
	$filter_role_preprocess = file_get_contents('filter_role_preprocess.tem');
	$filter_role_preprocess = str_replace('[TABLE_NAME]',$table,$filter_role_preprocess);
	$filter_preprocess.=$filter_role_preprocess;
}

$filter_process_tem = str_replace('[TABLE_ELEMENT]',$table_element,file_get_contents('filter_process.tem'));
$filter_process_tem = str_replace('[TABLE]',$table,$filter_process_tem);
$filter_process_tem = str_replace('[FILTER_FIELDS_ARRAY]','array("'.implode('","',$filterlist).'")',str_replace('[FILTER_IDS_ARRAY]',$filter_ids_array,$filter_process_tem));
$filter_process_tem = str_replace('[CLASS_NAME]',$class_name,$filter_process_tem);
$filter_process_tem = str_replace('[TABLE_NAME]',$table,$filter_process_tem);

if (trim($fill_filters))
{
    $filter_process = str_replace('[FILL_FILTERS]',$fill_filters,$filter_process_tem);
}
else
{
    $filter_process = '';
}


if (in_array('disclaimer',$argv))
{
    $header = file_get_contents('header.tem');
} else
{
    $header =  '';
}



//$tr_list.="\n</tr>\n";
//$th_list.="</tr>\n".$tf_list."</tr></thead>\n";
$tbody.='</tbody>';
$tbody_new.='</tbody>';


  
/*$edit_head= "<?php \n";
$edit_description = "Used to edit individual ".$class_name;
$edit_headear1 = str_replace('[DESCRIPTION]', $edit_description, $header);
$edit_headear = str_replace('[CLASS_NAME]', 'Edit '.$class_name.'.php', $edit_headear1);
$edit_head.=$edit_headear;*/

$view_object_head.= $foreign_require;

/*$edit_head.= "require_once 'Role.php'; \n";
$edit_head.= 'if (!$this->container) $this->container'."='div.page';\r\n";
$edit_head.= 'if (!isset($allowed_edit)) $allowed_edit=array("admin");'."\r\n";
$edit_head.= 'if (!isset($allowed)) $allowed=array("admin");'."\r\n";
$edit_head.= '$role = new Role();'."\r\n";
$edit_head.= 'if (!$role->isUserAnyOf($allowed))'."\r\n".'{'."\r\n";
$edit_head.= '	$role->showUnauthorizedMessage($allowed,"?command=display_php&base_path=usr/view&path=Edit '.$class_name.'.rest.php");
} else
{'."\r\n";
$edit_head.= "require_once '".$class_name."View.php'; \n";
$edit_head.= '$'.$table_element.'_view = new '.$class_name."View(); \n";
$edit_head.= '$'.$table_element.'_view->init_edit();'."?> \n";*/

$main_edit_column_images='';

$view_object_start.='    public $'.$table_element.";\n";
$view_object_start.='    public $page;'."\n";
$view_object_start.='    public $path;'."\n";

$view_base_template = file_get_contents(__DIR__.'/view_base.tem');
$edit_props_base_template = file_get_contents(__DIR__.'/edit_prop_base.tem');
$new_props_base_template = file_get_contents(__DIR__.'/new_prop_base.tem');
$view_base_template = str_replace('[EDIT_PROPS_BASE]',$edit_props_base_template,$view_base_template);
$view_base_template = str_replace('[NEW_PROPS_BASE]',$new_props_base_template,$view_base_template);
$view_base_template = str_replace('[DISTINCT_FIELDS_DECLARATION]',$distinct_columns,$view_base_template);

$edit_tem = file_get_contents(__DIR__.'/edit_object.tem');
if (in_array('link_image',$argv))
{
    /*$upload_image_tem = file_get_contents('form_upload.tem')."\n".$edit_column_images;
    $upload_image1 = str_replace('[TABLE]',$table,$upload_image_tem);
    $upload_image2 = str_replace('[TABLE_ELEMENT]',$table_element,$upload_image1);
    $upload_image = str_replace('[CLASS_NAME]',$class_name,$upload_image2);
    $edit_tem = str_replace('[LINK_IMAGE]',$upload_image,$edit_tem);*/
    
    $column_image = file_get_contents('render_upload_image.tem');
    $column_image =str_replace('[DATA_FUNCTION_NAME]','Image',$column_image);
    $column_image =str_replace('[DATA]','',$column_image);
    $column_image =str_replace('[TABLE_ELEMENT]',$table_element,$column_image);
    $column_image =str_replace('[VIEW_OBJECT]',$table_element.'_view',$column_image);
    $column_image =str_replace('[IMAGE_NAME]','Image',$column_image);
    $column_images=$column_image.$column_images;
                
    $main_edit_column_images.='<?php if (!isset($edit_remove_image)) '."\n";
    $main_edit_column_images.='{ '."\n";
    $main_edit_column_images.='   echo "<h5>Image</h5>";'."\n";
    $main_edit_column_images.='   if (!isset($edit_redirect_image)) $edit_redirect_image="?command=display_php&base_path=".$_REQUEST["base_path"]."&path=".$_REQUEST["path"]."&id=".$'.$table_element.'_view->'.$table_element.'->getId();'."\n";
    $main_edit_column_images.='   $'.$table_element.'_view->renderUploadImage($'.$table_element.'_view->'.$table_element.',TRUE,$edit_redirect_image,"",$role->isUserAnyOf($allowed_edit));'."\r\n";
    $main_edit_column_images.='} '."?>\n";    
    $edit_column_images=$main_edit_column_images.$edit_column_images;
}

$edit_tem = str_replace('[LINK_IMAGE]',$edit_column_images,$edit_tem);


$edit_tem = str_replace('[CLASS_NAME]',$class_name,$edit_tem);
$edit_tem = str_replace('[TABLE_ELEMENT]',$table_element,$edit_tem);
//$edit_tem = str_replace('[TABLE_CONTENT]',$thead_edit.$tbody,$edit_tem);
$edit_tem = str_replace('[VIEW_OBJECT]',$table_element.'_view',$edit_tem);
$edit_tem = str_replace('[ADMIN_PATH]',$paths->admin,$edit_tem);
$edit_tem = str_replace('[CONTROLLER_NAME]',$controller_name,$edit_tem);
if ($table_element==='user')
{
    $password_tem=file_get_contents('password.tem');
    $edit_tem = str_replace('[PASSWORD]',$password_tem,$edit_tem);
} else $edit_tem = str_replace('[PASSWORD]','//',$edit_tem);


//$html = $edit_head.$htm.$thead_edit.$tbody."\n".'</table>'."\r\n".$edit_tail;
$html=$edit_tem;
if (!file_exists("../old"))
{
	mkdir(__DIR__."/../old");
}

if (!file_exists("../usr"))
{
	mkdir(__DIR__."/../usr");
}

if (!file_exists("../usr_generated"))
{
	mkdir(__DIR__."/../usr_generated");
}

if (!file_exists("../old/usr"))
{
	mkdir(__DIR__."/../old/usr");
}

if (!file_exists("../old/usr_generated"))
{
	mkdir(__DIR__."/../old/usr_generated");
}


if (!file_exists("../usr/view"))
{
	mkdir(__DIR__."/../usr/view");
}
if (!file_exists("../old/usr/view"))
{
	mkdir(__DIR__."/../old/usr/view");
}

if (!file_exists("../usr/view/".$table))
{
	mkdir(__DIR__."/../usr/view/".$table);
}
if (!file_exists("../old/usr/view/".$table))
{
	mkdir(__DIR__."/../old/usr/view/".$table);
}

if (!file_exists("../usr_generated/view"))
{
	mkdir(__DIR__."/../usr_generated/view");
}
if (!file_exists("../old/usr_generated/view"))
{
	mkdir(__DIR__."/../old/usr_generated/view");
}

if (!file_exists("../usr_generated/view/".$table))
{
	mkdir(__DIR__."/../usr_generated/view/".$table);
}
if (!file_exists("../old/usr_generated/view/".$table))
{
	mkdir(__DIR__."/../old/usr_generated/view/".$table);
}

$now = new DateTime('NOW');

$edit_file = "edit_".$table_element.".tamsi.php";
if (file_exists("../usr_generated/view/$table/".$edit_file))
{
    print_r("Storing backup at ../old/usr_generated/view/$table/".$now->format('Ymd_his').'-'.$edit_file."\n");
    $backup = file_get_contents("../usr_generated/view/$table/".$edit_file);
    file_put_contents("../old/usr_generated/view/$table/".$now->format('Ymd_his').'-'.$edit_file,$backup);
}
file_put_contents("../usr_generated/view/$table/".$edit_file,$html);
print_r("done! $table created at ../usr_generated/view/$table/".$edit_file."\r\n");


$edit_file_usr = "edit_".$table_element.".rest.php";
if (file_exists("../usr/view/$table/".$edit_file_usr))
{
    if (in_array('replace_usr_table_views',$argv))
    {
        print_r("Storing backup at ../old/usr/view/$table/".$now->format('Ymd_his').'-'.$edit_file."\n");
        $backup = file_get_contents("../usr/view/$table/".$edit_file);
        file_put_contents("../old/usr/view/$table/".$now->format('Ymd_his').'-'.$edit_file,$backup);
    }
}

if ((!file_exists("../usr/view/$table/".$edit_file_usr)) || (in_array('replace_usr_table_views',$argv)))
{
    $edit_file_html="<?php \r\n";
    $edit_file_html.="//Things that you can override below to control the rendered new view \r\n";
    $edit_file_html.='// if (!isset($allowed)) $allowed=array("admin"); //sets what roles are allowed to view'."\r\n";
    $edit_file_html.='// if (!isset($allowed_edit)) $allowed_edit=array("admin"); //sets what roles are allowed to edit'."\r\n";
    $edit_file_html.="/* require_once '{$class_name}.php'; \r\n";
    $edit_file_html.="   if (!isset(".'$'."{$table_element}_view))\r\n";
    $edit_file_html.="   {\r\n";
    $edit_file_html.="       ".'$'."{$table_element}_view = new {$class_name}(); \r\n";
    $edit_file_html.="       ".'$'."{$table_element}_view->init_edit(); \r\n";
    $edit_file_html.="   }\r\n";
    $edit_file_html.=str_replace('$this','$'.$table_element.'_view',$new_props_base_template."\r\n".$new_props)."*/ \r\n";
    $edit_file_html.="  include __DIR__.'/../../../usr_generated/view/".$table.'/'.$edit_file."';\r\n";
    file_put_contents("../usr/view/$table/".$edit_file_usr,$edit_file_html);
    print_r("done! $table created at ../usr/view/$table/".$edit_file_usr."\r\n");
}

$admin_tem = file_get_contents('admin.tem');

if (file_exists($paths->public."/".$paths->admin))
{
}
else
{
    mkdir($paths->public."/".$paths->admin);
}

$admin_path = $paths->public."/".$paths->admin.'/'.$controller_name.'.dir';
if (file_exists($admin_path))
{
}
else
{
    mkdir($admin_path);
}
$edit_admin_file='Edit_'.$class_name.'.hid.rest.php';
if (!file_exists($admin_path.'/'.$edit_admin_file))
{
    $edit_content1 = str_replace('[NAME]','Edit_'.$class_name,$admin_tem);
    $edit_content2 = str_replace('[TABLE]',$table,$edit_content1);
    $edit_content = str_replace('[FILENAME]',$edit_file_usr,$edit_content2);
    $edit_props_comment = '$[TABLE_ELEMENT]_view->init_edit();'."\r\n".str_replace('$this','$'.$table_element.'_view',$edit_props_base_template."\r\n".$edit_props);
    $edit_content = str_replace('[PROPS_COMMENT]',$edit_props_comment,$edit_content);
    $edit_content = str_replace('[CLASS_NAME]', $class_name, $edit_content);
    $edit_content = str_replace('[TABLE_ELEMENT]', $table_element, $edit_content);
    $edit_content = str_replace('[CONTROLLER_NAME]', $controller_name, $edit_content);
    $edit_content = str_replace('[ADMIN_DIR]', $paths->admin, $edit_content);
    file_put_contents($admin_path.'/'.$edit_admin_file,$edit_content);
    print_r("Admin edit page created at ".$admin_path.'/'.$edit_admin_file."\n");
}

$view_admin_file='View_'.$class_name.'.hid.rest.php';
if (!file_exists($admin_path.'/'.$view_admin_file))
{
    $view_content1 = str_replace('[NAME]','Edit_'.$class_name,$admin_tem);
    $view_content2 = str_replace('[TABLE]',$table,$view_content1);
    $view_content = str_replace('[FILENAME]',$edit_file,$view_content2);
    $view_content = str_replace('[FILENAME]',$edit_file_usr,$edit_content2);
    $edit_props_comment = '$[TABLE_ELEMENT]_view->init_edit();'."\r\n".str_replace('$this','$'.$table_element.'_view',$edit_props_base_template."\r\n".$edit_props);
    $view_content = str_replace('[PROPS_COMMENT]',$edit_props_comment,$view_content);
    $view_content = str_replace('[CLASS_NAME]', $class_name, $view_content);
    $view_content = str_replace('[TABLE_ELEMENT]', $table_element, $view_content);
    $view_content = str_replace('[CONTROLLER_NAME]', $controller_name, $view_content);
    $view_content = str_replace('[ADMIN_DIR]', $paths->admin, $view_content);
    file_put_contents($admin_path.'/'.$view_admin_file,$view_content);
    print_r("Admin View page created at ".$admin_path.'/'.$view_admin_file."\n");
}

$new_head= "<?php \n";

$new_description = "Used to create new ".$class_name;
$new_header = str_replace('[DESCRIPTION]', $new_description, $header);
$new_header = str_replace('[CLASS_NAME]', 'New '.$class_name.'.php', $new_header);
$new_head.=$new_header."?>\r\n";

/*$new_object=$new_header;

$new_head.= "require_once 'Role.php'; \n";
$new_head.= 'if (!isset($allowed)) $allowed=array("admin");'."\r\n";
$new_head.= '$role = new Role();'."\r\n";
$new_head.= 'if (!$role->isUserAnyOf($allowed))'."\r\n".'{'."\r\n";
$new_head.='	$role->showUnauthorizedMessage($allowed,"?command=display_php&base_path=usr/view&path=New '.$class_name.'.rest.php");
} else
{'."\r\n";
$new_head.='if (!isset($'.$table_element.'_view)) '."\r\n";
$new_head.='{ '."\r\n";
$new_head.= "    require_once '".$class_name."View.php'; \n";
$new_head.= '    $'.$table_element.'_view = new '.$class_name."View(); \n";
$new_head.= '    $'.$table_element.'_view->init_new();'."\r\n";
$new_head.='} '."?> \r\n";
if (in_array('link_image',$argv))
{
    $main_new_column_images.='<?php if (!isset($new_remove_image)) '."\n";
    $main_new_column_images.='{ '."\n";
    $main_new_column_images.='   echo "<h5>Image</h5>";'."\n";
    $main_new_column_images.='   if (!isset($new_redirect_image)) $new_redirect_image="?command=display_php&base_path=".$_REQUEST["base_path"]."&path=".$_REQUEST["path"]."&id=".$'.$table_element.'_view->'.$table_element.'->getId();'."\n";
    $main_new_column_images.='   $'.$table_element.'_view->renderUploadImage($'.$table_element.'_view->'.$table_element.',TRUE,$new_redirect_image);'."\r\n";
    $main_new_column_images.='} '."?>\n";
    
    $new_column_images=$main_new_column_images.$new_column_images;
}

$new_head.=$new_column_images;

$new_head.= '<form id="new_'.$table_element.'"  <?php echo $'.$table_element.'_view->new_form_attributes;?> class="new" action="?command=new&object='.$class_name.'&base_path=<?php echo $_REQUEST["base_path"];?>&path=<?php echo $path;?>" method="POST">'."\n";
$new_head.= '<?php $'.$table_element.'_view->renderCsrf();'."?> \n";
$new_head.= '<?php $'.$table_element.'_view->renderCustomNew();'."?> \n";
$new_head.= '<?php $'.$table_element.'_view->renderNewControls(); ?>'."\r\n";
$new_tail='<div class="custom_widget">'."\r\n";
$new_tail.='<?php if ($'.$table_element.'_custom_widget) include "$'.$table_element.'_custom_widget"; ?>'."\r\n";
$new_tail.="</div>\r\n";

$new_tail.= '<?php $'.$table_element.'_view->renderNewControls(); ?>'."\r\n";
$new_tail.= '</form>'."\r\n";
$new_tail.= '</div>'."\r\n";
$new_tail.= '<?php'."\r\n";
$new_tail.= '}?>'."\r\n";




$html_new = $new_head.$htm.$thead_new.$tbody_new."\n".'</table>'.$new_tail;*/


$new_tem = file_get_contents(__DIR__.'/new_object.tem');
$new_tem = str_replace('[CLASS_NAME]',$class_name,$new_tem);
$new_tem = str_replace('[TABLE_ELEMENT]',$table_element,$new_tem);
//$edit_tem = str_replace('[TABLE_CONTENT]',$thead_edit.$tbody,$edit_tem);
$new_tem = str_replace('[VIEW_OBJECT]',$table_element.'_view',$new_tem);
$new_tem = str_replace('[ADMIN_PATH]',$paths->admin,$new_tem);
$new_tem = str_replace('[CONTROLLER_NAME]',$controller_name,$new_tem);

if ($table_element==='user')
{
    $password_tem=file_get_contents('password.tem');
    $new_tem = str_replace('[PASSWORD]',$password_tem,$new_tem);
    $new_tem = str_replace("edit_input","new_input",$new_tem);
} else $new_tem = str_replace('[PASSWORD]','//',$new_tem);


$new_file = "new_".$table_element.".tamsi.php";
if (file_exists("../usr_generated/view/$table/".$new_file))
{
    print_r("Storing backup at ../old/usr_generated/view/$table/".$now->format('Ymd_his').'-'.$new_file."\n");
    $backup = file_get_contents("../usr_generated/view/$table/".$new_file);
    file_put_contents("../old/usr_generated/view/$table/".$now->format('Ymd_his').'-'.$new_file,$backup);
}
file_put_contents("../usr_generated/view/$table/".$new_file,str_replace('[VIEW_OBJECT]',$table_element.'_view',$new_head.$new_tem));
print_r("done! $table created at ../usr_generated/view/$table/".$new_file."\r\n");


$new_file_usr = "new_".$table_element.".rest.php";
if (file_exists("../usr/view/$table/".$new_file_usr))
{
    if (in_array('replace_usr_table_views',$argv))
    {
        print_r("Storing backup at ../old/usr/view/$table/".$now->format('Ymd_his').'-'.$new_file_usr."\n");
        $backup = file_get_contents("../usr/view/$table/".$new_file_usr);
        file_put_contents("../old/usr/view/$table/".$now->format('Ymd_his').'-'.$new_file_usr,$backup);
    }
}

if ((!file_exists("../usr/view/$table/".$new_file_usr)) || (in_array('replace_usr_table_views',$argv)))
{
    $new_file_html="<?php \r\n";
    $new_file_html.="//Things that you can override below to control the rendered new view \r\n";
    $new_file_html.='// if (!isset($allowed)) $allowed=array("admin"); //sets what roles are allowed to access'."\r\n";
    $new_file_html.="/* require_once '{$class_name}.php'; \r\n";
    $new_file_html.="   if (!isset(".'$'."{$table_element}_view))\r\n";
    $new_file_html.="   {\r\n";
    $new_file_html.="       ".'$'."{$table_element}_view = new {$class_name}(); \r\n";
    $new_file_html.="       ".'$'."{$table_element}_view->init_new(); \r\n";
    $new_file_html.="   }\r\n";
    $new_file_html.=str_replace('$this','$'.$table_element.'_view',$new_props_base_template."\r\n".$new_props)."*/ \r\n";
    $new_file_html.="  include __DIR__.'/../../../usr_generated/view/".$table.'/'.$new_file."';\r\n";
    file_put_contents("../usr/view/$table/".$new_file_usr,$new_file_html);
    print_r("done! $table created at ../usr/view/$table/".$new_file_usr."\r\n");
}


$new_admin_file='New_'.$class_name.'.rest.php';
if (!file_exists($admin_path.'/'.$new_admin_file))
{
    $new_content1 = str_replace('[NAME]','New '.$class_name,$admin_tem);
    $new_content2 = str_replace('[TABLE]',$table,$new_content1);
    $new_content = str_replace('[FILENAME]',$new_file_usr,$new_content2);
    $new_props_comment = '$[TABLE_ELEMENT]_view->init_new();'."\r\n".str_replace('$this','$'.$table_element.'_view',$new_props_base_template."\r\n".$new_props);
    $new_content = str_replace('[PROPS_COMMENT]',$new_props_comment,$new_content);
    $new_content = str_replace('[CLASS_NAME]', $class_name, $new_content);
    $new_content = str_replace('[TABLE_ELEMENT]', $table_element, $new_content);
    $new_content = str_replace('[CONTROLLER_NAME]', $controller_name, $new_content);
    $new_content = str_replace('[ADMIN_DIR]', $paths->admin, $new_content);
    file_put_contents($admin_path.'/'.$new_admin_file,$new_content);
    print_r("Admin new page created at ".$admin_path.'/'.$new_admin_file."\n");
}

$html_list = "<?php\n";
$list_description = "Used to list all ".$class_name;
$list_headear1 = str_replace('[DESCRIPTION]', $list_description, $header);
$list_headear = str_replace('[CLASS_NAME]', 'List_'.ucfirst($table).'.rest.php', $list_headear1);
$html_list.=$list_headear.'?>'."\r\n";

/*$html_list.= "require_once '".$class_name.".php';\n";
$html_list.= "require_once '".$class_name."View.php';\n";

$html_list.= "require_once 'Role.php'; \n";*/

$view_object_start.= '    public $'."$element_array;\n";
$view_object_start.= '    public $container;'."\n";
$view_object_start.= '    public $item_per_page;'."\n";
$view_object_start.= '    public $pages;'."\n";
$view_object_start.= '    public $search_text;'."\n";
$view_object_start.= '    public $select_filters;'."\n";
$view_object_start.= '    public $sort_filters;'."\n";
$view_object_start.= '    public $sort_orders;'."\n";
$view_object_start.= $object_list_values;


//$view_object_start.= $filter_ids_array_public;
$view_base_template.="\n".$column_images;
$view_base = str_replace('[TABLE]',$table,$view_base_template);
$view_base = str_replace('[TABLE_ELEMENT]',$table_element,$view_base);
$view_base = str_replace('[CLASS_NAME]',$class_name,$view_base);
$view_base = str_replace('[CONTROLLER_NAME]',$controller_name,$view_base);
$view_base = str_replace('[FIELDS]','array("'.implode('","',$columns).'")',$view_base);
$view_base = str_replace('[SORT_PREPROCESS]',$sort_preprocess,$view_base);
$view_base = str_replace('[FILTER_PREPROCESS]',$filter_preprocess,$view_base);
$view_base = str_replace('[FILTER_PROCESS]',$filter_process,$view_base);
$view_base = str_replace('[ADMIN_DIR]',$paths->admin,$view_base);

$view_base = str_replace('[FIELD_PROPS]',$field_props,$view_base);
$view_base = str_replace('[EDIT_PROPS]',$edit_props,$view_base);
$view_base = str_replace('[NEW_PROPS]',$new_props,$view_base);



if (in_array('paginate=false',$argv))
{
    $view_base = str_replace('[PAGINATION_PREPROCESS]','',$view_base);
} else
{
    $pagination_prep = str_replace('[TABLE_ELEMENT]',$table_element,file_get_contents('pagination_preprocess.tem'));
    $pagination_preprocess = str_replace('[TABLE_NAME]',$table,$pagination_prep);
    $view_base = str_replace('[PAGINATION_PREPROCESS]',$pagination_preprocess,$view_base);
}

$search_text = str_replace('[TABLE_NAME]',$table,file_get_contents('search_text.tem'));
$view_base = str_replace('[SEARCH_TEXT]',$search_text,$view_base);
$view_base = str_replace('[HEADERS]',convertArrayToString($theaders),$view_base);
$view_object.=$view_base;
$view_object_comment="/*\r\n".$view_object."*/\r\n";


$list_objects_tem = file_get_contents('list_objects.tem');

$html_list.=$list_objects_tem;

$pre_headers = array('action','#');

$html_list = str_replace('[TABLE]',$table,$html_list);   
$html_list = str_replace('[CLASS_NAME]', $class_name, $html_list);
$html_list = str_replace('[TABLE_ELEMENT]', $table_element, $html_list);
$html_list = str_replace('[CONTROLLER_NAME]', $controller_name, $html_list);
$html_list = str_replace('[HEADERS]', json_encode(array_merge($pre_headers,$default_list_headers)), $html_list);


$list_file = "list_".$table.".tamsi.php";
if (file_exists("../usr_generated/view/$table/".$list_file))
{
    
    print_r("Storing backup at ../old/usr_generated/view/$table/".$now->format('Ymd_his').'-'.$list_file."\n");
    $backup = file_get_contents("../usr_generated/view/$table/".$list_file);
    file_put_contents("../old/usr_generated/view/$table/".$now->format('Ymd_his').'-'.$list_file,$backup);
}
file_put_contents("../usr_generated/view/$table/".$list_file,str_replace('[VIEW_OBJECT]',$table_element.'_view',$html_list));
print_r("done! $table created at ../usr_generated/view/$table/".$list_file."\r\n");


$list_file_usr = "list_".$table.".rest.php";
if (file_exists("../usr/view/$table/".$list_file_usr))
{
    if (in_array('replace_usr_table_views',$argv))
    {
        print_r("Storing backup at ../old/usr/view/$table/".$now->format('Ymd_his').'-'.$list_file_usr."\n");
        $backup = file_get_contents("../usr/view/$table/".$list_file_usr);
        file_put_contents("../old/usr/view/$table/".$now->format('Ymd_his').'-'.$list_file_usr,$backup);
    }
}


if ((!file_exists("../usr/view/$table/".$list_file_usr)) || (in_array('replace_usr_table_views',$argv)))
{
    $list_file_html="<?php \r\n";
    $list_file_html.='// if (!isset($allowed)) $allowed=array("admin"); //sets what roles are allowed to view'."\r\n";
    $list_file_html.='// if (!isset($allowed_edit)) $allowed_edit=array("admin"); //sets what roles are allowed to edit'."\r\n";
    $list_file_html.='// if (!isset($headers)) $headers = '.json_encode($default_list_headers).";//columns that will be displayed in the table\r\n";
    $list_file_html.="/* require_once '{$class_name}.php'; \r\n";
    $list_file_html.='/*if (!isset($'.$table_element.'_view)) '."\r\n";
    $list_file_html.='{ '."\r\n";
    $list_file_html.= '    $'.$table_element."_view = new ".$class_name."View();\n";
    $list_file_html.= '    $'.$table_element."_view->init_list();\n";
    $list_file_html.='} */'."\r\n"; 
    $list_file_html.="include __DIR__.'/../../../usr_generated/view/".$table.'/'.$list_file."';\r\n";   
    file_put_contents("../usr/view/$table/".$list_file_usr,$list_file_html);
    print_r("done! $table created at ../usr/view/$table/".$list_file_usr."\r\n");
}


$list_admin_file='List_'.$controller_name.'.rest.php';
if (!file_exists($admin_path.'/'.$list_admin_file))
{
    $list_content1 = str_replace('[NAME]','List_'.$controller_name,$admin_tem);
    $list_content2 = str_replace('[TABLE]',$table,$list_content1);
    $list_content = str_replace('[FILENAME]',$list_file_usr,$list_content2);
    $list_content = str_replace('[PROPS_COMMENT]','$[TABLE_ELEMENT]_view->init_list();'."\r\n".'$headers = '.json_encode($default_list_headers).";//columns that will be displayed in the list table\r\n",$list_content);
    $list_content = str_replace('[CLASS_NAME]', $class_name, $list_content);
    $list_content = str_replace('[TABLE_ELEMENT]', $table_element, $list_content);
    $list_content = str_replace('[CONTROLLER_NAME]', $controller_name, $list_content);
    $list_content = str_replace('[HEADERS]', json_encode(array_merge($pre_headers,$default_list_headers)), $list_content);
    file_put_contents($admin_path.'/'.$list_admin_file,$list_content);
    print_r("Admin list page created at ".$admin_path.'/'.$list_admin_file."\n");
}


if (file_exists("../usr_generated/view/".$class_name."ViewBase.php"))
{
    
    print_r("Storing backup at ../old/usr_generated/view/".$class_name."ViewBase".$now->format('Ymd_his').".bak"."\n");
    $backup = file_get_contents("../usr_generated/view/".$class_name."ViewBase.php");
    file_put_contents("../old/usr_generated/view/".$class_name."ViewBase".$now->format('Ymd_his').".bak",$backup);
}
$view_obj_content=$view_object_head.$view_object_start.$view_object."}\n";
$view_obj_content=str_replace('[TABLE_NAME]',$table,$view_obj_content);
file_put_contents("../usr_generated/view/".$class_name."ViewBase.php",$view_obj_content);
print_r("done! $table ViewBase created at ../usr_generated/view/".$class_name."ViewBase.php\n");


if (file_exists("../usr/view/".$class_name."View.php"))
{
} else
{
    $view_object_main="<?php\n
require_once '".$class_name."ViewBase.php';
class ".$class_name."View extends ".$class_name."ViewBase
{
    //functions you can override
    $view_object_comment
}";
    file_put_contents("../usr/view/".$class_name."View.php",$view_object_main);
    print_r("done! $table View created at ../usr/view/".$class_name."View.php");
}

if ($table=='users')
{
	if (file_exists("../usr/view/$table/register_".$table_element.".pub.php"))
	{
	} else
	{ //"../usr/view/$table/register_".$table_element.".rest.php"
		$register=file_get_contents("register_user.tem");
		$register=str_replace('[REGISTER_DEFAULTS]',$register_defaults,$register);
		file_put_contents("../usr/view/$table/register_".$table_element.".pub.php",$register);
		print_r("done! default registration created at ../usr/view/$table/register_".$table_element.".pub.php\n");
	}
}


//die(json_encode($argv));
/*function getEnumOptions($enum)
{
    $start = strpos($enum,'(')+1;
    $end = strpos($enum,')');
    $options = substr($enum,$start,$end-$start);
    return explode(',',$options);
}*/

function getCurrentListHeader($table)
{
    $headstr='';
    if (file_exists("../usr/view/$table/list_".$table.".rest.php"))
    {
        $list_file = file_get_contents("../usr/view/$table/list_".$table.".rest.php");    
        $start = strpos($list_file,'$headers =')+10;
        $end = strpos($list_file,';',$start);
        $headstr = substr($list_file,$start,$end-$start);
        //print_r('hdstr:'.$headstr);
    }
    return json_decode($headstr);
}

function getCurrentEditColumns($table,$table_element)
{
    $theads='';
    if (file_exists("../usr/view/$table/edit_".$table_element.".rest.php"))
    {
        $list_file = file_get_contents("../usr/view/$table/edit_".$table_element.".rest.php");    
        $start = strpos($list_file,'<thead>')+7;
        $end = strpos($list_file,'</thead>');
        $theads = substr($list_file,$start,$end-$start);
    }
    return $theads;
}


