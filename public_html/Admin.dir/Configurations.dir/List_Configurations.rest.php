<?php
echo '<h3 class="heading">List Configurations</h3>';
//Things that you can override
// require_one 'ConfigurationView.php';
// $configuration_view = new ConfigurationView();
/* $configuration_view->init_list();
$headers = ["id","name","value","description"];//fields that will be displayed in the list table
 */

include $config->paths->app_path.'/usr/view/configurations/list_configurations.rest.php';

