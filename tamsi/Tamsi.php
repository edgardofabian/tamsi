<?php
/** Main object tamsi framework 
 * Object instantiating the main model,view, and controller functions for tamsi framework
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
require_once __DIR__.'/../config/paths.cfg.php';
$paths = new Paths();
$paths->init();

require_once __DIR__.'/Core.php';
require_once __DIR__.'/../app/view/View.php';
require_once __DIR__.'/../app/model/Model.php';
require_once __DIR__.'/../app/controller/Controller.php';


require_once 'routes.cfg.php';

$routes = new Routes();

$routes->processNiceUrls();



class Tamsi extends Core
{
    public function __construct($first_word_last_level=FALSE,$enable_external_links=TRUE,$include_menu=array(),$icons=array(),$additional_menus=array(),$enable_site_map=TRUE,$container='div.page',$enable_login=TRUE)
    {
        session_start();
        $this->model = new Model();
        $this->controller = new Controller($this->model);
        $this->controller->receive();
        $this->controller->prepareMenu();

        
        
        $this->view  = new View($this->controller); 
        
    }    
}
