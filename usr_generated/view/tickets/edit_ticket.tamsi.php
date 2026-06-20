<?php 
require_once 'Role.php';
require_once 'Ticket.php';
require_once 'TicketView.php'; 
if (!isset($allowed_edit)) $allowed_edit=array("admin");
if (!isset($allowed)) $allowed=array("admin");
$role = new Role();
if (!$role->isUserAnyOf($allowed))
{
	$role->showUnauthorizedMessage($allowed,"?command=display_php&base_path=usr/view&path=Edit_Ticket.rest.php");
} else
{
if (!isset($ticket_view))
{
	require_once 'TicketView.php'; 
	$ticket_view = new TicketView(); 
	$ticket_view->init_edit();
}

if ($ticket_view->title)
{
    echo '<h2>'.$ticket_view->title.'</h2>';
}
?>

<?php if (!isset($edit_remove_image)) 
{ 
   echo "<h5>Image</h5>";
   if (!isset($edit_redirect_image)) $edit_redirect_image="?command=display_php&base_path=".$_REQUEST["base_path"]."&path=".$_REQUEST["path"]."&id=".$ticket_view->ticket->getId();
   $ticket_view->renderUploadImage($ticket_view->ticket,TRUE,$edit_redirect_image,"",$role->isUserAnyOf($allowed_edit));
} ?>


<form id="edit_ticket" <?php echo $ticket_view->form_attributes;?> class="edit" action="?command=edit&object=Ticket&base_path=<?php echo $_REQUEST["base_path"];?>&path=<?php echo $path;?>" method="POST">
<input class="field_pages" field_pages="1" type="hidden" value="1" name="pages" />
<?php $ticket_view->renderCsrf();?> 
<?php $ticket_view->renderCustomEdit();?> 
<?php if ($role->isUserAnyOf($allowed_edit)) { $ticket_view->renderEditControls(); }?>
<table class='odd_even_row_alternate_color full_width data' >
<thead><th><?php echo $ticket_view->field_header;?></th><th><?php echo $ticket_view->field_value_header;?><?php if ($role->isUserAnyOf($allowed_edit)) {?><button type='button' id='toggle_edit' class='button mx-'  mx-click='toggleEdit()'><li class='fa fa-pencil'></li> Enable Edit</button> <?php }?></th></thead>
<tbody>
<?php
foreach($ticket_view->edit_columns as $field)
{
    $label = $ticket_view->columns[$field]['label'];
    if (array_key_exists($field,$ticket_view->headers))
    {
		if (array_key_exists('label',$ticket_view->headers[$field]))
		{
			$label = $ticket_view->headers[$field]['label'];
		}
    }
    if ($field=='id')
    {?>
        <tr id='tr_id' ><td class='label'><?php echo $label;?></td><td id='td_id' ><input type='number' style='display:none' name='id' value='<?php echo $ticket_view->ticket->getId();?>' ></input><?php echo $ticket_view->ticket->getId();?></td></tr>
    <?php
    } else if ($field=='password')
    {?>
        //
    <?php
    } else 
    {
        echo "<tr id='tr_$field'><td class='label'>$label </td><td id='td_$field'>";
        if (!isset($ticket_view->columns[$field]['attributes'])) 
        {
            $ticket_view->columns[$field]['attributes']=array();
        }
        if (!isset($ticket_view->columns[$field]['attributes']["placeholder"])) 
        { 
            $ticket_view->columns[$field]['attributes']=array("placeholder"=>"Type ");
        }
        
        $render_func = 'render_'.$field; 
        $ticket_view->$render_func();
        
        ?>
        </td></tr>
    <?php
    }
} ?>
</tbody>
</table>
<div class="custom_widget">
<?php if ($ticket_custom_widget) include "$ticket_custom_widget"; ?>
</div>
<?php if ($role->isUserAnyOf($allowed_edit)) { $ticket_view->renderEditControls(); }?>
</form>
<?php
}?>
