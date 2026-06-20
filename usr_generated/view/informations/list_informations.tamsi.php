<?php
?>
<?php
require_once 'Information.php';
require_once 'InformationView.php';
require_once 'Role.php'; 
if (!isset($allowed)) $allowed=array("admin");
if (!isset($allowed_edit)) $allowed_edit=array("admin");
$role = new Role();
if (!$role->isUserAnyOf($allowed))
{

	$role->showUnauthorizedMessage($allowed,"?command=display_php&base_path=usr/view&path=List_Informations.rest.php");
} else
{
if (!isset($information_view)) 
{ 
    $information_view = new InformationView();
    $information_view->init_list();
    
} 
$information_view->table_only_paginator=true;
 
 $theaders=array();
 foreach($information_view->list_columns as $name)
 {
   if (array_key_exists($name,$information_view->headers))
   {
       $theaders[$name]=$information_view->headers[$name];
   } else 
   {
	   $names = explode(' ',$name);
	   $label='';
	   foreach($names as $nam)
	   {
		   if ($nam) $label.=ucfirst($nam).' ';
	   }
	   $label = trim($label);
       $theaders[$name]=array("label"=>$label,"sort"=>0,"search"=>1,"class"=>"","attributes"=>array());
   }
 }
 if (is_array($_REQUEST) && array_key_exists("informations_container",$_REQUEST)) $information_view->container = $_REQUEST["informations_container"];
?>
<h2><?php echo $information_view->title;?></h2>
<form id='<?php echo $information_view->form_list;?>' <?php echo $information_view->list_form_attributes;?> class="form list" action="<?php echo $information_view->action;?>" method="POST">
<input type='hidden' name='informations_container' value='<?php echo $information_view->container;?>' />
<?php $information_view->renderCsrf();?>
<?php $information_view->renderCustomList();?>
<?php echo $information_view->custom_html_list;?>
<table class='odd_even_row_alternate_color full_width data' >
<input class='field_pages' field_pages='<?php echo $information_view->pages; ?>' type='hidden' value='<?php echo $information_view->pages; ?>' name='informations_pages' />
<input class='field_page' field_page='<?php echo $information_view->page; ?>' type='hidden' value='<?php echo $information_view->page; ?>' name='informations_page' /> 
<input class='field_items_per_page' field_items_per_page='<?php echo $information_view->items_per_page; ?>' type='hidden' value='<?php echo $information_view->items_per_page; ?>' name='informations_items_per_page' /> 
<?php $information_view->renderListControls(); ?>

<thead><tr><?php 
foreach($theaders as $vfield=>$vhead) 
{ 
	$label = $vhead["label"];
	if (array_key_exists($vfield,$information_view->columns) && (array_key_exists('label',$information_view->columns[$vfield]))) $label = $information_view->columns[$vfield]['label'];
	?>
<th id="header_<?php echo $vfield;?>"  field="<?php echo $vfield;?>"> <?php echo ($vhead["sort"]?("<button class='toggle_sort fa fa-sort' type='button'> ".$label."</button>"):$vhead["label"]);?> <?php echo ($vhead["sort"]?("<input id='informations_sort_".$vfield."' type='hidden' field='".$vfield."' class='sort' name='informations_sort_filters[$vfield]' value='".($information_view->sort_filters[$vfield])."' style='display:none;'>"):"");?> </th>
<?php } ?></tr>
<tr><?php 
foreach($theaders as $vfield=>$vhead) { 
if ($vfield=="#") { echo "<th></th>"; } else 
if ($vfield=="action") 
{ 
    echo "<th><button id=\"informations_clear_search_and_sort\" type=\"button\" class=\"button\"><i class=\"fa fa-times\"></i> filters</button>";
    echo "<button id=\"informations_delete_selected\" type=\"button\" mx-confirm=\"Are you sure? This will delete in the system all the items that are checked in the list\" class=\"danger button mx- \" mx-click=\"".$information_view->list_delete_selected_url." \"  mx-next_container=\"div.page\" mx-next=\"".$information_view->list_delete_button_next_url."\"><i class=\"fa fa-trash\"></i> <i class='fa fa-check'></i></button></th>"; 
} else { 
$filter_td_func= "render_".$vfield."_filter"; ?>
<th> <?php if ($vhead["search"] && method_exists($information_view,$filter_td_func)) { $information_view->$filter_td_func();}?> </th>
<?php    } ?>
<?php } ?></tr></thead>
<tbody>
 <?php
    $current_user=new User();
    $id = $_SESSION["user_id"];
    $current_user=$current_user->find(array("id"=>$id));
    $i=($information_view->page-1)*$information_view->item_per_page;
    foreach ($information_view->informations as $information)
    { 
        $i=$i+1;
        $path_edit = "Edit Information";
        $item_num = ($information_view->page-1)*$information_view->items_per_page+$i;
?>
<tr class="id_<?php echo $information->getId(); ?>" ><?php 
foreach($theaders as $vfield=>$vhead) { 
if ($vfield=="#") { echo "<td>".$item_num."</td>"; } else 
if ($vfield=="action") 
{ ?>
    <td><?php $information_view->renderActions($information,"",$role->isUserAnyOf($allowed_edit));?></td> <?php 
} else { 
$get_td_func= "get_".$vfield; ?>
<td> <?php if (method_exists($information_view,$get_td_func)) { $information_view->$get_td_func($information); }?> </td>
<?php   }  
} ?>
<?php } ?>
</tbody>
</table><input class="sort_order" type="hidden" name="informations_sort_order" value="" />
</form>
<?php 
if ($information_view->enable_paginator)
{
    $information_view->renderPaginator();
}
?>
<?php $information_view->renderListControls(); ?>
<script>
var list_search_timer = 0;
$(document).ready(function() 
{
    var container = '<?php echo $information_view->container;?>';
    var form_list = '<?php echo $information_view->form_list;?>';
    var sort_filters = [<?PHP 
                        $i=0;
                        foreach ($information_view->sort_orders as $order) 
                        {
                            if ($i==0)
                            {
                                echo "'$order'";
                            } else echo ",'$order'";
                            $i=$i+1;
                        }?>];
    $('#'+form_list+' input.sort_order').val(sort_filters.toString());
    $('#<?php echo $information_view->form_list;?>').find('.toggle_sort').each( function()
    {
        //alert('loading '+$(this).attr('id'));
        var sort_handle = $(this).closest('th').find('input.sort');
        if (sort_handle.val()=='ASC')
        {
            $(this).removeClass('fa-sort-desc');
            $(this).removeClass('fa-sort');
            $(this).addClass('fa-sort-asc');
        } else if (sort_handle.val()=='DESC')
        {
            $(this).removeClass('fa-sort-asc');
            $(this).removeClass('fa-sort');
            $(this).addClass('fa-sort-desc');
        } else
        {
            $(this).removeClass('fa-sort-desc');
            $(this).removeClass('fa-sort-asc');
            $(this).addClass('fa-sort');                
        }        
        $(this).click( function()
        {
            var sort_handle = $(this).closest('th').find('input.sort');
            if ($(this).hasClass('fa-sort-asc'))
            {
                sort_handle.val('DESC');
            } else
            if ($(this).hasClass('fa-sort-desc'))
            {
                sort_handle.val('');
            } else
            if ($(this).hasClass('fa-sort'))
            {
                sort_handle.val('ASC');             
            }


            if (sort_handle.hasClass('sort'))
            {
                var index = sort_filters.indexOf(sort_handle.attr('field'));
                if (index>=0)
                {
                    sort_filters.splice(index, 1);
                } 
                if ((sort_handle.val()=='ASC')||(sort_handle.val()=='DESC'))
                {
                    sort_filters.unshift(sort_handle.attr('field'));
                }
            }
            if (sort_filters.length>0)
            {
                $('#'+form_list+' input.sort_order').val(sort_filters.toString());
            }
            
            var page=parseInt($('#'+form_list+' input.field_page').val());
            var url=$('#'+form_list).attr('action')+'&page='+page+'&sort_order='+$('#'+form_list+' input.sort_order').val();
            var url_rest = url.replace('display_php','display_rest');
            loadToSelector(url_rest,container,'#'+form_list,'',loader_img);
            
        });
    });
    $('#'+form_list).find('select.filter').each( function()
    {
        $(this).change( function()
        {
            if (sort_filters.length>0)
            {
                $('#'+form_list+' input.sort_order').val(sort_filters.toString());
            }

            var page=parseInt($('#'+form_list+' input.field_page').val());
            var url=$('#'+form_list).attr('action')+'&page='+page+'&sort_order='+$('#'+form_list+' input.sort_order').val();
            var url_rest = url.replace('display_php','display_rest');
            loadToSelector(url_rest,container,'#'+form_list,'',loader_img);
            
        });
    });    
    
    
	$('#'+form_list).find('.search_text').each( function()
    {
        $(this).keyup( function()
        {
            if (sort_filters.length>0)
            {
                $('#'+form_list+' input.sort_order').val(sort_filters.toString());
            }

            var page=parseInt($('#'+form_list+' input.field_page').val());
            var url=$('#'+form_list).attr('action')+'&page='+page+'&sort_order='+$('#'+form_list+' input.sort_order').val();
            var url_rest = url.replace('display_php','display_rest');
            
            clearTimeout(list_search_timer);
            
            list_search_timer = setTimeout( function () { loadToSelector(url_rest,container,'#'+form_list,'',loader_img) },1000);
            
        });
        $(this).change( function()
        {
            if (sort_filters.length>0)
            {
                $('#'+form_list+' input.sort_order').val(sort_filters.toString());
            }

            var page=parseInt($('#'+form_list+' input.field_page').val());
            var url=$('#'+form_list).attr('action')+'&page='+page+'&sort_order='+$('#'+form_list+' input.sort_order').val();
            var url_rest = url.replace('display_php','display_rest');
            
            clearTimeout(list_search_timer);
            
            list_search_timer = setTimeout( function () { loadToSelector(url_rest,container,'#'+form_list,'',loader_img) },1000);
            
        });
    });   
    $('#'+form_list).find('.search_text_button.clear').each( function()
    {
        $(this).click( function()
        {
            $(this).parent('.search_filter_header').find('.search_text').val("");
            $(this).parent('.search_filter_header').find('.search_text').trigger('change');

        });
    });     
    $("#informations_clear_search_and_sort").click( function()
    {
        $("#"+form_list+" table.data").find("input.search_text").each( function()
        {
            $(this).val("");
        });
        $("#"+form_list+" table.data").find("select.filter").each( function()
        {
            $(this).val("");
        });			
        $("#"+form_list+" table.data").find("input.sort").each( function()
        {
            $(this).val("");
        });
        $("#"+form_list+" table.data").find("a.toglle_sort").each( function()
        {
            if ($(this).hasClass("fa-sort-asc"))
            {
                $(this).removeClass("fa-sort-asc");
            }
            if ($(this).hasClass("fa-sort-desc"))
            {
                $(this).removeClass("fa-sort-desc");
            }
            if ($(this).hasClass("fa-sort"))
            {
            
            }
            else
            {
                $(this).addClass("fa-sort-asc");
            }
            
            $(this).removeClass("fa-sort-asc");
        });
        
        if (sort_filters.length>0)
        {
            $('#'+form_list+' input.sort_order').val(sort_filters.toString());
        }

        var page=parseInt($('#'+form_list+' input.field_page').val());
        var url=$('#'+form_list).attr('action')+'&page='+page+'&sort_order='+$('#'+form_list+' input.sort_order').val();
        var url_rest = url.replace('display_php','display_rest');
        loadToSelector(url_rest,container,'#'+form_list,'',loader_img);        
    });         
});
</script>
<?php
}?>
