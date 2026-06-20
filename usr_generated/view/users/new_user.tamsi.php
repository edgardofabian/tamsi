<?php 
?>
<?php 
require_once 'Role.php';
require_once 'User.php';
require_once 'UserView.php'; 
if (!isset($allowed_edit)) $allowed_edit=array("admin");
if (!isset($allowed)) $allowed=array("admin");
$role = new Role();
if (!$role->isUserAnyOf($allowed))
{
	$role->showUnauthorizedMessage($allowed,"?command=display_php&base_path=usr/view&path=New_User.rest.php");
} else
{
if (!isset($user_view))
{
	require_once 'UserView.php'; 
	$user_view = new UserView(); 
	$user_view->init_new();
}
?> 
<h2><?php echo $user_view->title;?></h2>
<form id="new_user" <?php echo $user_view->form_attributes;?> class="new" action="?command=new&object=User&base_path=<?php echo $_REQUEST["base_path"];?>&path=<?php echo $path;?>" method="POST">
<input class="field_pages" field_pages="1" type="hidden" value="1" name="pages" />
<?php $user_view->renderCsrf();?> 
<?php $user_view->renderCustomNew();?> 
<?php if ($role->isUserAnyOf($allowed_edit)) { $user_view->renderNewControls(); }?>
<table class='odd_even_row_alternate_color full_width data' >
<thead><th><?php echo $user_view->field_header;?></th><th><?php echo $user_view->field_value_header;?></th></thead>
<tbody>
<?php
foreach($user_view->new_columns as $field)
{
    $label = $field;
    if (array_key_exists($field,$user_view->headers))
    {
        if (array_key_exists('label',$user_view->headers[$field]))
        {
            $label = $user_view->headers[$field]['label'];
        }
    }
    if ($field=='id')
    {?>
        <tr id='tr_id' ><td class='label'>Id </td><td id='td_id' ><input type='number' style='display:none' name='id' value='<?php echo $user_view->user->getId();?>' ></input><?php echo $user_view->user->getId();?></td></tr>
    <?php
    } else if ($field=='password')
    {?>
        <tr><td class='label' colspan='2'>Change Password</td></tr>
<tr><td class='label'>password</td><td><input id='profile_password' class='new_input' type='password' name='password' value='' style='max-width:200px' ></input><button id='show_profile_password' type='button' class='new_input button' href='#' style='color:gray;background-color:white'> <i class='fa fa-eye'></i></button></td></tr>
<tr><td class='label'>confirm password</td><td><input id='profile_password_confirm' type='password' class='new_input' name='confirm_password' value='' style='max-width:200px' ></input><span id='password_match' class='new_input fa fa-times' name='match' style='margin-left:10px;width:30px'></span></td></tr>
<script>
$( function()
{
	$("#show_profile_password").click( function()
	{
		showProfilePassword("profile_password");
	});
	
	$("#profile_password").keyup( function()
	{
		if ($("#profile_password").val()==$("#profile_password_confirm").val()) 
		{
			$("#password_match").removeClass("fa-times");
			$("#password_match").addClass("fa-check");
			$("#password_match").css("background-color:green");
			
		} else
		{
			$("#password_match").removeClass("fa-check");
			$("#password_match").addClass("fa-times");
			$("#password_match").css("background-color:red");
		}
		
	});
	
	$("#profile_password_confirm").keyup( function()
	{
		if ($("#profile_password").val()==$("#profile_password_confirm").val()) 
		{
			$("#password_match").removeClass("fa-times");
			$("#password_match").addClass("fa-check");
			$("#password_match").css("background-color:green");
			
		} else
		{
			$("#password_match").removeClass("fa-check");
			$("#password_match").addClass("fa-times");
			$("#password_match").css("background-color:red");
		}
	});
	
});
function showProfilePassword(id) 
{
	var pw = document.getElementById(id);
	var pwc = document.getElementById(id+"_confirm");
	if (pw.type === "password") 
	{
		pw.type = "text";
		pwc.type = "text";
		setTimeout(function() { showProfilePassword(id); },1500);
	} else 
	{
		pw.type = "password";
		pwc.type = "password";
	}
}
</script>

    <?php
    } else 
    {
        echo "<tr id='tr_$field'><td class='label'>$label </td><td id='td_$field'>";
        if (!isset($user_view->columns[$field]['attributes']))
        {
            $user_view->columns[$field]['attributes']=array();
        }
        if (!isset($user_view->columns[$field]['attributes']["placeholder"])) 
        { 
            $user_view->columns[$field]['attributes']=array("placeholder"=>"Type ");
        }
        $render_func = 'render_'.$field; 
        $user_view->$render_func();
        ?>
        </td></tr>
    <?php
    }

} ?>
</tbody>
</table>
<div class="custom_widget">
<?php if ($user_custom_widget) include "$user_custom_widget"; ?>
</div>
<?php if ($role->isUserAnyOf($allowed_edit)) { $user_view->renderNewControls(); }?>
</form>
<?php
}?>
