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
 * @package CivilStatus
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
class CivilStatus
{
    protected $statuses;
    
    public function __construct()
    {
        $this->statuses = array(0=>'unknown',0x01=>'single',0x02=>'married',0x04=>'separated',0x08=>'divorced',0x10=>'widow',0x20=>'widower');
    }

    
    public function getValueFromName($status)
    {
        $name = strtolower($status);
        return array_search($name,$this->statuses);
    }

    
    public function getNameFromValue($val)
    {
        return $this->statuses[$val];
    }
    
    public function getStatuses()
    {
        return $this->statuses;
    }    
    
}
?>
