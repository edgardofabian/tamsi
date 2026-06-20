<?php

require_once 'DeviceViewBase.php';
class DeviceView extends DeviceViewBase
{
    //functions you can override
    /*
    public function renderId()
    {
        $class='';
        $attrs='';
        foreach($this->id_attributes as $name=>$value)
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

        $value=$this->device->getId();
        if ($this->hidden_id || $this->readonly_id)
        {
            echo "<input $attrs type='hidden' name='id'  value='$value' />";
            if ($this->readonly_id)
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->edit_id)
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='id' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='id' value='".$value."' ></input>";
            }
        }
    }
    public function renderName()
    {
        $class='';
        $attrs='';
        foreach($this->name_attributes as $name=>$value)
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

        $value=$this->device->getName();
        if ($this->hidden_name || $this->readonly_name)
        {
            echo "<input $attrs type='hidden' name='name'  value='$value' />";
            if ($this->readonly_name)
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->edit_name)
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='name' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='name' value='".$value."' ></input>";
            }
        }
    }
    public function renderOwner()
    {
        $class='';
        $attrs='';
        foreach($this->owner_attributes as $name=>$value)
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

        $value=$this->device->getOwner();
        if ($this->hidden_owner || $this->readonly_owner)
        {
            echo "<input $attrs type='hidden' name='owner'  value='$value' />";
            if ($this->readonly_owner)
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->edit_owner)
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='owner' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='owner' value='".$value."' ></input>";
            }
        }
    }
    public function renderDescriptionTextarea()
    {
        $class='';
        $attrs='';
        foreach($this->description_attributes as $name=>$value)
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

        $value=$this->device->getDescription();
        if ($this->hidden_description || $this->readonly_description)
        {
            echo "<input $attrs type='hidden' name='description'  value='$value' />";
            if ($this->readonly_description)
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->edit_description)
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_description' $attrs class='$class  edit_input' name='description' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_description' $attrs type='text' class='$class ' name='description' >".$value."</textarea>";
            }
        }
    }
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
    }
    
    public function init_edit()
    { 
        $this->security=new Security();
        if (array_key_exists("id",$_GET))
        {
            $this->id=$_GET["id"];
        } else
        {
            $this->id=$_REQUEST["id"];
        }
        $this->device = new Device();
        $this->device->find(array("id"=>$this->id));
        $this->page=$_REQUEST["selected"];
        $this->path=$_REQUEST["path"];
        $this->current_user=new User();
        $this->current_user->find(array("id"=>$_SESSION["user_id"]));
        $this->command = 'edit';
        
        $this->edit_form_attributes=''; //attributes added to the form
        $this->edit_button_name='Update'; //Visible name of the Edit submit button
        $this->edit_button_url='?command=editRest&object=Device'; //url of the controller where edit form will be posted by ajax
        $this->edit_button_next_url='?command=display_rest&path=Admin.dir/Devices.dir/Edit Device.hid.rest.php&id='.$this->device->getId(); //url that will be visited after completing the edit submission
        $this->edit_delete_button_url='?command=deleteRest&object=Device'; //url of the controller where delete form will be submitted
        $this->edit_delete_button_next_url='?command=display_rest&path=Admin.dir/Devices.dir/List Devices.rest.php'; //url that will be visited after completing the delete submission

        
        $this->edit_id=true;// allow id input to transition from lock to edit mode
        $this->readonly_id=false;// set the id as read only
        $this->hidden_id=false;// set the id as hidden
        $this->id_attributes=array();// attributes such as id, style etc that will be added to the id input
        $this->id_option_filters=array();// filters for selection of dropdown options
        $this->edit_remove_id=false;// remove the id input from the edit form
        $this->edit_name=true;// allow name input to transition from lock to edit mode
        $this->readonly_name=false;// set the name as read only
        $this->hidden_name=false;// set the name as hidden
        $this->name_attributes=array();// attributes such as id, style etc that will be added to the name input
        $this->name_option_filters=array();// filters for selection of dropdown options
        $this->edit_remove_name=false;// remove the name input from the edit form
        $this->edit_owner=true;// allow owner input to transition from lock to edit mode
        $this->readonly_owner=false;// set the owner as read only
        $this->hidden_owner=false;// set the owner as hidden
        $this->owner_attributes=array();// attributes such as id, style etc that will be added to the owner input
        $this->owner_option_filters=array();// filters for selection of dropdown options
        $this->edit_remove_owner=false;// remove the owner input from the edit form
        $this->edit_description=true;// allow description input to transition from lock to edit mode
        $this->readonly_description=false;// set the description as read only
        $this->hidden_description=false;// set the description as hidden
        $this->description_attributes=array();// attributes such as id, style etc that will be added to the description input
        $this->description_option_filters=array();// filters for selection of dropdown options
        $this->edit_remove_description=false;// remove the description input from the edit form
//        $this->description_attributes["class"]="jwysiwyg";
        $this->edit_created=true;// allow created input to transition from lock to edit mode
        $this->readonly_created=false;// set the created as read only
        $this->hidden_created=false;// set the created as hidden
        $this->created_attributes=array();// attributes such as id, style etc that will be added to the created input
        $this->created_option_filters=array();// filters for selection of dropdown options
        $this->edit_remove_created=false;// remove the created input from the edit form

        
    } 
    public function init_new()
    { 
        $this->security=new Security();
        $this->device = new Device();
        $this->page=$_REQUEST["selected"];
        $this->path=$_REQUEST["path"];
        $this->current_user=new User();
        $this->current_user->find(array("id"=>$_SESSION["user_id"]));
    
        $this->command = 'new';

        $this->new_form_attributes=''; //attributes added to the new form
        $this->new_button_name='Create'; //Label of the new submit button
        $this->new_button_url='?command=newRest&object=Device'; //url of the controller where the new form will be submitted through ajax
        $this->new_button_next_url='?command=display_rest&path=Admin.dir/Devices.dir/List Devices.rest.php'; //url of the controller that will be visited after completing the new submission

        
        $this->edit_id=false;
        $this->readonly_id=false;// set the id as read only
        $this->hidden_id=false;// set the id as hidden
        $this->id_attributes=array();// attributes such as id, style etc that will be added to the id input
        $this->id_option_filters=array();// filters for selection of dropdown options
        $this->new_remove_id=false;// remove the id input from the new form
        $this->edit_name=false;
        $this->readonly_name=false;// set the name as read only
        $this->hidden_name=false;// set the name as hidden
        $this->name_attributes=array();// attributes such as id, style etc that will be added to the name input
        $this->name_option_filters=array();// filters for selection of dropdown options
        $this->new_remove_name=false;// remove the name input from the new form
        $this->edit_owner=false;
        $this->readonly_owner=false;// set the owner as read only
        $this->hidden_owner=false;// set the owner as hidden
        $this->owner_attributes=array();// attributes such as id, style etc that will be added to the owner input
        $this->owner_option_filters=array();// filters for selection of dropdown options
        $this->new_remove_owner=false;// remove the owner input from the new form
        $this->edit_description=false;
        $this->readonly_description=false;// set the description as read only
        $this->hidden_description=false;// set the description as hidden
        $this->description_attributes=array();// attributes such as id, style etc that will be added to the description input
        $this->description_option_filters=array();// filters for selection of dropdown options
        $this->new_remove_description=false;// remove the description input from the new form
        $this->edit_created=false;
        $this->readonly_created=false;// set the created as read only
        $this->hidden_created=false;// set the created as hidden
        $this->created_attributes=array();// attributes such as id, style etc that will be added to the created input
        $this->created_option_filters=array();// filters for selection of dropdown options
        $this->new_remove_created=false;// remove the created input from the new form

    } 
    public function init_list()
    { 
        $this->security=new Security();
        $valid_filters=array("id","name","owner","description","created");
        $this->page=$_REQUEST["devices_selected"];
        $this->path=$_REQUEST["devices_path"];
        $this->list_form_attributes='';
        
        $this->command = 'list';
        $this->list_button_new_url='?command=display_rest&path=Admin.dir/Devices.dir/New Device.rest.php';
        $this->list_button_new_name='New';
		$this->form_list='form_list';
		$this->action='?command=display_php&path=Admin.dir/Devices.dir/List Devices.rest.php';


        if (!$this->container) $this->container='div.page';
        $device = new Device();
        if (!isset($_SESSION['list_devices']))
        {
            $_SESSION['list_devices']=array();
            $_SESSION['list_devices']['sort_filters']=array();
            $_SESSION['list_devices']['sort_order']='';
            $_SESSION['list_devices']['select_filters']=array();
            $_SESSION['list_devices']['search_texts']=array();
            $_SESSION['list_devices']['page']='1';
        }

        if (isset($_REQUEST['devices_sort_filters']))
        {
            $sort_lists = $_REQUEST['devices_sort_filters'];
            $_SESSION['list_devices']['sort_filters']=$sort_lists;
        }
        else
        {
            $sort_lists=$_SESSION['list_devices']['sort_filters'];
        }

        if (isset($_REQUEST['devices_sort_order']))
        {
            $sort_order = $_REQUEST['devices_sort_order'];
            $_SESSION['list_devices']['sort_order']=$sort_order;
        }
        else
        {
            $sort_order = $_SESSION['list_devices']['sort_order'];
        }
        
        if (!$sort_lists) 
        {
            $sort_lists = array('id'=>'DESC');
            $sort_order = 'id';
        }
        
        $this->sort_filters = array();
        if ($sort_order)
        {
            $this->sort_orders = explode(',',$sort_order);
            foreach ($this->sort_orders as $s_order)
            {
                $this->sort_filters[$s_order]=$sort_lists[$s_order];
            }
        } else $this->sort_orders = array();

		if (!isset($this->select_filters)) $this->select_filters=array();
        if (isset($_REQUEST['devices_select_filters']))
        {
            foreach($_REQUEST['devices_select_filters'] as $key=>$value)
            {
                if (!(is_numeric($key)) && in_array($key,$valid_filters))
                {
                    $this->select_filters[$key] = $value;
                }
            }
            $_SESSION['list_devices']['select_filters']=$this->select_filters;
        }
        else
        {
            $this->select_filters=array_merge($_SESSION['list_devices']['select_filters'],$this->select_filters);
        }
        
        




        $search='';
        if (isset($_REQUEST['devices_search_texts']))
        {
            $this->search_texts = $_REQUEST['devices_search_texts'];
            $_SESSION['list_devices']['search_texts']=$this->search_texts;
        }
        else
        {
            $this->search_texts = $_SESSION['list_devices']['search_texts'];
        }
        if (is_array($this->search_texts))
        {
            foreach($this->search_texts as $field=>$value)
            {
                if ((trim($value)) && in_array($field,$valid_filters))
                {
                    if ($search)
                    {
                        $search.=" AND $field like '%$value%' ";
                    }
                    else
                    {
                        $search=" $field like '%$value%' ";
                    }
                }
            }
        }
        if ($search)
        {
            $this->select_filters[] = $search;
        }


        if (!($this->item_per_page)) $this->item_per_page = 30;
        $this->pages=$device->getPageCount($this->select_filters,$this->sort_filters,$this->item_per_page,true);
        
        $this->page = 1;//default to 1 in case not provided below
        if (isset($_REQUEST['devices_page']))
        {
            $this->page = $_REQUEST['devices_page'];
            $_SESSION['list_devices']['page']=$this->page;
        } else
        {
            $this->page = $_SESSION['list_devices']['page'];
            if (array_key_exists('list_devices',$_SESSION) && array_key_exists('page',$_SESSION['list_devices']))
            {
                $this->page = $_SESSION['list_devices']['page'];
            }            
        }
        if (($this->page < 1) || ($this->page > $this->pages)) $this->page = 1;

        
        $this->devices = $device->findAll($this->select_filters,$this->sort_filters,$this->item_per_page,$this->page);
    } 
    public function renderPaginator()
    { 
        include 'PaginatorView.php'; 
        include 'PaginatorController.php'; 
    } 
    public function renderCsrf()
    { 
        echo '<input type="hidden" name="'.$this->security->getCsrfName().'" value="'.$this->security->getCsrfValue().'"/>'; 
    } 
    public function renderActions($device,$class="",$edit=TRUE)
    {
        if ($edit)
        {
            echo "<a class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Devices.dir&path=Edit Device.hid.rest.php&id=".$device->getId()."&object=Device' href='#' ><li class='fa fa-pencil'></li> edit</a>"; 
        } else
        {
            echo "<a class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Devices.dir&path=View Device.hid.rest.php&id=".$device->getId()."&object=Device' href='#' ><li class='fa fa-eye'></li> view</a>"; 
        }
    }
    
	public function renderEditControls()
    {
        if ($this->edit_button_next_url)
        { 
            $next_after_update='mx-next="'.$this->edit_button_next_url.'"';
        } else $next_after_update='';
        if ($this->edit_delete_button_next_url)
        {
            $next_after_delete='mx-next="'.$this->edit_delete_button_next_url.'"';
        } else $next_after_delete='';
		echo '<div class="control" style="height:80px">';
		echo '<a class="button mx-" mx-click="'.$this->edit_button_url.'"  href="#" mx-container="div.page" '.$next_after_update.' ><li class="fa fa-save"></li> '.$this->edit_button_name.'</a>';
		echo '<a class="button mx- danger" mx-confirm="Are you sure to delete?" mx-click="'.$this->edit_delete_button_url.'" href="#" mx-container="'.$this->container.'" '.$next_after_delete.' ><li class="fa fa-trash" ></li> Delete </a>';
		echo '</div>';
	}    
	
	public function renderNewControls()
	{
        if ($this->new_button_next_url)
        {
            $next_after_create='mx-next="'.$this->new_button_next_url.'"';
        } else
        {
            $next_after_create='';
        }
		echo '<div class="control" style="height:80px">';
		echo '<a class="button mx-" mx-click="'.$this->new_button_url.'" href="#" '.$next_after_create.' mx-container="'.$this->container.'"><li class="fa fa-plus"></li> '.$this->new_button_name.' </a>';
		echo '</div>';
	}
    
    public function renderListControls($new_after_new='')
    {
        echo '<div class="control" style="height:80px">'."\r\n";
        echo '<a class="button mx-" mx-container="'.$this->container.'" mx-click="'.$this->list_button_new_url.'" href="#" ><i class="fa fa-plus"></i> '.$this->list_button_new_name.'</a>'."\r\n";
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
    
    public function renderSearchText($field,$type)
    {
		echo '<div class="search_filter_header">';
		$value='';
		if (isset($this->search_texts) && array_key_exists($field,$this->search_texts)) $value=$this->search_texts[$field];
		echo '<input class="search_text '.$type.'" type="text" name="[TABLE_NAME]_search_texts['.$field.']" value="'.$value.'"></input><a class="search_text_button" ><li class="fa fa-search"></li></a>';
		echo '</div>';
    }

*/

}
