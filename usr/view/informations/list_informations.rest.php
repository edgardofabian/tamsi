<?php 
// if (!isset($allowed)) $allowed=array("admin"); //sets what roles are allowed to view
// if (!isset($allowed_edit)) $allowed_edit=array("admin"); //sets what roles are allowed to edit
// if (!isset($headers)) $headers = ["id","user_id","name","value","token","salt","active","created"];//columns that will be displayed in the table
/* require_once 'Information.php'; 
/*if (!isset($information_view)) 
{ 
    $information_view = new InformationView();
    $information_view->init_list();
} */
include __DIR__.'/../../../usr_generated/view/informations/list_informations.tamsi.php';
