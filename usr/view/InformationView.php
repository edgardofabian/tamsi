<?php

require_once 'InformationViewBase.php';
class InformationView extends InformationViewBase
{
    //functions you can override
    /*
    public function render_id()
    {
        $class='';
        $attrs='';
        $information_field='id';
        foreach($this->columns[$information_field]['attributes'] as $name=>$value)
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

        $value=($this->information->getId())?htmlentities($this->information->getId()):'';
        if ($this->columns[$information_field]['type']=='boolean')
        {
            $this->render_boolean($information_field,$value);
        }
        else if (($this->columns[$information_field]['type']=='hidden') || ($this->columns[$information_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='id'  value='$value' />";
            if ($this->columns[$information_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$information_field]['mode']=='edit')
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
	
    public function render_user_id()
    {
        $sids_str=$this->information->getUserId();
        
        $class='';
        $attrs='';
        $information_field='user_id';
        foreach($this->columns[$information_field]['attributes'] as $name=>$value)
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
        $value=($this->information->getUserId())?htmlentities($this->information->getUserId()):'';
        
        if ($this->columns[$information_field]['type']=='boolean')
        {
            $this->render_boolean($information_field,$value);
        }
        else if (($this->columns[$information_field]['type']=='hidden') || ($this->columns[$information_field]['type']=='readonly'))
        {
            echo "<input type='hidden' $attrs name='user_id'  value='$sids_str' />";
            if ($this->columns[$information_field]['type']=='readonly')
            {
                echo "<span >".$information->getUser()."</span>";
            }
        } else
        {
            if ($this->columns[$information_field]['mode']=='edit')
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
                if (array_key_exists('option_filters',$this->columns[$information_field]) && $this->columns[$information_field]['option_filters'])
                {
                    $param['filter'] = $this->columns[$information_field]['option_filters'];
                }
                if (array_key_exists('option_sort',$this->columns[$information_field]) && $this->columns[$information_field]['option_sort'])
                {
                    $param['sort'] = $this->columns[$information_field]['option_sort'];
                }
                if (array_key_exists('option_select',$this->columns[$information_field]) && $this->columns[$information_field]['option_select'])
                {
                    $param['select'] = $this->columns[$information_field]['option_select'];
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
            if ($this->columns[$information_field]['mode']=='edit') echo "<span class='view_input'>".implode(',',$data_names)."</span>";
       }
    }
    public function render_user_id_filter()
    {
		echo "<select  class='filter' name='[TABLE_NAME]_select_filters[user_id]' value='' > \r\n";
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
	
    public function render_name()
    {
        $class='';
        $attrs='';
        $information_field='name';
        foreach($this->columns[$information_field]['attributes'] as $name=>$value)
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

        $value=($this->information->getName())?htmlentities($this->information->getName()):'';
        if ($this->columns[$information_field]['type']=='boolean')
        {
            $this->render_boolean($information_field,$value);
        }
        else if (($this->columns[$information_field]['type']=='hidden') || ($this->columns[$information_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='name'  value='$value' />";
            if ($this->columns[$information_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$information_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='name' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='name' value='".$value."' ></input>";
            }
        }
    }
    public function render_name_filter()
    {
        $this->renderSearchText('name','text');
	}
	
    public function render_value()
    {
        $class='';
        $attrs='';
        $information_field='value';
        foreach($this->columns[$information_field]['attributes'] as $name=>$value)
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

        $value=($this->information->getValue())?htmlentities($this->information->getValue()):'';
        if (($this->columns[$information_field]['type']=='hidden') || ($this->columns[$information_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='value'  value='$value' />";
            if ($this->columns[$information_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$information_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_value' $attrs class='$class  edit_input' name='value' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_value' $attrs type='text' class='$class ' name='value' >".$value."</textarea>";
            }
        }
    }
    public function render_value_filter()
    {
        $this->renderSearchText('value','textarea');
	}
	
    public function render_token()
    {
        $class='';
        $attrs='';
        $information_field='token';
        foreach($this->columns[$information_field]['attributes'] as $name=>$value)
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

        $value=($this->information->getToken())?htmlentities($this->information->getToken()):'';
        if (($this->columns[$information_field]['type']=='hidden') || ($this->columns[$information_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='token'  value='$value' />";
            if ($this->columns[$information_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$information_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_token' $attrs class='$class  edit_input' name='token' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_token' $attrs type='text' class='$class ' name='token' >".$value."</textarea>";
            }
        }
    }
    public function render_token_filter()
    {
        $this->renderSearchText('token','textarea');
	}
	
    public function render_salt()
    {
        $class='';
        $attrs='';
        $information_field='salt';
        foreach($this->columns[$information_field]['attributes'] as $name=>$value)
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

        $value=($this->information->getSalt())?htmlentities($this->information->getSalt()):'';
        if ($this->columns[$information_field]['type']=='boolean')
        {
            $this->render_boolean($information_field,$value);
        }
        else if (($this->columns[$information_field]['type']=='hidden') || ($this->columns[$information_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='salt'  value='$value' />";
            if ($this->columns[$information_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$information_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='salt' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='salt' value='".$value."' ></input>";
            }
        }
    }
    public function render_salt_filter()
    {
        $this->renderSearchText('salt','text');
	}
	
    public function render_active()
    {
        $class='';
        $attrs='';
        $information_field='active';
        foreach($this->columns[$information_field]['attributes'] as $name=>$value)
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

        $value=($this->information->getActive())?htmlentities($this->information->getActive()):'';
        if ($this->columns[$information_field]['type']=='boolean')
        {
            $this->render_boolean($information_field,$value);
        }
        else if (($this->columns[$information_field]['type']=='hidden') || ($this->columns[$information_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='active'  value='$value' />";
            if ($this->columns[$information_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$information_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='active' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='active' value='".$value."' ></input>";
            }
        }
    }
    public function render_active_filter()
    {
		echo "<select  class='filter' name='[TABLE_NAME]_select_filters[active]' value='' > \r\n";
        echo "<option value=''>None</option> \r\n";
        
        $true_label='active';
        if (array_key_exists('active',$this->columns) && array_key_exists('true_label',$this->columns['active'])) $true_label = $this->columns['active']['true_label'];
        $false_label='inactive';
        if (array_key_exists('active',$this->columns) && array_key_exists('false_label',$this->columns['active'])) $false_label = $this->columns['active']['false_label'];
        
		$options = array(0=>$false_label,1=>$true_label);
        foreach($options as $val=>$label)
		{
			
			if ((array_key_exists('active',$this->select_filters)) && ($this->select_filters['active']==$val))
			{
				echo "<option selected='selected' value='".$val."' >".$label."</option>";
			} 
			else
			{
				echo "<option value='".$val."' >".$label."</option>";
			}
		}
		echo "</select> \r\n";		
	}
	
    public function render_created()
    {
        $class='';
        $attrs='';
        $information_field='created';
        foreach($this->columns[$information_field]['attributes'] as $name=>$value)
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

        $value=($this->information->getCreated())?htmlentities($this->information->getCreated()):'';
        if ($this->columns[$information_field]['type']=='boolean')
        {
            $this->render_boolean($information_field,$value);
        }
        else if (($this->columns[$information_field]['type']=='hidden') || ($this->columns[$information_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='created'  value='$value' />";
            if ($this->columns[$information_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$information_field]['mode']=='edit')
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
        $this->columns["user_id"]=array('type'=>"select",'label'=>"UserId",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["name"]=array('type'=>"text",'label'=>"Name",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["value"]=array('type'=>"textarea",'label'=>"Value",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["token"]=array('type'=>"textarea",'label'=>"Token",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["salt"]=array('type'=>"text",'label'=>"Salt",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["active"]=array('type'=>"boolean",'label'=>"Active",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["created"]=array('type'=>"datetime",'label'=>"Created",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());

        $this->headers = array("#"=>array("label"=>"#","sort"=>0,"class"=>"","attributes"=>array()),"action"=>array("label"=>"Action","sort"=>0,"class"=>"","attributes"=>array()),"id"=>array("label"=>"Id ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"user_id"=>array("label"=>"Users ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"name"=>array("label"=>"Name ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"value"=>array("label"=>"Value ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"token"=>array("label"=>"Token ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"salt"=>array("label"=>"Salt ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"active"=>array("label"=>"Active ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"created"=>array("label"=>"Created ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()));
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
        $this->title = 'Edit Information';
        $this->edit_columns = array("id","user_id","name","value","token","salt","active","created");

        $this->security=new TamsiSecurity();
        if (array_key_exists("id",$_GET))
        {
            $this->id=$_GET["id"];
        } else
        {
            $this->id=$_REQUEST["id"];
        }
        $this->information = new Information();
        $this->information->find(array("id"=>$this->id));
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
        $this->button_url='?command=editRest&object=Information'; //url of the controller where edit form will be posted by ajax
        $this->button_next_url='?command=display_rest&path=Admin.dir/Informations.dir/Edit Information.hid.rest.php&id='.$this->information->getId(); //url that will be visited after completing the edit submission
        $this->delete_button_url='?command=deleteRest&object=Information'; //url of the controller where delete form will be submitted
        $this->delete_button_next_url='?command=display_rest&path=Admin.dir/Informations.dir/List Informations.rest.php'; //url that will be visited after completing the delete submission

        
        $this->columns["id"]['mode']="edit";
        $this->columns["user_id"]['mode']="edit";
        $this->columns["user_id"]["attributes"]["class"]="chosen";
        $this->columns["name"]['mode']="edit";
        $this->columns["value"]['mode']="edit";
        $this->columns["token"]['mode']="edit";
        $this->columns["salt"]['mode']="edit";
        $this->columns["active"]['mode']="edit";
        $this->columns["created"]['mode']="edit";

        
        
    } 
    public function init_new()
    { 
        $this->title = 'New Information';
        $this->new_columns = array("id","user_id","name","value","token","salt","active","created");
        
        $this->security=new TamsiSecurity();
        $this->information = new Information();
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
        $this->button_url='?command=newRest&object=Information'; //url of the controller where the new form will be submitted through ajax
        $this->button_next_url='?command=display_rest&path=Admin.dir/Informations.dir/List Informations.rest.php'; //url of the controller that will be visited after completing the new submission

        
        $this->columns["id"]['mode']="new";
        $this->columns["user_id"]['mode']="new";
        $this->columns["user_id"]["attributes"]["class"]="chosen";
        $this->columns["name"]['mode']="new";
        $this->columns["value"]['mode']="new";
        $this->columns["token"]['mode']="new";
        $this->columns["salt"]['mode']="new";
        $this->columns["active"]['mode']="new";
        $this->columns["created"]['mode']="new";

        
    } 
    public function init_list_params()
    { 
        $this->security=new TamsiSecurity();
        $valid_filters=array("id","user_id","name","value","token","salt","active","created");
        if ($_REQUEST)
        {
            if (array_key_exists("informations_selected",$_REQUEST))
            {
                $this->page=$_REQUEST["informations_selected"];
            }
            if (array_key_exists("informations_path",$_REQUEST))
            {
                $this->path=$_REQUEST["informations_path"];
            }
        }
        $this->list_columns = array_merge(array('#','action'),$valid_filters);
        $this->list_form_attributes='';
        $this->custom_html_list='';
        $this->command = 'list';
        $this->list_controls = array('new'=>array('label'=>'New','url'=>'?command=display_rest&path=Admin.dir/Informations.dir/New Information.rest.php','container'=>$this->container));
		$this->form_list='form_list_informations';
		$this->action='?command=display_php&path=Admin.dir/Informations.dir/List Informations.rest.php';
        $this->list_delete_button_url = '?command=deleteRest&object=Information'; //url of the controller where delete form will be submitted
        $this->list_delete_button_next_url = '?command=display_rest&base_path=Admin.dir/Informations.dir&path=List Informations.rest.php';
        $this->list_delete_selected_url = '?command=deleteSelectedRest&object=Information'; //url of the controller where delete of selected objects will be submitted
        $this->enable_paginator = true;

        if (!$this->container) $this->container='div.page';
        
        $information = new Information();
        if (!isset($_SESSION['list_informations']))
        {
            $_SESSION['list_informations']=array();
            $_SESSION['list_informations']['sort_filters']=array();
            $_SESSION['list_informations']['sort_order']='';
            $_SESSION['list_informations']['select_filters']=array();
            $_SESSION['list_informations']['search_texts']=array();
            $_SESSION['list_informations']['page']='1';
        }

        if (isset($_REQUEST['informations_sort_filters']))
        {
            $sort_lists = $_REQUEST['informations_sort_filters'];
            $_SESSION['list_informations']['sort_filters']=$sort_lists;
        }
        else
        {
            $sort_lists=$_SESSION['list_informations']['sort_filters'];
        }

        if (isset($_REQUEST['informations_sort_order']))
        {
            $sort_order = $_REQUEST['informations_sort_order'];
            $_SESSION['list_informations']['sort_order']=$sort_order;
        }
        else
        {
            $sort_order = $_SESSION['list_informations']['sort_order'];
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
        if (isset($_REQUEST['informations_select_filters']))
        {
            foreach($_REQUEST['informations_select_filters'] as $key=>$value)
            {
                if (!(is_numeric($key)) && in_array($key,$valid_filters))
                {
                    $this->select_filters[$key] = $value;
                }
            }
            $_SESSION['list_informations']['select_filters']=$this->select_filters;
        }
        else
        {
            $this->select_filters=array_merge($_SESSION['list_informations']['select_filters'],$this->select_filters);
        }
        
        





        $this->distinct_user_id_lst=array();

		$res = $information->query('SELECT DISTINCT user_id from informations');

        foreach ($res as $item)
        {
			$user_id=$item['user_id'];
			$this->distinct_user_id_lst[$user_id]=$user_id;
        }




        $search='';
        if (isset($_REQUEST['informations_search_texts']))
        {
            $this->search_texts = $_REQUEST['informations_search_texts'];
            $_SESSION['list_informations']['search_texts']=$this->search_texts;
        }
        else
        {
            $this->search_texts = $_SESSION['list_informations']['search_texts'];
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


		$this->information = $information;
    }
    
    public function load_list()
    { 
        if (!($this->items_per_page)) $this->items_per_page = 25;
        if (isset($_REQUEST['informations_items_per_page']))
        {
            $this->items_per_page = $_REQUEST['informations_items_per_page'];
            $_SESSION['list_informations']['items_per_page']=$this->items_per_page;
        } else
        {
            if (array_key_exists('list_informations',$_SESSION) && array_key_exists('items_per_page',$_SESSION['list_informations']))
            {
                $this->items_per_page = $_SESSION['list_informations']['items_per_page'];
            }            
        }
        
        $this->pages=$this->information->getPageCount($this->select_filters,$this->sort_filters,$this->items_per_page,true);
        $this->total_db_items = $this->information->total_db_items;
        $this->page = 1;//default to 1 in case not provided below
        if (isset($_REQUEST['informations_page']))
        {
            $this->page = $_REQUEST['informations_page'];
            $_SESSION['list_informations']['page']=$this->page;
        } else
        {
            if (array_key_exists('list_informations',$_SESSION) && array_key_exists('page',$_SESSION['list_informations']))
            {
                $this->page = $_SESSION['list_informations']['page'];
            }            
        }
        if (($this->page < 1) || ($this->page > $this->pages)) $this->page = 1;

        
        $this->informations = $this->information->findAll($this->select_filters,$this->sort_filters,$this->items_per_page,$this->page);
    }
    
    public function init_list()
    { 
        $this->title = 'Informations';
        $this->init_list_params();
        $this->load_list();
    } 
    
    

    public function renderActions($information,$class="",$edit=TRUE)
    {
        if ($edit)
        {
            echo '<input type="checkbox" class="select_checkbox" id="'.$information->getId().'" value="'.$information->getId().'" name="delete_selected[]" /><br>';
            echo "<button type='button' class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Informations.dir&path=Edit Information.hid.rest.php&id=".$information->getId()."&object=Information' href='#' ><li class='fa fa-pencil'></li> edit</button>";
            echo '<button type="button" class="button mx- danger" mx-confirm="Are you sure to delete Information of id='.$information->getId().'?" mx-click="'.$this->list_delete_button_url.'&id='.$information->getId().'" href="#" mx-container="'.$this->container.'" mx-next="'.$this->list_delete_button_next_url.'" ><li class="fa fa-trash" ></li> Delete </button>';
        } else
        {
            echo "<button type='button' class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Informations.dir&path=View Information.hid.rest.php&id=".$information->getId()."&object=Information' href='#' ><li class='fa fa-eye'></li> view</button>"; 
        }
    }
    
	public function renderEditControls()
    {
        if ($this->button_next_url)
        { 
            $next_after_update='mx-next="'.$this->button_next_url.'"';
        } else $next_after_update='';
        if ($this->delete_button_next_url)
        {
            $next_after_delete='mx-next="'.$this->delete_button_next_url.'"';
        } else $next_after_delete='';
		echo '<div class="control">';
		echo '<button type="button" class="button mx-" mx-click="'.$this->button_url.'"  href="#" mx-container="div.page" '.$next_after_update.' ><li class="fa fa-save"></li> '.$this->button_name.'</button>';
		echo '<button type="button" class="button mx- danger" mx-confirm="Are you sure to delete Information of id='.$this->information->getId().'?" mx-click="'.$this->delete_button_url.'" href="#" mx-container="'.$this->container.'" '.$next_after_delete.' ><li class="fa fa-trash" ></li> Delete </button>';
        echo "<button type='button' class='button mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Informations.dir&path=List Informations.rest.php&object=Information'  ><li class='fa fa-list'></li> List</button>";
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
        echo '<button type="button" class="button mx-" mx-container="'.$this->container.'" mx-click="?command=display_rest&base_path=Admin.dir/Informations.dir&path=List Informations.rest.php&object=Information"  ><li class="fa fa-list"></li> List</button>';
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
    
   public function get_icon($information)
   {
		echo '<img class="icon" src="'.$this->paths->base_url.'/img/displaypng.php?id='.$_SESSION['user_id'].'&image='.$this->paths->user_image.'/[TABLE_NAME]/'.$information->getId().'_[TABLE_NAME]_icon.png&key='.$this->current_user->getSecurekey().'" />';
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
		echo '<input class="search_text '.$type.'" type="text" name="[TABLE_NAME]_search_texts['.$field.']" value="'.$value.'"></input><button type="button" class="search_text_button clear" ><li class="fa fa-times"></li></button>';
		echo '</div>';
    }
    
    public function get_id($information)
    {
        echo "<button type='button' class='mx-' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Informations.dir&path=Edit Information.hid.rest.php&id=".$information->getId()."&object=Information'  ><i class='fa fa-edit'></i>".$information->getId()."</button>";
    }
    
    public function renderPaginator()
    {?>
        <script>
            <?php
            if (!isset($this->container)) $this->container = 'div.page';
            if (!isset($this->form_list)) $this->form_list = 'form_list_informations';
            ?>
            function informations_paginate(page) 
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
                form.find('input.field_items_per_page').val($('#informations_items_per_page').val());
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
                <select name="informations_items_per_page" id="informations_items_per_page" style="width:60px" onchange="informations_paginate(<?php echo $this->page;?>)">
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
                        <li class=""><button type="button" onclick="informations_paginate(1)">1</button></li>
                        <?php } else
                        {?>
                        <li class="inactive">&nbsp;</li>		
                        <?php
                        }
                        if ($this->page>1) {?>
                        <li class="prev"><button type="button" onclick="informations_paginate(<?php echo ($this->page-1);?>)"><i class="fa fa-chevron-left"></i></button></li>
                        <?php }
                        else {
                        ?>
                        <li class="inactive"><i class="fa fa-chevron-left"></i></li>
                        <?php }?>
                        <li class="active"><?php echo $this->page;?></li>
                        <?php
                        if (($this->pages-$this->page)>0) {?>
                        <li class="next"><button type="button" onclick="informations_paginate(<?php echo ($this->page+1);?>)"><i class="fa fa-chevron-right"></i></button></li>
                        <?php
                        } else {?>
                        <li class="inactive"><i class="fa fa-chevron-right"></i></li>
                        <?php
                        }?>
                        <?php
                        if (($this->pages-$this->page)>1) {?>
                        <li class="next"><button type="button" onclick="informations_paginate(<?php echo $this->pages;?>)"><?php echo $this->pages;?></button></li>
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
                <input class='page_first first ' type='button' value='1' name='first' onclick='informations_paginate(1)' align='center'></input> 
                <?php
                if ($this->page>2)
                {?>
                    <input type='button'  value='<?php echo ($this->page-1);?>' name='prev_page' class='left ' onclick='informations_paginate(<?php echo ($this->page-1);?>)' align='center'></input>
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
                    <input class='page_next right' type='button' name='next_page' align='center' value='<?php echo ($this->page+1);?>' onclick='informations_paginate(<?php echo ($this->page+1);?>)' ></input>
                <?php
                } else
                {?>
                    <input class='page_next right' type='button' name='page' align='center' value=''  ></input>
                <?php
                }?>    
                <input type='button' class='page_last last ' value='<?php echo $this->pages;?>'  name='last_page' onclick='informations_paginate(<?php echo $this->pages;?>)' align='center'></input>
                <input type='hidden' page='1' name="current_page" />
                </div>
            <?php
            }
        }?>
            </div>
            <div class="column items" >
                    <?php
                    $start = ($this->page-1)*$this->items_per_page + 1;
                    $end = $start+count($this->informations)-1;
                    ?>
                    <div class="span5 dataTables_info" id="tableInfo">Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $this->total_db_items;?> entries</div>            
            </div>
        </div>
<?php
    }

*/

}