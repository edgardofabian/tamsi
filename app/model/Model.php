<?php
/** Main model of tamsi framework 
 * Object contaiting model objects for main tamsi framework. 
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
 * @package Model
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
require_once 'TamsiModel.php';
class Model extends TamsiModel
{
    public function __construct()
    {
        parent::__construct();
		$this->file_filters = array('.','..','img','lib','css','js','app','usr','.git','util','config','index.php','flat','_vti_bin','_vti_log','_vti_txt','_vti_cnt','_vti_cnf','_vti_pvt','cgi-bin','.Trash-1000','fonts','img','images','_private','index_all','get','homeserver','tamsi','usr_generated','.htaccess','.htpasswds','.phalcon','getServer.php','getmyip.php','view','audio','video','pdf','sgf','old','db_util.cfg.php','db_util.php'); //create your own filter
	}
}
