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
 * @package Tamsi
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
if (file_exists(__DIR__.'/database.cfg.php.home'))
{
    require_once __DIR__.'/database.cfg.php.home';
} else if (file_exists(__DIR__.'/database.cfg.php.prod'))
{
    require_once __DIR__.'/database.cfg.php.prod';
} else
{

class db
{
    public $host;
    public $name;
    public $user;
    public $password;
    public function __construct()
    {
        $this->host = "<MySQL Database Server HOST Name>";
        $this->name = "<MySQL Database Name>";
        $this->user   = "<MySQL Database User Name>";
        $this->password = "<MySQL Database User Password>";
    }
}

$dbc = new db();

$db_host = $dbc->host;
$db = $dbc->name;
$db_user   = $dbc->user;
$db_password = $dbc->password;

}
