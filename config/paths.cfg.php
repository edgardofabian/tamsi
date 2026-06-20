<?php
if (file_exists(__DIR__.'/main.cfg.php'))
{
    include __DIR__.'/main.cfg.php';
}
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
class Paths
{
    public $user_image;
    public $user_image_url;
    public $includes;
    public $app_path;
    public $home;
    public $admin;
    public $data;
    public $public;
    public $images;
    public $web_path;
    public $base_url;
    public function __construct()
    {
        //relative system root folder
        $this->app_path = dirname(__FILE__,2);
        $this->includes = '.:'.$this->app_path.':'.$this->app_path.'/lib:'.$this->app_path.'/lib/password_compat:'.$this->app_path.'/config';
        $this->includes.=':'.$this->app_path.'/app:'.$this->app_path.'/app/controller:'.$this->app_path.'/app/model:'.$this->app_path.'/app/view';        
        $this->includes.=':'.$this->app_path.'/tamsi:'.$this->app_path.'/tamsi/controller:'.$this->app_path.'/tamsi/model:'.$this->app_path.'/tamsi/view';
        $this->includes.=':'.$this->app_path.'/usr:'.$this->app_path.'/usr/controller:'.$this->app_path.'/usr/model:'.$this->app_path.'/usr/view';
        $this->includes.=':'.$this->app_path.'/usr_generated:'.$this->app_path.'/usr_generated/controller:'.$this->app_path.'/usr_generated/model:'.$this->app_path.'/usr_generated/view';
        $this->includes.=':'.$this->app_path.'/lib/diff-match-patch/src';
        $this->user_image_url = $this->app_path."/public_html/uploads/";
        $this->user_image = $this->app_path."/".$this->user_image_url;
        $this->images = $this->app_path."/public_html/img/";
        $this->public = $this->app_path."/public_html";
        //relative to www root folder
        $this->home = 'home.pub.hid.php';
        $this->admin = 'Admin.dir';
        $this->web_path = ''; //relative to document root
        $this->base_url = 'https://local.tamsi.net';  
        $this->init();
    }
    public function init()
    {
        set_include_path($this->includes);
    }    
}
