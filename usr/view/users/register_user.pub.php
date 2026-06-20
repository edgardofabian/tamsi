<?php 
if (!defined("PROJECT")) die("Unauthorized!");
require_once 'Role.php'; 
if (!isset($user_view)) 
{ 
	require_once 'UserView.php'; 
	$user_view = new UserView(); 
	$user_view->init_new();
}?> 
<form id="register_user"  <?php echo $user_view->new_form_attributes;?> class="new" action="?command=register&object=User&base_path=<?php echo $_REQUEST["base_path"];?>&path=<?php echo $path;?>" method="POST">
<?php $user_view->renderLoggedOutCsrf();?> 
<?php $user_view->renderCustomNew();?> 
<table class='odd_even_row_alternate_color full_width data' >
<thead><th>Field</th><th>Data</th></thead>
<tbody>
<tr id='tr_firstname'><td class='label'>Firstname </td><td id='td_firstname'>
<?php
    $user_view->columns["firstname"]["attributes"]=array("placeholder"=>"Firstname","class"=>"mx-required"); 
    $user_view->render_firstname();
?>
</td></tr>
<tr id='tr_lastname'><td class='label'>Lastname </td><td id='td_lastname'>
<?php
    $user_view->columns["lastname"]["attributes"]=array("placeholder"=>"Lastname","class"=>"mx-required"); 
    $user_view->render_lastname();
?>
</td></tr>
<tr id='tr_email'><td class='label'>Email </td><td id='td_email'>
<?php
    $user_view->columns["email"]["attributes"]=array("placeholder"=>"Email","class"=>"mx-required"); 
    $user_view->render_email();
?>
</td></tr>
<tr><td class='label' colspan='2'>Change Password</td></tr>
<tr><td class='label'>password</td><td><input id='profile_password' class='mx-required' type='password' name='password' value='' style='max-width:200px' ></input><button id='show_profile_password' type='button' class=' button' href='#' style='color:gray;background-color:white'> <i class='fa fa-eye'></i></button></td></tr>
<tr><td class='label'>confirm password</td><td><input id='profile_password_confirm' type='password' class='mx-required' name='confirm_password' value='' style='max-width:200px' ></input><span id='password_match' class=' fa fa-times' name='match' style='margin-left:10px;width:30px'></span></td></tr>
<input type='hidden' name='id' value='0' />
<input type='hidden' name='created' value='2000-01-01 00:00:00' />
<input type='hidden' name='role' value='0' />
<input type='hidden' name='salt' value='' />
<input type='hidden' name='ip' value='' />
<input type='hidden' name='age' value='0' />
<input type='hidden' name='birthdate' value='2000-01-01' />
<input type='hidden' name='occupation' value='' />
<input type='hidden' name='address' value='' />
<input type='hidden' name='city' value='' />
<input type='hidden' name='province' value='' />
<input type='hidden' name='mobile' value='' />
<input type='hidden' name='landline' value='' />
<input type='hidden' name='status' value='0' />
<input type='hidden' name='gender' value='0' />
<input type='hidden' name='custom' value='' />
<input type='hidden' name='login' value='2000-01-01 00:00:00' />
<input type='hidden' name='loginip' value='' />
<input type='hidden' name='updateip' value='' />
<input type='hidden' name='resetkey' value='' />
<input type='hidden' name='securekey' value='' />

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
</tbody>
</table><div class="custom_widget">
<?php if ($user_custom_widget) include "$user_custom_widget"; ?>
</div>
<a class="button mx-" mx-click="?command=register&object=User" href="#" mx-next="?command=display_rest&path=Admin.dir/Users.dir/List Users.rest.php" mx-container="div.page"><li class="fa fa-plus"></li> Register </a>
</form>
</div>
