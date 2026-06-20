<?php
    if (!defined("PROJECT")) die("Unauthorized!");
    
    
    
    $base = new Fileproperties($_REQUEST['base_path']);
    $name = $base->getName();    
    $menu_id = preg_replace('/_[0-9][0-9]/','',str_replace('.dir','',strtolower(str_replace(' ','_',str_replace('/','_',$_REQUEST['base_path'])))));
    
    $page_path = new Fileproperties($_REQUEST['path']);
    $page_name = $page_path->getName();    
    $page_menu_id = $menu_id.'_'.str_replace(' ','_',strtolower($page_name));
    
    echo "<h3>Title: $name</h3>";
    
	echo '<div class="editor">'."\r\n";
	include __DIR__.'/../../usr/view/editor.php';
	echo '</div>'."\r\n";
	echo '<div class="edit_area"><div class="editable">'."\r\n";
	include $path;
	echo '</div></div>'."\r\n";
    if (isset($author))
    {
        echo '<p class="book"><span style="float:left">by:'.$author.'</span>'.'<span style="float:right">Updated:'.(isset($last_update)?$last_update:'').'</span></p>';
    }
	
?>
<script>
$( function()
{
    var pages = $('#<?php echo $menu_id;?>').parent('li').find('>ul').clone();
    pages.find('li.admin').remove();
    var n = pages.find('li').length;
    var w_u = 150*n;
    //pages.css('width',w_u+'px');
    pages.addClass('book_pages');
    pages.find('li a#<?php echo $page_menu_id;?>').parent('li').addClass('active');
    var z = 500+n;
    var i=1;
    pages.find('li').each( function()
    {
        $(this).css('z-index',z);
        var name = $(this).find('a').text();
        var sname = name.substr(0,15);
        $(this).prepend($('<label>'+name+'</label>'));
        $(this).find('a').text(i+'. '+sname+'...').attr('place_holder',name);
        z=z-1;
        i=i+1;
    });
    $('div.editor').after(pages);
    
    pages.show();
    
    $('div.page').find('li.has_child').off('click');
    
    $('div.page').find('li.has_child').click( function()
    {
        var mx_click=$(this).find('ul li').first().find('a').attr('mx-click');
        var mx_container=$(this).find('ul li').first().find('a').attr('mx-container');
       loadToSelector(mx_click,'div.page','','',loader_img);
    });
    
	$("#book #num_apples").change( function()
	{
		var num_apples = parseInt($(this).val());
		if (num_apples < 1) alert('There should be at least 1 apple.');
		else
		{
			var apples='';
			for(var i=0;i<num_apples;i++)
			{
				apples = apples + '<img src="img/apple.png" style="max-width:64px"></img>';
			}
			$('#book div.draggable').html(apples);
			$('#book ').find('.draggable').children().each( function()
			{
				$(this).draggable();
			});
		}
	});    
});
</script>
