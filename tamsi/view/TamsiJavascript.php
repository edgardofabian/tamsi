<?php 
require_once 'Javascript.php';
require_once 'Security.php';
require_once 'routes.cfg.php';
$routes = new Routes();
$js = new Javascript();
$paths = new Paths();
if (!defined("mx-hidden-form"))
{
    define("mx-hidden-form",true);?>
    <form id="mx-hidden-form">
    <?php
    $security=new Security();
    echo '<input type="hidden" name="'.$security->getCsrfName().'" value="'.$security->getCsrfValue().'"/>';
    ?>
    <textarea type="text" id="clipboard" name="" style="display:none;" ></textarea>
    <div id="clipboard_notice" class="notice" style="display:none;position:fixed;top:0px;left:10%;width:80%;z-index:4000;padding:0px 5px;background-color:#88FF88"></div>
    
    </form>
<?php
}?>
<script type="text/javascript">
    var ajax_supported;
    var school_title = "Gahum's School .NET";
    var loader_img='<?php ($js->renderAjaxloader());?>';
    var routes=<?php echo json_encode($routes->urls);?>;
    
    
    
    if (loader_img.trim().length==0) //use default if not provided
    {
        loader_img='<div class="ajax_loader" style="z-inder:5000;position:fixed;width:34px;height:34px;top:50px;left:50%;margin-left:-17px"><svg width="32" height="32" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><ellipse cx="16" cy="16" rx="14" ry="14" stroke="white" stroke-width="2" fill="black"  /><line x1="16" y1="16" x2="16" y2="4" stroke="white" stroke-width="2" /><animateTransform attributeType="xml" attributeName="transform" type="rotate"  from="0 0 0"   to="360 0 0"  dur="1s"  repeatCount="indefinite"/> </svg></div>';
    }
    
    function copyToClipBoard(val,label)
    {
        $('#clipboard').show();
        $('#clipboard').val(val);
        
        var clipboard = document.getElementById('clipboard');

        clipboard.select();
        clipboard.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");    
        $('#clipboard_notice').html("Copied "+label+" to clipboard.");
        $('#clipboard_notice').css('display','inline-block');
        if (label.indexOf("`")>=0) MathJax.Hub.Queue(["Typeset",MathJax.Hub,'#clipboard_notice']); 
        $('#clipboard').hide();
        
        setTimeout(function() { $('#clipboard_notice').hide(); }, 3000);         
    }

    $( function()
    {
        if (window.XMLHttpRequest) 
        {
            ajax_supported = true;
        } else 
        {
            ajax_supported = false; 
        }

        window.onpopstate = function(e)
        {
            if(e.state)
            {
                //document.getElementById("content").innerHTML = e.state.html;
                //alert('back state');
                $(e.state.selector).html(e.state.html);
                document.title = e.state.pageTitle;
                reloadJs(e.state.selector);
            } else
            {
                //window.history.back();
            }
        };       
        /*
        window.onhashchange = function(e)
        {
            alert('haschange');
        };*/ 
        
    });
    
    var insert_id = 0;

    
    function capitalFirst(str)
    {
		return str.substring(0,1).toUpperCase()+str.substring(1);
	}
    
     function loadAdjaxData(selector,htm,title, urlPath, push_state)
     {
         var $htm_data =$(htm);
         $(selector).html('');
         if ((htm.length>0) && $htm_data.length>0)
         {
            $(selector).append($htm_data);
         } else 
         {
             if (htm.length>0)
             {
                 $htm_data = $('<div>'+htm+'</htm>').html(); 
                 $(selector).html($htm_data);
             }
         }
         

         if (push_state)
         {
             if (urlPath.includes('display_rest')) urlPath = urlPath.replace('display_rest','display_php');


             if (Object.keys(routes).length>0)
             {
                 for (const url in routes) 
                 {
                     urlPath = urlPath.replace(routes[url].query,url+'?');
                     urlPath = urlPath.replace('?&','?');
                     slen = urlPath.length;
                     if (urlPath.charAt(slen-1)=='?')
                     {
                         urlPath = urlPath.replace('?','');
                     }
                 }
             }
            
             window.history.pushState({"html":htm,"pageTitle":title,"selector":selector},"", urlPath);
         }
         document.title = title;
         reloadJs(selector);
         //resetSitemap();
     }    
    
    
    function checkRequired(form_selector)
    {
		var msg='';
		$(form_selector).find('input.mx-required').each(function()
		{
            var ms = capitalFirst($(this).attr('name'))+" is required. \r\n";;
            if ($(this)[0].hasAttribute('mx-required_name'))
            {
                ms = replaceDynamicVariables($(this).attr('mx-required_name'));
            }
			if ($(this).val()=='') msg=msg+ms;    
		});
		$(form_selector).find('select.mx-required').each(function()
		{
            var ms = capitalFirst($(this).attr('name'))+" is required. \r\n";;
            if ($(this)[0].hasAttribute('mx-required_name'))
            {
                ms = replaceDynamicVariables($(this).attr('mx-required_name'));
            }
			if ($(this).val()=='') msg=msg+ms;  
		});		
		$(form_selector).find('textarea.mx-required').each(function()
		{
            var ms = capitalFirst($(this).attr('name'))+" is required. \r\n";;
            if ($(this)[0].hasAttribute('mx-required_name'))
            {
                ms = replaceDynamicVariables($(this).attr('mx-required_name'));
            }
			if ($(this).val()=='') msg=msg+ms;  
		});		
        $(form_selector).find('input.email').each( function()
        {
            if (checkEmail($(this).val())) {  ;} else {msg=msg+$(this).attr('name')+" is invalid email. \r\n";}
        });
		return msg;
	}

    function checkEmail(email)
    {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,6})+$/;
        return regex.test(email);
    }
    
    function replaceDynamicVariables(text)
    {
        var txt = text;
        while (txt.indexOf("{{") >= 0)
        { 
            var st=txt.indexOf("{{");
            var en=txt.indexOf("}}");
            var dyn = txt.substring(st+2, en).split(";");
            var val='';
            if (dyn[1]=='html')
            {
                val = $(dyn[0]).html();
                if (val == undefined) alert('Undefined $('+dyn[0]+'html()!');
            }
            else if (dyn[1]=='value')
            {
                val = $(dyn[0]).val();
                if (val == undefined) alert('Undefined $('+dyn[0]+'val()!');
            }
            else if (dyn[1]=='text')
            {
                val = $(dyn[0]).text();
                if (val == undefined) alert('Undefined $('+dyn[0]+'text()!');
            }
            else 
            {
                val = $(dyn[0]).attr(dyn[1]);
                if (val == undefined) alert('Undefined $('+dyn[0]+'attr('+dyn[1]+')!');
            }
            
            var left = txt.substring(0,st);
            var right = txt.substring(en+2);
            txt = left+val+right;
        }
        
        return txt;
    }
    
    function processEvent(me,url)
    {
        if (url.length==0)
        {
            if (me[0].hasAttribute('mx-confirm'))
            {
                var conf = 'Are you sure?';
                if (me.attr('mx-confirm').length >0)
                {
                    var mx_confirm = me.attr('mx-confirm');
                    var fs=mx_confirm.indexOf("(");
                    var fe=mx_confirm.indexOf(")",fs);
                    if ((fs>0) && (fe>0)) //a function
                    {
                        var func_name = mx_confirm.substr(0,fs);
                        
                        var func_param = '';
                        if (fe>(fs+1)) func_param = mx_confirm.substr(fs+1,fs-fe-1);
                        
                        
                        
                        if (typeof window[func_name] === 'function') 
                        { 
                            //alert(func_name+"+"+func_param);
                            if (!window[func_name](func_param)) return false;
                            conf = '';
                        } else conf = replaceDynamicVariables(mx_confirm);
                        
                    } else
                    {
                        conf = replaceDynamicVariables(mx_confirm);
                    }
                }
                
                if ((conf.length>0) && (!confirm(conf)))
                {
                    return false;
                }
            }
            
            if (me[0].hasAttribute('mx-redirect'))
            {
                window.location.href='<?php echo $paths->base_url;?>/'+me.attr('mx-redirect');
            }
            
            if (me[0].hasAttribute('mx-new_window'))
            {
                window.open(me.attr('mx-new_window'));
            }
        } 
        else
        {
            var form_selector = '';
            if (me[0].hasAttribute('mx-form'))
            {
                if (me.attr('mx-form').length >0)
                {
                    form_selector = me.attr('mx-form');
                } 
            } 
            if (form_selector.length==0)
            {
                if (me.closest('form').length>0)
                {
                    if (me.closest('form')[0].hasAttribute('id'))
                    {
                        form_selector = '#'+me.closest('form').attr('id');
                    }
                } 
            }
            if (form_selector.length==0)
            {
                form_selector = "#mx-hidden-form";
            }
            
            
            if (me[0].hasAttribute('mx-no_required'))
            {
            }
            else
            {
                var msg='';
                msg=checkRequired(form_selector);
                
                if (msg.length>0)
                {
                    alert(msg);
                    return false;
                }
            }
            
            
            if (me[0].hasAttribute('mx-confirm'))
            {
                var conf = 'Are you sure?';
                if (me.attr('mx-confirm').length >0)
                {
                    var mx_confirm = me.attr('mx-confirm');
                    var fs=mx_confirm.indexOf("(");
                    var fe=mx_confirm.indexOf(")",fs);
                    if ((fs>0) && (fe>0)) //a function
                    {
                        var func_name = mx_confirm.substr(0,fs);
                        
                        var func_param = '';
                        if (fe>(fs+1)) func_param = mx_confirm.substr(fs+1,fs-fe-1);
                        
                       
                        
                        if (typeof window[func_name] === 'function') 
                        { 
                             //alert(func_name+"+"+func_param);
                            if (!window[func_name](func_param)) return false;
                            conf='';
                        } else conf = replaceDynamicVariables(mx_confirm);
                        
                    } else
                    {
                        conf = replaceDynamicVariables(mx_confirm);
                    }
                }
                
                if ((conf.length>0) && (!confirm(conf)))
                {
                    return false;
                }
                
            }
            
            var iop=url.indexOf("(");
            if (iop>0) //a function
            {
                var func_name = url.substr(0,iop);
                var iope = url.indexOf(")");
                var func_param = '';
                if (iope>(iop+1)) func_param = url.substr(iop+1,iope-iop-1);
                //alert('running '+func_name);
                if (typeof window[func_name] === 'function') 
                {
                     
                    window[func_name](func_param);
                    return false;
                }
            }
            
            
            var container = '';
            if (me[0].hasAttribute('mx-container'))
            {
                container=me.attr('mx-container');
            }
            
            if ((ajax_supported) && (!(me[0].hasAttribute('mx-submit'))))
            {
                var success={url:'',container:container,clear:0,next_silent:0,silent:0,confirm:''};
                if (me[0].hasAttribute('mx-next'))
                {
                    if (me[0].hasAttribute('mx-next_confirm'))
                    {
                        if (me.attr('mx-next_confirm').length>0)
                        {
                            success.confirm = me.attr('mx-next_confirm');
                        }
                    }

                    var next_url = me.attr('mx-next');
                    if (typeof next_url !== typeof undefined && next_url !== false) 
                    {
                        success.url=next_url;
                        var next_container = me.attr('mx-next_container');
                        if (typeof next_container !== typeof undefined && next_container !== false) 
                        {
                            success.container = next_container;						
                        }
                        else
                        {
                            success.container = container;
                        }
                    }
                    var next_form = '#mx-hidden-form';
                    if ($(form_selector).length > 0)
                    {
                        next_form = form_selector;
                    }
                    if (me[0].hasAttribute('mx-next_form'))
                    {
                        if (me.attr('mx-next_form').length>0)
                        {
                            success.form = me.attr('mx-next_form');
                        }
                        else success.form = next_form;
                    }
                    else success.form = next_form;
                }
                if (me.hasClass('mx-clear_after'))
                {
                    success.clear=1;
                }
                if (me.hasClass('mx-silent'))
                {
                    success.silent=1;
                }
                if (me.hasClass('mx-next_silent'))
                {
                    success.next_silent=1;
                }
                url = '<?php echo $paths->base_url;?>/'+url;
                loadToSelector(url,container,form_selector,success,loader_img);
            } else
            {
                url = '<?php echo $paths->base_url;?>/'+url;
                $(form_selector).attr('action',url);
                $(form_selector).attr('method','POST');
                $(form_selector).submit();
            }
        }
        return false;        
    }

    function loopAction(url,container,form_selector,success,loader_img,loop_time)
    {
        loadToSelector(url,container,form_selector,success,loader_img);
        setTimeout(function() { loopAction(url,container,form_selector,success,loader_img,loop_time); }, loop_time);
    }
    
    function loadActions(selector)
    {
        if (selector.length==0) selector='body';
        $(selector).find('.mx-').each( function()
        {
            if ($(this)[0].hasAttribute('mx-click'))
            {
                $(this).off("click");
                
                $(this).click( function()
                {
                    var url = $(this).attr('mx-click').trim();
                    
                    processEvent($(this),url);
                });
            } else if ($(this)[0].hasAttribute('mx-change'))
            {
                $(this).off("change");
                
                $(this).change( function()
                {
                    var url = $(this).attr('mx-change').trim();
                    processEvent($(this),url);
                });

            } else if (($(this)[0].hasAttribute('mx-loop')) && (parseInt($(this).attr('mx-loop-time'))>0))
            {
                var container = '';
                if ($(this)[0].hasAttribute('mx-container'))
                {
                    container=$(this).attr('mx-container');
                }

                var form_selector = '';
                if ($(this)[0].hasAttribute('mx-form'))
                {
                    if ($(this).attr('mx-form').length >0)
                    {
                        form_selector = $(this).attr('mx-form');
                    } 
                } 
                if (form_selector.length==0)
                {
                    if ($(this).closest('form').length>0)
                    {
                        if ($(this).closest('form')[0].hasAttribute('id'))
                        {
                            form_selector = '#'+$(this).closest('form').attr('id');
                        }
                    } 
                }
                if (form_selector.length==0)
                {
                    form_selector = "#mx-hidden-form";
                }                
                var success={url:'',container:container,clear:0,next_silent:1,silent:1,confirm:''};
                
                var url = $(this).attr('mx-loop');
                var loop_time = parseInt($(this).attr('mx-loop-time'));
                loopAction(url,container,form_selector,success,loader_img,loop_time);
            }
        });        
    }
       
	function loadToSelector(url,selector,form_selector,success_action,loader_img)
	{
        var form_data;
        
		if (!$(form_selector).length)
		{
			form_selector = "#mx-hidden-form";
		}
		if ($(form_selector).length)
        {
			form_data = $(form_selector).serialize();
		}
		
		if ($(selector).length)
		{
			form_data = form_data+'&mx-container='+selector+'&mx-form='+form_selector;
		}
		//loadToSelector_form_data(url,selector,form_data,success_action,loader_img);
        var param = { url: url, selector:selector, push_state:1 };
        post_form_data(param,form_data,success_action,'',loader_img);
	}	
    
	function loadToSelectorVolatile(url,selector,form_selector,success_action,loader_img)
	{
        var form_data;
        
		if (!$(form_selector).length)
		{
			form_selector = "#mx-hidden-form";
		}
		if ($(form_selector).length)
        {
			form_data = $(form_selector).serialize();
		}
		
		if ($(selector).length)
		{
			form_data = form_data+'&mx-container='+selector+'&mx-form='+form_selector;
		}
		//loadToSelector_form_data(url,selector,form_data,success_action,loader_img);
        var param = { url: url, selector:selector, push_state:0 };
        post_form_data(param,form_data,success_action,'',loader_img);
	}    
   
	function post_form_data(param,form_data,success_action,fail_action,loader_img)
	{
        if (param.selector.trim().length>0)
        {
            $(param.selector).append(loader_img);
        }
        else
        {
			$('body').append(loader_img);
		}
        
        if (param.url.length > 0)
        {
            var iop=param.url.indexOf("(");
            if (iop>0) //a function
            {
                var func_name = param.url.substr(0,iop);
                var iope = param.url.indexOf(")");
                var func_param = '';
                if (iope>(iop+1)) func_param = param.url.substr(iop+1,iope-iop-1);
                //alert('running '+func_name);
                if (typeof window[func_name] === 'function') 
                {
                     
                    window[func_name](func_param);
                    
                    if ((typeof success_action =='object') && (success_action.url.trim().length>0))
                    {
                        
                        var url=success_action.url;
                        var iop=url.indexOf("(");
                        if (iop>0) //a function
                        {
                            var func_name = url.substr(0,iop);
                            var iope = url.indexOf(")");
                            var func_param = '';
                            if (iope>(iop+1)) func_param = url.substr(iop+1,iope-iop-1);
                            //alert('running '+func_name);
                            if (typeof window[func_name] === 'function') 
                            {
                                var container = 'body';
                                if (param.selector.trim().length>0) container=param.selector;
                                 
                                $('body').find('.ajax_loader').each(function()
                                {
                                    $(this).remove();
                                });                                     
                                window[func_name](func_param);
                                return false;
                            }
                        }                                                      
                        perform_next(success_action,param.selector,json_data.message);
                    } else
                    {
                        return false;
                    }
                }
            }        
        }
        //alert(param.url);    
		$.post(encodeURI(param.url),form_data,function(data)
		{
			var json=1;
			var json_data;
			
			if(typeof data =='object')
			{
				json_data = data;
			}
			else
			{
				try 
				{
					json_data = JSON.parse(data);
				} catch (e) 
				{
					json=0;
				}
			}
			if (json)
			{
				// It is JSON
				if (json_data.success)
				{
					if (json_data.hasOwnProperty('next'))
					{
						if (typeof json_data.next =='object')
						{
							success_action = json_data.next;
						}
					}
					
					if (json_data.hasOwnProperty('html'))
					{
						if (json_data.html.length > 0)
						{
							if (param.selector.trim().length>0)
							{
								//$(param.selector).html(json_data.html);
                                loadAdjaxData(param.selector,data,school_title, param.url);
							}
						}
					} else if (json_data.hasOwnProperty('html_elements'))
					{
						$.each(json_data.html_elements, function(index, element) 
						{
							if (element.hasOwnProperty('selector'))
							{
								var selector = element.selector;
								for(var key in element) 
								{
									if ((element.hasOwnProperty(key)) && (key!=selector))
									{
										if (key=='html')
										{
											$(selector).html(element.html);
										} else if (key=='value')
										{
											$(selector).val(element.value);
										} else
										{
											$(selector).prop(key,element[key]);
										}
									}
								} 								
							}
						});					
					}
					
                    if (jQuery.isFunction(success_action)) 
                    {
                        success_action(json_data,param);
                    }
					else 
                    {
                        
                        
                        if (json_data.hasOwnProperty('message'))
                        {
                            if ((typeof success_action =='object') && (success_action.silent))
                            {
                                //silent
                            }
                            else if ( (json_data.message!=null) && (json_data.message.length > 0))
                            {
                                if (typeof showSuccessMessage === "function") 
                                { 
                                    showSuccessMessage(json_data.message);
                                }
                                else
                                {
                                    alert(json_data.message);
                                }
                            }
                        }
                        
                        if ((typeof success_action =='object') && (success_action.url.trim().length>0))
                        {
                            
                            var url=success_action.url;
                            var iop=url.indexOf("(");
                            if (iop>0) //a function
                            {
                                var func_name = url.substr(0,iop);
                                var iope = url.indexOf(")");
                                var func_param = '';
                                if (iope>(iop+1)) func_param = url.substr(iop+1,iope-iop-1);
                                //alert('running '+func_name);
                                if (typeof window[func_name] === 'function') 
                                {
                                    var container = 'body';
                                    if (param.selector.trim().length>0) container=param.selector;
                                     
                                    $('body').find('.ajax_loader').each(function()
                                    {
                                        $(this).remove();
                                    });                                     
                                    window[func_name](func_param);
                                    return false;
                                }
                            }                                                      
                            perform_next(success_action,param.selector,json_data.message);
                        } 
                    }
				} else
				{
                    if (jQuery.isFunction(fail_action)) 
                    {
                        fail_action(json_data,param);
                    }
					else if (json_data.hasOwnProperty('message'))
					{
						if (success_action.silent)
                        {
                            //silent
                        }
						else if ( (json_data.message!=null) && (json_data.message.length > 0))
						{
							if (typeof showFailMessage === "function") 
                            { 
                                showFailMessage(json_data.message);
                            }
                            else
                            {
                                alert(json_data.message);
                            }
						}
                        

                        
					}
				}  
				var container = 'body';
				if (param.selector.trim().length>0) container=param.selector;
				 
				$('body').find('.ajax_loader').each(function()
				{
					$(this).remove();
				});
			}
			else
			{
				if (param.selector.trim().length>0)
				{
					//$(param.selector).html(data);
                    loadAdjaxData(param.selector,data,school_title, param.url,param.push_state);
                    //reloadJs(param.selector);
                    if (jQuery.isFunction(success_action)) 
                    {
                        success_action('',param);
                    } else if ((typeof success_action =='object') && (success_action.url.trim().length>0))
                    {
                        perform_next(success_action,'','');
                    }
				} 
				var container = 'body';
				if (param.selector.trim().length>0) container=param.selector;
				 
				$('body').find('.ajax_loader').each(function()
				{
					$(this).remove();
				});                
			}
			
		});        
    }
    
    function loadToSelector_form_data(url,selector,form_data,success_action,loader_img)
    {
        var param = { url: url, selector:selector };
        post_form_data(param,form_data,success_action,'',loader_img);
    }
   
    <?php
    $js->renderFunctions();
    ?>
   
    function reloadJs(selector)
    {
        //alert('reload '+selector);
        loadActions(selector);
        <?php
        $js->addFunctions();
        ?>
        initEditMode();
        if ($("#site_map").length>0)
        {
            resetSitemap();
        }
    }

    
    function successCallback(data)
    {
        alert('Success!');
    }


    
    function perform_next(success_action,selector,message,loader_img)
    {
        if (success_action.clear)
        {
            if (success_action.form.length>0)
            {
                $(success_action.form).find('input').each( function()
                {
                    if ($(this).attr('type')!='hidden')
                    {
                        $(this).attr('value','');
                        $(this).val('');
                    }
                });
                $(success_action.form).find('select').each( function()
                {
                    $(this).find('option').each( function()
                    {
                        $(this).removeAttr('selected');
                    });
                    $(this).val('');
                });
                $(success_action.form).find('textarea').each( function()
                {
                    $(this).html('');
                    $(this).val('');
                });
            }
            success_action.clear=0;
        }
        if (success_action.next_silent)
        {
            success_action.silent = 1;
        }
        if (success_action.url.length>0)
        {
            if (success_action.confirm.length>0)
            {
                var msg;
                if (message.length) msg = message+'. '+success_action.confirm;
                else msg=success_action.confirm;
                if (!confirm(msg))
                {
                    $(selector).find('.ajax_loader').each( function()
                    {
                        $(this).remove();
                    });
                    return false;
                }
                success_action.confirm='';
            }
            
            

            var url = success_action.url;
            
            success_action.url = '';
            /*alert('container:'+success_action.container+' form:'+success_action.form);*/
            loadToSelectorVolatile(url,success_action.container,success_action.form,success_action,loader_img);
            
        }        
    }

    function initEditMode()
    {
        if ($('#toggle_edit').hasClass('edit_mode'))
        {
            /* do not toggle initially*/
        }
        else
        {
            $('body').find('.edit_input').each( function()
            {
                $(this).parents('td').find('.select2-container').css('width','100%').hide();
                $(this).hide();
            });      
        }
    }
    function toggleEdit()
    {
        toggleThisEdit('body','td','#toggle_edit');
    }
    function toggleThisEdit(container,field_container,edit_button)
    {
        if ($(edit_button).hasClass('edit_mode'))
        {
            if ($(edit_button).hasClass('changed'))
            {
                if (!confirm('You haved unsaved changes. Lock anyway?'))
                {
                    return false;
                }
                
            }
        }
        $(container).find('.view_input').each( function()
        {
            $(this).toggle();
        });
        $(container).find('.edit_input').each( function()
        {
            if ($(this).hasClass('jwysiwyg'))
            {
                $(this).parents('div.wysiwyg').toggle();
            } else if ($(this).hasClass('chosen'))
            {
                $(this).parents(field_container).find('.select2-container').toggle().css('width','100%');
            }
            else
            {
                $(this).toggle();
            }
            if ($('#toggle_edit').hasClass('edit_mode'))
            {
                var val='';
                if ($(this).is('select'))
                {
                    val=$(this).find("option:selected").html();
                } else if ($(this).is('span')||$(this).is('div')||$(this).is('label'))
                {
                    $(this).find('input[type="checkbox"]').each( function()
                    {
                        if ($(this).is(':checked'))
                        {
                            if (val.length>0) val=val+', '+$(this).parents('label').text();
                            else val=$(this).parents('label').text();
                        }
                        $(this).click( function()
                        {
                            if  ($('#toggle_edit').hasClass('changed'))
                            {
                                ;
                            }
                            else
                            {
                                $('#toggle_edit').addClass('changed');
                            }
                        });
                    });
                    $(this).find('input[type="radio"]').each( function()
                    {
                        if ($(this).is(':checked'))
                        {
                            if (val.length>0) val=val+', '+$(this).parents('label').text();
                            else val=$(this).parents('label').text();
                        }
                        $(this).click( function()
                        {
                            if  ($('#toggle_edit').hasClass('changed'))
                            {
                                ;
                            }
                            else
                            {
                                $('#toggle_edit').addClass('changed');
                            }
                        });                        
                    });          
                } else
                {
                    val=$(this).val();             
                }
                
                    
                
                $(this).parents('td').find('.view_input').html(val);
            } 
            
            $(this).change( function()
            {
                if  ($('#toggle_edit').hasClass('changed'))
                {
                    ;
                }
                else
                {
                    $('#toggle_edit').addClass('changed');
                }
            });               
        });
        if ($(edit_button).hasClass('edit_mode'))
        {
            var edit_name='Enable Edit';
            if ( typeof ($(edit_button).attr('edit_name')) != 'undefined') edit_name=$(edit_button).attr('edit_name');
            $(edit_button).html('<li class="fa fa-edit"></li> '+edit_name);
            $(edit_button).removeClass('edit_mode');
        } else
        {
            var lock_name='Lock';
            if ( typeof ($(edit_button).attr('lock_name')) != 'undefined') lock_name=$(edit_button).attr('lock_name');
            $(edit_button).html('<li class="fa fa-lock"></li>'+lock_name);
            $(edit_button).addClass('edit_mode');
        }
    }    
    
    
    function loadJsonMessageToSelector(url,form,selector,callback) 
    {
        $.post(encodeURI(url),$(form).serialize(),function(data)
        {
			if (data.success)
			{
				//$(selector).html(data.message);
                push_state=0;
                if (selector=='<?php echo MAIN_CONTAINER?>') push_state=1;
                loadAdjaxData(selector,data.message,school_title, url,push_state);
			}
            var param='';
            if(data.hasOwnProperty('callback_param'))
            {
                param = data.callback_param;
            }
			callback(param);
        });
        return false;   
    }    
    
    function center(div)
    {
        var view_width = $(window).width();
        left = ($(window).width() - div.width())/2;
        div.css('left',left);
    }
    

    $(document).ready(function() 
    {
        if (typeof jQuery != "undefined") 
        {
        } else
        {
            //alert("jQuery library is not found!");
        }        
        reloadJs('body');
    });    
</script>
