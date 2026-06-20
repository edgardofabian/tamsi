<?php
require_once 'ViewBase.php';
require_once 'Configuration.php';
class ConfigurationViewBase extends ViewBase 
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
    public $configuration;
    public $page;
    public $path;
    public $configurations;
    public $container;
    public $item_per_page;
    public $pages;
    public $search_text;
    public $select_filters;
    public $sort_filters;
    public $sort_orders;
    public function get_name($configuration)
    {
        echo $configuration->getName();
    }
    public function get_value($configuration)
    {
        echo $configuration->getValue();
    }
    public function get_description($configuration)
    {
        echo $configuration->getDescription();
    }
    public function render_id()
    {
        $class='';
        $attrs='';
        $configuration_field='id';
        foreach($this->columns[$configuration_field]['attributes'] as $name=>$value)
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

        $value=($this->configuration->getId())?htmlentities($this->configuration->getId()):'';
        if ($this->columns[$configuration_field]['type']=='boolean')
        {
            $this->render_boolean($configuration_field,$value);
        }
        else if (($this->columns[$configuration_field]['type']=='hidden') || ($this->columns[$configuration_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='id'  value='$value' />";
            if ($this->columns[$configuration_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$configuration_field]['mode']=='edit')
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
	
    public function render_name()
    {
        $class='';
        $attrs='';
        $configuration_field='name';
        foreach($this->columns[$configuration_field]['attributes'] as $name=>$value)
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

        $value=($this->configuration->getName())?htmlentities($this->configuration->getName()):'';
        if ($this->columns[$configuration_field]['type']=='boolean')
        {
            $this->render_boolean($configuration_field,$value);
        }
        else if (($this->columns[$configuration_field]['type']=='hidden') || ($this->columns[$configuration_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='name'  value='$value' />";
            if ($this->columns[$configuration_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$configuration_field]['mode']=='edit')
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
        $configuration_field='value';
        foreach($this->columns[$configuration_field]['attributes'] as $name=>$value)
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

        $value=($this->configuration->getValue())?htmlentities($this->configuration->getValue()):'';
        if (($this->columns[$configuration_field]['type']=='hidden') || ($this->columns[$configuration_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='value'  value='$value' />";
            if ($this->columns[$configuration_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$configuration_field]['mode']=='edit')
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
	
    public function render_description()
    {
        $class='';
        $attrs='';
        $configuration_field='description';
        foreach($this->columns[$configuration_field]['attributes'] as $name=>$value)
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

        $value=($this->configuration->getDescription())?htmlentities($this->configuration->getDescription()):'';
        if (($this->columns[$configuration_field]['type']=='hidden') || ($this->columns[$configuration_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='description'  value='$value' />";
            if ($this->columns[$configuration_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$configuration_field]['mode']=='edit')
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
        $this->columns["name"]=array('type'=>"text",'label'=>"Name",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["value"]=array('type'=>"textarea",'label'=>"Value",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["description"]=array('type'=>"textarea",'label'=>"Description",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());

        $this->headers = array("#"=>array("label"=>"#","sort"=>0,"class"=>"","attributes"=>array()),"action"=>array("label"=>"Action","sort"=>0,"class"=>"","attributes"=>array()),"id"=>array("label"=>"Id ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"name"=>array("label"=>"Name ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"value"=>array("label"=>"Value ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"description"=>array("label"=>"Description ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()));
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
        $this->title = 'Edit Configuration';
        $this->edit_columns = array("id","name","value","description");

        $this->security=new TamsiSecurity();
        if (array_key_exists("id",$_GET))
        {
            $this->id=$_GET["id"];
        } else
        {
            $this->id=$_REQUEST["id"];
        }
        $this->configuration = new Configuration();
        $this->configuration->find(array("id"=>$this->id));
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
        $this->button_url='?command=editRest&object=Configuration'; //url of the controller where edit form will be posted by ajax
        $this->button_next_url='?command=display_rest&path=Admin.dir/Configurations.dir/Edit_Configuration.hid.rest.php&id='.$this->configuration->getId(); //url that will be visited after completing the edit submission
        $this->delete_button_url='?command=deleteRest&object=Configuration'; //url of the controller where delete form will be submitted
        $this->delete_button_next_url='?command=display_rest&path=Admin.dir/Configurations.dir/List_Configurations.rest.php'; //url that will be visited after completing the delete submission

        
        $this->columns["id"]['mode']="edit";
        $this->columns["name"]['mode']="edit";
        $this->columns["value"]['mode']="edit";
        $this->columns["description"]['mode']="edit";

        
        
    } 
    public function init_new()
    { 
        $this->title = 'New Configuration';
        $this->new_columns = array("id","name","value","description");
        
        $this->security=new TamsiSecurity();
        $this->configuration = new Configuration();
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
        $this->button_url='?command=newRest&object=Configuration'; //url of the controller where the new form will be submitted through ajax
        $this->button_next_url='?command=display_rest&path=Admin.dir/Configurations.dir/List_Configurations.rest.php'; //url of the controller that will be visited after completing the new submission

        
        $this->columns["id"]['mode']="new";
        $this->columns["name"]['mode']="new";
        $this->columns["value"]['mode']="new";
        $this->columns["description"]['mode']="new";

        
    } 
    public function init_list_params()
    { 
        $this->security=new TamsiSecurity();
        $valid_filters=array("id","name","value","description");
        if ($_REQUEST)
        {
            if (array_key_exists("configurations_selected",$_REQUEST))
            {
                $this->page=$_REQUEST["configurations_selected"];
            }
            if (array_key_exists("configurations_path",$_REQUEST))
            {
                $this->path=$_REQUEST["configurations_path"];
            }
        }
        
        if (!$this->container) $this->container='div.page';
        $this->list_columns = array_merge(array('#','action'),$valid_filters);
        $this->list_form_attributes='';
        $this->custom_html_list='';
        $this->command = 'list';
        $this->list_controls = array('new'=>array('label'=>'New','url'=>'?command=display_rest&path=Admin.dir/Configurations.dir/New_Configuration.rest.php','container'=>$this->container));
		$this->form_list='form_list_configurations';
		$this->action='?command=display_php&path=Admin.dir/Configurations.dir/List_Configurations.rest.php';
        $this->list_delete_button_url = '?command=deleteRest&object=Configuration'; //url of the controller where delete form will be submitted
        $this->list_delete_button_next_url = '?command=display_rest&base_path=Admin.dir/Configurations.dir&path=List_Configurations.rest.php';
        $this->list_delete_selected_url = '?command=deleteSelectedRest&object=Configuration'; //url of the controller where delete of selected objects will be submitted
        $this->enable_paginator = true;
        $this->usr=array();
        $configuration = new Configuration();
        if (!isset($_SESSION['list_configurations']))
        {
            $_SESSION['list_configurations']=array();
            $_SESSION['list_configurations']['sort_filters']=array();
            $_SESSION['list_configurations']['sort_order']='';
            $_SESSION['list_configurations']['select_filters']=array();
            $_SESSION['list_configurations']['search_texts']=array();
            $_SESSION['list_configurations']['page']='1';
        }

        if (isset($_REQUEST['configurations_sort_filters']))
        {
            $sort_lists = $_REQUEST['configurations_sort_filters'];
            $_SESSION['list_configurations']['sort_filters']=$sort_lists;
        }
        else
        {
            $sort_lists=$_SESSION['list_configurations']['sort_filters'];
        }

        if (isset($_REQUEST['configurations_sort_order']))
        {
            $sort_order = $_REQUEST['configurations_sort_order'];
            $_SESSION['list_configurations']['sort_order']=$sort_order;
        }
        else
        {
            $sort_order = $_SESSION['list_configurations']['sort_order'];
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
        if (isset($_REQUEST['configurations_select_filters']))
        {
            foreach($_REQUEST['configurations_select_filters'] as $key=>$value)
            {
                if (!(is_numeric($key)) && in_array($key,$valid_filters))
                {
                    $this->select_filters[$key] = $value;
                }
            }
            $_SESSION['list_configurations']['select_filters']=$this->select_filters;
        }
        else
        {
            $this->select_filters=array_merge($_SESSION['list_configurations']['select_filters'],$this->select_filters);
        }
        
        




        $search='';
        if (isset($_REQUEST['configurations_search_texts']))
        {
            $this->search_texts = $_REQUEST['configurations_search_texts'];
            $_SESSION['list_configurations']['search_texts']=$this->search_texts;
        }
        else
        {
            $this->search_texts = $_SESSION['list_configurations']['search_texts'];
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


		$this->configuration = $configuration;
    }
    
    public function load_list()
    { 
        if (!($this->items_per_page)) $this->items_per_page = 25;
        if (isset($_REQUEST['configurations_items_per_page']))
        {
            $this->items_per_page = $_REQUEST['configurations_items_per_page'];
            $_SESSION['list_configurations']['items_per_page']=$this->items_per_page;
        } else
        {
            if (array_key_exists('list_configurations',$_SESSION) && array_key_exists('items_per_page',$_SESSION['list_configurations']))
            {
                $this->items_per_page = $_SESSION['list_configurations']['items_per_page'];
            }            
        }
        
        $this->pages=$this->configuration->getPageCount($this->select_filters,$this->sort_filters,$this->items_per_page,true);
        $this->total_db_items = $this->configuration->total_db_items;
        $this->page = 1;//default to 1 in case not provided below
        if (isset($_REQUEST['configurations_page']))
        {
            $this->page = $_REQUEST['configurations_page'];
            $_SESSION['list_configurations']['page']=$this->page;
        } else
        {
            if (array_key_exists('list_configurations',$_SESSION) && array_key_exists('page',$_SESSION['list_configurations']))
            {
                $this->page = $_SESSION['list_configurations']['page'];
            }            
        }
        if (($this->page < 1) || ($this->page > $this->pages)) $this->page = 1;

        
        $this->configurations = $this->configuration->findAll($this->select_filters,$this->sort_filters,$this->items_per_page,$this->page);
    }
    
    public function init_list()
    { 
        $this->title = 'Configurations';
        $this->init_list_params();
        $this->load_list();
    } 
    

    public function renderDeleteCheckbox($configuration)
    {
        echo '<input type="checkbox" class="select_checkbox" id="'.$configuration->getId().'" value="'.$configuration->getId().'" name="delete_selected[]" /><br>';
    }
    
    public function renderEditAction($configuration)
    {
        echo "<button type='button' class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Configurations.dir&path=Edit_Configuration.hid.rest.php&id=".$configuration->getId()."&object=Configuration' href='#' ><li class='fa fa-pencil'></li> edit</button>";
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
    
    public function renderDeleteAction($configuration,$next_url,$delete_url)
    {
        $mx_next_url='';
        if ($next_url)
        {
            $mx_next_url = 'mx-next="'.$next_url.'"';
        }
        echo '<button type="button" class="button mx- danger" mx-confirm="Are you sure to delete Configuration of id='.$configuration->getId().'?" mx-click="'.$delete_url.'&id='.$configuration->getId().'" href="#" mx-container="'.$this->container.'" '.$mx_next_url.' ><li class="fa fa-trash" ></li> del</button>';
    }
    
    public function renderViewAction($configuration)
    {
        echo "<button type='button' class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Configurations.dir&path=View_Configuration.hid.rest.php&id=".$configuration->getId()."&object=Configuration' href='#' ><li class='fa fa-eye'></li> view</button>";
    }

    public function renderListAction()
    {
        echo "<button type='button' class='button mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Configurations.dir&path=List_Configurations.rest.php&object=Configuration'  ><li class='fa fa-list'></li> List</button>";
    }

    public function renderActions($configuration,$class="",$edit=TRUE)
    {
        if ($edit)
        {
            $this->renderDeleteCheckbox($configuration);
            $this->renderEditAction($configuration);
            $this->renderDeleteAction($configuration,$this->list_delete_button_next_url,$this->list_delete_button_url);
        } else
        {
            $this->renderViewAction($configuration);
        }
    }
    
	public function renderEditControls()
    {
		echo '<div class="control">';		
		$this->renderSaveAction($this->button_next_url);
        $this->renderDeleteAction($this->configuration,$this->delete_button_next_url,$this->delete_button_url);
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
    
   public function get_icon($configuration)
   {
		echo '<img class="icon" src="'.$this->paths->base_url.'/img/displaypng.php?id='.$_SESSION['user_id'].'&image='.$this->paths->user_image.'/configurations/'.$configuration->getId().'_configurations_icon.png&key='.$this->current_user->getSecurekey().'" />';
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
		echo '<input class="search_text '.$type.'" type="text" name="configurations_search_texts['.$field.']" value="'.$value.'"></input><button type="button" class="search_text_button clear" ><li class="fa fa-times"></li></button>';
		echo '</div>';
    }
    
    public function get_id($configuration)
    {
        echo "<button type='button' class='mx-' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Configurations.dir&path=Edit_Configuration.hid.rest.php&id=".$configuration->getId()."&object=Configuration'  ><i class='fa fa-edit'></i>".$configuration->getId()."</button>";
    }
    
    public function renderPaginator()
    {?>
        <script>
            <?php
            if (!isset($this->container)) $this->container = 'div.page';
            if (!isset($this->form_list)) $this->form_list = 'form_list_configurations';
            ?>
            function configurations_paginate(page) 
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
                form.find('input.field_items_per_page').val($('#configurations_items_per_page').val());
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
                <select name="configurations_items_per_page" id="configurations_items_per_page" style="width:60px" onchange="configurations_paginate(<?php echo $this->page;?>)">
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
                        <li class=""><button type="button" onclick="configurations_paginate(1)">1</button></li>
                        <?php } else
                        {?>
                        <li class="inactive">&nbsp;</li>		
                        <?php
                        }
                        if ($this->page>1) {?>
                        <li class="prev"><button type="button" onclick="configurations_paginate(<?php echo ($this->page-1);?>)"><i class="fa fa-chevron-left"></i></button></li>
                        <?php }
                        else {
                        ?>
                        <li class="inactive"><i class="fa fa-chevron-left"></i></li>
                        <?php }?>
                        <li class="active"><?php echo $this->page;?></li>
                        <?php
                        if (($this->pages-$this->page)>0) {?>
                        <li class="next"><button type="button" onclick="configurations_paginate(<?php echo ($this->page+1);?>)"><i class="fa fa-chevron-right"></i></button></li>
                        <?php
                        } else {?>
                        <li class="inactive"><i class="fa fa-chevron-right"></i></li>
                        <?php
                        }?>
                        <?php
                        if (($this->pages-$this->page)>1) {?>
                        <li class="next"><button type="button" onclick="configurations_paginate(<?php echo $this->pages;?>)"><?php echo $this->pages;?></button></li>
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
                <input class='page_first first ' type='button' value='1' name='first' onclick='configurations_paginate(1)' align='center'></input> 
                <?php
                if ($this->page>2)
                {?>
                    <input type='button'  value='<?php echo ($this->page-1);?>' name='prev_page' class='left ' onclick='configurations_paginate(<?php echo ($this->page-1);?>)' align='center'></input>
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
                    <input class='page_next right' type='button' name='next_page' align='center' value='<?php echo ($this->page+1);?>' onclick='configurations_paginate(<?php echo ($this->page+1);?>)' ></input>
                <?php
                } else
                {?>
                    <input class='page_next right' type='button' name='page' align='center' value=''  ></input>
                <?php
                }?>    
                <input type='button' class='page_last last ' value='<?php echo $this->pages;?>'  name='last_page' onclick='configurations_paginate(<?php echo $this->pages;?>)' align='center'></input>
                <input type='hidden' page='1' name="current_page" />
                </div>
            <?php
            }
        }?>
            </div>
            <div class="column items" >
                    <?php
                    $start = ($this->page-1)*$this->items_per_page + 1;
                    $end = $start+count($this->configurations)-1;
                    ?>
                    <div class="span5 dataTables_info" id="tableInfo">Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $this->total_db_items;?> entries</div>            
            </div>
        </div>
<?php
    }

}
