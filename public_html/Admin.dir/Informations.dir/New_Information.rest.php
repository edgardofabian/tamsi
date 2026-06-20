<?php
//Things that you can override
// require_one 'InformationView.php';
// $information_view = new InformationView();
/* $information_view->init_new();
        $information_view->form_attributes=''; //attributes added to the new form
        $information_view->button_name='Create'; //Label of the new submit button
        $information_view->button_url='?command=newRest&object=Information'; //url of the controller where the new form will be submitted through ajax
        $information_view->button_next_url='?command=display_rest&path=Admin.dir/Informations.dir/List Informations.rest.php'; //url of the controller that will be visited after completing the new submission

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

include $config->paths->app_path.'/usr/view/informations/new_information.rest.php';

