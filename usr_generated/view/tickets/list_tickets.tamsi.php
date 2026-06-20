<?php
?>
<?php
require_once 'Ticket.php';
require_once 'TicketView.php';
require_once 'Role.php'; 
if (!isset($allowed)) $allowed=array("admin");
if (!isset($allowed_edit)) $allowed_edit=array("admin");
$role = new Role();
if (!$role->isUserAnyOf($allowed))
{

	$role->showUnauthorizedMessage($allowed,"?command=display_php&base_path=usr/view&path=List_Tickets.rest.php");
} else
{
if (!isset($ticket_view)) 
{ 
    $ticket_view = new TicketView();
    $ticket_view->init_list();
    
} 
$ticket_view->table_only_paginator=true;
 
 $theaders=array();
 foreach($ticket_view->list_columns as $name)
 {
   if (array_key_exists($name,$ticket_view->headers))
   {
       $theaders[$name]=$ticket_view->headers[$name];
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
 if (is_array($_REQUEST) && array_key_exists("tickets_container",$_REQUEST)) $ticket_view->container = $_REQUEST["tickets_container"];
?>
<h2><?php echo $ticket_view->title;?></h2>
<form id='<?php echo $ticket_view->form_list;?>' <?php echo $ticket_view->list_form_attributes;?> class="form list" action="<?php echo $ticket_view->action;?>" method="POST">
<input type='hidden' name='tickets_container' value='<?php echo $ticket_view->container;?>' />
<?php $ticket_view->renderCsrf();?>
<?php $ticket_view->renderCustomList();?>
<?php echo $ticket_view->custom_html_list;?>
<table class='odd_even_row_alternate_color full_width data' >
<input class='field_pages' field_pages='<?php echo $ticket_view->pages; ?>' type='hidden' value='<?php echo $ticket_view->pages; ?>' name='tickets_pages' />
<input class='field_page' field_page='<?php echo $ticket_view->page; ?>' type='hidden' value='<?php echo $ticket_view->page; ?>' name='tickets_page' /> 
<input class='field_items_per_page' field_items_per_page='<?php echo $ticket_view->items_per_page; ?>' type='hidden' value='<?php echo $ticket_view->items_per_page; ?>' name='tickets_items_per_page' /> 
<?php $ticket_view->renderListControls(); ?>

<thead><tr><?php 
foreach($theaders as $vfield=>$vhead) 
{ 
	$label = $vhead["label"];
	if (array_key_exists($vfield,$ticket_view->columns) && (array_key_exists('label',$ticket_view->columns[$vfield]))) $label = $ticket_view->columns[$vfield]['label'];
	?>
<th id="header_<?php echo $vfield;?>"  field="<?php echo $vfield;?>"> <?php echo ($vhead["sort"]?("<button class='toggle_sort fa fa-sort' type='button'> ".$label."</button>"):$vhead["label"]);?> <?php echo ($vhead["sort"]?("<input id='tickets_sort_".$vfield."' type='hidden' field='".$vfield."' class='sort' name='tickets_sort_filters[$vfield]' value='".($ticket_view->sort_filters[$vfield])."' style='display:none;'>"):"");?> </th>
<?php } ?></tr>
<tr><?php 
foreach($theaders as $vfield=>$vhead) { 
if ($vfield=="#") { echo "<th></th>"; } else 
if ($vfield=="action") 
{ 
    echo "<th><button id=\"tickets_clear_search_and_sort\" type=\"button\" class=\"button\"><i class=\"fa fa-times\"></i> filters</button>";
    echo "<button id=\"tickets_delete_selected\" type=\"button\" mx-confirm=\"Are you sure? This will delete in the system all the items that are checked in the list\" class=\"danger button mx- \" mx-click=\"".$ticket_view->list_delete_selected_url." \"  mx-next_container=\"div.page\" mx-next=\"".$ticket_view->list_delete_button_next_url."\"><i class=\"fa fa-trash\"></i> <i class='fa fa-check'></i></button></th>"; 
} else { 
$filter_td_func= "render_".$vfield."_filter"; ?>
<th> <?php if ($vhead["search"] && method_exists($ticket_view,$filter_td_func)) { $ticket_view->$filter_td_func();}?> </th>
<?php    } ?>
<?php } ?></tr></thead>
<tbody>
 <?php
    $current_user=new User();
    $id = $_SESSION["user_id"];
    $current_user=$current_user->find(array("id"=>$id));
    $i=($ticket_view->page-1)*$ticket_view->item_per_page;
    foreach ($ticket_view->tickets as $ticket)
    { 
        $i=$i+1;
        $path_edit = "Edit Ticket";
        $item_num = ($ticket_view->page-1)*$ticket_view->items_per_page+$i;
?>
<tr class="id_<?php echo $ticket->getId(); ?>" ><?php 
foreach($theaders as $vfield=>$vhead) { 
if ($vfield=="#") { echo "<td>".$item_num."</td>"; } else 
if ($vfield=="action") 
{ ?>
    <td><?php $ticket_view->renderActions($ticket,"",$role->isUserAnyOf($allowed_edit));?></td> <?php 
} else { 
$get_td_func= "get_".$vfield; ?>
<td> <?php if (method_exists($ticket_view,$get_td_func)) { $ticket_view->$get_td_func($ticket); }?> </td>
<?php   }  
} ?>
<?php } ?>
</tbody>
</table><input class="sort_order" type="hidden" name="tickets_sort_order" value="" />
</form>
<?php 
if ($ticket_view->enable_paginator)
{
    $ticket_view->renderPaginator();
}
?>
<?php $ticket_view->renderListControls(); ?>
<script>
var list_search_timer = 0;
$(document).ready(function() 
{
    var container = '<?php echo $ticket_view->container;?>';
    var form_list = '<?php echo $ticket_view->form_list;?>';
    var sort_filters = [<?PHP 
                        $i=0;
                        foreach ($ticket_view->sort_orders as $order) 
                        {
                            if ($i==0)
                            {
                                echo "'$order'";
                            } else echo ",'$order'";
                            $i=$i+1;
                        }?>];
    $('#'+form_list+' input.sort_order').val(sort_filters.toString());
    $('#<?php echo $ticket_view->form_list;?>').find('.toggle_sort').each( function()
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
    $("#tickets_clear_search_and_sort").click( function()
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
