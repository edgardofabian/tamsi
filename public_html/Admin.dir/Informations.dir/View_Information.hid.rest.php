<?php
//Things that you can override
// require_one 'InformationView.php';
// $information_view = new InformationView();
/* $information_view->init_edit();
        $information_view->form_attributes=''; //attributes added to the form
        $information_view->button_name='Update'; //Visible name of the Edit submit button
        $information_view->button_url='?command=editRest&object=Information'; //url of the controller where edit form will be posted by ajax
        $information_view->button_next_url='?command=display_rest&path=Admin.dir/Informations.dir/Edit Information.hid.rest.php&id='.$information_view->information->getId(); //url that will be visited after completing the edit submission
        $information_view->delete_button_url='?command=deleteRest&object=Information'; //url of the controller where delete form will be submitted
        $information_view->delete_button_next_url='?command=display_rest&path=Admin.dir/Informations.dir/List Informations.rest.php'; //url that will be visited after completing the delete submission

        $information_view->columns["id"]['mode']="edit";
        $information_view->columns["user_id"]['mode']="edit";
        $information_view->columns["user_id"]["attributes"]["class"]="chosen";
        $information_view->columns["name"]['mode']="edit";
        $information_view->columns["value"]['mode']="edit";
        $information_view->columns["token"]['mode']="edit";
        $information_view->columns["salt"]['mode']="edit";
        $information_view->columns["active"]['mode']="edit";
        $information_view->columns["created"]['mode']="edit";
 */

include $config->paths->app_path.'/usr/view/informations/edit_information.rest.php';

