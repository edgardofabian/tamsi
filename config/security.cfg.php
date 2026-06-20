<?php
if (file_exists(__DIR__.'/security.cfg.php.home'))
{
    include __DIR__.'/security.cfg.php.home';
} else
{
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
    class Secure_config
    {
        public $secret_key;
        public $domain;
        public $encrypt_keys;
        
        public function __construct()
        {
            $this->secret_key='<your secret key>'; //any text of length not less than 6 but not greater than 64
            $this->domain='tamsi'; 
            $this->encrypt_keys=array();
        }
    }
}
