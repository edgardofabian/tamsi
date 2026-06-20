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
 * @package Gender
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
class Gender
{
    protected $genders;
    
    public function __construct()
    {
        $this->genders = array(0=>'unknown',0x01=>'male',0x02=>'female');
    }

    
    public function getValueFromName($gender)
    {
        $name = strtolower($gender);
        return array_search($name,$this->genders);
    }

    
    public function getNameFromValue($val)
    {
        return $this->genders[$val];
    }
    
    public function getGenders()
    {
        return $this->genders;
    }    
    
}
?>
