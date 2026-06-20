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
 * @package SearchResults
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
class TamsiSearchResult
{   
    protected $name;
    
    protected $table;
    
    protected $table_id;
    
    protected $rank;
    
    protected $matches;
    
    protected $href;
    
    public function __construct()
    {
        $this->rank=0;
        $this->matches='';
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setRank($rank)
    {
        $this->rank = $rank;
        return $this;
    }

    public function getRank()
    {
        return $this->rank;
    }    

    public function incrementRank()
    {
        $this->rank = $this->rank + 1;
    }
    
    public function setHref($href)
    {
        $this->href = $href;
        return $this;
    }

    public function getHref()
    {
        return $this->href;
    }    
    
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }    
    
    public function setTableId($table_id)
    {
        $this->table_id = $table_id;
        return $this;
    }

    public function getTableId()
    {
        return $this->table_id;
    }     
    
    public function addMatches($match)
    {
        $this->matches.='<p>..'.$match." .. </p>";
        return $this;
    }

    public function getMatches()
    {
        return $this->matches;
    }     
    
}
?>
