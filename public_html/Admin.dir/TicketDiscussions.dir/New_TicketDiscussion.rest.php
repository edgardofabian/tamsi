<?php
echo '<h3 class="heading">New TicketDiscussion</h3>';
//Things that you can override
// require_one 'TicketDiscussionView.php';
// $ticket_discussion_view = new TicketDiscussionView();
/* $ticket_discussion_view->init_new();
        $ticket_discussion_view->new_form_attributes=''; //attributes added to the new form
        $ticket_discussion_view->new_button_name='Create'; //Label of the new submit button
        $ticket_discussion_view->new_button_url='?command=newRest&object=TicketDiscussion'; //url of the controller where the new form will be submitted through ajax
        $ticket_discussion_view->new_button_next_url='?command=display_rest&path=Admin.dir/TicketDiscussions.dir/List TicketDiscussions.rest.php'; //url of the controller that will be visited after completing the new submission

        $ticket_discussion_view->edit_id=false;
        $ticket_discussion_view->readonly_id=false;// set the id as read only
        $ticket_discussion_view->hidden_id=false;// set the id as hidden
        $ticket_discussion_view->id_attributes=array();// attributes such as id, style etc that will be added to the id input
        $ticket_discussion_view->id_option_filters=array();// filters for selection of dropdown options
        $ticket_discussion_view->new_remove_id=false;// remove the id input from the new form
        $ticket_discussion_view->edit_ticket_id=false;
        $ticket_discussion_view->readonly_ticket_id=false;// set the ticket_id as read only
        $ticket_discussion_view->hidden_ticket_id=false;// set the ticket_id as hidden
        $ticket_discussion_view->ticket_id_attributes=array();// attributes such as id, style etc that will be added to the ticket_id input
        $ticket_discussion_view->ticket_id_option_filters=array();// filters for selection of dropdown options
        $ticket_discussion_view->new_remove_ticket_id=false;// remove the ticket_id input from the new form
        $ticket_discussion_view->ticket_id_attributes["class"]="chosen";
        $ticket_discussion_view->edit_user_id=false;
        $ticket_discussion_view->readonly_user_id=false;// set the user_id as read only
        $ticket_discussion_view->hidden_user_id=false;// set the user_id as hidden
        $ticket_discussion_view->user_id_attributes=array();// attributes such as id, style etc that will be added to the user_id input
        $ticket_discussion_view->user_id_option_filters=array();// filters for selection of dropdown options
        $ticket_discussion_view->new_remove_user_id=false;// remove the user_id input from the new form
        $ticket_discussion_view->user_id_attributes["class"]="chosen";
        $ticket_discussion_view->edit_message=false;
        $ticket_discussion_view->readonly_message=false;// set the message as read only
        $ticket_discussion_view->hidden_message=false;// set the message as hidden
        $ticket_discussion_view->message_attributes=array();// attributes such as id, style etc that will be added to the message input
        $ticket_discussion_view->message_option_filters=array();// filters for selection of dropdown options
        $ticket_discussion_view->new_remove_message=false;// remove the message input from the new form
        $ticket_discussion_view->edit_created=false;
        $ticket_discussion_view->readonly_created=false;// set the created as read only
        $ticket_discussion_view->hidden_created=false;// set the created as hidden
        $ticket_discussion_view->created_attributes=array();// attributes such as id, style etc that will be added to the created input
        $ticket_discussion_view->created_option_filters=array();// filters for selection of dropdown options
        $ticket_discussion_view->new_remove_created=false;// remove the created input from the new form
 */

include $config->paths->app_path.'/usr/view/ticket_discussions/new_ticket_discussion.rest.php';

