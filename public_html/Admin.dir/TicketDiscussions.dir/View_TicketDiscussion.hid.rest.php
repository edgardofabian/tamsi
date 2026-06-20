<?php
echo '<h3 class="heading">Edit TicketDiscussion</h3>';
//Things that you can override
// require_one 'TicketDiscussionView.php';
// $ticket_discussion_view = new TicketDiscussionView();
/* $ticket_discussion_view->init_edit();
        $ticket_discussion_view->edit_form_attributes=''; //attributes added to the form
        $ticket_discussion_view->edit_button_name='Update'; //Visible name of the Edit submit button
        $ticket_discussion_view->edit_button_url='?command=editRest&object=TicketDiscussion'; //url of the controller where edit form will be posted by ajax
        $ticket_discussion_view->edit_button_next_url='?command=display_rest&path=Admin.dir/TicketDiscussions.dir/Edit TicketDiscussion.hid.rest.php&id='.$ticket_discussion_view->ticket_discussion->getId(); //url that will be visited after completing the edit submission
        $ticket_discussion_view->edit_delete_button_url='?command=deleteRest&object=TicketDiscussion'; //url of the controller where delete form will be submitted
        $ticket_discussion_view->edit_delete_button_next_url='?command=display_rest&path=Admin.dir/TicketDiscussions.dir/List TicketDiscussions.rest.php'; //url that will be visited after completing the delete submission

        $ticket_discussion_view->edit_id=true;// allow id input to transition from lock to edit mode
        $ticket_discussion_view->readonly_id=false;// set the id as read only
        $ticket_discussion_view->hidden_id=false;// set the id as hidden
        $ticket_discussion_view->id_attributes=array();// attributes such as id, style etc that will be added to the id input
        $ticket_discussion_view->id_option_filters=array();// filters for selection of dropdown options
        $ticket_discussion_view->edit_remove_id=false;// remove the id input from the edit form
        $ticket_discussion_view->edit_ticket_id=true;// allow ticket_id input to transition from lock to edit mode
        $ticket_discussion_view->readonly_ticket_id=false;// set the ticket_id as read only
        $ticket_discussion_view->hidden_ticket_id=false;// set the ticket_id as hidden
        $ticket_discussion_view->ticket_id_attributes=array();// attributes such as id, style etc that will be added to the ticket_id input
        $ticket_discussion_view->ticket_id_option_filters=array();// filters for selection of dropdown options
        $ticket_discussion_view->edit_remove_ticket_id=false;// remove the ticket_id input from the edit form
        $ticket_discussion_view->ticket_id_attributes["class"]="chosen";
        $ticket_discussion_view->edit_user_id=true;// allow user_id input to transition from lock to edit mode
        $ticket_discussion_view->readonly_user_id=false;// set the user_id as read only
        $ticket_discussion_view->hidden_user_id=false;// set the user_id as hidden
        $ticket_discussion_view->user_id_attributes=array();// attributes such as id, style etc that will be added to the user_id input
        $ticket_discussion_view->user_id_option_filters=array();// filters for selection of dropdown options
        $ticket_discussion_view->edit_remove_user_id=false;// remove the user_id input from the edit form
        $ticket_discussion_view->user_id_attributes["class"]="chosen";
        $ticket_discussion_view->edit_message=true;// allow message input to transition from lock to edit mode
        $ticket_discussion_view->readonly_message=false;// set the message as read only
        $ticket_discussion_view->hidden_message=false;// set the message as hidden
        $ticket_discussion_view->message_attributes=array();// attributes such as id, style etc that will be added to the message input
        $ticket_discussion_view->message_option_filters=array();// filters for selection of dropdown options
        $ticket_discussion_view->edit_remove_message=false;// remove the message input from the edit form
//        $ticket_discussion_view->message_attributes["class"]="jwysiwyg";
        $ticket_discussion_view->edit_created=true;// allow created input to transition from lock to edit mode
        $ticket_discussion_view->readonly_created=false;// set the created as read only
        $ticket_discussion_view->hidden_created=false;// set the created as hidden
        $ticket_discussion_view->created_attributes=array();// attributes such as id, style etc that will be added to the created input
        $ticket_discussion_view->created_option_filters=array();// filters for selection of dropdown options
        $ticket_discussion_view->edit_remove_created=false;// remove the created input from the edit form
 */

include $config->paths->app_path.'/usr/view/ticket_discussions/edit_ticket_discussion.rest.php';

