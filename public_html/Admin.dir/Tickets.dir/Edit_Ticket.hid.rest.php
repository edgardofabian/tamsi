<?php
require_once 'TicketDiscussion.php';
require_once 'TicketDiscussionView.php';
require_once 'TicketView.php';
require_once 'Role.php';
$role = new Role();
echo '<h3 class="heading">Edit Ticket</h3>';
echo '<style> #td_description p { margin-top:0px;margin-bottom:0px} </style>';
include $config->paths->app_path.'/usr/view/tickets/edit_ticket.rest.php';


$td = new TicketDiscussion();
$param=array();
$param['filter']=array('ticket_id'=>$ticket_view->ticket->getId());
$param['order']=array('created'=>'ASC');
$discussions = $td->getAll($param);

echo '<h3 class="heading">Ticket Discussions</h3>';
echo '<table class="discussions odd_even_row_alternate_color full_width data" >';
echo '<tbody>';
foreach($discussions as $discus)
{
    echo '<tr><td>';
    echo '<div>'.$discus->getCreated().' : '.$discus->getUser().'</div>';
    echo '<span>'.$discus->getMessage().'</span>';
    echo '</td></tr>';
}
echo '</tbody>';
echo '</table>';

$hidden_ticket_id=TRUE;
$readonly_user_id=TRUE;
$ticket_discussion_view = new TicketDiscussionView();
$ticket_discussion_view->init_new();
$ticket_discussion_view->ticket_discussion->setTicketId($ticket_view->ticket->getId());
$ticket_discussion_view->ticket_discussion->setUserId($_SESSION['user_id']);
$ticket_discussion_view->new_button_next_url='?command=display_rest&path=admin.dir/Tickets.dir/Edit Ticket.hid.rest.php&id='.$ticket_view->ticket->getId();
echo '<style> #tr_ticket_id, #tr_ticket_id td { font-size:0;margin:0;height:0;padding:0px} table.discussions td { padding-top:5px;padding-bottom:5px;} </style>';
echo '<div style="margin-top:40px">Add Message</div>';
$message_attributes=array("class"=>"jwysiwyg","placeholder"=>"Message ");
include $config->paths->app_path.'/usr/view/ticket_discussions/new_ticket_discussion.rest.php';


$dir=$config->paths->app_path;


if (file_exists($dir.'/.git'))
{
    echo '<style> div.commits { text-align:left; margin-left:20px;margin-right:20px;margin-top:20px; border-bottom:2px solid #AAAAAA;} div.commits .commit_link a { font-size:12px} </style>';
    echo '<div class="commits">';
    echo '<h3 class="heading">Commits:</h3>';



    $output = array();
    $tid=$ticket_view->ticket->getId();
    $git_cmd='git log --grep="Refs#'.PROJECT.$tid.'$"';
    chdir($dir);
    exec($git_cmd,$output);
    $htm='';
    foreach($output as $line)
    {
        if(strpos($line, 'commit')===0)
        {
            if ($htm) $htm.="\r\n</pre>";
            $commit['hash']   = substr($line, strlen('commit'));
            
        }
        else if(strpos($line, 'Author')===0)
        {
            $commit['author'] = substr($line, strlen('Author:'));
        }
        else if(strpos($line, 'Date')===0)
        {
            $commit['date']   = substr($line, strlen('Date:'));
            $htm.="<div class='commit_link'><a target='_blank' href='?command=display_php&path=usr/view/tickets/view_commit.php&commit=".$commit['hash']."&tid=$tid'>".$commit['hash'].':</a>'.$commit['author'].' '.$commit['date']."</div>";
            $htm.="<pre>\r\n";
        }
        else
        {		
            $htm.="\r\n".$line;
        }
    }    
    echo $htm;
    echo '</div>';
}


