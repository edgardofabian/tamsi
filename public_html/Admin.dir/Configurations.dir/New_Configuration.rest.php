<?php
echo '<h3 class="heading">New Configuration</h3>';
//Things that you can override
// require_one 'ConfigurationView.php';
// $configuration_view = new ConfigurationView();
/* $configuration_view->init_new();
        $configuration_view->new_form_attributes=''; //attributes added to the new form
        $configuration_view->new_button_name='Create'; //Label of the new submit button
        $configuration_view->new_button_url='?command=newRest&object=Configuration'; //url of the controller where the new form will be submitted through ajax
        $configuration_view->new_button_next_url='?command=display_rest&path=Admin.dir/Configurations.dir/List Configurations.rest.php'; //url of the controller that will be visited after completing the new submission

        $configuration_view->edit_id=false;
        $configuration_view->readonly_id=false;// set the id as read only
        $configuration_view->hidden_id=false;// set the id as hidden
        $configuration_view->id_attributes=array();// attributes such as id, style etc that will be added to the id input
        $configuration_view->id_option_filters=array();// filters for selection of dropdown options
        $configuration_view->new_remove_id=false;// remove the id input from the new form
        $configuration_view->edit_name=false;
        $configuration_view->readonly_name=false;// set the name as read only
        $configuration_view->hidden_name=false;// set the name as hidden
        $configuration_view->name_attributes=array();// attributes such as id, style etc that will be added to the name input
        $configuration_view->name_option_filters=array();// filters for selection of dropdown options
        $configuration_view->new_remove_name=false;// remove the name input from the new form
        $configuration_view->edit_value=false;
        $configuration_view->readonly_value=false;// set the value as read only
        $configuration_view->hidden_value=false;// set the value as hidden
        $configuration_view->value_attributes=array();// attributes such as id, style etc that will be added to the value input
        $configuration_view->value_option_filters=array();// filters for selection of dropdown options
        $configuration_view->new_remove_value=false;// remove the value input from the new form
        $configuration_view->edit_description=false;
        $configuration_view->readonly_description=false;// set the description as read only
        $configuration_view->hidden_description=false;// set the description as hidden
        $configuration_view->description_attributes=array();// attributes such as id, style etc that will be added to the description input
        $configuration_view->description_option_filters=array();// filters for selection of dropdown options
        $configuration_view->new_remove_description=false;// remove the description input from the new form
 */

include $config->paths->app_path.'/usr/view/configurations/new_configuration.rest.php';

