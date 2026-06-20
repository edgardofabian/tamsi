<?php

require_once 'FinanceViewBase.php';
class FinanceView extends FinanceViewBase
{
    //functions you can override
    /*
    public function render_id()
    {
        $class='';
        $attrs='';
        $finance_field='id';
        foreach($this->columns[$finance_field]['attributes'] as $name=>$value)
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

        $value=($this->finance->getId())?htmlentities($this->finance->getId()):'';
        if ($this->columns[$finance_field]['type']=='boolean')
        {
            $this->render_boolean($finance_field,$value);
        }
        else if (($this->columns[$finance_field]['type']=='hidden') || ($this->columns[$finance_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='id'  value='$value' />";
            if ($this->columns[$finance_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$finance_field]['mode']=='edit')
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
	
    public function render_description()
    {
        $class='';
        $attrs='';
        $finance_field='description';
        foreach($this->columns[$finance_field]['attributes'] as $name=>$value)
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

        $value=($this->finance->getDescription())?htmlentities($this->finance->getDescription()):'';
        if (($this->columns[$finance_field]['type']=='hidden') || ($this->columns[$finance_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='description'  value='$value' />";
            if ($this->columns[$finance_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$finance_field]['mode']=='edit')
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
	
    public function render_expense()
    {
        $class='';
        $attrs='';
        $finance_field='expense';
        foreach($this->columns[$finance_field]['attributes'] as $name=>$value)
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

        $value=($this->finance->getExpense())?htmlentities($this->finance->getExpense()):'';
        if (($this->columns[$finance_field]['type']=='hidden') || ($this->columns[$finance_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='expense'  value='$value' />";
            if ($this->columns[$finance_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$finance_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_expense' $attrs class='$class  edit_input' name='expense' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_expense' $attrs type='text' class='$class ' name='expense' >".$value."</textarea>";
            }
        }
    }
    public function render_expense_filter()
    {
        $this->renderSearchText('expense','textarea');
	}
	
    public function render_income()
    {
        $class='';
        $attrs='';
        $finance_field='income';
        foreach($this->columns[$finance_field]['attributes'] as $name=>$value)
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

        $value=($this->finance->getIncome())?htmlentities($this->finance->getIncome()):'';
        if (($this->columns[$finance_field]['type']=='hidden') || ($this->columns[$finance_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='income'  value='$value' />";
            if ($this->columns[$finance_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$finance_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_income' $attrs class='$class  edit_input' name='income' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_income' $attrs type='text' class='$class ' name='income' >".$value."</textarea>";
            }
        }
    }
    public function render_income_filter()
    {
        $this->renderSearchText('income','textarea');
	}
	
    public function render_source()
    {
        $class='';
        $attrs='';
        $finance_field='source';
        foreach($this->columns[$finance_field]['attributes'] as $name=>$value)
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

        $value=($this->finance->getSource())?htmlentities($this->finance->getSource()):'';
        if (($this->columns[$finance_field]['type']=='hidden') || ($this->columns[$finance_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='source'  value='$value' />";
            if ($this->columns[$finance_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$finance_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_source' $attrs class='$class  edit_input' name='source' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_source' $attrs type='text' class='$class ' name='source' >".$value."</textarea>";
            }
        }
    }
    public function render_source_filter()
    {
        $this->renderSearchText('source','textarea');
	}
	
    public function render_destination()
    {
        $class='';
        $attrs='';
        $finance_field='destination';
        foreach($this->columns[$finance_field]['attributes'] as $name=>$value)
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

        $value=($this->finance->getDestination())?htmlentities($this->finance->getDestination()):'';
        if (($this->columns[$finance_field]['type']=='hidden') || ($this->columns[$finance_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='destination'  value='$value' />";
            if ($this->columns[$finance_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$finance_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_destination' $attrs class='$class  edit_input' name='destination' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_destination' $attrs type='text' class='$class ' name='destination' >".$value."</textarea>";
            }
        }
    }
    public function render_destination_filter()
    {
        $this->renderSearchText('destination','textarea');
	}
	
    public function render_expense_type()
    {
		$class='';
        $attrs='';
        $finance_field='expense_type';
        foreach($this->columns[$finance_field]['attributes'] as $name=>$value)
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
    
        $val=$this->finance->getExpenseType();
		if ($this->columns[$finance_field]['type']=='boolean')
        {
            $this->render_boolean($finance_field,$value);
        }
        else if (($this->columns[$finance_field]['type']=='hidden') || ($this->columns[$finance_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='expense_type'  value='$val' />";
            if ($this->columns[$finance_field]['type']=='readonly')
            {
                echo "<span >$val</span>";
            }
        } else
        {
            if ($this->columns[$finance_field]['mode']=='edit')
            {
                echo "<select $attrs class='$class edit_input'  name='expense_type' >\n";
            } else
            {
                echo "<select $attrs class='$class'  name='expense_type' >\n";
            } 
			$options=array("basic_needs"=>"basic_needs","real_estate_purchase"=>"real_estate_purchase","lent"=>"lent","donation"=>"donation","mama_dor"=>"mama_dor","mama_caring"=>"mama_caring","kyle"=>"kyle","xavier"=>"xavier","cairi"=>"cairi","sinaman_diwan"=>"sinaman_diwan","barili_bukid"=>"barili_bukid","vehicle"=>"vehicle","others"=>"others");
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
            if ($this->columns[$finance_field]['mode']=='edit') echo "<span class='view_input'>$val</span>";
            
        }
    }
    public function render_expense_type_filter()
    {
		echo "<select  class='filter' name='[TABLE_NAME]_select_filters[expense_type]' value='' > \r\n";
        echo "<option value=''>All</option> \r\n";
		$options=array("basic_needs"=>"basic_needs","real_estate_purchase"=>"real_estate_purchase","lent"=>"lent","donation"=>"donation","mama_dor"=>"mama_dor","mama_caring"=>"mama_caring","kyle"=>"kyle","xavier"=>"xavier","cairi"=>"cairi","sinaman_diwan"=>"sinaman_diwan","barili_bukid"=>"barili_bukid","vehicle"=>"vehicle","others"=>"others");
        if (array_key_exists('expense_type',$this->columns) && array_key_exists('options',$this->columns['expense_type']) && ($this->columns['expense_type']['options']))
        {
            $options = $this->columns['expense_type']['options'];
        } 
        foreach($options as $option=>$label)
		{
			if ($option)
            {
                if ((array_key_exists('expense_type',$this->select_filters)) && ($this->select_filters['expense_type']==$option))
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
	
    public function render_income_type()
    {
		$class='';
        $attrs='';
        $finance_field='income_type';
        foreach($this->columns[$finance_field]['attributes'] as $name=>$value)
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
    
        $val=$this->finance->getIncomeType();
		if ($this->columns[$finance_field]['type']=='boolean')
        {
            $this->render_boolean($finance_field,$value);
        }
        else if (($this->columns[$finance_field]['type']=='hidden') || ($this->columns[$finance_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='income_type'  value='$val' />";
            if ($this->columns[$finance_field]['type']=='readonly')
            {
                echo "<span >$val</span>";
            }
        } else
        {
            if ($this->columns[$finance_field]['mode']=='edit')
            {
                echo "<select $attrs class='$class edit_input'  name='income_type' >\n";
            } else
            {
                echo "<select $attrs class='$class'  name='income_type' >\n";
            } 
			$options=array("nekpa"=>"nekpa","local_work"=>"local_work","lent_payment"=>"lent_payment","donated"=>"donated","found"=>"found","sale"=>"sale","others"=>"others");
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
            if ($this->columns[$finance_field]['mode']=='edit') echo "<span class='view_input'>$val</span>";
            
        }
    }
    public function render_income_type_filter()
    {
		echo "<select  class='filter' name='[TABLE_NAME]_select_filters[income_type]' value='' > \r\n";
        echo "<option value=''>All</option> \r\n";
		$options=array("nekpa"=>"nekpa","local_work"=>"local_work","lent_payment"=>"lent_payment","donated"=>"donated","found"=>"found","sale"=>"sale","others"=>"others");
        if (array_key_exists('income_type',$this->columns) && array_key_exists('options',$this->columns['income_type']) && ($this->columns['income_type']['options']))
        {
            $options = $this->columns['income_type']['options'];
        } 
        foreach($options as $option=>$label)
		{
			if ($option)
            {
                if ((array_key_exists('income_type',$this->select_filters)) && ($this->select_filters['income_type']==$option))
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
	
    public function render_transaction_date()
    {
        $class='';
        $attrs='';
        $finance_field='transaction_date';
        foreach($this->columns[$finance_field]['attributes'] as $name=>$value)
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

        $value=($this->finance->getTransactionDate())?htmlentities($this->finance->getTransactionDate()):'';
        if ($this->columns[$finance_field]['type']=='boolean')
        {
            $this->render_boolean($finance_field,$value);
        }
        else if (($this->columns[$finance_field]['type']=='hidden') || ($this->columns[$finance_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='transaction_date'  value='$value' />";
            if ($this->columns[$finance_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$finance_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class date edit_input' name='transaction_date' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class date' name='transaction_date' value='".$value."' ></input>";
            }
        }
    }
    public function render_transaction_date_filter()
    {
        $this->renderSearchText('transaction_date','date');
	}
	
    public function render_created()
    {
        $class='';
        $attrs='';
        $finance_field='created';
        foreach($this->columns[$finance_field]['attributes'] as $name=>$value)
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

        $value=($this->finance->getCreated())?htmlentities($this->finance->getCreated()):'';
        if ($this->columns[$finance_field]['type']=='boolean')
        {
            $this->render_boolean($finance_field,$value);
        }
        else if (($this->columns[$finance_field]['type']=='hidden') || ($this->columns[$finance_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='created'  value='$value' />";
            if ($this->columns[$finance_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$finance_field]['mode']=='edit')
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
	
    public $distinct_expense_type_lst;
    public $distinct_income_type_lst;

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
        $this->columns["description"]=array('type'=>"textarea",'label'=>"Description",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["expense"]=array('type'=>"textarea",'label'=>"Expense",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["income"]=array('type'=>"textarea",'label'=>"Income",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["source"]=array('type'=>"textarea",'label'=>"Source",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["destination"]=array('type'=>"textarea",'label'=>"Destination",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["expense_type"]=array('type'=>"enum",'label'=>"ExpenseType",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["income_type"]=array('type'=>"enum",'label'=>"IncomeType",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["transaction_date"]=array('type'=>"date",'label'=>"TransactionDate",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["created"]=array('type'=>"datetime",'label'=>"Created",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());

        $this->headers = array("#"=>array("label"=>"#","sort"=>0,"class"=>"","attributes"=>array()),"action"=>array("label"=>"Action","sort"=>0,"class"=>"","attributes"=>array()),"id"=>array("label"=>"Id ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"description"=>array("label"=>"Description ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"expense"=>array("label"=>"Expense ","sort"=>"0","search"=>"0","class"=>"","attributes"=>array()),"income"=>array("label"=>"Income ","sort"=>"0","search"=>"0","class"=>"","attributes"=>array()),"source"=>array("label"=>"Source ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"destination"=>array("label"=>"Destination ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"expense_type"=>array("label"=>"Expense Type ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"income_type"=>array("label"=>"Income Type ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"transaction_date"=>array("label"=>"Transaction Date ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"created"=>array("label"=>"Created ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()));
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
        $this->title = 'Edit Finance';
        $this->edit_columns = array("id","description","expense","income","source","destination","expense_type","income_type","transaction_date","created");

        $this->security=new TamsiSecurity();
        if (array_key_exists("id",$_GET))
        {
            $this->id=$_GET["id"];
        } else
        {
            $this->id=$_REQUEST["id"];
        }
        $this->finance = new Finance();
        $this->finance->find(array("id"=>$this->id));
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
        $this->button_url='?command=editRest&object=Finance'; //url of the controller where edit form will be posted by ajax
        $this->button_next_url='?command=display_rest&path=Admin.dir/Finances.dir/Edit Finance.hid.rest.php&id='.$this->finance->getId(); //url that will be visited after completing the edit submission
        $this->delete_button_url='?command=deleteRest&object=Finance'; //url of the controller where delete form will be submitted
        $this->delete_button_next_url='?command=display_rest&path=Admin.dir/Finances.dir/List Finances.rest.php'; //url that will be visited after completing the delete submission

        
        $this->columns["id"]['mode']="edit";
        $this->columns["description"]['mode']="edit";
        $this->columns["expense"]['mode']="edit";
        $this->columns["income"]['mode']="edit";
        $this->columns["source"]['mode']="edit";
        $this->columns["destination"]['mode']="edit";
        $this->columns["expense_type"]['mode']="edit";
        $this->columns["expense_type"]["attributes"]["class"]="chosen";
        $this->columns["income_type"]['mode']="edit";
        $this->columns["income_type"]["attributes"]["class"]="chosen";
        $this->columns["transaction_date"]['mode']="edit";
        $this->columns["created"]['mode']="edit";

        
        
    } 
    public function init_new()
    { 
        $this->title = 'New Finance';
        $this->new_columns = array("id","description","expense","income","source","destination","expense_type","income_type","transaction_date","created");
        
        $this->security=new TamsiSecurity();
        $this->finance = new Finance();
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
        $this->button_url='?command=newRest&object=Finance'; //url of the controller where the new form will be submitted through ajax
        $this->button_next_url='?command=display_rest&path=Admin.dir/Finances.dir/List Finances.rest.php'; //url of the controller that will be visited after completing the new submission

        
        $this->columns["id"]['mode']="new";
        $this->columns["description"]['mode']="new";
        $this->columns["expense"]['mode']="new";
        $this->columns["income"]['mode']="new";
        $this->columns["source"]['mode']="new";
        $this->columns["destination"]['mode']="new";
        $this->columns["expense_type"]['mode']="new";
        $this->columns["expense_type"]["attributes"]["class"]="chosen";
        $this->columns["income_type"]['mode']="new";
        $this->columns["income_type"]["attributes"]["class"]="chosen";
        $this->columns["transaction_date"]['mode']="new";
        $this->columns["created"]['mode']="new";

        
    } 
    public function init_list_params()
    { 
        $this->security=new TamsiSecurity();
        $valid_filters=array("id","description","expense","income","source","destination","expense_type","income_type","transaction_date","created");
        if ($_REQUEST)
        {
            if (array_key_exists("finances_selected",$_REQUEST))
            {
                $this->page=$_REQUEST["finances_selected"];
            }
            if (array_key_exists("finances_path",$_REQUEST))
            {
                $this->path=$_REQUEST["finances_path"];
            }
        }
        $this->list_columns = array_merge(array('#','action'),$valid_filters);
        $this->list_form_attributes='';
        $this->custom_html_list='';
        $this->command = 'list';
        $this->list_controls = array('new'=>array('label'=>'New','url'=>'?command=display_rest&path=Admin.dir/Finances.dir/New Finance.rest.php','container'=>$this->container));
		$this->form_list='form_list_finances';
		$this->action='?command=display_php&path=Admin.dir/Finances.dir/List Finances.rest.php';
        $this->list_delete_button_url = '?command=deleteRest&object=Finance'; //url of the controller where delete form will be submitted
        $this->list_delete_button_next_url = '?command=display_rest&base_path=Admin.dir/Finances.dir&path=List Finances.rest.php';
        $this->list_delete_selected_url = '?command=deleteSelectedRest&object=Finance'; //url of the controller where delete of selected objects will be submitted
        $this->enable_paginator = true;

        if (!$this->container) $this->container='div.page';
        
        $finance = new Finance();
        if (!isset($_SESSION['list_finances']))
        {
            $_SESSION['list_finances']=array();
            $_SESSION['list_finances']['sort_filters']=array();
            $_SESSION['list_finances']['sort_order']='';
            $_SESSION['list_finances']['select_filters']=array();
            $_SESSION['list_finances']['search_texts']=array();
            $_SESSION['list_finances']['page']='1';
        }

        if (isset($_REQUEST['finances_sort_filters']))
        {
            $sort_lists = $_REQUEST['finances_sort_filters'];
            $_SESSION['list_finances']['sort_filters']=$sort_lists;
        }
        else
        {
            $sort_lists=$_SESSION['list_finances']['sort_filters'];
        }

        if (isset($_REQUEST['finances_sort_order']))
        {
            $sort_order = $_REQUEST['finances_sort_order'];
            $_SESSION['list_finances']['sort_order']=$sort_order;
        }
        else
        {
            $sort_order = $_SESSION['list_finances']['sort_order'];
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
        if (isset($_REQUEST['finances_select_filters']))
        {
            foreach($_REQUEST['finances_select_filters'] as $key=>$value)
            {
                if (!(is_numeric($key)) && in_array($key,$valid_filters))
                {
                    $this->select_filters[$key] = $value;
                }
            }
            $_SESSION['list_finances']['select_filters']=$this->select_filters;
        }
        else
        {
            $this->select_filters=array_merge($_SESSION['list_finances']['select_filters'],$this->select_filters);
        }
        
        





        $this->distinct_expense_type_lst=array();

		$res = array('basic_needs','real_estate_purchase','lent','donation','mama_dor','mama_caring','kyle','xavier','cairi','sinaman_diwan','barili_bukid','vehicle','others');

        foreach ($res as $item)
        {
			$expense_type=$item;
			$this->distinct_expense_type_lst[$expense_type]=$expense_type;
        }

        $this->distinct_income_type_lst=array();

		$res = array('nekpa','local_work','lent_payment','donated','found','sale','others');

        foreach ($res as $item)
        {
			$income_type=$item;
			$this->distinct_income_type_lst[$income_type]=$income_type;
        }




        $search='';
        if (isset($_REQUEST['finances_search_texts']))
        {
            $this->search_texts = $_REQUEST['finances_search_texts'];
            $_SESSION['list_finances']['search_texts']=$this->search_texts;
        }
        else
        {
            $this->search_texts = $_SESSION['list_finances']['search_texts'];
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


		$this->finance = $finance;
    }
    
    public function load_list()
    { 
        if (!($this->items_per_page)) $this->items_per_page = 25;
        if (isset($_REQUEST['finances_items_per_page']))
        {
            $this->items_per_page = $_REQUEST['finances_items_per_page'];
            $_SESSION['list_finances']['items_per_page']=$this->items_per_page;
        } else
        {
            if (array_key_exists('list_finances',$_SESSION) && array_key_exists('items_per_page',$_SESSION['list_finances']))
            {
                $this->items_per_page = $_SESSION['list_finances']['items_per_page'];
            }            
        }
        
        $this->pages=$this->finance->getPageCount($this->select_filters,$this->sort_filters,$this->items_per_page,true);
        $this->total_db_items = $this->finance->total_db_items;
        $this->page = 1;//default to 1 in case not provided below
        if (isset($_REQUEST['finances_page']))
        {
            $this->page = $_REQUEST['finances_page'];
            $_SESSION['list_finances']['page']=$this->page;
        } else
        {
            if (array_key_exists('list_finances',$_SESSION) && array_key_exists('page',$_SESSION['list_finances']))
            {
                $this->page = $_SESSION['list_finances']['page'];
            }            
        }
        if (($this->page < 1) || ($this->page > $this->pages)) $this->page = 1;

        
        $this->finances = $this->finance->findAll($this->select_filters,$this->sort_filters,$this->items_per_page,$this->page);
    }
    
    public function init_list()
    { 
        $this->title = 'Finances';
        $this->init_list_params();
        $this->load_list();
    } 
    
    

    public function renderActions($finance,$class="",$edit=TRUE)
    {
        if ($edit)
        {
            echo '<input type="checkbox" class="select_checkbox" id="'.$finance->getId().'" value="'.$finance->getId().'" name="delete_selected[]" /><br>';
            echo "<button type='button' class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Finances.dir&path=Edit Finance.hid.rest.php&id=".$finance->getId()."&object=Finance' href='#' ><li class='fa fa-pencil'></li> edit</button>";
            echo '<button type="button" class="button mx- danger" mx-confirm="Are you sure to delete Finance of id='.$finance->getId().'?" mx-click="'.$this->list_delete_button_url.'&id='.$finance->getId().'" href="#" mx-container="'.$this->container.'" mx-next="'.$this->list_delete_button_next_url.'" ><li class="fa fa-trash" ></li> Delete </button>';
        } else
        {
            echo "<button type='button' class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Finances.dir&path=View Finance.hid.rest.php&id=".$finance->getId()."&object=Finance' href='#' ><li class='fa fa-eye'></li> view</button>"; 
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
		echo '<button type="button" class="button mx- danger" mx-confirm="Are you sure to delete Finance of id='.$this->finance->getId().'?" mx-click="'.$this->delete_button_url.'" href="#" mx-container="'.$this->container.'" '.$next_after_delete.' ><li class="fa fa-trash" ></li> Delete </button>';
        echo "<button type='button' class='button mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Finances.dir&path=List Finances.rest.php&object=Finance'  ><li class='fa fa-list'></li> List</button>";
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
        echo '<button type="button" class="button mx-" mx-container="'.$this->container.'" mx-click="?command=display_rest&base_path=Admin.dir/Finances.dir&path=List Finances.rest.php&object=Finance"  ><li class="fa fa-list"></li> List</button>';
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
    
   public function get_icon($finance)
   {
		echo '<img class="icon" src="'.$this->paths->base_url.'/img/displaypng.php?id='.$_SESSION['user_id'].'&image='.$this->paths->user_image.'/[TABLE_NAME]/'.$finance->getId().'_[TABLE_NAME]_icon.png&key='.$this->current_user->getSecurekey().'" />';
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
    
    public function get_id($finance)
    {
        echo "<button type='button' class='mx-' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Finances.dir&path=Edit Finance.hid.rest.php&id=".$finance->getId()."&object=Finance'  ><i class='fa fa-edit'></i>".$finance->getId()."</button>";
    }
    
    public function renderPaginator()
    {?>
        <script>
            <?php
            if (!isset($this->container)) $this->container = 'div.page';
            if (!isset($this->form_list)) $this->form_list = 'form_list_finances';
            ?>
            function finances_paginate(page) 
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
                form.find('input.field_items_per_page').val($('#finances_items_per_page').val());
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
                <select name="finances_items_per_page" id="finances_items_per_page" style="width:60px" onchange="finances_paginate(<?php echo $this->page;?>)">
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
                        <li class=""><button type="button" onclick="finances_paginate(1)">1</button></li>
                        <?php } else
                        {?>
                        <li class="inactive">&nbsp;</li>		
                        <?php
                        }
                        if ($this->page>1) {?>
                        <li class="prev"><button type="button" onclick="finances_paginate(<?php echo ($this->page-1);?>)"><i class="fa fa-chevron-left"></i></button></li>
                        <?php }
                        else {
                        ?>
                        <li class="inactive"><i class="fa fa-chevron-left"></i></li>
                        <?php }?>
                        <li class="active"><?php echo $this->page;?></li>
                        <?php
                        if (($this->pages-$this->page)>0) {?>
                        <li class="next"><button type="button" onclick="finances_paginate(<?php echo ($this->page+1);?>)"><i class="fa fa-chevron-right"></i></button></li>
                        <?php
                        } else {?>
                        <li class="inactive"><i class="fa fa-chevron-right"></i></li>
                        <?php
                        }?>
                        <?php
                        if (($this->pages-$this->page)>1) {?>
                        <li class="next"><button type="button" onclick="finances_paginate(<?php echo $this->pages;?>)"><?php echo $this->pages;?></button></li>
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
                <input class='page_first first ' type='button' value='1' name='first' onclick='finances_paginate(1)' align='center'></input> 
                <?php
                if ($this->page>2)
                {?>
                    <input type='button'  value='<?php echo ($this->page-1);?>' name='prev_page' class='left ' onclick='finances_paginate(<?php echo ($this->page-1);?>)' align='center'></input>
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
                    <input class='page_next right' type='button' name='next_page' align='center' value='<?php echo ($this->page+1);?>' onclick='finances_paginate(<?php echo ($this->page+1);?>)' ></input>
                <?php
                } else
                {?>
                    <input class='page_next right' type='button' name='page' align='center' value=''  ></input>
                <?php
                }?>    
                <input type='button' class='page_last last ' value='<?php echo $this->pages;?>'  name='last_page' onclick='finances_paginate(<?php echo $this->pages;?>)' align='center'></input>
                <input type='hidden' page='1' name="current_page" />
                </div>
            <?php
            }
        }?>
            </div>
            <div class="column items" >
                    <?php
                    $start = ($this->page-1)*$this->items_per_page + 1;
                    $end = $start+count($this->finances)-1;
                    ?>
                    <div class="span5 dataTables_info" id="tableInfo">Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $this->total_db_items;?> entries</div>            
            </div>
        </div>
<?php
    }

*/

}