<?php
require_once 'Security.php';
require_once 'paths.cfg.php';
require_once 'Configurations.php';

class ViewBase
{
   public $paths;
   public $config;
   public $list_columns;
   public $edit_columns;
   public $new_columns;
   public $security;

   public function __construct()
   {
      $this->paths = new Paths();
      $this->config = new Configurations();
      $this->security=new Security();
   }

   public function renderCsrf()
   { 
      echo '<input type="hidden" name="'.$this->security->getCsrfName().'" value="'.$this->security->getCsrfValue().'"/>'; 
   }

   public function renderLoggedOutCsrf()
   { 
      echo '<input type="hidden" name="'.$this->security->getLoggedOutCsrfName().'" value="'.$this->security->getLoggedOutCsrfValue().'"/>'; 
   }
   
   public function set_list_columns($columns)
   {
      $this->list_columns = $columns;
   }
   
   public function remove_from_list($field_name)
   {
       $idx = array_search($field_name,$this->list_columns);
       unset($this->list_columns[$idx]);
   }

   public function set_edit_columns($columns)
   {
      $this->edit_columns = $columns;
   }
   public function remove_from_edit($field_name)
   {
       if (is_array($this->edit_columns))
       {
            $idx = array_search($field_name,$this->edit_columns);
            unset($this->edit_columns[$idx]);
       }
   }   

   public function set_new_columns($columns)
   {
      $this->new_columns = $columns;
   }

   public function remove_from_new($field_name)
   {
       $idx = array_search($field_name,$this->new_columns);
       unset($this->new_columns[$idx]);
   }   
   
   public function removeFromList($field_name)
   {
       $this->remove_from_list($field_name);
   }

   public function removeFromEdit($field_name)
   {
       $this->remove_from_edit($field_name);
   }   

   public function removeFromNew($field_name)
   {
       $this->remove_from_new($field_name);
   }   
   
   public function setListColumns($columns)
   {
      $this->set_list_columns($columns);
   }
   
   public function setEditColumns($columns)
   {
      $this->set_edit_columns($columns);
   }
   

   public function setNewColumns($columns)
   {
      $this->set_new_columns($columns);
   }


   public function render_boolean($field,$value)
   {
        $label=$this->columns[$field][$label];
        $true_label='active';
        if (array_key_exists('true_label',$this->columns[$field])) $true_label = $this->columns[$field]['true_label'];
        $false_label='inactive';
        if (array_key_exists('false_label',$this->columns[$field])) $false_label = $this->columns[$field]['false_label'];
        
        $class='';
        $attrs='';
        foreach($this->columns[$field]['attributes'] as $name=>$val)
        {
            if ($name=='class')
            {
               $class=$val;
            }
            else
            {
               $attrs.=$name.'="'.$val.'" ';
            }
            
        }

        

        if ($this->columns[$field]['mode']=='edit')
        {
            echo "<span class='edit_input' >";
        } else
        {
           echo "<span>";
        }
        $options = array(0=>$false_label,1=>$true_label);
        foreach ($options as $key=>$option)
        {
            if ( intval($value) == intval($key))
            {
                echo "<label><input $attrs type='radio' class='$class' checked='checked' value='$key' name='$field'> $option</label>";
                $role_names[] = $option;
            } else
            {
                echo "<label><input $attrs type='radio' class='$class' value='$key' name=$field> $option</label>";
            }
         }
         echo "</span>";
         echo '<span class="view_input">'.($value?$true_label:$false_label).'</span>';
   }
   

   
}
