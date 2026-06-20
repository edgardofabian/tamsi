<?php
/** Object for menu of tamsi framework 
 * Object contaiting elements of a tamsi framework menu
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
 * @package MenuElement 
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
require_once 'TamsiMenuElement.php';
class MenuElement extends TamsiMenuElement
{
    public $token;			// token to pass
	public $post_data;		// array of data to be submitted via post method
	public $get_data;		// array of data to be submitted via get method
	public $selected;		// true if currently selected, false otherwise
    public $attributes;   	// any other attributes     
    public function __construct($name,$url)
    {
        parent::__construct($name,$url);
	}
	public function addGetData($name,$value)
	{
		$this->get_data[$name] = $value;
	}

	public function addPostData($name,$value)
	{
		$this->post_data[$name] = $value;
	}
    
    public function getGetData()
	{
		return $this->get_data;
	}
	public function getPostData()
	{
		return $this->post_data;
	}
    public function getToken()
    {
        return $this->token;
    }
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }
    
}
