<?php 
class Javascript
{
    public function renderAjaxLoader()
    {
        ;
    }
    
    public function addFunctions() //these are scripts that are loaded to the loadScript() function that is loaded when a page is loaded
    {
?>
        if (isMobile())
		{
            //normal select 
        } else //use chosen
        {
            $("div.exam_questions").find("select.chosen").each( function()
            {
                $(this).chosen({width: '100%'});
            });
            $("form.edit").find("select.chosen").each( function()
            {
                $(this).chosen({width: '100%'});
            });
            $("form.new").find("select.chosen").each( function()
            {
                $(this).chosen({width: '100%'});
            });
        }
        loadScripts();
        reloadSpeak();
        displayStaticClock();
        displayStaticWeighingScale();
        generatePlots('div.page');
        /*enableDrawCanvas();*/
        enableDraggables();
        //enableDrawSvg();
        if (!isMobile())
		{

            $("div.exam_questions").find('select').each( function()
            {
                var wid = parseInt($(this).width())+10;
                $(this).css('width',wid+'px');
                $(this).attr('width',wid+'px');
                var $this_sel = $(this);
                $(this).chosen({width:wid+'px'}).on('chosen:showing_dropdown', function() 
                {
                    $this_sel.trigger('chosen:updated');
                });
            });
            $("div.exam_questions").find('select').each( function()
            {
                $(this).click( function()
                {
                    
                });

                $(this).change( function()
                {
                    $(this).trigger('chosen:updated');
                });
            });                
        }
        setTimeout(function () {markEquations();},1000);
        enableToolTip();
        reloadMathjax(selector);
        

<?php
    }  

    public function renderFunctions()
    {?> //javascript code functions here
		
        
    function enableToolTip()
    {
		$('div.page').find('[tooltip]').each( function()
		{
			var tooltip = $(this).attr('tooltip');
			var $tooltip_htm;

			if (!($(this).find('.tooltip_icon').length>0))
			{
				$tooltip_icon_htm = $('<i class="tooltip_icon fa fa-info-circle" style="opacity:1;display:inline;padding:2px 5px;color:#aaa;top:-15px;left:0;position:absolute;z-index:500"> </i>');
				$(this).append($tooltip_icon_htm);
			}
			$(this).append($tooltip_htm);
			$(this).mouseover( function()
			{
				if ($(this).find('span.tooltip').length>0)
				{
					$tooltip_htm = $(this).find('span.tooltip');
					$tooltip_htm.html(tooltip);
				}
				else $tooltip_htm = $('<span class="tooltip" style="opacity:1;border:1px solid red;display:none;padding:2px 5px;top:-20px;left:0;background-color:gray;color:white;border-radius:5px;position:absolute;z-index:1000">'+tooltip+'</span>');

				

				$(this).append($tooltip_htm);
				var rect = $(this)[0].getBoundingClientRect();
				//$tooltip_htm.css('left',rect.left);
				
				var hei = rect.bottom - rect.top;
				if (rect.top > hei) too_y = rect.top - hei - 5;
				//$tooltip_htm.css('top',too_y);
				
				$tooltip_htm.show();
			});
			
			$(this).mouseout( function()
			{
				$('.tooltip').hide();
				
			});
		});
	}    
        
    function enableDraggables()
    {
		$('div.page').find('.draggable').children().each( function()
		{
			$(this).draggable();
		});
	}

    function markEquations()
    {
        $('div.page').find('script[type="math/asciimath"]').each(function()
        {
            var eqn = $(this).html();
            /*alert('equation='+eqn);*/
            var idx_eq = eqn.indexOf('=');
            if (idx_eq == -1)
            {
                /* do not mark no equal sign */
            }
            else if ((eqn.match(new RegExp("=", "g")) || []).length > 1)
            {
                /* do not mark */
            } else if (idx_eq==0) 
            {
                /* start of string do not mark */
            } else if (idx_eq==(eqn.length-1)) 
            {
                /* end of string do not mark */
            } else if (eqn.charAt(idx_eq-1)==' ')
            {
                /* space before = do not mark */
            } else if (eqn.charAt(idx_eq+1)==' ')
            {
                /* dispplay value no equation */
            } else if (eqn.charAt(idx_eq+1)=='?')
            {
                /* dispplay value no equation */
            } else
            {
                $(this).prev('.MathJax_SVG').addClass("math-equation");
            }
            
        });
        $('div.exam_questions').find('.chosen-container').each( function()
        {
            $(this).css('width','auto');
            $(this).css('height','auto');
        });        
    }    
    

    
    function preview(selector,view_selector,default_input)
    {
        preview_missing(selector,view_selector,default_input,'')
    }
    
    function preview_missing(selector,view_selector,default_input,sentence_selector)
    {
        var choices = $(selector).val().replace(/==/g, '=').replace(/=[/?]/g, '=').replace(/`[/?]/g, '`');;
        var data = '';
        var pos=0;
        while(choices.indexOf("style='",pos)>0)
        {
			pos=choices.indexOf("style='",pos);
			var end=choices.indexOf("'",pos+8);
			var end_o=choices.indexOf('>',pos);
			end_m="'";
			if ((end_o>0) && (end_o<end)) 
			{
				end=end_o;
				end_m='>';
			}
			var style=choices.substr(pos+7,end-(pos+7));
			var style_safe = style.replace(/;/g, "_#_");
			choices = choices.replace("style='"+style,"style='"+style_safe);
			pos=pos+1;
		}
		pos=0;
		while(choices.indexOf('style="',pos)>0)
        {
			pos=choices.indexOf('style="',pos);
			var end=choices.indexOf('"',pos+8);
			var end_o=choices.indexOf('>',pos+8);
			end_m='"';
			if ((end_o>0) && (end_o<end)) 
			{
				end=end_o;
				end_m='>';
			}
			var style=choices.substr(pos+7,end-(pos+7));
			var style_safe = style.replace(/;/g, "_#_");
			choices = choices.replace('style="'+style,'style="'+style_safe);
			pos=pos+1;
			
		}
        
        pos=0;
        while(choices.indexOf("points='",pos)>0)
        {
			pos=choices.indexOf("points='",pos);
			var end=choices.indexOf("'",pos+9);
			var end_o=choices.indexOf('>',pos);
			end_m="'";
			if ((end_o>0) && (end_o<end)) 
			{
				end=end_o;
				end_m='>';
			}
			var style=choices.substr(pos+8,end-(pos+8));
			var style_safe = style.replace(/,/g, "_##_");
			choices = choices.replace("points='"+style,"points='"+style_safe);
			pos=pos+1;
		}
		
		pos=0;
        while(choices.indexOf('points="',pos)>0)
        {
			pos=choices.indexOf('points="',pos);
			var end=choices.indexOf('"',pos+9);
			var end_o=choices.indexOf('>',pos);
			end_m='"';
			if ((end_o>0) && (end_o<end)) 
			{
				end=end_o;
				end_m='>';
			}
			var style=choices.substr(pos+8,end-(pos+8));
			var style_safe = style.replace(/,/g, "_##_");
			choices = choices.replace('points="'+style,'points="'+style_safe);
			pos=pos+1;
		}
        correct_ans = $('#edit_correct_answer_input').val();
        
        if ((choices.indexOf('["')==0) || (($(sentence_selector).length) && ( ($(sentence_selector).val().indexOf('_{')>=0) && ($(sentence_selector).val().indexOf('}_')>=0) || ($(sentence_selector).val().indexOf('__')>=0))))
        {
            var answers;
            if (choices.indexOf('["')==0)
            {
                answers = JSON.parse(choices);
            } else
            {
                answers = choices.split(' ');
            }
            for(var i=0;i<answers.length;i++)
			{
				answers[i]=answers[i].replace('~','');
			}
            var sentences;
            if ((sentence_selector.length>0) && ($(sentence_selector).length>0))
            {
                var sentence = $(sentence_selector).val().replace(/\"/g,'').replace(/\'/g,'');
                if ( (sentence.indexOf('__')>=0) || ((sentence.indexOf('_{')>=0) && (sentence.indexOf('}_')>sentence.indexOf('_{'))) )
                {
                   if ((sentence.indexOf('_{')>=0) && (sentence.indexOf('}_')>sentence.indexOf('_{')))
                   {
                       while (sentence.indexOf('_{')>0)
                       {
                           var start = sentence.indexOf('_{');
                           var end = sentence.indexOf('}_');
                           
                           var choice_str = sentence.substr(start+2,end-start-2);
                           
                           
                           var sel = '__';
                           sentence = sentence.replace("_{"+choice_str+"}_",sel);          
                       }
                   }
                   if (sentence.indexOf('__')>=0)
                   {
                       sentences = sentence.split('__');
                   } 
                } else if (sentence.indexOf('<underlinable>')>=0)
                {
                    //sortable answers
                    sentence.replace('<underlinable>','').replace('</underlinable>','');
                    var array_choices = sentence.split(" ");
                    var num_choices = array_choices.length;
                    data = '';
                    var answer = '';
                    var found = 0;
                    for (var i=0; i < num_choices; i++)
                    {
                        if ((found<answers.length) && (array_choices[i].trim()==answers[found]))
                        {
                            data = data + " <u style='color:green'>"+array_choices[i]+"</u>";
                            found = found + 1;
                        } else
                        {
                            if (i<array_choices.length)
                            {
                                data = data + " <span>"+array_choices[i]+"</span>";
                            } 
                        }
                    }
                    
                    sentences = '';
                } else sentences[0] = sentence; 
            }
            if (sentences.length>0)
            {
                data='';
                
                for (var i=0; i < sentences.length; i++)
                {
					var last_index = sentences[i].length-1;
                    if (i<answers.length)
                    {
						if ((i<(sentences.length-1)) && (sentences[i].indexOf('`')==last_index) && (sentences[i+1].indexOf('`')==0))
						{
							sentences[i]=sentences[i].substring(0,last_index);
							sentences[i+1]=sentences[i+1].substring(1,sentences[i+1].length);
							data = data + sentences[i]+"<u style='color:green'>`"+answers[i]+"`</u>";
						} else
						{
							data = data + sentences[i]+"<u style='color:green'>"+answers[i]+"</u>";
						}
                    } else
                    {
                        data = data + sentences[i];
                    }
                }
            }
        } else if (choices.indexOf('={')>=0)
        {
            var replaced = choices.replace("={","").replace("}","").replace(/\"/g,'').replace(/\'/g,'');
            var array_choices = replaced.split(",");
            var num_choices = array_choices.length;
            data='<div>';
            for (var i=0; i < num_choices; i++)
            {
                data = data + "<label class='choices_any' >"+array_choices[i]+"</label>";
            }
            data=data+'<b>(any order)</b></div>';
        } else if (choices.indexOf(';')>=0)
        {
            var array_choices = choices.split(";");
            var num_choices = array_choices.length;
            for (var i=0; i < num_choices; i++)
            {
                data = data + "<div><label  class='choices' ><input type='checkbox' name='choice' value='"+array_choices[i]+"'  />"+array_choices[i]+"</label></div>";
            }
        } else if (choices.indexOf(',')>=0)
        {
            var replaced = choices.replace(/\\,/g,'~~~');
            var array_choices = replaced.split(",");
            var num_choices = array_choices.length;
            for (var i=0; i < num_choices; i++)
            {
                var achoices = array_choices[i].replace(/~~~/g,',');
                data = data + "<div><label  class='choices' ><input type='radio' name='choice' value='"+achoices+"'  />"+achoices+"</label></div>";
            }
        } else if (choices.trim().length==0)
        {
            if (default_input)
            {
                data = data + "<div><label  class='choices' ><input type='text' name='choice' value=''  /></label></div>";
            }
            else
            {
                data = data + choices;
            }
        } else
        {
            data = data + choices;
        }        

        if ((choices.indexOf('<RANDOM>')>=0) && (correct_ans.indexOf('|')>0))
        {
            
            var array_choices = _.shuffle(correct_ans.split("|"));
            
            var num_choices = array_choices.length;
            var sortable_data = '';
            var answer = '';
            for (var i=0; i < num_choices; i++)
            {
                sortable_data = sortable_data + "<span>"+array_choices[i]+"</span>";
                if (answer.length>0)
                {
                    answer += '|'+array_choices[i];
                } else
                {
                    answer = array_choices[i];
                }
            }
            data = '<div class="sortable">'+sortable_data+'</div>';
        }
        if (choices.indexOf('|')>0)
        {
            //sortable answers
            var array_choices = choices.split("|");
            var num_choices = array_choices.length;
            var sortable_data = '';
            var answer = '';
            for (var i=0; i < num_choices; i++)
            {
                sortable_data = sortable_data + "<span>"+array_choices[i]+"</span>";
                if (answer.length>0)
                {
                    answer += '|'+array_choices[i];
                } else
                {
                    answer = array_choices[i];
                }
            }
            data = '<div class="sortable">'+sortable_data+'</div>';
        } 
        
        if (choices.indexOf('<underlinable>')>=0)
        {
            //underlinable answers
            //choices.replace('<underlinable>','').replace('</underlinable>','');
            
            var start_i = choices.indexOf('<underlinable>')+14;
            var end_i = choices.indexOf('</underlinable>');
            var underlinable = choices.substring(start_i,end_i).trim();
            
            var array_choices = underlinable.split(" ");
            var num_choices = array_choices.length;
            var sortable_data = '';
            var answer = '';
            for (var i=0; i < num_choices; i++)
            {
                sortable_data = sortable_data + "<span>"+array_choices[i]+"</span>";
                if (answer.length>0)
                {
                    answer += ' '+array_choices[i];
                } else
                {
                    answer = array_choices[i];
                }
            }
            data = '<div class="underlinable">'+sortable_data+'</div>';
            to_replace = choices.substring(start_i-14,end_i+15);
            choices = choices.replace(to_replace,data);
        }  
        if (choices.indexOf('__')>=0)
        {
            while (choices.indexOf('__')>=0)
            {
                choices = choices.replace('__',"<input class='no_submit_enter expandable_text_input' type='text' size='2' style=''  onkeypress='this.style.width = ((this.value.length + 1)) + \"ch\"' />");
            }
            data = choices;
        } 
        if (choices.indexOf('??')>=0)
        {
            while (choices.indexOf("??")>=0)
            {
                choices = choices.replace("??","<input class='no_submit_enter expandable_text_input' type='text' size='2' style=''  onkeypress='this.style.width = ((this.value.length + 1)) + \"ch\"' />");
            }
            data = choices;
        } 
        if ((choices.indexOf('_{')>=0) && (choices.indexOf('}_')>choices.indexOf('_{')))
        {
            while ((choices.indexOf('_{')>=0) && (choices.indexOf('}_')>choices.indexOf('_{')))
            {
                var start = choices.indexOf('_{');
                var end = choices.indexOf('}_');
         
                if (end > start)
                {
                    var choice_str = choices.substr(start+2,end-start-2);
                    var options = choice_str.split(';');
                    
                    var sel = '<select class="dropdown_select chosen" name="choice_" ><option value="">Select ..</option>';
                    for(var i=0;i<options.length;i++)
                    {
                        sel=sel+'<option value="'+options[i]+'">'+options[i]+'</option>';
                    }
                    sel=sel+'</select>';
                    choices = choices.replace("_{"+choice_str+"}_",sel);          
                } else break;
            }
            
            data = choices;
        }
        
            
		data = data.replace(/_#_/g, ";");
		data = data.replace(/_##_/g, ",");
        $(view_selector).html($.parseHTML(data));
        
        loadScripts();
        enableDroppables();
        MathJax.Hub.Queue(["Typeset",MathJax.Hub,view_selector]);
        
        var hs=$(selector).closest('div').innerHeight();
        var hv=$(view_selector).closest('div').innerHeight();
        if (hs>hv)
        {
            $(view_selector).closest('div').innerHeight(hs);
        }
        else
        {
            $(selector).closest('div').innerHeight(hv);
        }
    }
		

    function displayStaticClock()
    {
        $('div.page').find('.clock').each( function()
        {
            var hour = parseFloat($(this).attr('hour'));
            var minute = parseFloat($(this).attr('minute'));
            var second = 0;
            if ($(this)[0].hasAttribute('second'))
            {
                second = parseFloat($(this).attr('second'));
                var clock = $('<div id="clock"><div class="hour_hand" style="transform: rotate(334.5deg);"><img src="images/hourhand.png"></div><div class="minute_hand" style="transform: rotate(54deg);"><img src="images/minhand.png"></div><div class="second_hand" style="transform: rotate(108deg);"><img src="images/sechand.png"></div></div>');
            } else
            {
                var clock = $('<div id="clock"><div class="hour_hand" style="transform: rotate(334.5deg);"><img src="images/hourhand.png"></div><div class="minute_hand" style="transform: rotate(54deg);"><img src="images/minhand.png"></div></div>');
            }
            $(this).append(clock);
            set_clock($(this),hour,minute,second);
        });
    }
    function set_clock(this_clock,h,minute,second) 
    {
            var angle = 360/60;
            var hour = h;
            if (h > 12) 
            {
                hour = h - 12;
            }
            
            hourAngle = (360/12) * hour + (360/(12*60)) * minute;

            this_clock.find('.minute_hand').css('transform','rotate('+angle * minute+'deg)');
            this_clock.find('.second_hand').css('transform','rotate('+angle * second+'deg)');
            this_clock.find('.hour_hand').css('transform','rotate('+hourAngle+'deg)');
    }

	function displayStaticWeighingScale()
    {
        $('div.page').find('.weighing_scale').each( function()
        {
            var weight = parseFloat($(this).attr('weight'));
            var load = $(this).attr('load');
            var max=1000;
            if ($(this)[0].hasAttribute('unit') && ($(this).attr('unit')=='kg'))
            {
                var scale = $('<div class="view_scale_kg"><div class="load"><img src="'+load+'"></div><div class="main_scale"><div class="scale_hand" style="transform: rotate(108deg);"><img src="images/scale_hand.png"></div></div></div>');
                max=10;
            } else
            {
                var scale = $('<div class="view_scale_g"><div class="load"><img src="'+load+'"></div><div class="main_scale"><div class="scale_hand" style="transform: rotate(108deg);"><img src="images/scale_hand.png"></div></div></div>');
            }
            $(this).html(scale);
            set_weighing_scale($(this),weight,max);
        });
    }
    function set_weighing_scale(me,weight,max) 
    {
            var angle = 360/max;
            me.find('.scale_hand').css('transform','rotate('+angle * weight+'deg)');
    }

  
    function showStep($container) 
    {
        var n = parseInt($container.find(".step").attr('n'));
        $container.find(".step"+n).show(500); 
        n++;
        $container.find(".step").attr('n',n);
        
        if ($container.find(".step"+n).length<1) 
        {
            $container.find(".step").hide(300);
            $container.find(".view_all").hide(300);
            $container.find(".reset").show(300);
            return false;
        } else
        {
            return true;
        }
    }
  

    function showAll($container)
    {
        if (showStep($container))
        {
            setTimeout(showAll($container),500);
        }
    }

    //
    //  Enable the step button and disable the reset button.
    //  Hide the steps.
    //
    function resetSteps($container) 
    {
        var i=1; 
        while ($container.find(".step"+i).length) 
        {
            $container.find(".step"+i).hide(100); 
            i++;
        }
        $container.find(".step").show(300);
        $container.find(".view_all").show(300);
        $container.find(".reset").hide(300);       
        $container.find(".step").attr('n',1); 
    }

    function showSuccessMessage(msg)
    {
        var message = $('<div class="success_message hanger" style="background-color:lightblue;position:fixed;top:0px;width:100%">'+msg+'<i class="fa fa-close" style="float:right"></i></div>');
        $('body').append(message);
        setTimeout(function() { message.remove(); },5000);
        message.click( function()
        {
            $(this).remove();
        });
    }

    function showFailMessage(msg)
    {
        var message = $('<div class="fail_message hanger" style="background-color:#FFAAAA;position:fixed;top:0px;width:100%">'+msg+'<i class="fa fa-close" style="float:right"></i></div>');
        $('body').append(message);
        setTimeout(function() { message.remove(); },5000);
        message.click( function()
        {
            $(this).remove();
        });
    }

    function reloadMathjax(selector)
    {
        if ( (mathjax_enabled !== undefined) && mathjax_enabled)
        {
            MathJax.Hub.setRenderer("SVG");
            if ($(selector).length>0)
            {
                MathJax.Hub.Queue(["Typeset",MathJax.Hub,selector]); 
            } else
            {
                MathJax.Hub.Queue(["Typeset",MathJax.Hub]); 
            }
        }    
    }        
 /*       
    function initEditMode()
    {
        if ($('#toggle_edit').hasClass('edit_mode'))
        {
            
        }
        else
        {
            $('body').find('.edit_input').each( function()
            {
                $(this).parents('td').find('div.chosen-container').css('width','100%').hide();
                $(this).hide();
            });      
        }        
    }*/
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
                $(this).parents(field_container).find('div.chosen-container').toggle().css('width','100%');
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
    
    function enableDroppables()
    {
        $('body').find('textarea.droppable').each( function()
        {
            var data = $(this).val();
            //$(this).focus().val('').val(data);
            $(this).droppable(
            {
                drop: function( event, ui ) 
                {
                    //$(this).insertAtCaret(ui.draggable.find('.item').html());
                    $(this).insertAtCaret(ui.draggable.find('.item').attr('drop'));
                }
            });
        });     
        $('div.items_draggable').find('.draggable_item').each( function()
        {
            var width = parseInt($(this).find('.item').width())+4;
            var width1 = parseInt($(this).find('label').width())+2;
            if (width < width1)
            {
                width = width1;
            }        
            $(this).css('max-width',width+'px');
            $(this).draggable(
            {
                cancel: "a.ui-icon", // clicking an icon won't initiate dragging
                revert: "invalid", // when not dropped, the item will revert back to its initial position
                containment: "document",
                helper: "clone",
                cursor: "move"
            });
        });   
    }      
    
    function getAsciiMathInput($container)
    {
        var htm='';       
        jax = MathJax.Hub.getAllJax();
        for (var i = 0, m = jax.length; i < m; i++) 
        {
            var script = jax[i].SourceElement();
            $parent = $(script).parent().clone();
            $parent.html('');
            $parent.html('`'+$(script).html()+'`');
            htm = htm + $('<div></div>').append($parent).html();
            //htm = htm + $(this).html();
        }
        return htm;
    }     

    function loadScripts()
    {
		$('peso-sign').html("&#8369;");
		$('cent-sign').html("&cent;");
		
		$('.dropdown_select option[value="<peso-sign></peso-sign>"]').html("&#8369;");
		$('.dropdown_select option[value="<cent-sign></cent-sign>"]').html("&cent;");
        $('div.page').find('div.assistive').each( function()
        {
            $(this).find('button.add_checkbox').each( function()
            {
                $(this).unbind('click');
                $(this).click( function()
                {
                    button = $('<input type="checkbox" style="margin-right:10px" />');
                    $(this).closest('div.assistive').find('div.checkbox').append(button);
                });   
                
            }); 
            $(this).find('button.del_checkbox').each( function()
            {
                $(this).unbind('click');
                $(this).click( function()
                {
                    $(this).closest('div.assistive').find('div.checkbox input:last-child').remove();
                });   
                
            });       
            $(this).find('button.add_plus').each( function()
            {
                $(this).unbind('click');
                $(this).click( function()
                {
                    $(this).closest('div.assistive').find('div.checkbox').append($('<input size="1" style="width:30px" value="+" />'));
                });   
                
            });
        });
        $('div.page').find('.sortable').each( function()
        {
            $div = $(this);
            $div.height($(this).height());
            $(this).sortable(
            {
                stop: function(event, ui) 
                {
                    var data = "";
                    var name = $(this).attr('name');
                    $(this).find('span').each( function(i, el)
                    {
                        var p = $(el).html();/*.replace(" ", "");*/
                        if (data.length==0)
                        {
                            data = p;
                        } else
                        {
                            data += "|" + p;
                        }
                        /*$div.draggable({ cancel: $(this) });*/
                    });
                    $(this).next('input').val(data);
                    
                }
            });
            $(this).disableSelection();
            
        });
        $('div.page').find('.underlinable').each( function()
        {
            $div = $(this);
            $div.height($(this).height());
            var data='';
            $(this).find('span'). each( function()
            {
                $this_span = $(this);
                $this_span.unbind('click');
                $this_span.click( function() 
                {
                    $click_span = $(this);
                    var underlined = $(this).css('text-decoration');
                    if (underlined.indexOf('underline')>=0)
                    {
                        
                        $(this).css('text-decoration','');
                        $(this).css('border','0px solid transparent');
                    } else
                    {
                        
                        $(this).css('text-decoration','underline');
                        $(this).css('border','1px solid gray');
                    }
                    var data ='';
                    $(this).closest('.underlinable').find('span'). each( function()
                    {
                        var decor = $(this).css('text-decoration');
                        if (decor.indexOf('underline')>=0)
                        {
                            var p = $(this).html().replace(" ", "");
                            if (data.length==0)
                            {
                                data = p;
                            } else
                            {
                                data += " " + p;
                            }
                            
                        }
                    });
                    
                    $(this).closest('.underlinable').next('input').val(data);
                });
                
            });
            $(this).disableSelection();
            
        });
        
        function show_objects(count,obj,container)
        {
            //alert(obj);
            container.html('');
            for(var i=0;i<count;i++)
            {
                var li_obj = $('<li>'+obj+'</li>');
                li_obj.css('width','64px').find('img').css('height','48px');
                container.append(li_obj);
            }
            enable_des_draggable(container);
        }
        
        function fill_divisor_box_at_load(container)
        {
            var div_share = container.find('input.division-share').val().replace('<u>','').replace('</u>','');
            
            var obj = container.attr('object');
            var div_div = container.find('.divisor-div');
            if ((!(typeof div_share === 'undefined')) &&  (div_share.length>0))
            {    
                container.find('.dividend-items').html('');   
                div_div.html('');     
                var div_shares=div_share.split('+');
                for(var i=0;i<div_shares.length;i++)
                {
                    var div_box = $('<div class="divisor-group group_'+i+'" ></div>');                
                    var $list = $( "ul", div_box ).length ? $( "ul", div_box ) : $( "<ul class='gallery ui-helper-reset'/>" ).appendTo( div_box );
                    var count = parseInt(div_shares[i]);
                    for(var j=0;j<count;j++)
                    {
                        $list.append($('<li >'+obj+'</li>'));
                    }
                    $list.find('li img').width('48px').height('36px');
                    div_box.css('display','inline-block');
                    div_box.css('border','2px solid blue');
                    div_box.css('margin-right','5px');
                    div_div.append(div_box);
                }
            }
            
        }
        
        function show_divisor_box(count,container)
        {
            //alert(obj);
            container.html('');
            for(var i=0;i<count;i++)
            {
                var divisor_box = $('<div class="divisor-group group_'+i+'" ></div>');
                var width = 100/(parseInt(count)+1);
                divisor_box.css('width',width+'%');
                divisor_box.css('display','inline-block');
                divisor_box.css('border','2px solid blue');
                divisor_box.css('margin-right','5px');
                container.append(divisor_box);
            }
            enable_des_droppable(container);
        }
        var des_dividend_timeout;
        var des_divisor_timeout;
        // Image deletion function
        
        function deleteImage($item,$from) 
        {
          $item.fadeOut(function() 
          {
            var $list = $( "ul", $from ).length ?
              $( "ul", $from ) :
              $( "<ul class='gallery ui-helper-reset'/>" ).appendTo( $from );
     
            $item.appendTo( $list ).fadeIn(function() 
            {
              $item
                .animate({ width: "48px" })
                .find( "img" )
                  .animate({ height: "36px" });
                  var container = $(this).parents('.divisor-div');
                  update_division_share(container);
            });
          });
        }
     
        // Image recycle function
        var trash_icon = "<i class='fa fa-trash ui-icon ui-icon-trash'>Delete image</i>";
        function recycleImage( $item, $to ) {
          $item.fadeOut(function() {
          $(this).css( "width", "64px")
          .find( "img" )
            .css( "height", "48px" )
          .end()
          .appendTo( $to )
          .fadeIn();
          var container = $(this).parents('.division-equal-sharing').find('.divisor-div');
          update_division_share(container);
          });
        }
              
        function enable_des_draggable(container)
        {
            container.find('li').each( function()
            {
                $(this).draggable(
                {
                  cancel: "a.ui-icon", // clicking an icon won't initiate dragging
                  revert: "invalid", // when not dropped, the item will revert back to its initial position
                  containment: container.parents('.division-equal-sharing'),
                  helper: "clone",
                  cursor: "move"
                });
            });
        }
        
        function update_division_share(container)
        {
            var div_share = '';
            container.find('.divisor-group').each( function()
            {
                if (div_share.length>0) div_share = div_share + '+' ; 
                var count = $(this).find('li').length;
                div_share = div_share+count;
            });
            //alert('div_share='+div_share);
            container.parents('.division-equal-sharing').find('.division-share').val(div_share);
            //var val = container.parents('.division-equal-sharing').find('.division-share').val();
            //alert(div_share+'='+val);
        }
        
        function enable_des_droppable(container)
        {
            container.find('.divisor-group').droppable(
            {
                //accept: container.find('li'),
                classes: {
                            "ui-droppable-active": "ui-state-highlight"
                         },
                drop: function( event, ui ) {
                    deleteImage( ui.draggable, $(this) );
                    //update_division_share(container);
                },
            });            
        }
        
        $('div.page').find('.division-equal-sharing').each( function()
        {
            var obj = $(this).attr('object');
            var dividend = $(this).find('.dividend').val();
            var container = $(this).find('.dividend-items');
            show_objects(dividend,obj,container); 
            $(this).find('.dividend').keyup( function()
            {
                //var obj = $(this).parents('.division-equal-sharing').attr('object');
                var value = $(this).val();
                clearTimeout(des_dividend_timeout);
                //var container = $(this).parents('.division-equal-sharing').find('.dividend-items');
                des_dividend_timeout = setTimeout(function () { show_objects(value,obj,container); },300);
            });
            
            var divisor = $(this).find('.divisor').val();
            var divisor_container = $(this).find('.divisor-div');
            show_divisor_box(divisor,divisor_container);
            $(this).find('.divisor').keyup( function()
            {
                var dividend = $(this).parents('.division-equal-sharing').find('.dividend').val();
                var value = $(this).val();
                clearTimeout(des_divisor_timeout);
                //var container = $(this).parents('.division-equal-sharing').find('.dividend-items');
                des_divisor_timeout = setTimeout(function () { show_divisor_box(value,divisor_container); },300);
                des_dividend_timeout = setTimeout(function () { show_objects(dividend,obj,container); },300);
            });
            
            container.droppable({
              //accept: $(this).find('.divisor-group > li'),
              classes: {
                "ui-droppable-active": "custom-state-active"
              },
              drop: function( event, ui ) {
                recycleImage( ui.draggable, $(this) );
              }
            });
            
            fill_divisor_box_at_load($(this));
        });
        $('table.math tr.input').find('td').each( function()
        {
            $(this).keyup( function(event)
            {
                var max_idx = $(this).parent('table.math tr.input').children('td').length - 1;
                var idx=$(this).index();
                if (event.keyCode==37)
                {
                    if (idx>0) idx = idx-1;
                    $(this).parent('table.math tr.input').children('td').eq(idx).find('input').focus();
                }
                else if (event.keyCode==39)
                {
                    if (idx<max_idx) idx = idx+1;
                    $(this).parent('table.math tr.input').children('td').eq(idx).find('input').focus();
                }
                else
                {
                    debouncedMathInputRender(idx,$(this).find('input').val(),$(this));
                }
            });
        });
        
        $('.math.fraction_blocks').find('input.no_submit_enter').each( function()
        {
            $(this).keyup( function(event)
            {
                debounceShowFractionBlocks($(this),event);
            });
            showFractionBlocks($(this));
        });
        
        $('body').find('.steps').each( function()
        {
            resetSteps($(this));
            $(this).find(".step-entry").hide();
            
            $(this).find(".step").show(300);
            $(this).find(".step").attr('n',1);
            $(this).find(".step").unbind('click');

            $(this).find('.step').click( function()
            {
                showStep($(this).closest('.steps'));
            });
            $(this).find('.reset').unbind('click');
            $(this).find('.reset').click( function()
            {
                resetSteps($(this).closest('.steps'));
            });      
            $(this).find('.view_all').click( function()
            {
                showAll($(this).closest('.steps'));
            });  
            $(this).find(".reset").hide(300); 
        });        
        
        $('#take_task_form').find('.read_comprehend_sequence').each( function()
        {
            var start_button = $('<button type="button" class="start_read_comprehend_sequence">Start</button>');
            if ($(this).parent('div').find('.start_read_comprehend_sequence').length==0)
            {
                $(this).before(start_button);
            }
            var this_sequence = $(this);
            $('button.start_read_comprehend_sequence').click( function()
            {
                $(this).hide();
                $(this).parent('div').find('.read_comprehend_sequence').show();
                var loader = $('<div class="ajax_loader" style="z-inder:1000;position:fixed;width:34px;height:34px;top:50px;left:50%;margin-left:-17px"><svg width="32" height="32" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><ellipse cx="16" cy="16" rx="14" ry="14" stroke="lightblue" stroke-width="2" fill="none"  /><line x1="16" y1="16" x2="16" y2="4" stroke="lightblue" stroke-width="2" /><animateTransform attributeType="xml" attributeName="transform" type="rotate"  from="0 0 0"   to="360 0 0"  dur="1s"  repeatCount="indefinite"/> </svg></div>');
                this_sequence.append(loader);
                setTimeout(function(){ this_sequence.hide(); },40000);
            });
            $(this).hide();
        });
        
        initEditMode();
    }


    function reloadSpeak() 
    {
        //console.log("reloadspeak");
        $("body").find(".speak").each( function(idx)
        {
            $(this).unbind('click');
            
            var id="speak_"+idx;
            if ($(this)[0].hasAttribute('id') && ($(this).attr('id').length>0))
            {
                id = $(this).attr('id');
            } else 
            {
                $(this).attr('id',id);
            }
            
            if ($(this)[0].hasAttribute('speak_this') && ($(this).attr('speak_this').length>0))
            {
                var speak_this = $(this).attr('speak_this');
                var speak_this_id = id+'_this';
                if ($('#'+speak_this_id).length>0) $('#'+speak_this_id).remove(); 
                $(this).after($('<span id="'+speak_this_id+'" style="display:none">'+speak_this+'</span>'));
                $(this).attr('speak_id',speak_this_id); 
            } else
            {
                $(this).attr('speak_id',id); 
            }
            

            $(this).click( function()
            {
                var selector = $(this).attr('speak_id');
                $('#'+selector).articulate('speak');
                
            });
        });
        
        $("body").find(".speak_phonic").each( function()
        {
            $(this).unbind('click');
            $(this).click( function()
            {
                var chars = '';
                var vowels = ["a", "e", "i", "o", "u"];
                var semi_consonant = ["f", "n", "m"];

                if ($(this).is("input") || $(this).is("button"))
                {
                    chars = $(this).val().trim();
                    
                } else
                {
                 if ($(this).attr("speak_this"))
                 {
                    chars = $(this).attr("speak_this").trim();
                 } else
                 {
                    chars = $('<p></p>').html($(this).html()).text().trim();
                 }
                }
                var phonics = '';
                for(var i=0;i<chars.length;i++)
                {
                     if (vowels.includes(chars.charAt(i).toLowerCase()))
                     {
                        if (chars.charAt(i).toLowerCase()=='u') phonics=phonics+'ooh ';
                        else if (chars.charAt(i).toLowerCase()=='i') phonics=phonics+'eeh ';
                        else phonics=phonics+chars.charAt(i)+'h ';
                     } else if (semi_consonant.includes(chars.charAt(i).toLowerCase()))
                     {
                         phonics=phonics+chars.charAt(i)+'uh ';
                     }
                     else if (chars.charAt(i).toLowerCase()=='q') phonics=phonics+'quah ';
                     else phonics=phonics+chars.charAt(i)+'ah ';
                }
                speak(phonics,{ amplitude: 100, wordgap: 150, pitch: 50, speed: 290 });
                 
            });
        });
    };


    var debounceMath;
    
    function debouncedMathInputRender(index,value,me)
    {
        clearTimeout(debounceMath);  
        debounceMath = setTimeout(function()
        { 
            me.parents('table.math').find('tr.output td').eq(index).html('`'+value+'`');
            MathJax.Hub.Queue(["Typeset",MathJax.Hub,me.parents('table.math').attr('id')]);
        },1000);
        
    }
    
    function debounceShowFractionBlocks(me,ev)
    {
        clearTimeout(debounceMath);
        debounceMath = setTimeout(function()
        {
            var idx=me.parents('.math.fraction_blocks').find('input.no_submit_enter').index(me);
            var max_idx = me.parents('.math.fraction_blocks').find('input.no_submit_enter').length - 1;
            
            if (ev.keyCode==37)
            {
                if (idx>0) idx = idx-1;
                me.parents('.math.fraction_blocks').find('input.no_submit_enter').eq(idx).focus();
            }
            else if (ev.keyCode==39)
            {
                if (idx<max_idx) idx = idx+1;
                me.parents('.math.fraction_blocks').find('input.no_submit_enter').eq(idx).focus();
            }
            else
            {
                showFractionBlocks(me);
            }
             
        },1000);
    }
    function showFractionBlocks(me)
    {
        
        var div = parseInt(me.parents('.math.fraction_blocks').find('input.no_submit_enter').eq(0).val());
        var divisor = parseInt(me.parents('.math.fraction_blocks').find('input.no_submit_enter').eq(1).val());
        var num_blocks = parseInt(me.parents('.math.fraction_blocks').find('input.no_submit_enter').eq(2).val());
        
        
        var block = 0;
        if (divisor > 0)
        {
            block = div/divisor;
        }
        
        if ((div>0) && (divisor>0))
        {
            me.parents('.math.fraction_blocks').find('.each_block_fraction').html('`'+div+'/'+divisor+'=`');
        }
        
        if (block>0)
        {
            me.parents('.math.fraction_blocks').find('.each_block').html(block);
        }
        
        var html_blocks='';
        for(var i=0;i<num_blocks;i++)
        {
            html_blocks=html_blocks+'<span class="bordered">'+block+'</span>';
        }
        
        me.parents('.math.fraction_blocks').find('div.input').html(html_blocks);    
        MathJax.Hub.Queue(["Typeset",MathJax.Hub,me.parents('.math.fraction_blocks').attr('id')]);    
        
        var answer_blocks = parseInt(me.parents('.math.fraction_blocks').find('input.no_submit_enter').eq(3).val());
        
        var answer = 0;
        if (answer_blocks>0) 
        {
            answer = answer_blocks * block;
            me.parents('.math.fraction_blocks').find('.answer').html(answer);
        }
    }
    
    function getTerms(str)
    {
		var exp = '';
        if (str != null) exp = str.trim();
		var vecs = [];
		var vc = 0;
		for(var i=0;i<exp.length;i++)
		{
			if (i>0)
			{
				if ((exp[i]=='+')||(exp[i]=='-'))
				{
					vc=vc+1;
					vecs[vc]='';
				}
			} else vecs[vc]='';
			vecs[vc]=vecs[vc]+exp[i];
		}
		return vecs;
		
	}
	
	function getMagnitude(str, unit)
	{
		var mag=0;
		if (str.indexOf(unit)>=0)
		{
			var val_s = str.replace(unit.trim(),'').trim();
			mag = 1;
			if (val_s.length>0) 
			{
				if ((val_s.length==1) && (val_s[0]=='+'))
				{
					mag=1;
				} else if ((val_s.length==1) && (val_s[0]=='-'))
				{
					mag=-1;
				} else
				{
					mag=parseFloat(val_s);
				}
			}	
		} else mag=0;
		return mag;	
	}
	
	function processExpressions2D(obj,title,xvars,yvars,xmin,xmax,xstep,xdtick,ydtick,xtitle,ytitle,line_colors,line_widths)
	{
		var exprs = [];
		var expressions = [];	
        var data_points = [];			
		if (obj[0].hasAttribute('expressions'))
		{
			expressions = obj.attr('expressions').split(",");
			
			for(var i=0;i<expressions.length;i++)
			{
				var expr = math.compile(expressions[i]);
				exprs[i] = expr;
			}
			
		} else if (obj[0].hasAttribute('expression'))
		{
			
			var expression = obj.attr('expression');
			var expr = math.compile(expression);
			exprs[0] = expr;
			expressions[0] = expression;
		} else if (obj[0].hasAttribute('points'))
        {
            var sets = obj.attr('points').split('|');
            for (var i=0;i<sets.length;i++)
            {
                var set = sets[i].split(':');
                
                var points = set[1].split(';');
                
                var xvalues = [];
                var yvalues = [];
                for (var j=0;j<points.length;j++)
                {
                    point = points[j];
                    xy = point.split(',');
                    xvalues[j]=xy[0];
                    yvalues[j]=xy[1];
                }
                data_points[i]={name:set[0],x:xvalues,y:yvalues};
                if (obj[0].hasAttribute('xvar'))
                {
                   xvars[i] =  obj.attr('xvar');
                } else xvars[i]='x';

                if (obj[0].hasAttribute('yvar'))
                {
                   yvars[i] =  obj.attr('yvar');
                } else yvars[i]='y';
                
                if (obj[0].hasAttribute('line_color'))
                {
                   line_colors[i] =  obj.attr('line_color');
                } else line_colors[i]=''; 
                
                if (obj[0].hasAttribute('width'))
                {
                   line_widths[i] =  obj.attr('line_width');
                } else line_widths[i]=2;
            }
        }


		for(var i=0;i<expressions.length;i++)
		{
			xvars[i] = 'x';
			if (obj[0].hasAttribute('xvar'))
			{
			   xvars[i] =  obj.attr('xvar');
			}

			yvars[i] = 'y';
			if (obj[0].hasAttribute('yvar'))
			{
			   yvars[i] =  obj.attr('yvar');
			}
			line_colors[i] = '';
			line_widths[i] = 2;
		}

		var i = expressions.length;

		obj.find('.expression').each( function ()
		{
			expressions[i]=obj.val();
			var expr = math.compile(expressions[i]);
			exprs[i] = expr;
			if (obj[0].hasAttribute('xvar'))
			{
			   xvars[i] =  obj.attr('xvar');
			} else xvars[i]='x';

			if (obj[0].hasAttribute('yvar'))
			{
			   yvars[i] =  obj.attr('yvar');
			} else yvars[i]='y';
			
			if (obj[0].hasAttribute('line_color'))
			{
			   line_colors[i] =  obj.attr('line_color');
			} else line_colors[i]=''; 
			
			if (obj[0].hasAttribute('width'))
			{
			   line_widths[i] =  obj.attr('line_width');
			} else line_widths[i]=2;                    
			i=i+1;
		});
        
        var equation_label_on = 1;
        if (obj[0].hasAttribute('equation_label_off'))
		{
            equation_label_on = 0;
        }
        
        var names = [];
        if (obj[0].hasAttribute('names'))
		{
            names = obj.attr('names').split(',');
        }
                		
		var data = [];
		for(var i=0;i<exprs.length;i++)
		{
		  var expr = exprs[i];
          var end = xmax+xstep;
		  var xValues = math.range(xmin, end, xstep).toArray()
		  var yValues = xValues.map(function (x) 
		  {
			var scope = JSON.parse('{"'+xvars[i]+'":'+x+'}');
			return expr.evaluate(scope)
		  })
		  

          var item_num=i+1;  
          var eqn_name='eqn '+item_num;
          if(typeof names[i] === 'undefined') 
          {
          }
          else 
          {
              eqn_name = names[i].trim(); 
          }
		  // render the plot using plotly
		  if (line_colors[i].length>0)
		  {
              
			  var trace_points = {
				x: xValues,
				y: yValues,
				type: 'scatter',
				name: eqn_name,
				mode: 'lines',
				line: {

					color: line_colors[i],

					width: line_widths[i]

				  }
			  }
              
		  } else
		  {
			  var trace_points = {
				x: xValues,
				y: yValues,
				type: 'scatter',
				name: eqn_name,
				mode: 'lines',
				line: {

					width: line_widths[i]

				  }
			  }
		  }
		  data[i] = trace_points;
          if (equation_label_on)
          {
            obj.before($('<div>eqn '+item_num+':`'+yvars[i]+'='+expressions[i]+'`</div>'));
          }
		}		
        
        for(var i=0;i<data_points.length;i++)
		{
		  
		  
		  var xautotick = true;
		  if (xdtick>0) 
		  {
			  xautotick = false;
		  }
		  
		  var yautotick = true;
		  if (ydtick>0) 
		  {
			  yautotick = false;
		  }



		  // render the plot using plotly
		  if (line_colors[i].length>0)
		  {
			  var trace_points = {
				x: data_points[i].x,
				y: data_points[i].y,
				type: 'scatter',
				name: data_points[i].name,
				mode: 'lines',
				line: {

					color: line_colors[i],

					width: line_widths[i]

				  }
			  }
		  } else
		  {
			  var trace_points = {
				x: data_points[i].x,
				y: data_points[i].y,
				type: 'scatter',
				name: data_points[i].name,				
                mode: 'lines',
				line: {

					width: line_widths[i]

				  }
			  }
		  }
		  data[i] = trace_points;
		}        
		return data;
	}
	
	function get2DMarkers(obj,data)
	{
		var i = data.length;
	  if (obj[0].hasAttribute('markers'))
	  {
		var markers = obj.attr('markers').split("|");
		var m=[];
		for(var j=0;j<markers.length;j++)
		{
			
			mark = markers[j].split(";");
			
			var mx = mark[0].split(",");
			var my = mark[1].split(",");
			
			var xa = [];
			var ya = [];
			for(v=0;v<mx.length;v++)
			{
				xa[v]=parseFloat(mx[v]);
				ya[v]=parseFloat(my[v]);
			}
			
			if (mark[2]=='lines')
			{
			
				m[j] = {

					x: xa,

					y: ya,

					mode: mark[2],

					name: mark[3],

					text: [mark[4]],

					textposition: mark[5],

					showlegend: false
					
				}
			} else
			if (mark[2]=='vector')
			{
				if ((xa.length==2) && (ya.length==2))
				{
					var lx = xa[1]-xa[0];
					var ly = ya[1]-ya[0];
					var rx = Math.sqrt(lx*lx + ly*ly);
					var theta = Math.atan(lx/ly);
					var al = 0.1*rx;
					if (al>10) al=10;
					var angle=8;
					
					xa[2] = xa[1]-al*Math.sin(theta-angle*Math.PI/180);
					ya[2] = ya[1]-al*Math.cos(theta-angle*Math.PI/180);
					
					xa[3] = xa[1]-al*Math.sin(theta+angle*Math.PI/180);
					ya[3] = ya[1]-al*Math.cos(theta+angle*Math.PI/180);
					
					xa[4] = xa[1];
					ya[4] = ya[1];
					
					m[j] = {

						x: xa,

						y: ya,

						mode: "lines+text",

						name: mark[3],

						text: [mark[4]],

						textposition: mark[5],

						showlegend: false
						
					}
				}
			} else
			{
				m[j] = {

					x: xa,

					y: ya,

					mode: mark[2],

					name: mark[3],

					text: [mark[4]],

					textposition: mark[5],

					type: 'scatter',
					
					showlegend: false,
					
					marker: { size: 10}
				}                            
			}
			data[i+j]=m[j];
		};
	  }
	  return data;		
	}
	
	function get2DVectors(obj,data,line_colors,line_widths)
	{
		obj.find('data.vectors').each( function()
		{
			
			var i = data.length;
			
			var htm = obj.html();
			
			//alert(data);
			var vector_names = [];
			var k = data.length;
			var eqns = htm.match(/\`.*?`/g);
			var line_colors = [];
			var line_widths = [];
			for(var i=0;i<eqns.length;i++)
			{
				
				if (obj[0].hasAttribute('line_color'))
				{
				   line_colors[i] =  obj.attr('line_color');
				} else line_colors[i]='rgb(0,0,0)'; 
				
				if (obj[0].hasAttribute('width'))
				{
				   line_widths[i] =  obj.attr('line_width');
				} else line_widths[i]=2;                    
				
				
				var xa = [];
				var ya = [];
				
				var eqn_parts = eqns[i].replace(/`/g,'').split("=");
				vector_names[i] = eqn_parts[0];
				var vec_dir = [];
				var start = [0,0];
				if ((eqn_parts != null) && (eqn_parts.length>1))
				{
					vec_dir = getTerms(eqn_parts[1]);
					if (eqn_parts[1].indexOf('@')>0)
					{
						var vec_and_point = eqn_parts[1].split('@');
						vec_dir = getTerms(vec_and_point[0]);
						var p_s = vec_and_point[1].replace('(','').replace(')','').split(',');
						for(var j=0;j<p_s.length;j++)
						{
							start[j]=parseFloat(p_s[j]);
						}
					}
				}
				var vectors = [0,0];
				for(var j=0;j<vec_dir.length;j++)
				{
					if (vec_dir[j].indexOf('vecx')>=0)
					{
						var val_s = vec_dir[j].replace('vecx','').trim();
						vectors[0]=1;
						if (val_s.length>0) vectors[0]=parseFloat(val_s); 
					} else if (vec_dir[j].indexOf('vecy')>=0)
					{
						var val_s = vec_dir[j].replace('vecy','').trim();
						vectors[1]=1;
						if (val_s.length>0) vectors[1]=parseFloat(val_s); 
					} 
				}
				
				
				
				xa[0]=start[0];
				xa[1]=start[0]+vectors[0];
				ya[0]=start[1];
				ya[1]=start[1]+vectors[1];
					
			
			
			
			
				var lx = xa[1]-xa[0];
				var ly = ya[1]-ya[0];
				var rx = Math.sqrt(lx*lx + ly*ly );
				var theta = Math.atan(lx/ly);
				
				
				var al = 0.1*rx;
				if (al>10) al=10;
				var angle=8;
				
				xa[2] = xa[1]-al*Math.sin(theta-angle*Math.PI/180);
				ya[2] = ya[1]-al*Math.cos(theta-angle*Math.PI/180);
				
				
				xa[3] = xa[1]-al*Math.sin(theta+angle*Math.PI/180);
				ya[3] = ya[1]-al*Math.cos(theta+angle*Math.PI/180);
				
				
				xa[4] = xa[1];
				ya[4] = ya[1];
				
				
				
				var trace_points = {
				x: xa,
				y: ya,
				type: 'scatter',
				name: vector_names[i],
				mode: 'lines',
				line: {

					color: line_colors[i],

					width: line_widths[i]

				  }
			  }
			
			  data[k+i]=trace_points;
				
			}
			
		});	
		return data;	
	}
	
	function get3DVectors(obj,data3d,line_colors,line_widths)
	{
		//alert(obj.attr('id'));
		//alert(obj.html());
		obj.find('.vectors3d').each( function()
		{

			var htm = $(this).html();
			
			
			var vector_names = [];
			var k = data3d.length;
			var eqns = htm.match(/\`.*?`/g);
			if (eqns == null) 
			{
				eqns = [];
				var i=0;
				$(this).find('script[type="math/asciimath"]').each( function()
				{
					eqns[i]=$(this).text();
					i=i+1;
				});
			}
			
			var line_colors = [];
			var line_widths = [];
			if (eqns != null)
			for(var i=0;i<eqns.length;i++)
			{
				
				if (obj[0].hasAttribute('line_color'))
				{
				   line_colors[i] =  $(this).attr('line_color');
				} else line_colors[i]='rgb(0,0,0)'; 
				
				if (obj[0].hasAttribute('width'))
				{
				   line_widths[i] =  $(this).attr('line_width');
				} else line_widths[i]=2;                    
				
				
				var xa = [];
				var ya = [];
				var za = [];
				var eqn_parts = eqns[i].replace(/`/g,'').split("=");
				vector_names[i] = eqn_parts[0];
				var vec_dir = [];
				var start = [0,0,0];
				var vectors = [0,0,0];
				if ((eqn_parts != null) && (eqn_parts.length>1))
				{
					vec_dir = getTerms(eqn_parts[1]);
					if (eqn_parts[1].indexOf('@')>0)
					{
						var vec_and_point = eqn_parts[1].split('@');
						vec_dir = getTerms(vec_and_point[0]);
						var p_s = vec_and_point[1].replace('(','').replace(')','').split(',');
						for(var j=0;j<p_s.length;j++)
						{
							start[j]=parseFloat(p_s[j]);
						}
					}
					vectors = [0,0,0];
					for(var j=0;j<vec_dir.length;j++)
					{
						//alert(vec_dir[j]);
						if (vec_dir[j].indexOf('vecx')>=0)
						{
							vectors[0]=getMagnitude(vec_dir[j],'vecx');
						} else if (vec_dir[j].indexOf('vecy')>=0)
						{
							vectors[1]=getMagnitude(vec_dir[j],'vecy');
						} else if (vec_dir[j].indexOf('vecz')>=0)
						{
							vectors[2]=getMagnitude(vec_dir[j],'vecz');
						}
					}
				}
				
				
				xa[0]=start[0];
				xa[1]=start[0]+vectors[0];
				ya[0]=start[1];
				ya[1]=start[1]+vectors[1];
				za[0]=start[2];
				za[1]=start[2]+vectors[2];
					
			
			
			
			
				var lx = xa[1]-xa[0];
				var ly = ya[1]-ya[0];
				var lz = za[1]-za[0];
				var rx = Math.sqrt(lx*lx + ly*ly + lz*lz);
				var theta = Math.atan(lx/ly);
				var phi = Math.atan(lx/lz);
				
				var al = 0.1*rx;
				if (al>10) al=10;
				var angle=8;
				
				xa[2] = xa[1]-al*Math.sin(theta-angle*Math.PI/180);
				ya[2] = ya[1]-al*Math.cos(theta-angle*Math.PI/180);
				za[2] = za[1]-al*Math.cos(phi-angle*Math.PI/180);
				
				xa[3] = xa[1]-al*Math.sin(theta+angle*Math.PI/180);
				ya[3] = ya[1]-al*Math.cos(theta+angle*Math.PI/180);
				za[3] = za[1]-al*Math.cos(phi+angle*Math.PI/180);
				
				xa[4] = xa[1];
				ya[4] = ya[1];
				za[4] = za[1];
				
				
				var trace_points = {
				x: xa,
				y: ya,
				z: za,
				type: 'scatter3d',
				name: vector_names[i],
				mode: 'lines',
				line: {

					color: line_colors[i],

					width: line_widths[i]

				  }
			  }
			
			  data3d[k+i]=trace_points;
				
			}
			
		});		
		return data3d;
	}
	
	function processExpressions3D(obj,title,xvars,yvars,xmin,xmax,xstep,xdtick,ydtick,xtitle,ytitle,line_colors,line_widths)
	{
		var exprs_xy = [];
		var expressions_xy = [];
		var exprs_xz = [];
		var expressions_xz = [];
		var zvars = [];

		var i = 0;


		obj.find('data.expression3d').each( function ()
		{
			expressions_xy[i]=$(this).val();
			var expr = math.compile(expressions_xy[i]);
			exprs_xy[i] = expr;
			
			
			if (obj[0].hasAttribute('expression_xz'))
			{
			   expressions_xz[i] =  $(this).attr('expression_xz');
			} else expressions_xz[i]='0';
			
			var exprxz = math.compile(expressions_xz[i]);
			exprs_xz[i] = exprxz;
			
			if (obj[0].hasAttribute('xvar'))
			{
			   xvars[i] =  $(this).attr('xvar');
			} else xvars[i]='x';

			if (obj[0].hasAttribute('yvar'))
			{
			   yvars[i] =  $(this).attr('yvar');
			} else yvars[i]='y';
			
			if (obj[0].hasAttribute('zvar'))
			{
			   zvars[i] =  $(this).attr('zvar');
			} else yvars[i]='z';
			
			if (obj[0].hasAttribute('line_color'))
			{
			   line_colors[i] =  $(this).attr('line_color');
			} else line_colors[i]=''; 
			
			if (obj[0].hasAttribute('width'))
			{
			   line_widths[i] =  $(this).attr('line_width');
			} else line_widths[i]=2;                    
			i=i+1;
		});


		  
		var data3d = [];
		for(var i=0;i<exprs_xy.length;i++)
		{
			var exprxy = exprs_xy[i];
			var xValues = math.range(xmin, xmax, xstep).toArray()
			var yValues = xValues.map(function (x) 
			{
			var scope = JSON.parse('{"'+xvars[i]+'":'+x+'}');
			return exprxy.evaluate(scope)
			})

			var exprxz = exprs_xz[i];
			var zValues = xValues.map(function (x) 
			{
			var scope = JSON.parse('{"'+xvars[i]+'":'+x+'}');
			return exprxz.evaluate(scope)
			})



			// render the plot using plotly
			if (line_colors[i].length>0)
			{
			  var trace_points = {
				x: xValues,
				y: yValues,
				z: zValues,
				type: 'scatter3d',
				name: yvars[i]+'='+expressions[i],
				mode: 'lines',
				line: {

					color: line_colors[i],

					width: line_widths[i]

				  }
			  }
			} else
			{
			  var trace_points = {
				x: xValues,
				y: yValues,
				z: zValues,
				type: 'scatter3d',
				name: yvars[i]+'='+expressions[i],
				mode: 'lines',
				line: {

					width: line_widths[i]

				  }
			  }
			}
			data3d[i] = trace_points;
		}
		return data3d;
	}
    
    function generatePlots(container)
    {
        $(container).find('div.graph').each( function()
        {
            try 
            {

                var xvars = [];
                var yvars = [];
                var line_colors = [];
                var line_widths = [];				

				
                var xlayout_type = 'linear';
                var ylayout_type = 'linear';
                var zlayout_type = 'linear';
                
                var xmin = -10;
                if ($(this)[0].hasAttribute('xlayout_type'))
                {
                   xlayout_type =   ($(this).attr('xlayout_type'))
                }

                if ($(this)[0].hasAttribute('ylayout_type'))
                {
                   ylayout_type =   ($(this).attr('ylayout_type'))
                }

                if ($(this)[0].hasAttribute('zlayout_type'))
                {
                   zlayout_type =   ($(this).attr('zlayout_type'))
                }

                
                if ($(this)[0].hasAttribute('xmin'))
                {
                   xmin =   parseInt($(this).attr('xmin'))
                }
                
                
                
                var xmax = 10;
                if ($(this)[0].hasAttribute('xmax'))
                {
                   xmax =   parseInt($(this).attr('xmax'))
                }
                var xstep = 0.5;
                if ($(this)[0].hasAttribute('xstep'))
                {
                   xstep = parseFloat($(this).attr('xstep'));
                }
                
                var title = 'Graph';
                if ($(this)[0].hasAttribute('title'))
                {
                   title =  $(this).attr('title')
                }
                                
                var xtitle = 'x';
                if ($(this)[0].hasAttribute('xtitle'))
                {
                   xtitle =  $(this).attr('xtitle')
                }
                var ytitle = 'y';
                if ($(this)[0].hasAttribute('ytitle'))
                {
                   ytitle =  $(this).attr('ytitle')
                }
                var ztitle = 'z';
                if ($(this)[0].hasAttribute('ztitle'))
                {
                   ztitle =  $(this).attr('ztitle')
                }
                
                var xdtick = 0;
                if ($(this)[0].hasAttribute('xdtick'))
                {
                   xdtick =  parseFloat($(this).attr('xdtick'));
                }

                var ydtick = 0;
                if ($(this)[0].hasAttribute('ydtick'))
                {
                   ydtick =  parseFloat($(this).attr('ydtick'));
                }
                
                var zdtick = 0;
                if ($(this)[0].hasAttribute('zdtick'))
                {
                   zdtick =  parseFloat($(this).attr('zdtick'));
                }

                var layout = [];

                var xautotick = true;
                if (xdtick>0) 
                {
                  xautotick = false;
                }

                var yautotick = true;
                if (ydtick>0) 
                {
                  yautotick = false;
                }

                var annotations=[];

                if ($(this)[0].hasAttribute('annotations'))
                {
                    annots = $(this).attr('annotations').split("|");
                    
                    for(var j=0;j<annots.length;j++)
                    { 
                        ano = annots[j].split(";");                         
                        annotations[j]=
                        {

                          x: parseInt(ano[0]),

                          y: parseInt(ano[1]),
                          
                          z: parseInt(ano[2]),

                          xref: ano[2],

                          yref: ano[3],

                          text: ano[4],

                          showarrow: true,

                          arrowhead: 3,

                          ax: -30,

                          ay: -40

                        }
                    }
                }

                var layout = {
                  title: title,
                  xaxis: {
                    type: xlayout_type,
                    autotick:xautotick,
                    ticks:'inside',
                    dtick: xdtick,
                    title: xtitle,
                    showgrid: true,
                    
                  },
                  yaxis: {
                    type: ylayout_type,
                    autotick:yautotick,
                    ticks:'inside',
                    dtick: ydtick,  
                    title: ytitle,
                    showline: true,
                    
                  },
                  zaxis: {
                    type: zlayout_type,
                    autotick:yautotick,
                    ticks:'inside',
                    dtick: zdtick,  
                    title: ztitle,
                    showline: true,
                    
                  },                          
                  //annotations:annotations
                  
                };
				
				data = processExpressions2D($(this),title,xvars,yvars,xmin,xmax,xstep,xdtick,ydtick,xtitle,ytitle,line_colors,line_widths);
                  
				data = get2DMarkers($(this),data);
				data = get2DVectors($(this),data,line_colors,line_widths)  

				if (data.length>0)
				{
					Plotly.newPlot($(this).attr('id'), data, layout);     
				}
                  
				data3d = processExpressions3D($(this),title,xvars,yvars,xmin,xmax,xstep,xdtick,ydtick,xtitle,ytitle,line_colors,line_widths);
                  
				data3d = get3DVectors($(this),data3d,line_colors,line_widths)


				if (data3d.length)
				{
				  Plotly.newPlot($(this).attr('id'), data3d, layout);
				}                  
                  
                         
            }
            catch (err) 
            {
                console.error(err)
                alert(err)
            }
        });
    }    

<?php
    }
}?>
    
