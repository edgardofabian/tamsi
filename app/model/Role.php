<?php
/** Object for roles of tamsi framework 
 * Object contaiting elements of a tamsi framework role intended to be hardcoded values.
 * This page is intended to be edited by programmers to fit their needs
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
 * @package Role
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
require_once 'TamsiRole.php';
class Role extends TamsiRole
{    
    public function __construct()
    {
        parent::__construct();
        $this->rolenames = array(0x00=>'guest',0x01=>'client',0x02=>'developer',0x04=>'supervisor',0x08=>'admin');
    }
    
    public function getActualId($id,$key)
    {
        $id = $this->security->decrypt($id,$key);
    }
}

