<?php
require_once __DIR__.'/../tamsi/model/TamsiRoutes.php';
class Routes extends TamsiRoutes
{
    public $urls;
    public function __construct()
    {
        $paths = new Paths();
        $this->urls = array();
        
        //define default view and data of view
        //$this->views['#main_nav']=array('path'=>($paths->public).'/header.php','data'=>array());
        $this->urls['']=array('query'=>'?command=display_php&path=home.pub.hid.php','vars'=>'');
/*
        $this->urls['admin/tasks/list']=array('query'=>'?command=display_php&base_path=_01Admin.dir/Tasks.dir&path=List Tasks.rest.php','variables'=>'');
        $this->urls['admin/tasks/edit']=array('query'=>'?command=display$thiphp&base_path=_01Admin.dir/Tasks.dir&path=Edit Task.hid.rest.php','variables'=>array('id'=>4));
        $this->urls['admin/tickets/list']=array('query'=>'?command=display_php&path=_01Admin.dir/Tickets.dir/List Tickets.rest.php','variables'=>'');
        $this->urls['admin/users/edit']=array('query'=>'?command=display_php&path=_01Admin.dir/Users.dir/Edit User.hid.rest.php','variables'=>array('id'=>4));
        //$this->urls['admin/exams/upload']=array('query'=>'?command=display_php&base_path=_01Admin.dir/Exams.dir&path=Upload Text Exam.rest.php','variables'=>'');
        $this->urls['admin/deposits/earnings']=array('query'=>'?command=display_php&path=_01Admin.dir/Deposits.dir/List My Deposits.rest.php','variables'=>array('user_id'=>4));
        */
        //note that the login and logout routes are default to Tamsi frameword and should not be used in any other purpose
        $this->urls['login']=array('query'=>'?command=login&object=User','variables'=>'');
        $this->urls['logout']=array('query'=>'?command=logout','variables'=>'');

        
    }
}
