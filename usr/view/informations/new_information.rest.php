<?php 
//Things that you can override below to control the rendered new view 
// if (!isset($allowed)) $allowed=array("admin"); //sets what roles are allowed to access
/* require_once 'Information.php'; 
   if (!isset($information_view))
   {
       $information_view = new Information(); 
       $information_view->init_new(); 
   }
        $information_view->form_attributes=''; //attributes added to the new form
        $information_view->button_name='Create'; //Label of the new submit button
        $information_view->button_url='?command=newRest&object=[CLASS_NAME]'; //url of the controller where the new form will be submitted through ajax
        $information_view->button_next_url='?command=display_rest&path=[ADMIN_DIR]/[CONTROLLER_NAME].dir/List [CONTROLLER_NAME].rest.php'; //url of the controller that will be visited after completing the new submission

        $information_view->columns["id"]['mode']="new";
        $information_view->columns["user_id"]['mode']="new";
        $information_view->columns["user_id"]["attributes"]["class"]="chosen";
        $information_view->columns["name"]['mode']="new";
        $information_view->columns["value"]['mode']="new";
        $information_view->columns["token"]['mode']="new";
        $information_view->columns["salt"]['mode']="new";
        $information_view->columns["active"]['mode']="new";
        $information_view->columns["created"]['mode']="new";
*/ 
  include __DIR__.'/../../../usr_generated/view/informations/new_information.tamsi.php';
