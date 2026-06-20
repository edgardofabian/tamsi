<?php
//Things that you can override
require_once 'InformationView.php';
$information_view = new InformationView();
$information_view->init_list(); 
$information_view->setListColumns(["action","id","user_id","name","active","created"]);//columns that will be displayed in the list table

include $config->paths->app_path.'/usr/view/informations/list_informations.rest.php';

