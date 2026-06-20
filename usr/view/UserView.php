<?php

require_once 'UserViewBase.php';
class UserView extends UserViewBase
{
    public function renderActions($user,$class="",$edit=TRUE)
    {
        parent::renderActions($user,$class,$edit);
        $role = new Role();
        if ($role->isUserAuthorizedAs('admin'))
        {
            echo '<a class="admin"  href="?command=assumePerson&object=User&new_user_id='.$user->getId().'" ><i class="fa fa-user-secret"></i>Assume</a>';
        }
    }
}
