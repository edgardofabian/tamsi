<?php
echo '<h3 class="heading">Edit Configuration</h3>';
//Things that you can override
// require_one 'ConfigurationView.php';
// $configuration_view = new ConfigurationView();
/* $configuration_view->init_edit();
        $configuration_view->edit_form_attributes=''; //attributes added to the form
        $configuration_view->edit_button_name='Update'; //Visible name of the Edit submit button
        $configuration_view->edit_button_url='?command=editRest&object=Configuration'; //url of the controller where edit form will be posted by ajax
        $configuration_view->edit_button_next_url='?command=display_rest&path=Admin.dir/Configurations.dir/Edit Configuration.hid.rest.php&id='.$configuration_view->configuration->getId(); //url that will be visited after completing the edit submission
        $configuration_view->edit_delete_button_url='?command=deleteRest&object=Configuration'; //url of the controller where delete form will be submitted
        $configuration_view->edit_delete_button_next_url='?command=display_rest&path=Admin.dir/Configurations.dir/List Configurations.rest.php'; //url that will be visited after completing the delete submission

        $configuration_view->edit_id=true;// allow id input to transition from lock to edit mode
        $configuration_view->readonly_id=false;// set the id as read only
        $configuration_view->hidden_id=false;// set the id as hidden
        $configuration_view->id_attributes=array();// attributes such as id, style etc that will be added to the id input
        $configuration_view->id_option_filters=array();// filters for selection of dropdown options
        $configuration_view->edit_remove_id=false;// remove the id input from the edit form
        $configuration_view->edit_name=true;// allow name input to transition from lock to edit mode
        $configuration_view->readonly_name=false;// set the name as read only
        $configuration_view->hidden_name=false;// set the name as hidden
        $configuration_view->name_attributes=array();// attributes such as id, style etc that will be added to the name input
        $configuration_view->name_option_filters=array();// filters for selection of dropdown options
        $configuration_view->edit_remove_name=false;// remove the name input from the edit form
        $configuration_view->edit_value=true;// allow value input to transition from lock to edit mode
        $configuration_view->readonly_value=false;// set the value as read only
        $configuration_view->hidden_value=false;// set the value as hidden
        $configuration_view->value_attributes=array();// attributes such as id, style etc that will be added to the value input
        $configuration_view->value_option_filters=array();// filters for selection of dropdown options
        $configuration_view->edit_remove_value=false;// remove the value input from the edit form
//        $configuration_view->value_attributes["class"]="jwysiwyg";
        $configuration_view->edit_description=true;// allow description input to transition from lock to edit mode
        $configuration_view->readonly_description=false;// set the description as read only
        $configuration_view->hidden_description=false;// set the description as hidden
        $configuration_view->description_attributes=array();// attributes such as id, style etc that will be added to the description input
        $configuration_view->description_option_filters=array();// filters for selection of dropdown options
        $configuration_view->edit_remove_description=false;// remove the description input from the edit form
//        $configuration_view->description_attributes["class"]="jwysiwyg";
 */

include $config->paths->app_path.'/usr/view/configurations/edit_configuration.rest.php';

