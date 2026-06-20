<?php
/**  
 * Used to generate view from database tables
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
 * @package genereView
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.gahum.tech
 */

require_once __DIR__.'/../app/config/database.cfg.php';
require_once __DIR__.'/../app/controller/TamsiController.php';
require_once __DIR__.'/../app/model/TamsiModel.php';

if ($argc<1) 
{
    print_r("Insufficient parameters\n");
    print_r("generateAdminLinks\n");
} else
{
	//get all tables
	$db = new db();
	$mysqli = new mysqli($db->host,$db->user, $db->password, $db->name);
	if ($mysqli->connect_errno) 
	{
		$_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
		return false;
	}
			
	$sql="SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '".$db->name."'"; 
	$res = $mysqli->query($sql);
	$tables = array();
	while ($row = $res->fetch_array()) 
	{
		$tables[]=$row['TABLE_NAME'];
	}
	
	sort($tables);
	$i=0;
	foreach ($tables as $table)
	{
		if (strpos($table,'ies')==(strlen($table)-3))
		{
			$object = substr($table,0,strlen($table)-3).'y';
		} 
		else if (strpos($table,'sses')==(strlen($table)-2))
		{
			$object = substr($table,0,strlen($table)-2);
		} 
		else
		{
			$object = substr($table,0,strlen($table)-1);
		}
		$controller_name = getFunctionName(ucfirst($table));
		$class_name = ucfirst($object);       
		//create list link
		$i=$i+1;
		echo "creating admin.dir/List $controller_name".".".$i.".php'\n"; 
		shell_exec("ln -s '../usr/view/List $controller_name".".php'  '../admin.dir/List $controller_name".".".$i.".php'");
		//create edit link
		$i=$i+1;
		echo "creating admin.dir/Edit $class_name".".".$i.".hid.php'\n";
		shell_exec("ln -s '../usr/view/Edit $class_name".".php'  '../admin.dir/Edit $class_name".".".$i.".hid.php'");
		//create new link
		$i=$i+1;
		echo "creating '../admin.dir/New $class_name".".".$i.".php'\n";
		shell_exec("ln -s '../usr/view/New $class_name".".php'  '../admin.dir/New $class_name".".".$i.".php'");

	}
	echo "done...\n";
}
