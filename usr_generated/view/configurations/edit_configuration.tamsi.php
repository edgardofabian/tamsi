<?php 
require_once 'Role.php';
require_once 'Configuration.php';
require_once 'ConfigurationView.php'; 
if (!isset($allowed_edit)) $allowed_edit=array("admin");
if (!isset($allowed)) $allowed=array("admin");
$role = new Role();
if (!$role->isUserAnyOf($allowed))
{
	$role->showUnauthorizedMessage($allowed,"?command=display_php&base_path=usr/view&path=Edit_Configuration.rest.php");
} else
{
if (!isset($configuration_view))
{
	require_once 'ConfigurationView.php'; 
	$configuration_view = new ConfigurationView(); 
	$configuration_view->init_edit();
}

if ($configuration_view->title)
{
    echo '<h2>'.$configuration_view->title.'</h2>';
}
?>



<form id="edit_configuration" <?php echo $configuration_view->form_attributes;?> class="edit" action="?command=edit&object=Configuration&base_path=<?php echo $_REQUEST["base_path"];?>&path=<?php echo $path;?>" method="POST">
<input class="field_pages" field_pages="1" type="hidden" value="1" name="pages" />
<?php $configuration_view->renderCsrf();?> 
<?php $configuration_view->renderCustomEdit();?> 
<?php if ($role->isUserAnyOf($allowed_edit)) { $configuration_view->renderEditControls(); }?>
<table class='odd_even_row_alternate_color full_width data' >
<thead><th><?php echo $configuration_view->field_header;?></th><th><?php echo $configuration_view->field_value_header;?><?php if ($role->isUserAnyOf($allowed_edit)) {?><button type='button' id='toggle_edit' class='button mx-'  mx-click='toggleEdit()'><li class='fa fa-pencil'></li> Enable Edit</button> <?php }?></th></thead>
<tbody>
<?php
foreach($configuration_view->edit_columns as $field)
{
    $label = $configuration_view->columns[$field]['label'];
    if (array_key_exists($field,$configuration_view->headers))
    {
		if (array_key_exists('label',$configuration_view->headers[$field]))
		{
			$label = $configuration_view->headers[$field]['label'];
		}
    }
    if ($field=='id')
    {?>
        <tr id='tr_id' ><td class='label'><?php echo $label;?></td><td id='td_id' ><input type='number' style='display:none' name='id' value='<?php echo $configuration_view->configuration->getId();?>' ></input><?php echo $configuration_view->configuration->getId();?></td></tr>
    <?php
    } else if ($field=='password')
    {?>
        //
    <?php
    } else 
    {
        echo "<tr id='tr_$field'><td class='label'>$label </td><td id='td_$field'>";
        if (!isset($configuration_view->columns[$field]['attributes'])) 
        {
            $configuration_view->columns[$field]['attributes']=array();
        }
        if (!isset($configuration_view->columns[$field]['attributes']["placeholder"])) 
        { 
            $configuration_view->columns[$field]['attributes']=array("placeholder"=>"Type ");
        }
        
        $render_func = 'render_'.$field; 
        $configuration_view->$render_func();
        
        ?>
        </td></tr>
    <?php
    }
} ?>
</tbody>
</table>
<div class="custom_widget">
<?php if ($configuration_custom_widget) include "$configuration_custom_widget"; ?>
</div>
<?php if ($role->isUserAnyOf($allowed_edit)) { $configuration_view->renderEditControls(); }?>
</form>
<?php
}?>
