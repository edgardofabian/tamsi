<?php
/** 
 * Object for managing pages 
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
 * @package TamsiPage
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
class TamsiPage
{
    public $prev;
    public $prev_class;
    public $val;
    public $next;
    public $next_class;
    public $first_class;
    public $last_class;
    
	public function __construct($page, $pages)
    {
        if ($page <= $pages)
        {
            $this->val = $page;
            
        } else
        {
            $this->val = $pages;
        }
        
        if ($page > 1)
        {
            $this->first_class = '';
        } else
        {
            $this->first_class = 'disabled';
        }
        
        if ($page > 2)
        {
            $this->prev = $page - 1;
            if ($this->prev > $pages)
            {
                $this->prev = $pages;
            }
            $this->prev_class = '';
        } else
        {
            $this->prev = 1;
            $this->prev_class = 'disabled';
        }
        if ($page < $pages - 1)
        {
            $this->next = $page + 1;
            $this->next_class = '';
        } else
        {
            $this->next = $pages;
            $this->next_class = 'disabled';
        }        
        
        if ($page < $pages)
        {
            $this->last_class = '';
        } else
        {
            $this->last_class = 'disabled';
        }
    }
	
}

