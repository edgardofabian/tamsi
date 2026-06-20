<?php
if (!defined('PROJECT')) 
{
	header("Location: https://".$_SERVER['HTTP_HOST']);
	die();
}
?>
<span id="tamsi_notice_message"><?php echo $message;?></span><i class="fa fa-close" style="float:right;margin-right:20px;vertical-align:middle;margin-top:4px"></i>
<script>
/*$(function()
{
    $('#tamsi_notice').show();
    $('#tamsi_notice').click( function()
    {
        $(this).hide();
    });
});*/
</script>
