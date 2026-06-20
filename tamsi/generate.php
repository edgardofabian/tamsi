<?php
/**  
 * Common functions for generating MVC from database tables
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
 * @package generate
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
function getFunctionName($str)
{

    //$data = preg_replace('/[^a-z0-9@.\s]/ui', '', $str);
    //print_r('data='.$data);
    $data_array = preg_split('/[\s|@|._]+/', $str);
    $camels =array();
    foreach ($data_array as $data_str)
    {
        $camels[] = ucfirst(strtolower($data_str));
    }
    return implode('', $camels);
}

function getForeignTableElement($name)
{
    $idx = strpos($name,'_id');
    $idsx = strpos($name,'_ids');
    
    if ($idsx > 0)
    {
        $idx = $idsx-1;
    } else if ($idx > 0)
    {
       
    } else  $idx = 0;
    
    
    if ($idx > 0 )
    {
        $foreign = substr($name,0,$idx);
    } else
    {
        $foreign = '';
    }
    
    return $foreign;
}

function getTableNames($mysqli)
{
    $res = $mysqli->query("show tables;");
    $tables = array();
    while ($row = $res->fetch_array())
    {
        $tables[] = $row[0];
    }
    return $tables;
}

function getForeignAliasName($name)
{
    $idx = strpos($name,'_id_');
    $idsx = strpos($name,'_ids_');
    if ($idx > 0)
    {
        $idx = $idx+4;
    } else if ($idsx > 0)
    {
        $idx = $idsx+5;
    } else $idx = 0;
    $foreign_name = '';
    if ($idx > 0 )
    {
        if ($idx < (strlen($name)-1))
        {
            $foreign_name = substr($name,$idx);
        } 
    }
    return $foreign_name;
}

function getEnumOptions( $mysqli, $table, $field )
{	
    $type = $mysqli->query("SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'")->fetch_array(MYSQLI_ASSOC)['Type'];
    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
    $enums = explode("','", $matches[1]);
    $options = array();
    foreach($enums as $enum)
    {
        $options[$enum]=$enum;
    }
    return $options;
}

function convertArrayToString($arr)
{
    $arr_str = 'array'.str_replace('{','(',str_replace(':','=>',str_replace('}',')',str_replace(':{','=>array(',str_replace('[]','array()',json_encode($arr,TRUE))))));
    return $arr_str;
}


function generateTableElementName($table)
{
    if (strrpos($table,'ies')==(strlen($table)-3))
    {
        $table_element = substr($table,0,strlen($table)-3).'y';
    } 
    else if ((strrpos($table,'sses')==(strlen($table)-4)) || (strrpos($table,'uses')==(strlen($table)-4)))
    {
        $table_element = substr($table,0,strlen($table)-2);
    } 
    else
    {
        $table_element = substr($table,0,strlen($table)-1);
    }
    return $table_element;
}

function generateTableNameFromElement($table_element)
{
    if (strrpos($table_element,'y')==(strlen($table_element)-1))
    {
        $table = $table_element;
        $table[strrpos($table_element,'y')]='i';
        $table = $table.'es';
    } 
    else if (strrpos($table_element,'s')==(strlen($table_element)-1))
    {
        $table = $table_element.'es';
    } 
    else
    {
        $table = $table_element.'s';
    }
    return $table;
}

function getCapitalizedNiceName($data)
{
    $nice_name='';
    $names=explode('_',$data);
    foreach($names as $name)
    {
        $nice_name.=ucfirst(trim($name)).' ';
    }
    return $nice_name;
}

function getColumNames($table,$db,$mysqli)
{
    $sql ="SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' and TABLE_SCHEMA='$db';";

    $res = $mysqli->query($sql);
    
    $names = array();
    
    while ($row = $res->fetch_array()) 
    {
        $names[] = $row['column_name'];
    }
    $res->close();
    return $names;
}
