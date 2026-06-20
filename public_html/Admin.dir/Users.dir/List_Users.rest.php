<?php
$headers = ["id","firstname","lastname","email","created","role","mobile"];
echo '<h3 class="heading">List Users</h3>';
include $config->paths->app_path.'/usr/view/users/list_users.rest.php';

