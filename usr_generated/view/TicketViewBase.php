<?php
require_once 'ViewBase.php';
require_once 'Ticket.php';
require_once 'User.php';
class TicketViewBase extends ViewBase 
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
    public $ticket;
    public $page;
    public $path;
    public $tickets;
    public $container;
    public $item_per_page;
    public $pages;
    public $search_text;
    public $select_filters;
    public $sort_filters;
    public $sort_orders;
    public function get_status($ticket)
    {
        echo $ticket->getStatus();
    }
    public function get_user_id_submitter($ticket)
    {
         echo $ticket->getSubmitter();
    }
    public function get_description($ticket)
    {
        echo $ticket->getDescription();
    }
    public function get_created($ticket)
    {
        echo $ticket->getCreated();
    }
    public function render_id()
    {
        $class='';
        $attrs='';
        $ticket_field='id';
        foreach($this->columns[$ticket_field]['attributes'] as $name=>$value)
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

        $value=($this->ticket->getId())?htmlentities($this->ticket->getId()):'';
        if ($this->columns[$ticket_field]['type']=='boolean')
        {
            $this->render_boolean($ticket_field,$value);
        }
        else if (($this->columns[$ticket_field]['type']=='hidden') || ($this->columns[$ticket_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='id'  value='$value' />";
            if ($this->columns[$ticket_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$ticket_field]['mode']=='edit')
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
	
    public function render_status()
    {
		$class='';
        $attrs='';
        $ticket_field='status';
        foreach($this->columns[$ticket_field]['attributes'] as $name=>$value)
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
    
        $val=$this->ticket->getStatus();
		if ($this->columns[$ticket_field]['type']=='boolean')
        {
            $this->render_boolean($ticket_field,$value);
        }
        else if (($this->columns[$ticket_field]['type']=='hidden') || ($this->columns[$ticket_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='status'  value='$val' />";
            if ($this->columns[$ticket_field]['type']=='readonly')
            {
                echo "<span >$val</span>";
            }
        } else
        {
            if ($this->columns[$ticket_field]['mode']=='edit')
            {
                echo "<select $attrs class='$class edit_input'  name='status' >\n";
            } else
            {
                echo "<select $attrs class='$class'  name='status' >\n";
            } 
			$options=array("active"=>"active","closed"=>"closed","cancelled"=>"cancelled");
            if (array_key_exists('[FILTER_FIELD]',$this->columns) && array_key_exists('options',$this->columns['[FILTER_FIELD]']) && ($this->columns['[FILTER_FIELD]']['options']))
            {
                $options = $this->columns['[FILTER_FIELD]']['options'];
            }            
			foreach($options as $option=>$label)
			{
				if ($val==$option)
				{
					echo "<option selected='selected' value='$option' >$label</option>\n";
				} else
				{
					echo "<option value='$option' >$label</option>\n";
				}
			}
			echo "</select>\n";
            if ($this->columns[$ticket_field]['mode']=='edit') echo "<span class='view_input'>$val</span>";
            
        }
    }
    public function render_status_filter()
    {
		echo "<select  class='filter' name='tickets_select_filters[status]' value='' > \r\n";
        echo "<option value=''>All</option> \r\n";
		$options=array("active"=>"active","closed"=>"closed","cancelled"=>"cancelled");
        if (array_key_exists('status',$this->columns) && array_key_exists('options',$this->columns['status']) && ($this->columns['status']['options']))
        {
            $options = $this->columns['status']['options'];
        } 
        foreach($options as $option=>$label)
		{
			if ($option)
            {
                if ((array_key_exists('status',$this->select_filters)) && ($this->select_filters['status']==$option))
                {
                    echo "<option selected='selected' value='".$option."' >".$label."</option>";
                } 
                else
                {
                    echo "<option value='".$option."' >".$label."</option>";
                }
            }
		}
		echo "</select> \r\n";		
	}
	
    public function render_user_id_submitter()
    {
        $sids_str=$this->ticket->getUserIdSubmitter();
        
        $class='';
        $attrs='';
        $ticket_field='user_id_submitter';
        foreach($this->columns[$ticket_field]['attributes'] as $name=>$value)
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
        $value=($this->ticket->getUserIdSubmitter())?htmlentities($this->ticket->getUserIdSubmitter()):'';
        
        if ($this->columns[$ticket_field]['type']=='boolean')
        {
            $this->render_boolean($ticket_field,$value);
        }
        else if (($this->columns[$ticket_field]['type']=='hidden') || ($this->columns[$ticket_field]['type']=='readonly'))
        {
            echo "<input type='hidden' $attrs name='user_id_submitter'  value='$sids_str' />";
            if ($this->columns[$ticket_field]['type']=='readonly')
            {
                echo "<span >".$this->ticket->getSubmitter()."</span>";
            }
        } else
        {
            if ($this->columns[$ticket_field]['mode']=='edit')
            {
                echo "<select $attrs class='$class edit_input'  name='user_id_submitter' >\n";
            } else
            {
                echo "<select $attrs class='$class'  name='user_id_submitter' >\n";
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
            
                $submitter = new User();
                $param=array();
                if (array_key_exists('option_filters',$this->columns[$ticket_field]) && $this->columns[$ticket_field]['option_filters'])
                {
                    $param['filter'] = $this->columns[$ticket_field]['option_filters'];
                }
                if (array_key_exists('option_sort',$this->columns[$ticket_field]) && $this->columns[$ticket_field]['option_sort'])
                {
                    $param['sort'] = $this->columns[$ticket_field]['option_sort'];
                }
                if (array_key_exists('option_select',$this->columns[$ticket_field]) && $this->columns[$ticket_field]['option_select'])
                {
                    $param['select'] = $this->columns[$ticket_field]['option_select'];
                }    
                
                $objs=$submitter->getAll($param);
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
            if ($this->columns[$ticket_field]['mode']=='edit') echo "<span class='view_input'>".implode(',',$data_names)."</span>";
       }
    }
    public function render_user_id_submitter_filter()
    {
		echo "<select  class='filter' name='tickets_select_filters[user_id_submitter]' value='' > \r\n";
		echo "<option value=''>All</option> \r\n";
	
		if (in_array('0',$this->distinct_user_id_submitter_lst)) 
		{
			if (array_key_exists('user_id_submitter',$this->select_filters) && ($this->select_filters['user_id_submitter']=='0'))
			{
				echo "<option selected='selected' value='0' >Unassigned</option>";
			} else
			{
				echo "<option value='0' >Unassigned</option>";
			}
		}
		$user_handle = new User();
		$users = $user_handle->findAll(array('id'=>$this->distinct_user_id_submitter_lst),array('lastname'=>'ASC'));
		foreach ($users as $user)
		{
			if (($this->select_filters['user_id_submitter']) && ($this->select_filters['user_id_submitter']==$user->getId()))
			{
				echo "<option selected='selected' value='".$user->getId()."' >".$user->getName()."</option>";
			} else
			{
				echo "<option value='".$user->getId()."' >".$user->getName()."</option>";
			}
		}
		echo "</select> \r\n";		
	}
	
    public function render_description()
    {
        $class='';
        $attrs='';
        $ticket_field='description';
        foreach($this->columns[$ticket_field]['attributes'] as $name=>$value)
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

        $value=($this->ticket->getDescription())?htmlentities($this->ticket->getDescription()):'';
        if (($this->columns[$ticket_field]['type']=='hidden') || ($this->columns[$ticket_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='description'  value='$value' />";
            if ($this->columns[$ticket_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$ticket_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_description' $attrs class='$class  edit_input' name='description' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_description' $attrs type='text' class='$class ' name='description' >".$value."</textarea>";
            }
        }
    }
    public function render_description_filter()
    {
        $this->renderSearchText('description','textarea');
	}
	
    public function render_created()
    {
        $class='';
        $attrs='';
        $ticket_field='created';
        foreach($this->columns[$ticket_field]['attributes'] as $name=>$value)
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

        $value=($this->ticket->getCreated())?htmlentities($this->ticket->getCreated()):'';
        if ($this->columns[$ticket_field]['type']=='boolean')
        {
            $this->render_boolean($ticket_field,$value);
        }
        else if (($this->columns[$ticket_field]['type']=='hidden') || ($this->columns[$ticket_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='created'  value='$value' />";
            if ($this->columns[$ticket_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$ticket_field]['mode']=='edit')
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
	

    public $distinct_status_lst;
    public $distinct_user_id_submitter_lst;

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
        $this->columns["status"]=array('type'=>"enum",'label'=>"Status",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["user_id_submitter"]=array('type'=>"select",'label'=>"UserIdSubmitter",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["description"]=array('type'=>"textarea",'label'=>"Description",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["created"]=array('type'=>"datetime",'label'=>"Created",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());

        $this->headers = array("#"=>array("label"=>"#","sort"=>0,"class"=>"","attributes"=>array()),"action"=>array("label"=>"Action","sort"=>0,"class"=>"","attributes"=>array()),"icon"=>array("label"=>"Icon","sort"=>0,"class"=>"","attributes"=>array()),"id"=>array("label"=>"Id ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"status"=>array("label"=>"Status ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"user_id_submitter"=>array("label"=>"Submitter ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"description"=>array("label"=>"Description ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"created"=>array("label"=>"Created ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()));
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
        $this->title = 'Edit Ticket';
        $this->edit_columns = array("id","status","user_id_submitter","description","created");

        $this->security=new TamsiSecurity();
        if (array_key_exists("id",$_GET))
        {
            $this->id=$_GET["id"];
        } else
        {
            $this->id=$_REQUEST["id"];
        }
        $this->ticket = new Ticket();
        $this->ticket->find(array("id"=>$this->id));
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
        $this->button_url='?command=editRest&object=Ticket'; //url of the controller where edit form will be posted by ajax
        $this->button_next_url='?command=display_rest&path=Admin.dir/Tickets.dir/Edit_Ticket.hid.rest.php&id='.$this->ticket->getId(); //url that will be visited after completing the edit submission
        $this->delete_button_url='?command=deleteRest&object=Ticket'; //url of the controller where delete form will be submitted
        $this->delete_button_next_url='?command=display_rest&path=Admin.dir/Tickets.dir/List_Tickets.rest.php'; //url that will be visited after completing the delete submission

        
        $this->columns["id"]['mode']="edit";
        $this->columns["status"]['mode']="edit";
        $this->columns["status"]["attributes"]["class"]="chosen";
        $this->columns["user_id_submitter"]['mode']="edit";
        $this->columns["user_id_submitter"]["attributes"]["class"]="chosen";
        $this->columns["description"]['mode']="edit";
        $this->columns["created"]['mode']="edit";

        
        
    } 
    public function init_new()
    { 
        $this->title = 'New Ticket';
        $this->new_columns = array("id","status","user_id_submitter","description","created");
        
        $this->security=new TamsiSecurity();
        $this->ticket = new Ticket();
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
        $this->button_url='?command=newRest&object=Ticket'; //url of the controller where the new form will be submitted through ajax
        $this->button_next_url='?command=display_rest&path=Admin.dir/Tickets.dir/List_Tickets.rest.php'; //url of the controller that will be visited after completing the new submission

        
        $this->columns["id"]['mode']="new";
        $this->columns["status"]['mode']="new";
        $this->columns["status"]["attributes"]["class"]="chosen";
        $this->columns["user_id_submitter"]['mode']="new";
        $this->columns["user_id_submitter"]["attributes"]["class"]="chosen";
        $this->columns["description"]['mode']="new";
        $this->columns["created"]['mode']="new";

        
    } 
    public function init_list_params()
    { 
        $this->security=new TamsiSecurity();
        $valid_filters=array("id","status","user_id_submitter","description","created");
        if ($_REQUEST)
        {
            if (array_key_exists("tickets_selected",$_REQUEST))
            {
                $this->page=$_REQUEST["tickets_selected"];
            }
            if (array_key_exists("tickets_path",$_REQUEST))
            {
                $this->path=$_REQUEST["tickets_path"];
            }
        }
        
        if (!$this->container) $this->container='div.page';
        $this->list_columns = array_merge(array('#','action'),$valid_filters);
        $this->list_form_attributes='';
        $this->custom_html_list='';
        $this->command = 'list';
        $this->list_controls = array('new'=>array('label'=>'New','url'=>'?command=display_rest&path=Admin.dir/Tickets.dir/New_Ticket.rest.php','container'=>$this->container));
		$this->form_list='form_list_tickets';
		$this->action='?command=display_php&path=Admin.dir/Tickets.dir/List_Tickets.rest.php';
        $this->list_delete_button_url = '?command=deleteRest&object=Ticket'; //url of the controller where delete form will be submitted
        $this->list_delete_button_next_url = '?command=display_rest&base_path=Admin.dir/Tickets.dir&path=List_Tickets.rest.php';
        $this->list_delete_selected_url = '?command=deleteSelectedRest&object=Ticket'; //url of the controller where delete of selected objects will be submitted
        $this->enable_paginator = true;
        $this->usr=array();
        $ticket = new Ticket();
        if (!isset($_SESSION['list_tickets']))
        {
            $_SESSION['list_tickets']=array();
            $_SESSION['list_tickets']['sort_filters']=array();
            $_SESSION['list_tickets']['sort_order']='';
            $_SESSION['list_tickets']['select_filters']=array();
            $_SESSION['list_tickets']['search_texts']=array();
            $_SESSION['list_tickets']['page']='1';
        }

        if (isset($_REQUEST['tickets_sort_filters']))
        {
            $sort_lists = $_REQUEST['tickets_sort_filters'];
            $_SESSION['list_tickets']['sort_filters']=$sort_lists;
        }
        else
        {
            $sort_lists=$_SESSION['list_tickets']['sort_filters'];
        }

        if (isset($_REQUEST['tickets_sort_order']))
        {
            $sort_order = $_REQUEST['tickets_sort_order'];
            $_SESSION['list_tickets']['sort_order']=$sort_order;
        }
        else
        {
            $sort_order = $_SESSION['list_tickets']['sort_order'];
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
        if (isset($_REQUEST['tickets_select_filters']))
        {
            foreach($_REQUEST['tickets_select_filters'] as $key=>$value)
            {
                if (!(is_numeric($key)) && in_array($key,$valid_filters))
                {
                    $this->select_filters[$key] = $value;
                }
            }
            $_SESSION['list_tickets']['select_filters']=$this->select_filters;
        }
        else
        {
            $this->select_filters=array_merge($_SESSION['list_tickets']['select_filters'],$this->select_filters);
        }
        
        





        $this->distinct_status_lst=array();

		$res = array('active','closed','cancelled');

        foreach ($res as $item)
        {
			$status=$item;
			$this->distinct_status_lst[$status]=$status;
        }

        $this->distinct_user_id_submitter_lst=array();

		$res = $ticket->query('SELECT DISTINCT user_id_submitter from tickets');

        foreach ($res as $item)
        {
			$user_id_submitter=$item['user_id_submitter'];
			$this->distinct_user_id_submitter_lst[$user_id_submitter]=$user_id_submitter;
        }




        $search='';
        if (isset($_REQUEST['tickets_search_texts']))
        {
            $this->search_texts = $_REQUEST['tickets_search_texts'];
            $_SESSION['list_tickets']['search_texts']=$this->search_texts;
        }
        else
        {
            $this->search_texts = $_SESSION['list_tickets']['search_texts'];
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


		$this->ticket = $ticket;
    }
    
    public function load_list()
    { 
        if (!($this->items_per_page)) $this->items_per_page = 25;
        if (isset($_REQUEST['tickets_items_per_page']))
        {
            $this->items_per_page = $_REQUEST['tickets_items_per_page'];
            $_SESSION['list_tickets']['items_per_page']=$this->items_per_page;
        } else
        {
            if (array_key_exists('list_tickets',$_SESSION) && array_key_exists('items_per_page',$_SESSION['list_tickets']))
            {
                $this->items_per_page = $_SESSION['list_tickets']['items_per_page'];
            }            
        }
        
        $this->pages=$this->ticket->getPageCount($this->select_filters,$this->sort_filters,$this->items_per_page,true);
        $this->total_db_items = $this->ticket->total_db_items;
        $this->page = 1;//default to 1 in case not provided below
        if (isset($_REQUEST['tickets_page']))
        {
            $this->page = $_REQUEST['tickets_page'];
            $_SESSION['list_tickets']['page']=$this->page;
        } else
        {
            if (array_key_exists('list_tickets',$_SESSION) && array_key_exists('page',$_SESSION['list_tickets']))
            {
                $this->page = $_SESSION['list_tickets']['page'];
            }            
        }
        if (($this->page < 1) || ($this->page > $this->pages)) $this->page = 1;

        
        $this->tickets = $this->ticket->findAll($this->select_filters,$this->sort_filters,$this->items_per_page,$this->page);
    }
    
    public function init_list()
    { 
        $this->title = 'Tickets';
        $this->init_list_params();
        $this->load_list();
    } 
    

    public function renderDeleteCheckbox($ticket)
    {
        echo '<input type="checkbox" class="select_checkbox" id="'.$ticket->getId().'" value="'.$ticket->getId().'" name="delete_selected[]" /><br>';
    }
    
    public function renderEditAction($ticket)
    {
        echo "<button type='button' class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Tickets.dir&path=Edit_Ticket.hid.rest.php&id=".$ticket->getId()."&object=Ticket' href='#' ><li class='fa fa-pencil'></li> edit</button>";
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
    
    public function renderDeleteAction($ticket,$next_url,$delete_url)
    {
        $mx_next_url='';
        if ($next_url)
        {
            $mx_next_url = 'mx-next="'.$next_url.'"';
        }
        echo '<button type="button" class="button mx- danger" mx-confirm="Are you sure to delete Ticket of id='.$ticket->getId().'?" mx-click="'.$delete_url.'&id='.$ticket->getId().'" href="#" mx-container="'.$this->container.'" '.$mx_next_url.' ><li class="fa fa-trash" ></li> del</button>';
    }
    
    public function renderViewAction($ticket)
    {
        echo "<button type='button' class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Tickets.dir&path=View_Ticket.hid.rest.php&id=".$ticket->getId()."&object=Ticket' href='#' ><li class='fa fa-eye'></li> view</button>";
    }

    public function renderListAction()
    {
        echo "<button type='button' class='button mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Tickets.dir&path=List_Tickets.rest.php&object=Ticket'  ><li class='fa fa-list'></li> List</button>";
    }

    public function renderActions($ticket,$class="",$edit=TRUE)
    {
        if ($edit)
        {
            $this->renderDeleteCheckbox($ticket);
            $this->renderEditAction($ticket);
            $this->renderDeleteAction($ticket,$this->list_delete_button_next_url,$this->list_delete_button_url);
        } else
        {
            $this->renderViewAction($ticket);
        }
    }
    
	public function renderEditControls()
    {
		echo '<div class="control">';		
		$this->renderSaveAction($this->button_next_url);
        $this->renderDeleteAction($this->ticket,$this->delete_button_next_url,$this->delete_button_url);
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
    
   public function get_icon($ticket)
   {
		echo '<img class="icon" src="'.$this->paths->base_url.'/img/displaypng.php?id='.$_SESSION['user_id'].'&image='.$this->paths->user_image.'/tickets/'.$ticket->getId().'_tickets_icon.png&key='.$this->current_user->getSecurekey().'" />';
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
		echo '<input class="search_text '.$type.'" type="text" name="tickets_search_texts['.$field.']" value="'.$value.'"></input><button type="button" class="search_text_button clear" ><li class="fa fa-times"></li></button>';
		echo '</div>';
    }
    
    public function get_id($ticket)
    {
        echo "<button type='button' class='mx-' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Tickets.dir&path=Edit_Ticket.hid.rest.php&id=".$ticket->getId()."&object=Ticket'  ><i class='fa fa-edit'></i>".$ticket->getId()."</button>";
    }
    
    public function renderPaginator()
    {?>
        <script>
            <?php
            if (!isset($this->container)) $this->container = 'div.page';
            if (!isset($this->form_list)) $this->form_list = 'form_list_tickets';
            ?>
            function tickets_paginate(page) 
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
                form.find('input.field_items_per_page').val($('#tickets_items_per_page').val());
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
                <select name="tickets_items_per_page" id="tickets_items_per_page" style="width:60px" onchange="tickets_paginate(<?php echo $this->page;?>)">
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
                        <li class=""><button type="button" onclick="tickets_paginate(1)">1</button></li>
                        <?php } else
                        {?>
                        <li class="inactive">&nbsp;</li>		
                        <?php
                        }
                        if ($this->page>1) {?>
                        <li class="prev"><button type="button" onclick="tickets_paginate(<?php echo ($this->page-1);?>)"><i class="fa fa-chevron-left"></i></button></li>
                        <?php }
                        else {
                        ?>
                        <li class="inactive"><i class="fa fa-chevron-left"></i></li>
                        <?php }?>
                        <li class="active"><?php echo $this->page;?></li>
                        <?php
                        if (($this->pages-$this->page)>0) {?>
                        <li class="next"><button type="button" onclick="tickets_paginate(<?php echo ($this->page+1);?>)"><i class="fa fa-chevron-right"></i></button></li>
                        <?php
                        } else {?>
                        <li class="inactive"><i class="fa fa-chevron-right"></i></li>
                        <?php
                        }?>
                        <?php
                        if (($this->pages-$this->page)>1) {?>
                        <li class="next"><button type="button" onclick="tickets_paginate(<?php echo $this->pages;?>)"><?php echo $this->pages;?></button></li>
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
                <input class='page_first first ' type='button' value='1' name='first' onclick='tickets_paginate(1)' align='center'></input> 
                <?php
                if ($this->page>2)
                {?>
                    <input type='button'  value='<?php echo ($this->page-1);?>' name='prev_page' class='left ' onclick='tickets_paginate(<?php echo ($this->page-1);?>)' align='center'></input>
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
                    <input class='page_next right' type='button' name='next_page' align='center' value='<?php echo ($this->page+1);?>' onclick='tickets_paginate(<?php echo ($this->page+1);?>)' ></input>
                <?php
                } else
                {?>
                    <input class='page_next right' type='button' name='page' align='center' value=''  ></input>
                <?php
                }?>    
                <input type='button' class='page_last last ' value='<?php echo $this->pages;?>'  name='last_page' onclick='tickets_paginate(<?php echo $this->pages;?>)' align='center'></input>
                <input type='hidden' page='1' name="current_page" />
                </div>
            <?php
            }
        }?>
            </div>
            <div class="column items" >
                    <?php
                    $start = ($this->page-1)*$this->items_per_page + 1;
                    $end = $start+count($this->tickets)-1;
                    ?>
                    <div class="span5 dataTables_info" id="tableInfo">Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $this->total_db_items;?> entries</div>            
            </div>
        </div>
<?php
    }

    public $src_;
    public $src__icon;
    public function renderUploadImage($ticket,$disable__icon=FALSE,$redirect_after_image_upload='',$img_attributes='',$edit_enabled=TRUE)
    {
        if (!isset($this->src_)) $this->src_=$this->paths->base_url.'/img/displaypng.php?id='.$this->current_user->getId().'&image='.$this->ticket->getImage('').'&key='.urlencode($this->current_user->getSecurekey()).'&rnd='.rand(1,100);    
        if (!isset($this->src__icon)) $this->src__icon='img/displaypng.php?id='.$this->current_user->getId().'&image='.$this->ticket->getImageIcon('').'&key='.urlencode($this->current_user->getSecurekey()).'&rnd='.rand(1,100);    
        if ($edit_enabled)
        {
            echo '<form id="upload_" action="<?php echo $this->paths->base_url;?>/?command=uploadImage&object=Ticket&id='.$ticket->getId().'" class="form image round-corners" method="post" style="float:center; display:block" enctype="multipart/form-data" >';
        }
        echo '<div>
                <div class="profile_container" >
                    <img class="profile"  src="'.$this->src_.'" '.$img_attributes.'/>
                </div>';
                if (!(isset($disable__icon) && $disable__icon))
                {
                    echo '<div style="display:inline-block">';
                    echo '<img class="profile_icon"  src="'.$this->src__icon.'" />';
                    echo '<label style="display:block">Icon</label>';
                    echo '</div>';
                }
        if ($edit_enabled)
        {
                $this->renderCsrf();
            echo '<input type="file" name="file" id="file" class="button fa fa-plus" accept="image/*" />';
            echo '<input type="hidden" name="id" value="'.$this->ticket->getId().'" />';
            echo '<input type="hidden" name="redirect" value="'.$redirect_after_image_upload.'" />';
            echo '<textarea id="upload_content" type="submit" style="display:none" name="upload_content" value=""></textarea>';
            echo '<button type="submit" class="button" name="submit"><i class="fa fa-upload"></i>Upload</button>';
        }
        
            echo '</div>';
        if ($edit_enabled)
        {
            echo '</form>';
        }
    }

    public function renderImage($ticket)
    {
        echo '<span>'.$ticket->getImage().". (See upload button above this table)</span>\n";
    }
}
