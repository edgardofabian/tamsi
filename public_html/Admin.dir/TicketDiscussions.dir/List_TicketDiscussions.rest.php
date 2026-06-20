<?php
echo '<h3 class="heading">List TicketDiscussions</h3>';
//Things that you can override
// require_one 'TicketDiscussionView.php';
// $ticket_discussion_view = new TicketDiscussionView();
/* $ticket_discussion_view->init_list();
$headers = ["id","ticket_id","user_id","message","created"];//fields that will be displayed in the list table
 */

include $config->paths->app_path.'/usr/view/ticket_discussions/list_ticket_discussions.rest.php';

