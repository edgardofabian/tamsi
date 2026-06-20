<?php 
?>
<?php 
require_once 'Role.php';
require_once 'Information.php';
require_once 'InformationView.php'; 
if (!isset($allowed_edit)) $allowed_edit=array("admin");
if (!isset($allowed)) $allowed=array("admin");
$role = new Role();
if (!$role->isUserAnyOf($allowed))
{
	$role->showUnauthorizedMessage($allowed,"?command=display_php&base_path=usr/view&path=New_Information.rest.php");
} else
{
if (!isset($information_view))
{
	require_once 'InformationView.php'; 
	$information_view = new InformationView(); 
	$information_view->init_new();
}
?> 
<h2><?php echo $information_view->title;?></h2>
<form id="new_information" <?php echo $information_view->form_attributes;?> class="new" action="?command=new&object=Information&base_path=<?php echo $_REQUEST["base_path"];?>&path=<?php echo $path;?>" method="POST">
<input class="field_pages" field_pages="1" type="hidden" value="1" name="pages" />
<?php $information_view->renderCsrf();?> 
<?php $information_view->renderCustomNew();?> 
<?php if ($role->isUserAnyOf($allowed_edit)) { $information_view->renderNewControls(); }?>
<table class='odd_even_row_alternate_color full_width data' >
<thead><th><?php echo $information_view->field_header;?></th><th><?php echo $information_view->field_value_header;?></th></thead>
<tbody>
<?php
foreach($information_view->new_columns as $field)
{
    $label = $field;
    if (array_key_exists($field,$information_view->headers))
    {
        if (array_key_exists('label',$information_view->headers[$field]))
        {
            $label = $information_view->headers[$field]['label'];
        }
    }
    if ($field=='id')
    {?>
        <tr id='tr_id' ><td class='label'>Id </td><td id='td_id' ><input type='number' style='display:none' name='id' value='<?php echo $information_view->information->getId();?>' ></input><?php echo $information_view->information->getId();?></td></tr>
    <?php
    } else if ($field=='password')
    {?>
        //
    <?php
    } else 
    {
        echo "<tr id='tr_$field'><td class='label'>$label </td><td id='td_$field'>";
        if (!isset($information_view->columns[$field]['attributes']))
        {
            $information_view->columns[$field]['attributes']=array();
        }
        if (!isset($information_view->columns[$field]['attributes']["placeholder"])) 
        { 
            $information_view->columns[$field]['attributes']=array("placeholder"=>"Type ");
        }
        $render_func = 'render_'.$field; 
        $information_view->$render_func();
        ?>
        </td></tr>
    <?php
    }

} ?>
</tbody>
</table>
<div class="custom_widget">
<?php if ($information_custom_widget) include "$information_custom_widget"; ?>
</div>
<?php if ($role->isUserAnyOf($allowed_edit)) { $information_view->renderNewControls(); }?>
</form>
<?php
}?>
