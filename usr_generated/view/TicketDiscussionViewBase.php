<?php
require_once 'ViewBase.php';
require_once 'TicketDiscussion.php';
require_once 'Ticket.php';
require_once 'User.php';
class TicketDiscussionViewBase extends ViewBase 
{ 
    public $id;
    public $security;
    public $current_user;
    public $columns;
    public $headers;
    public $title;
    public $list_form_attributes;
    public $custom_html_list;
    public $command;
    public $list_controls;
    public $form_list;
    public $action;
    public $list_delete_button_url;
    public $list_delete_button_next_url;
    public $list_delete_selected_url;
    public $enable_paginator;
    public $status_lst;
    public $search_texts;
    public $items_per_page;
    public $total_db_items;
    public $table_only_paginator;
    public $field_header;
    public $field_value_header;
    public $form_attributes;
    public $button_name;
    public $button_url;
    public $button_next_url;
    public $delete_button_url;
    public $delete_button_next_url;
    public $usr;
    public $ticket_discussion;
    public $page;
    public $path;
    public $ticket_discussions;
    public $container;
    public $item_per_page;
    public $pages;
    public $search_text;
    public $select_filters;
    public $sort_filters;
    public $sort_orders;
    public function get_ticket_id($ticket_discussion)
    {
         echo $ticket_discussion->getTicket();
    }
    public function get_user_id($ticket_discussion)
    {
         echo $ticket_discussion->getUser();
    }
    public function get_message($ticket_discussion)
    {
        echo $ticket_discussion->getMessage();
    }
    public function get_created($ticket_discussion)
    {
        echo $ticket_discussion->getCreated();
    }
    public function render_id()
    {
        $class='';
        $attrs='';
        $ticket_discussion_field='id';
        foreach($this->columns[$ticket_discussion_field]['attributes'] as $name=>$value)
        {
            if ($name=='class')
            {
               $class.=' '.$value;
            }
            else
            {
               $attrs.=$name.'="'.$value.'" ';
            }
            
        }

        $value=($this->ticket_discussion->getId())?htmlentities($this->ticket_discussion->getId()):'';
        if ($this->columns[$ticket_discussion_field]['type']=='boolean')
        {
            $this->render_boolean($ticket_discussion_field,$value);
        }
        else if (($this->columns[$ticket_discussion_field]['type']=='hidden') || ($this->columns[$ticket_discussion_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='id'  value='$value' />";
            if ($this->columns[$ticket_discussion_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$ticket_discussion_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='id' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='id' value='".$value."' ></input>";
            }
        }
    }
    public function render_id_filter()
    {
        $this->renderSearchText('id','number');
	}
	
    public function render_ticket_id()
    {
        $sids_str=$this->ticket_discussion->getTicketId();
        
        $class='';
        $attrs='';
        $ticket_discussion_field='ticket_id';
        foreach($this->columns[$ticket_discussion_field]['attributes'] as $name=>$value)
        {
            if ($name=='class')
            {
               $class.=' '.$value;
            }
            else
            {
               $attrs.=$name.'="'.$value.'" ';
            }
            
        }
        $value=($this->ticket_discussion->getTicketId())?htmlentities($this->ticket_discussion->getTicketId()):'';
        
        if ($this->columns[$ticket_discussion_field]['type']=='boolean')
        {
            $this->render_boolean($ticket_discussion_field,$value);
        }
        else if (($this->columns[$ticket_discussion_field]['type']=='hidden') || ($this->columns[$ticket_discussion_field]['type']=='readonly'))
        {
            echo "<input type='hidden' $attrs name='ticket_id'  value='$sids_str' />";
            if ($this->columns[$ticket_discussion_field]['type']=='readonly')
            {
                echo "<span >".$this->ticket_discussion->getTicket()."</span>";
            }
        } else
        {
            if ($this->columns[$ticket_discussion_field]['mode']=='edit')
            {
                echo "<select $attrs class='$class edit_input'  name='ticket_id' >\n";
            } else
            {
                echo "<select $attrs class='$class'  name='ticket_id' >\n";
            }
            $selected_ids=array();
            if ($sids_str)
            {
                $selected_ids = explode(",",$sids_str);
            }
            if (array_key_exists('[FILTER_FIELD]',$this->columns) && array_key_exists('options',$this->columns['[FILTER_FIELD]']) && ($this->columns['[FILTER_FIELD]']['options']))
            {
                $options = $this->columns['[FILTER_FIELD]']['options'];
                foreach ($obptions as $option=>$label)
                {
                    if (in_array($option,$selected_ids))
                    {
                        echo "<option selected='selected' value='$option' >$label</option>\n";
                        $data_names[] = $label;
                    } else
                    {
                        echo "<option value='$option' >$label</option>\n";
                    }
                }                
            } else
            {
            
                $ticket = new Ticket();
                $param=array();
                if (array_key_exists('option_filters',$this->columns[$ticket_discussion_field]) && $this->columns[$ticket_discussion_field]['option_filters'])
                {
                    $param['filter'] = $this->columns[$ticket_discussion_field]['option_filters'];
                }
                if (array_key_exists('option_sort',$this->columns[$ticket_discussion_field]) && $this->columns[$ticket_discussion_field]['option_sort'])
                {
                    $param['sort'] = $this->columns[$ticket_discussion_field]['option_sort'];
                }
                if (array_key_exists('option_select',$this->columns[$ticket_discussion_field]) && $this->columns[$ticket_discussion_field]['option_select'])
                {
                    $param['select'] = $this->columns[$ticket_discussion_field]['option_select'];
                }    
                
                $objs=$ticket->getAll($param);
                $data_names = array();
                foreach ($objs as $option)
                {
                    $name = $option->getName();
                    $oid = $option->getId();
                    if ($oid)
                    {
                        if (in_array($oid,$selected_ids))
                        {
                            echo "<option selected='selected' value='$oid' >$name</option>\n";
                            $data_names[] = $name;
                        } else
                        {
                            echo "<option value='$oid' >$name</option>\n";
                        }
                    }
                }
            }
            echo "</select>\n";
            if ($this->columns[$ticket_discussion_field]['mode']=='edit') echo "<span class='view_input'>".implode(',',$data_names)."</span>";
       }
    }
    public function render_ticket_id_filter()
    {
		echo "<select  class='filter' name='ticket_discussions_select_filters[ticket_id]' value='' > \r\n";
		echo "<option value=''>All</option> \r\n";
	
		if (in_array('0',$this->distinct_ticket_id_lst)) 
		{
			if (array_key_exists('ticket_id',$this->select_filters) && ($this->select_filters['ticket_id']=='0'))
			{
				echo "<option selected='selected' value='0' >Unassigned</option>";
			} else
			{
				echo "<option value='0' >Unassigned</option>";
			}
		}
		$ticket_handle = new Ticket();
		$tickets = $ticket_handle->findAll(array('id'=>$this->distinct_ticket_id_lst),array('id'=>'ASC'));
		foreach ($tickets as $ticket)
		{
			if (($this->select_filters['ticket_id']) && ($this->select_filters['ticket_id']==$ticket->getId()))
			{
				echo "<option selected='selected' value='".$ticket->getId()."' >".$ticket->getName()."</option>";
			} else
			{
				echo "<option value='".$ticket->getId()."' >".$ticket->getName()."</option>";
			}
		}
		echo "</select> \r\n";		
	}
	
    public function render_user_id()
    {
        $sids_str=$this->ticket_discussion->getUserId();
        
        $class='';
        $attrs='';
        $ticket_discussion_field='user_id';
        foreach($this->columns[$ticket_discussion_field]['attributes'] as $name=>$value)
        {
            if ($name=='class')
            {
               $class.=' '.$value;
            }
            else
            {
               $attrs.=$name.'="'.$value.'" ';
            }
            
        }
        $value=($this->ticket_discussion->getUserId())?htmlentities($this->ticket_discussion->getUserId()):'';
        
        if ($this->columns[$ticket_discussion_field]['type']=='boolean')
        {
            $this->render_boolean($ticket_discussion_field,$value);
        }
        else if (($this->columns[$ticket_discussion_field]['type']=='hidden') || ($this->columns[$ticket_discussion_field]['type']=='readonly'))
        {
            echo "<input type='hidden' $attrs name='user_id'  value='$sids_str' />";
            if ($this->columns[$ticket_discussion_field]['type']=='readonly')
            {
                echo "<span >".$this->ticket_discussion->getUser()."</span>";
            }
        } else
        {
            if ($this->columns[$ticket_discussion_field]['mode']=='edit')
            {
                echo "<select $attrs class='$class edit_input'  name='user_id' >\n";
            } else
            {
                echo "<select $attrs class='$class'  name='user_id' >\n";
            }
            $selected_ids=array();
            if ($sids_str)
            {
                $selected_ids = explode(",",$sids_str);
            }
            if (array_key_exists('[FILTER_FIELD]',$this->columns) && array_key_exists('options',$this->columns['[FILTER_FIELD]']) && ($this->columns['[FILTER_FIELD]']['options']))
            {
                $options = $this->columns['[FILTER_FIELD]']['options'];
                foreach ($obptions as $option=>$label)
                {
                    if (in_array($option,$selected_ids))
                    {
                        echo "<option selected='selected' value='$option' >$label</option>\n";
                        $data_names[] = $label;
                    } else
                    {
                        echo "<option value='$option' >$label</option>\n";
                    }
                }                
            } else
            {
            
                $user = new User();
                $param=array();
                if (array_key_exists('option_filters',$this->columns[$ticket_discussion_field]) && $this->columns[$ticket_discussion_field]['option_filters'])
                {
                    $param['filter'] = $this->columns[$ticket_discussion_field]['option_filters'];
                }
                if (array_key_exists('option_sort',$this->columns[$ticket_discussion_field]) && $this->columns[$ticket_discussion_field]['option_sort'])
                {
                    $param['sort'] = $this->columns[$ticket_discussion_field]['option_sort'];
                }
                if (array_key_exists('option_select',$this->columns[$ticket_discussion_field]) && $this->columns[$ticket_discussion_field]['option_select'])
                {
                    $param['select'] = $this->columns[$ticket_discussion_field]['option_select'];
                }    
                
                $objs=$user->getAll($param);
                $data_names = array();
                foreach ($objs as $option)
                {
                    $name = $option->getName();
                    $oid = $option->getId();
                    if ($oid)
                    {
                        if (in_array($oid,$selected_ids))
                        {
                            echo "<option selected='selected' value='$oid' >$name</option>\n";
                            $data_names[] = $name;
                        } else
                        {
                            echo "<option value='$oid' >$name</option>\n";
                        }
                    }
                }
            }
            echo "</select>\n";
            if ($this->columns[$ticket_discussion_field]['mode']=='edit') echo "<span class='view_input'>".implode(',',$data_names)."</span>";
       }
    }
    public function render_user_id_filter()
    {
		echo "<select  class='filter' name='ticket_discussions_select_filters[user_id]' value='' > \r\n";
		echo "<option value=''>All</option> \r\n";
	
		if (in_array('0',$this->distinct_user_id_lst)) 
		{
			if (array_key_exists('user_id',$this->select_filters) && ($this->select_filters['user_id']=='0'))
			{
				echo "<option selected='selected' value='0' >Unassigned</option>";
			} else
			{
				echo "<option value='0' >Unassigned</option>";
			}
		}
		$user_handle = new User();
		$users = $user_handle->findAll(array('id'=>$this->distinct_user_id_lst),array('lastname'=>'ASC'));
		foreach ($users as $user)
		{
			if (($this->select_filters['user_id']) && ($this->select_filters['user_id']==$user->getId()))
			{
				echo "<option selected='selected' value='".$user->getId()."' >".$user->getName()."</option>";
			} else
			{
				echo "<option value='".$user->getId()."' >".$user->getName()."</option>";
			}
		}
		echo "</select> \r\n";		
	}
	
    public function render_message()
    {
        $class='';
        $attrs='';
        $ticket_discussion_field='message';
        foreach($this->columns[$ticket_discussion_field]['attributes'] as $name=>$value)
        {
            if ($name=='class')
            {
               $class.=' '.$value;
            }
            else
            {
               $attrs.=$name.'="'.$value.'" ';
            }
            
        }

        $value=($this->ticket_discussion->getMessage())?htmlentities($this->ticket_discussion->getMessage()):'';
        if (($this->columns[$ticket_discussion_field]['type']=='hidden') || ($this->columns[$ticket_discussion_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='message'  value='$value' />";
            if ($this->columns[$ticket_discussion_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$ticket_discussion_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_message' $attrs class='$class  edit_input' name='message' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_message' $attrs type='text' class='$class ' name='message' >".$value."</textarea>";
            }
        }
    }
    public function render_message_filter()
    {
        $this->renderSearchText('message','textarea');
	}
	
    public function render_created()
    {
        $class='';
        $attrs='';
        $ticket_discussion_field='created';
        foreach($this->columns[$ticket_discussion_field]['attributes'] as $name=>$value)
        {
            if ($name=='class')
            {
               $class.=' '.$value;
            }
            else
            {
               $attrs.=$name.'="'.$value.'" ';
            }
            
        }

        $value=($this->ticket_discussion->getCreated())?htmlentities($this->ticket_discussion->getCreated()):'';
        if ($this->columns[$ticket_discussion_field]['type']=='boolean')
        {
            $this->render_boolean($ticket_discussion_field,$value);
        }
        else if (($this->columns[$ticket_discussion_field]['type']=='hidden') || ($this->columns[$ticket_discussion_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='created'  value='$value' />";
            if ($this->columns[$ticket_discussion_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$ticket_discussion_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class date edit_input' name='created' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class date' name='created' value='".$value."' ></input>";
            }
        }
    }
    public function render_created_filter()
    {
        ;
	}
	

    public $distinct_ticket_id_lst;
    public $distinct_user_id_lst;

   public function __construct($param=array('container'=>'div.page'))
   {
      foreach($param as $field=>$value)
      {
         $this->$field=$value;
      }
      if (!isset($this->container) || (!$this->container))
      {
         $this->container='div.page';
      }
      $this->paths = new Paths();
      $this->config = new Configurations();
      $this->current_user=new User();
      $this->current_user->find(array("id"=>$_SESSION["user_id"]));

        
        
        $this->columns["id"]=array('type'=>"number",'label'=>"Id",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["ticket_id"]=array('type'=>"select",'label'=>"TicketId",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["user_id"]=array('type'=>"select",'label'=>"UserId",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["message"]=array('type'=>"textarea",'label'=>"Message",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["created"]=array('type'=>"datetime",'label'=>"Created",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());

        $this->headers = array("#"=>array("label"=>"#","sort"=>0,"class"=>"","attributes"=>array()),"action"=>array("label"=>"Action","sort"=>0,"class"=>"","attributes"=>array()),"id"=>array("label"=>"Id ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"ticket_id"=>array("label"=>"Tickets ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"user_id"=>array("label"=>"Users ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"message"=>array("label"=>"Message ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"created"=>array("label"=>"Created ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()));
   } 
    
   public function setLabels($labels)
   {
      foreach($labels as $field=>$label)
      {
         if (array_key_exists($field,$this->columns) && array_key_exists('label',$this->columns[$field]))
         {
             $this->columns[$field]['label']=$label;
         } else
         if (array_key_exists($field,$this->headers))
         {
             $this->headers[$field]['label']=$label;
         }
         
      }
   }
    
    public function init_edit()
    { 
        $this->title = 'Edit TicketDiscussion';
        $this->edit_columns = array("id","ticket_id","user_id","message","created");

        $this->security=new TamsiSecurity();
        if (array_key_exists("id",$_GET))
        {
            $this->id=$_GET["id"];
        } else
        {
            $this->id=$_REQUEST["id"];
        }
        $this->ticket_discussion = new TicketDiscussion();
        $this->ticket_discussion->find(array("id"=>$this->id));
        if ($_REQUEST)
        {
            if (array_key_exists("selected",$_REQUEST))
            {
                $this->page=$_REQUEST["selected"];
            }
            if (array_key_exists("path",$_REQUEST))
            {
                $this->page=$_REQUEST["path"];
            }
        }
        $this->field_header = 'Field';
        $this->field_value_header = 'Value';
        $this->command = 'edit';
        
        $this->form_attributes=''; //attributes added to the form
        $this->button_name='Update'; //Visible name of the Edit submit button
        $this->button_url='?command=editRest&object=TicketDiscussion'; //url of the controller where edit form will be posted by ajax
        $this->button_next_url='?command=display_rest&path=Admin.dir/TicketDiscussions.dir/Edit_TicketDiscussion.hid.rest.php&id='.$this->ticket_discussion->getId(); //url that will be visited after completing the edit submission
        $this->delete_button_url='?command=deleteRest&object=TicketDiscussion'; //url of the controller where delete form will be submitted
        $this->delete_button_next_url='?command=display_rest&path=Admin.dir/TicketDiscussions.dir/List_TicketDiscussions.rest.php'; //url that will be visited after completing the delete submission

        
        $this->columns["id"]['mode']="edit";
        $this->columns["ticket_id"]['mode']="edit";
        $this->columns["ticket_id"]["attributes"]["class"]="chosen";
        $this->columns["user_id"]['mode']="edit";
        $this->columns["user_id"]["attributes"]["class"]="chosen";
        $this->columns["message"]['mode']="edit";
        $this->columns["created"]['mode']="edit";

        
        
    } 
    public function init_new()
    { 
        $this->title = 'New TicketDiscussion';
        $this->new_columns = array("id","ticket_id","user_id","message","created");
        
        $this->security=new TamsiSecurity();
        $this->ticket_discussion = new TicketDiscussion();
        if ($_REQUEST)
        {
            if (array_key_exists("selected",$_REQUEST))
            {
                $this->page=$_REQUEST["selected"];
            }
            if (array_key_exists("path",$_REQUEST))
            {
                $this->page=$_REQUEST["path"];
            }
        }
        $this->field_header = 'Field';
        $this->field_value_header = 'Value';
        $this->command = 'new';

        $this->form_attributes=''; //attributes added to the new form
        $this->button_name='Create'; //Label of the new submit button
        $this->button_url='?command=newRest&object=TicketDiscussion'; //url of the controller where the new form will be submitted through ajax
        $this->button_next_url='?command=display_rest&path=Admin.dir/TicketDiscussions.dir/List_TicketDiscussions.rest.php'; //url of the controller that will be visited after completing the new submission

        
        $this->columns["id"]['mode']="new";
        $this->columns["ticket_id"]['mode']="new";
        $this->columns["ticket_id"]["attributes"]["class"]="chosen";
        $this->columns["user_id"]['mode']="new";
        $this->columns["user_id"]["attributes"]["class"]="chosen";
        $this->columns["message"]['mode']="new";
        $this->columns["created"]['mode']="new";

        
    } 
    public function init_list_params()
    { 
        $this->security=new TamsiSecurity();
        $valid_filters=array("id","ticket_id","user_id","message","created");
        if ($_REQUEST)
        {
            if (array_key_exists("ticket_discussions_selected",$_REQUEST))
            {
                $this->page=$_REQUEST["ticket_discussions_selected"];
            }
            if (array_key_exists("ticket_discussions_path",$_REQUEST))
            {
                $this->path=$_REQUEST["ticket_discussions_path"];
            }
        }
        
        if (!$this->container) $this->container='div.page';
        $this->list_columns = array_merge(array('#','action'),$valid_filters);
        $this->list_form_attributes='';
        $this->custom_html_list='';
        $this->command = 'list';
        $this->list_controls = array('new'=>array('label'=>'New','url'=>'?command=display_rest&path=Admin.dir/TicketDiscussions.dir/New_TicketDiscussion.rest.php','container'=>$this->container));
		$this->form_list='form_list_ticket_discussions';
		$this->action='?command=display_php&path=Admin.dir/TicketDiscussions.dir/List_TicketDiscussions.rest.php';
        $this->list_delete_button_url = '?command=deleteRest&object=TicketDiscussion'; //url of the controller where delete form will be submitted
        $this->list_delete_button_next_url = '?command=display_rest&base_path=Admin.dir/TicketDiscussions.dir&path=List_TicketDiscussions.rest.php';
        $this->list_delete_selected_url = '?command=deleteSelectedRest&object=TicketDiscussion'; //url of the controller where delete of selected objects will be submitted
        $this->enable_paginator = true;
        $this->usr=array();
        $ticket_discussion = new TicketDiscussion();
        if (!isset($_SESSION['list_ticket_discussions']))
        {
            $_SESSION['list_ticket_discussions']=array();
            $_SESSION['list_ticket_discussions']['sort_filters']=array();
            $_SESSION['list_ticket_discussions']['sort_order']='';
            $_SESSION['list_ticket_discussions']['select_filters']=array();
            $_SESSION['list_ticket_discussions']['search_texts']=array();
            $_SESSION['list_ticket_discussions']['page']='1';
        }

        if (isset($_REQUEST['ticket_discussions_sort_filters']))
        {
            $sort_lists = $_REQUEST['ticket_discussions_sort_filters'];
            $_SESSION['list_ticket_discussions']['sort_filters']=$sort_lists;
        }
        else
        {
            $sort_lists=$_SESSION['list_ticket_discussions']['sort_filters'];
        }

        if (isset($_REQUEST['ticket_discussions_sort_order']))
        {
            $sort_order = $_REQUEST['ticket_discussions_sort_order'];
            $_SESSION['list_ticket_discussions']['sort_order']=$sort_order;
        }
        else
        {
            $sort_order = $_SESSION['list_ticket_discussions']['sort_order'];
        }
        
        if (!$sort_lists) 
        {
            $sort_lists = array('id'=>'DESC');
            $sort_order = 'id';
        }
        
        $this->sort_filters = array();
        if (is_array($sort_order))
        {
            $this->sort_orders = $sort_order;
        } else if (is_string($sort_order))
        {
            $this->sort_orders = explode(',',$sort_order);
            foreach ($this->sort_orders as $s_order)
            {
                $this->sort_filters[$s_order]=$sort_lists[$s_order];
            }
        } else $this->sort_orders = array();

		if (!isset($this->select_filters)) $this->select_filters=array();
        if (isset($_REQUEST['ticket_discussions_select_filters']))
        {
            foreach($_REQUEST['ticket_discussions_select_filters'] as $key=>$value)
            {
                if (!(is_numeric($key)) && in_array($key,$valid_filters))
                {
                    $this->select_filters[$key] = $value;
                }
            }
            $_SESSION['list_ticket_discussions']['select_filters']=$this->select_filters;
        }
        else
        {
            $this->select_filters=array_merge($_SESSION['list_ticket_discussions']['select_filters'],$this->select_filters);
        }
        
        





        $this->distinct_ticket_id_lst=array();

		$res = $ticket_discussion->query('SELECT DISTINCT ticket_id from ticket_discussions');

        foreach ($res as $item)
        {
			$ticket_id=$item['ticket_id'];
			$this->distinct_ticket_id_lst[$ticket_id]=$ticket_id;
        }

        $this->distinct_user_id_lst=array();

		$res = $ticket_discussion->query('SELECT DISTINCT user_id from ticket_discussions');

        foreach ($res as $item)
        {
			$user_id=$item['user_id'];
			$this->distinct_user_id_lst[$user_id]=$user_id;
        }




        $search='';
        if (isset($_REQUEST['ticket_discussions_search_texts']))
        {
            $this->search_texts = $_REQUEST['ticket_discussions_search_texts'];
            $_SESSION['list_ticket_discussions']['search_texts']=$this->search_texts;
        }
        else
        {
            $this->search_texts = $_SESSION['list_ticket_discussions']['search_texts'];
        }
        if (is_array($this->search_texts))
        {
            foreach($this->search_texts as $fld=>$val)
            {
                if ((trim($val)) && in_array($fld,$valid_filters))
                {
                    if ($search)
                    {
                        $search.=" AND $fld like '%$val%' ";
                    }
                    else
                    {
                        $search=" $fld like '%$val%' ";
                    }
                }
            }
        }
        if ($search)
        {
            $this->select_filters[] = $search;
        }


		$this->ticket_discussion = $ticket_discussion;
    }
    
    public function load_list()
    { 
        if (!($this->items_per_page)) $this->items_per_page = 25;
        if (isset($_REQUEST['ticket_discussions_items_per_page']))
        {
            $this->items_per_page = $_REQUEST['ticket_discussions_items_per_page'];
            $_SESSION['list_ticket_discussions']['items_per_page']=$this->items_per_page;
        } else
        {
            if (array_key_exists('list_ticket_discussions',$_SESSION) && array_key_exists('items_per_page',$_SESSION['list_ticket_discussions']))
            {
                $this->items_per_page = $_SESSION['list_ticket_discussions']['items_per_page'];
            }            
        }
        
        $this->pages=$this->ticket_discussion->getPageCount($this->select_filters,$this->sort_filters,$this->items_per_page,true);
        $this->total_db_items = $this->ticket_discussion->total_db_items;
        $this->page = 1;//default to 1 in case not provided below
        if (isset($_REQUEST['ticket_discussions_page']))
        {
            $this->page = $_REQUEST['ticket_discussions_page'];
            $_SESSION['list_ticket_discussions']['page']=$this->page;
        } else
        {
            if (array_key_exists('list_ticket_discussions',$_SESSION) && array_key_exists('page',$_SESSION['list_ticket_discussions']))
            {
                $this->page = $_SESSION['list_ticket_discussions']['page'];
            }            
        }
        if (($this->page < 1) || ($this->page > $this->pages)) $this->page = 1;

        
        $this->ticket_discussions = $this->ticket_discussion->findAll($this->select_filters,$this->sort_filters,$this->items_per_page,$this->page);
    }
    
    public function init_list()
    { 
        $this->title = 'TicketDiscussions';
        $this->init_list_params();
        $this->load_list();
    } 
    

    public function renderDeleteCheckbox($ticket_discussion)
    {
        echo '<input type="checkbox" class="select_checkbox" id="'.$ticket_discussion->getId().'" value="'.$ticket_discussion->getId().'" name="delete_selected[]" /><br>';
    }
    
    public function renderEditAction($ticket_discussion)
    {
        echo "<button type='button' class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/TicketDiscussions.dir&path=Edit_TicketDiscussion.hid.rest.php&id=".$ticket_discussion->getId()."&object=TicketDiscussion' href='#' ><li class='fa fa-pencil'></li> edit</button>";
    }
    
    public function renderSaveAction($next_url)
    {
        $mx_next_url='';
        if ($next_url)
        {
            $mx_next_url = 'mx-next="'.$next_url.'"';
        }
        echo '<button type="button" class="button mx-" mx-click="'.$this->button_url.'"  href="#" mx-container="div.page" '.$mx_next_url.' ><li class="fa fa-save"></li> '.$this->button_name.'</button>';
    }
    
    public function renderDeleteAction($ticket_discussion,$next_url,$delete_url)
    {
        $mx_next_url='';
        if ($next_url)
        {
            $mx_next_url = 'mx-next="'.$next_url.'"';
        }
        echo '<button type="button" class="button mx- danger" mx-confirm="Are you sure to delete TicketDiscussion of id='.$ticket_discussion->getId().'?" mx-click="'.$delete_url.'&id='.$ticket_discussion->getId().'" href="#" mx-container="'.$this->container.'" '.$mx_next_url.' ><li class="fa fa-trash" ></li> del</button>';
    }
    
    public function renderViewAction($ticket_discussion)
    {
        echo "<button type='button' class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/TicketDiscussions.dir&path=View_TicketDiscussion.hid.rest.php&id=".$ticket_discussion->getId()."&object=TicketDiscussion' href='#' ><li class='fa fa-eye'></li> view</button>";
    }

    public function renderListAction()
    {
        echo "<button type='button' class='button mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/TicketDiscussions.dir&path=List_TicketDiscussions.rest.php&object=TicketDiscussion'  ><li class='fa fa-list'></li> List</button>";
    }

    public function renderActions($ticket_discussion,$class="",$edit=TRUE)
    {
        if ($edit)
        {
            $this->renderDeleteCheckbox($ticket_discussion);
            $this->renderEditAction($ticket_discussion);
            $this->renderDeleteAction($ticket_discussion,$this->list_delete_button_next_url,$this->list_delete_button_url);
        } else
        {
            $this->renderViewAction($ticket_discussion);
        }
    }
    
	public function renderEditControls()
    {
		echo '<div class="control">';		
		$this->renderSaveAction($this->button_next_url);
        $this->renderDeleteAction($this->ticket_discussion,$this->delete_button_next_url,$this->delete_button_url);
        $this->renderListAction();
		echo '</div>';
	}    
	
	public function renderNewControls()
	{
        if ($this->button_next_url)
        {
            $next_after_create='mx-next="'.$this->button_next_url.'"';
        } else
        {
            $next_after_create='';
        }
		echo '<div class="control" >';
		echo '<button type="button" class="button mx-" mx-click="'.$this->button_url.'" href="#" '.$next_after_create.' mx-container="'.$this->container.'"><li class="fa fa-plus"></li> '.$this->button_name.' </button>';
        $this->renderListAction();
		echo '</div>';
	}
    
   public function renderListControls($after_new='')
   {
      echo '<div class="control" >'."\r\n";
      foreach($this->list_controls as $control)
      {
         echo '<button type="button" class="button mx-" mx-container="'.$control['container'].'" mx-click="'.$control['url'].'" href="#" ><i class="fa fa-plus"></i> '.$control['label'].'</button>'."\r\n";
      }
      echo '</div>'."\r\n";  
   }

   public function renderCustomEdit()
   {
   }

   public function renderCustomNew()
   {
   }

   public function renderCustomlist()
   {
   }
    
   public function get_icon($ticket_discussion)
   {
		echo '<img class="icon" src="'.$this->paths->base_url.'/img/displaypng.php?id='.$_SESSION['user_id'].'&image='.$this->paths->user_image.'/ticket_discussions/'.$ticket_discussion->getId().'_ticket_discussions_icon.png&key='.$this->current_user->getSecurekey().'" />';
   }
   
   public function render_icon_filter()
   {
      echo '';
   }
    
    public function renderSearchText($field,$type)
    {
		echo '<div class="search_filter_header">';
		$value='';
		if (isset($this->search_texts) && array_key_exists($field,$this->search_texts)) $value=$this->search_texts[$field];
		echo '<input class="search_text '.$type.'" type="text" name="ticket_discussions_search_texts['.$field.']" value="'.$value.'"></input><button type="button" class="search_text_button clear" ><li class="fa fa-times"></li></button>';
		echo '</div>';
    }
    
    public function get_id($ticket_discussion)
    {
        echo "<button type='button' class='mx-' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/TicketDiscussions.dir&path=Edit_TicketDiscussion.hid.rest.php&id=".$ticket_discussion->getId()."&object=TicketDiscussion'  ><i class='fa fa-edit'></i>".$ticket_discussion->getId()."</button>";
    }
    
    public function renderPaginator()
    {?>
        <script>
            <?php
            if (!isset($this->container)) $this->container = 'div.page';
            if (!isset($this->form_list)) $this->form_list = 'form_list_ticket_discussions';
            ?>
            function ticket_discussions_paginate(page) 
            {
                var form = $("#<?php echo $this->form_list;?>");
                
                var target = "<?php echo $this->container;?>";
                
                var url_submit = form.attr('action');        
                var url = url_submit;
                if (url_submit.indexOf('command=display_php')>=0)
                {
                    url = url_submit.replace('command=display_php','command=display_rest');
                } 
                
                form.find('input.field_page').val(page);
                form.find('input.field_items_per_page').val($('#ticket_discussions_items_per_page').val());
                if (typeof loadToSelector == 'function') 
                { 
                    loadToSelector(url,target,form,'',loader_img)
                } else
                {
                    form.attr('action',url_submit);
                    form.submit();
                }
                
            }
        </script>
        <div class="paginator_container" >
            <div class="column items_per_page" >
                Show
                <select name="ticket_discussions_items_per_page" id="ticket_discussions_items_per_page" style="width:60px" onchange="ticket_discussions_paginate(<?php echo $this->page;?>)">
                    <option class="entry-option" value="10" <?php echo (($this->items_per_page==10)?('selected="true"'):(''));?>>10</option>
                    <option class="entry-option" value="25" <?php echo (($this->items_per_page==25)?('selected="true"'):(''));?>>25</option>
                    <option class="entry-option" value="50" <?php echo (($this->items_per_page==50)?('selected="true"'):(''));?>>50</option>
                    <option class="entry-option" value="100" <?php echo (($this->items_per_page==100)?('selected="true"'):(''));?>>100</option>
                </select>
                entries
            </div>
            <div class="column pages" >
        <?php
        if ($this->pages>1)
        {
            if (isset($this->table_only_paginator) && ($this->table_only_paginator))
            {?>
                    <ul class="table_only_paginitor">
                        <?php if ($this->page>2) {?>
                        <li class=""><button type="button" onclick="ticket_discussions_paginate(1)">1</button></li>
                        <?php } else
                        {?>
                        <li class="inactive">&nbsp;</li>		
                        <?php
                        }
                        if ($this->page>1) {?>
                        <li class="prev"><button type="button" onclick="ticket_discussions_paginate(<?php echo ($this->page-1);?>)"><i class="fa fa-chevron-left"></i></button></li>
                        <?php }
                        else {
                        ?>
                        <li class="inactive"><i class="fa fa-chevron-left"></i></li>
                        <?php }?>
                        <li class="active"><?php echo $this->page;?></li>
                        <?php
                        if (($this->pages-$this->page)>0) {?>
                        <li class="next"><button type="button" onclick="ticket_discussions_paginate(<?php echo ($this->page+1);?>)"><i class="fa fa-chevron-right"></i></button></li>
                        <?php
                        } else {?>
                        <li class="inactive"><i class="fa fa-chevron-right"></i></li>
                        <?php
                        }?>
                        <?php
                        if (($this->pages-$this->page)>1) {?>
                        <li class="next"><button type="button" onclick="ticket_discussions_paginate(<?php echo $this->pages;?>)"><?php echo $this->pages;?></button></li>
                        <?php
                        } else {?>
                        <li class="inactive">&nbsp;</li>
                        <?php
                        }?>
                   </ul>
            <?php
            } else 
            {
                ?>
                <div align='center' class='paginator' style='' >
                <input class='page_first first ' type='button' value='1' name='first' onclick='ticket_discussions_paginate(1)' align='center'></input> 
                <?php
                if ($this->page>2)
                {?>
                    <input type='button'  value='<?php echo ($this->page-1);?>' name='prev_page' class='left ' onclick='ticket_discussions_paginate(<?php echo ($this->page-1);?>)' align='center'></input>
                <?php
                } else
                {?>
                    <input type='button'  value='' name='page' class='left '  align='center'></input>
                <?php
                }?>
                <input class='no_hover_effect center' type='button' disabled class='' value='<?php echo $this->page;?>'></input>
                <?php
                if ($this->page<$this->pages-1)
                {?>
                    <input class='page_next right' type='button' name='next_page' align='center' value='<?php echo ($this->page+1);?>' onclick='ticket_discussions_paginate(<?php echo ($this->page+1);?>)' ></input>
                <?php
                } else
                {?>
                    <input class='page_next right' type='button' name='page' align='center' value=''  ></input>
                <?php
                }?>    
                <input type='button' class='page_last last ' value='<?php echo $this->pages;?>'  name='last_page' onclick='ticket_discussions_paginate(<?php echo $this->pages;?>)' align='center'></input>
                <input type='hidden' page='1' name="current_page" />
                </div>
            <?php
            }
        }?>
            </div>
            <div class="column items" >
                    <?php
                    $start = ($this->page-1)*$this->items_per_page + 1;
                    $end = $start+count($this->ticket_discussions)-1;
                    ?>
                    <div class="span5 dataTables_info" id="tableInfo">Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $this->total_db_items;?> entries</div>            
            </div>
        </div>
<?php
    }

}
