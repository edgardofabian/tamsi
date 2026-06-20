<?php 
/**  
 * [DESCRIPTION]
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
 * @package tamsi
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */

require_once 'database.cfg.php';
require_once 'Configuration.php';
class TamsiConfigurations
{
    public $columns=array();
    public function __construct()
    {
        $config = new Configuration();
        $configs = $config->findAll();
        foreach($configs as $conf)
        {
            $name = $conf->getName();
            $this->columns[$name] = $conf->getValue();
        }
    }
    
    public function get($name)
    {
        if (array_key_exists($name,$this->columns))
        {
            return $this->columns[$name];
        }
        return '';
    }
    
    public function render($name)
    {
        echo $this->$name;
    }
    
    public function update($name,$value)
    {
        $config = new Configuration();
        $config->find(array('name'=>$name));
        if ($config->getId())
        {
            $config->setValue($value);
            $config->save();
        }

        if (array_key_exists($name,$this->columns))
        {
            $this->columns[$name]=$value;;
        }
        return $config;
    }
}

