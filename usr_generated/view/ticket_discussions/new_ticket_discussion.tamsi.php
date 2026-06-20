<?php 
?>
<?php 
require_once 'Role.php';
require_once 'TicketDiscussion.php';
require_once 'TicketDiscussionView.php'; 
if (!isset($allowed_edit)) $allowed_edit=array("admin");
if (!isset($allowed)) $allowed=array("admin");
$role = new Role();
if (!$role->isUserAnyOf($allowed))
{
	$role->showUnauthorizedMessage($allowed,"?command=display_php&base_path=usr/view&path=New_TicketDiscussion.rest.php");
} else
{
if (!isset($ticket_discussion_view))
{
	require_once 'TicketDiscussionView.php'; 
	$ticket_discussion_view = new TicketDiscussionView(); 
	$ticket_discussion_view->init_new();
}
?> 
<h2><?php echo $ticket_discussion_view->title;?></h2>
<form id="new_ticket_discussion" <?php echo $ticket_discussion_view->form_attributes;?> class="new" action="?command=new&object=TicketDiscussion&base_path=<?php echo $_REQUEST["base_path"];?>&path=<?php echo $path;?>" method="POST">
<input class="field_pages" field_pages="1" type="hidden" value="1" name="pages" />
<?php $ticket_discussion_view->renderCsrf();?> 
<?php $ticket_discussion_view->renderCustomNew();?> 
<?php if ($role->isUserAnyOf($allowed_edit)) { $ticket_discussion_view->renderNewControls(); }?>
<table class='odd_even_row_alternate_color full_width data' >
<thead><th><?php echo $ticket_discussion_view->field_header;?></th><th><?php echo $ticket_discussion_view->field_value_header;?></th></thead>
<tbody>
<?php
foreach($ticket_discussion_view->new_columns as $field)
{
    $label = $field;
    if (array_key_exists($field,$ticket_discussion_view->headers))
    {
        if (array_key_exists('label',$ticket_discussion_view->headers[$field]))
        {
            $label = $ticket_discussion_view->headers[$field]['label'];
        }
    }
    if ($field=='id')
    {?>
        <tr id='tr_id' ><td class='label'>Id </td><td id='td_id' ><input type='number' style='display:none' name='id' value='<?php echo $ticket_discussion_view->ticket_discussion->getId();?>' ></input><?php echo $ticket_discussion_view->ticket_discussion->getId();?></td></tr>
    <?php
    } else if ($field=='password')
    {?>
        //
    <?php
    } else 
    {
        echo "<tr id='tr_$field'><td class='label'>$label </td><td id='td_$field'>";
        if (!isset($ticket_discussion_view->columns[$field]['attributes']))
        {
            $ticket_discussion_view->columns[$field]['attributes']=array();
        }
        if (!isset($ticket_discussion_view->columns[$field]['attributes']["placeholder"])) 
        { 
            $ticket_discussion_view->columns[$field]['attributes']=array("placeholder"=>"Type ");
        }
        $render_func = 'render_'.$field; 
        $ticket_discussion_view->$render_func();
        ?>
        </td></tr>
    <?php
    }

} ?>
</tbody>
</table>
<div class="custom_widget">
<?php if ($ticket_discussion_custom_widget) include "$ticket_discussion_custom_widget"; ?>
</div>
<?php if ($role->isUserAnyOf($allowed_edit)) { $ticket_discussion_view->renderNewControls(); }?>
</form>
<?php
}?>
