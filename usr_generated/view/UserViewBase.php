<?php
require_once 'ViewBase.php';
require_once 'User.php';
require_once 'Role.php';
class UserViewBase extends ViewBase 
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
    public $user;
    public $page;
    public $path;
    public $users;
    public $container;
    public $item_per_page;
    public $pages;
    public $search_text;
    public $select_filters;
    public $sort_filters;
    public $sort_orders;
    public function get_firstname($user)
    {
        echo $user->getFirstname();
    }
    public function get_lastname($user)
    {
        echo $user->getLastname();
    }
    public function get_email($user)
    {
        echo $user->getEmail();
    }
    public function get_profile($user)
    {
        echo $user->getProfile();
    }
    public function get_created($user)
    {
        echo $user->getCreated();
    }
    public function get_modified($user)
    {
        echo $user->getModified();
    }
    public function get_login($user)
    {
        echo $user->getLogin();
    }
    public function get_role($user)
    {
        $role = new Role();
echo $role->getRoleName($user->getRole());
    }
    public function get_salt($user)
    {
        echo $user->getSalt();
    }
    public function get_password($user)
    {
        echo $user->getPassword();
    }
    public function get_ip($user)
    {
        echo $user->getIp();
    }
    public function get_updateip($user)
    {
        echo $user->getUpdateip();
    }
    public function get_loginip($user)
    {
        echo $user->getLoginip();
    }
    public function get_resetkey($user)
    {
        echo $user->getResetkey();
    }
    public function get_securekey($user)
    {
        echo $user->getSecurekey();
    }
    public function render_id()
    {
        $class='';
        $attrs='';
        $user_field='id';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
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

        $value=($this->user->getId())?htmlentities($this->user->getId()):'';
        if ($this->columns[$user_field]['type']=='boolean')
        {
            $this->render_boolean($user_field,$value);
        }
        else if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='id'  value='$value' />";
            if ($this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
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
	
    public function render_firstname()
    {
        $class='';
        $attrs='';
        $user_field='firstname';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
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

        $value=($this->user->getFirstname())?htmlentities($this->user->getFirstname()):'';
        if ($this->columns[$user_field]['type']=='boolean')
        {
            $this->render_boolean($user_field,$value);
        }
        else if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='firstname'  value='$value' />";
            if ($this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='firstname' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='firstname' value='".$value."' ></input>";
            }
        }
    }
    public function render_firstname_filter()
    {
        $this->renderSearchText('firstname','text');
	}
	
    public function render_lastname()
    {
        $class='';
        $attrs='';
        $user_field='lastname';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
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

        $value=($this->user->getLastname())?htmlentities($this->user->getLastname()):'';
        if ($this->columns[$user_field]['type']=='boolean')
        {
            $this->render_boolean($user_field,$value);
        }
        else if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='lastname'  value='$value' />";
            if ($this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='lastname' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='lastname' value='".$value."' ></input>";
            }
        }
    }
    public function render_lastname_filter()
    {
        $this->renderSearchText('lastname','text');
	}
	
    public function render_email()
    {
        $class='';
        $attrs='';
        $user_field='email';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
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

        $value=($this->user->getEmail())?htmlentities($this->user->getEmail()):'';
        if ($this->columns[$user_field]['type']=='boolean')
        {
            $this->render_boolean($user_field,$value);
        }
        else if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='email'  value='$value' />";
            if ($this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='email' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='email' value='".$value."' ></input>";
            }
        }
    }
    public function render_email_filter()
    {
        $this->renderSearchText('email','text');
	}
	
    public function render_profile()
    {
        $class='';
        $attrs='';
        $user_field='profile';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
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

        $value=($this->user->getProfile())?htmlentities($this->user->getProfile()):'';
        if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='profile'  value='$value' />";
            if ($this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_profile' $attrs class='$class  edit_input' name='profile' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_profile' $attrs type='text' class='$class ' name='profile' >".$value."</textarea>";
            }
        }
    }
    public function render_profile_filter()
    {
        $this->renderSearchText('profile','textarea');
	}
	
    public function render_created()
    {
        $class='';
        $attrs='';
        $user_field='created';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
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

        $value=($this->user->getCreated())?htmlentities($this->user->getCreated()):'';
        if ($this->columns[$user_field]['type']=='boolean')
        {
            $this->render_boolean($user_field,$value);
        }
        else if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='created'  value='$value' />";
            if ($this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
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
	
    public function render_modified()
    {
        $class='';
        $attrs='';
        $user_field='modified';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
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

        $value=($this->user->getModified())?htmlentities($this->user->getModified()):'';
        if ($this->columns[$user_field]['type']=='boolean')
        {
            $this->render_boolean($user_field,$value);
        }
        else if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='modified'  value='$value' />";
            if ($this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class date edit_input' name='modified' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class date' name='modified' value='".$value."' ></input>";
            }
        }
    }
    public function render_modified_filter()
    {
        ;
	}
	
    public function render_login()
    {
        $class='';
        $attrs='';
        $user_field='login';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
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

        $value=($this->user->getLogin())?htmlentities($this->user->getLogin()):'';
        if ($this->columns[$user_field]['type']=='boolean')
        {
            $this->render_boolean($user_field,$value);
        }
        else if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='login'  value='$value' />";
            if ($this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class datetime edit_input' name='login' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class datetime' name='login' value='".$value."' ></input>";
            }
        }
    }
    public function render_login_filter()
    {
        ;
	}
	
    public function render_role()
    {
        $class='';
        $attrs='';
        $user_field='role';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
        {
            if ($name=='class')
            {
               $class=$value;
            }
            else
            {
               $attrs.=$name.'="'.$value.'" ';
            }
            
        }

        $role = new Role();
        $options=$role->getRoleNames();
        $role_names = array();
        $value=$this->user->getRole();
        if ($this->columns[$user_field]['type']=='boolean')
        {
            $this->render_boolean($user_field,$value);
        }
        else if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='role[]'  value='$value' />";
            if ($this->$this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>".$role->getRoleName($value)."</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
            {
                echo "<span class='edit_input'>";
            } else
            {
               echo "<span>";
            }
            foreach ($options as $key=>$option)
            {
                if ($value & $key)
                {
                    echo "<label><input $attrs type='checkbox' class='$class' checked='checked' value='$key' name=role[]> $option</label>";
                    $role_names[] = $option;
                } else
                {
                    echo "<label><input $attrs type='checkbox' class='$class' value='$key' name=role[]> $option</label>";
                }
             }
             echo "</span>";
             echo '<span class="view_input">'.implode(", ",$role_names).'</span>';
        }
    }
    public function render_role_filter()
    {
		echo "<!-- ".json_encode($this->select_filters)." --> \r\n";
		echo "<select  class='filter' name='users_select_filters[role]' value='' > \r\n";
		echo "<option value=''>All</option> \r\n";
		
		$role = new Role();
		
		foreach ($role->getRoleNames() as $val=>$name)
		{
			
			if ((array_key_exists('role',$this->select_filters)) && ($this->select_filters['role']) && in_array($val,$this->select_filters['role']))
			{
				echo "<option selected='selected' value='".$val."' >".$name."</option>";
			} else if (($val==0) && (in_array("(role < 1)",$this->select_filters))) 
			{
				echo "<option selected='selected' value='".$val."' >".$name."</option>";
			} 
			else
			{
				echo "<option value='".$val."' >".$name."</option>";
			}
		}
		echo "</select> \r\n";		
	}
	
    public function render_salt()
    {
        $class='';
        $attrs='';
        $user_field='salt';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
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

        $value=($this->user->getSalt())?htmlentities($this->user->getSalt()):'';
        if ($this->columns[$user_field]['type']=='boolean')
        {
            $this->render_boolean($user_field,$value);
        }
        else if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='salt'  value='$value' />";
            if ($this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
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
	
    public function render_password_filter()
    {
        ;
	}
	
    public function render_ip()
    {
        $class='';
        $attrs='';
        $user_field='ip';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
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

        $value=($this->user->getIp())?htmlentities($this->user->getIp()):'';
        if ($this->columns[$user_field]['type']=='boolean')
        {
            $this->render_boolean($user_field,$value);
        }
        else if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='ip'  value='$value' />";
            if ($this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='ip' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='ip' value='".$value."' ></input>";
            }
        }
    }
    public function render_ip_filter()
    {
        $this->renderSearchText('ip','text');
	}
	
    public function render_updateip()
    {
        $class='';
        $attrs='';
        $user_field='updateip';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
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

        $value=($this->user->getUpdateip())?htmlentities($this->user->getUpdateip()):'';
        if ($this->columns[$user_field]['type']=='boolean')
        {
            $this->render_boolean($user_field,$value);
        }
        else if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='updateip'  value='$value' />";
            if ($this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='updateip' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='updateip' value='".$value."' ></input>";
            }
        }
    }
    public function render_updateip_filter()
    {
        $this->renderSearchText('updateip','text');
	}
	
    public function render_loginip()
    {
        $class='';
        $attrs='';
        $user_field='loginip';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
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

        $value=($this->user->getLoginip())?htmlentities($this->user->getLoginip()):'';
        if ($this->columns[$user_field]['type']=='boolean')
        {
            $this->render_boolean($user_field,$value);
        }
        else if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='loginip'  value='$value' />";
            if ($this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='loginip' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='loginip' value='".$value."' ></input>";
            }
        }
    }
    public function render_loginip_filter()
    {
        $this->renderSearchText('loginip','text');
	}
	
    public function render_resetkey()
    {
        $class='';
        $attrs='';
        $user_field='resetkey';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
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

        $value=($this->user->getResetkey())?htmlentities($this->user->getResetkey()):'';
        if ($this->columns[$user_field]['type']=='boolean')
        {
            $this->render_boolean($user_field,$value);
        }
        else if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='resetkey'  value='$value' />";
            if ($this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='resetkey' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='resetkey' value='".$value."' ></input>";
            }
        }
    }
    public function render_resetkey_filter()
    {
        $this->renderSearchText('resetkey','text');
	}
	
    public function render_securekey()
    {
        $class='';
        $attrs='';
        $user_field='securekey';
        foreach($this->columns[$user_field]['attributes'] as $name=>$value)
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

        $value=($this->user->getSecurekey())?htmlentities($this->user->getSecurekey()):'';
        if (($this->columns[$user_field]['type']=='hidden') || ($this->columns[$user_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='securekey'  value='$value' />";
            if ($this->columns[$user_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->columns[$user_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_securekey' $attrs class='$class  edit_input' name='securekey' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_securekey' $attrs type='text' class='$class ' name='securekey' >".$value."</textarea>";
            }
        }
    }
    public function render_securekey_filter()
    {
        $this->renderSearchText('securekey','textarea');
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
        $this->columns["firstname"]=array('type'=>"text",'label'=>"Firstname",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["lastname"]=array('type'=>"text",'label'=>"Lastname",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["email"]=array('type'=>"text",'label'=>"Email",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["profile"]=array('type'=>"textarea",'label'=>"Profile",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["created"]=array('type'=>"datetime",'label'=>"Created",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["modified"]=array('type'=>"datetime",'label'=>"Modified",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["login"]=array('type'=>"datetime",'label'=>"Login",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["role"]=array('type'=>"number",'label'=>"Role",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["salt"]=array('type'=>"text",'label'=>"Salt",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["password"]=array('type'=>"password",'label'=>"Password",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["ip"]=array('type'=>"text",'label'=>"Ip",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["updateip"]=array('type'=>"text",'label'=>"Updateip",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["loginip"]=array('type'=>"text",'label'=>"Loginip",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["resetkey"]=array('type'=>"text",'label'=>"Resetkey",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->columns["securekey"]=array('type'=>"textarea",'label'=>"Securekey",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());

        $this->headers = array("#"=>array("label"=>"#","sort"=>0,"class"=>"","attributes"=>array()),"action"=>array("label"=>"Action","sort"=>0,"class"=>"","attributes"=>array()),"icon"=>array("label"=>"Icon","sort"=>0,"class"=>"","attributes"=>array()),"id"=>array("label"=>"Id ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"firstname"=>array("label"=>"Firstname ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"lastname"=>array("label"=>"Lastname ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"email"=>array("label"=>"Email ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"profile"=>array("label"=>"Profile ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"created"=>array("label"=>"Created ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"modified"=>array("label"=>"Modified ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"login"=>array("label"=>"Login ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"role"=>array("label"=>"Role ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"salt"=>array("label"=>"Salt ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"password"=>array("label"=>"Password ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"ip"=>array("label"=>"Ip ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"updateip"=>array("label"=>"Updateip ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"loginip"=>array("label"=>"Loginip ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"resetkey"=>array("label"=>"Resetkey ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"securekey"=>array("label"=>"Securekey ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()));
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
        $this->title = 'Edit User';
        $this->edit_columns = array("id","firstname","lastname","email","profile","created","modified","login","role","salt","password","ip","updateip","loginip","resetkey","securekey");

        $this->security=new TamsiSecurity();
        if (array_key_exists("id",$_GET))
        {
            $this->id=$_GET["id"];
        } else
        {
            $this->id=$_REQUEST["id"];
        }
        $this->user = new User();
        $this->user->find(array("id"=>$this->id));
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
        $this->button_url='?command=editRest&object=User'; //url of the controller where edit form will be posted by ajax
        $this->button_next_url='?command=display_rest&path=Admin.dir/Users.dir/Edit_User.hid.rest.php&id='.$this->user->getId(); //url that will be visited after completing the edit submission
        $this->delete_button_url='?command=deleteRest&object=User'; //url of the controller where delete form will be submitted
        $this->delete_button_next_url='?command=display_rest&path=Admin.dir/Users.dir/List_Users.rest.php'; //url that will be visited after completing the delete submission

        
        $this->columns["id"]['mode']="edit";
        $this->columns["firstname"]['mode']="edit";
        $this->columns["lastname"]['mode']="edit";
        $this->columns["email"]['mode']="edit";
        $this->columns["profile"]['mode']="edit";
        $this->columns["created"]['mode']="edit";
        $this->columns["modified"]['mode']="edit";
        $this->columns["login"]['mode']="edit";
        $this->columns["role"]['mode']="edit";
        $this->columns["salt"]['mode']="edit";
        $this->columns["password"]['mode']="edit";
        $this->columns["ip"]['mode']="edit";
        $this->columns["updateip"]['mode']="edit";
        $this->columns["loginip"]['mode']="edit";
        $this->columns["resetkey"]['mode']="edit";
        $this->columns["securekey"]['mode']="edit";

        
        
    } 
    public function init_new()
    { 
        $this->title = 'New User';
        $this->new_columns = array("id","firstname","lastname","email","profile","created","modified","login","role","salt","password","ip","updateip","loginip","resetkey","securekey");
        
        $this->security=new TamsiSecurity();
        $this->user = new User();
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
        $this->button_url='?command=newRest&object=User'; //url of the controller where the new form will be submitted through ajax
        $this->button_next_url='?command=display_rest&path=Admin.dir/Users.dir/List_Users.rest.php'; //url of the controller that will be visited after completing the new submission

        
        $this->columns["id"]['mode']="new";
        $this->columns["firstname"]['mode']="new";
        $this->columns["lastname"]['mode']="new";
        $this->columns["email"]['mode']="new";
        $this->columns["profile"]['mode']="new";
        $this->columns["created"]['mode']="new";
        $this->columns["modified"]['mode']="new";
        $this->columns["login"]['mode']="new";
        $this->columns["role"]['mode']="new";
        $this->columns["salt"]['mode']="new";
        $this->columns["password"]['mode']="new";
        $this->columns["ip"]['mode']="new";
        $this->columns["updateip"]['mode']="new";
        $this->columns["loginip"]['mode']="new";
        $this->columns["resetkey"]['mode']="new";
        $this->columns["securekey"]['mode']="new";

        
    } 
    public function init_list_params()
    { 
        $this->security=new TamsiSecurity();
        $valid_filters=array("id","firstname","lastname","email","profile","created","modified","login","role","salt","password","ip","updateip","loginip","resetkey","securekey");
        if ($_REQUEST)
        {
            if (array_key_exists("users_selected",$_REQUEST))
            {
                $this->page=$_REQUEST["users_selected"];
            }
            if (array_key_exists("users_path",$_REQUEST))
            {
                $this->path=$_REQUEST["users_path"];
            }
        }
        
        if (!$this->container) $this->container='div.page';
        $this->list_columns = array_merge(array('#','action'),$valid_filters);
        $this->list_form_attributes='';
        $this->custom_html_list='';
        $this->command = 'list';
        $this->list_controls = array('new'=>array('label'=>'New','url'=>'?command=display_rest&path=Admin.dir/Users.dir/New_User.rest.php','container'=>$this->container));
		$this->form_list='form_list_users';
		$this->action='?command=display_php&path=Admin.dir/Users.dir/List_Users.rest.php';
        $this->list_delete_button_url = '?command=deleteRest&object=User'; //url of the controller where delete form will be submitted
        $this->list_delete_button_next_url = '?command=display_rest&base_path=Admin.dir/Users.dir&path=List_Users.rest.php';
        $this->list_delete_selected_url = '?command=deleteSelectedRest&object=User'; //url of the controller where delete of selected objects will be submitted
        $this->enable_paginator = true;
        $this->usr=array();
        $user = new User();
        if (!isset($_SESSION['list_users']))
        {
            $_SESSION['list_users']=array();
            $_SESSION['list_users']['sort_filters']=array();
            $_SESSION['list_users']['sort_order']='';
            $_SESSION['list_users']['select_filters']=array();
            $_SESSION['list_users']['search_texts']=array();
            $_SESSION['list_users']['page']='1';
        }

        if (isset($_REQUEST['users_sort_filters']))
        {
            $sort_lists = $_REQUEST['users_sort_filters'];
            $_SESSION['list_users']['sort_filters']=$sort_lists;
        }
        else
        {
            $sort_lists=$_SESSION['list_users']['sort_filters'];
        }

        if (isset($_REQUEST['users_sort_order']))
        {
            $sort_order = $_REQUEST['users_sort_order'];
            $_SESSION['list_users']['sort_order']=$sort_order;
        }
        else
        {
            $sort_order = $_SESSION['list_users']['sort_order'];
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
        if (isset($_REQUEST['users_select_filters']))
        {
            foreach($_REQUEST['users_select_filters'] as $key=>$value)
            {
                if (!(is_numeric($key)) && in_array($key,$valid_filters))
                {
                    $this->select_filters[$key] = $value;
                }
            }
            $_SESSION['list_users']['select_filters']=$this->select_filters;
        }
        else
        {
            $this->select_filters=array_merge($_SESSION['list_users']['select_filters'],$this->select_filters);
        }
        
        
		if (isset($this->select_filters)) 
		{
			if (array_key_exists('role',$this->select_filters))
			{
				if (strlen(trim($this->select_filters['role']))==0)
				{
					unset($this->select_filters['role']);
				}
				else if ($this->select_filters['role']=='0')
				{
					unset($this->select_filters['role']);
					$this->select_filters[]="(role < 1)";
				}
				else
				{
					$vals=array();
					for($i=0;$i<=15;$i++)
					{
						if ($i & $this->select_filters['role'])
						{
							$vals[] = $i;
						}
					}
					$this->select_filters['role']=$vals;
				}
				
			}
		}
        
        




        $search='';
        if (isset($_REQUEST['users_search_texts']))
        {
            $this->search_texts = $_REQUEST['users_search_texts'];
            $_SESSION['list_users']['search_texts']=$this->search_texts;
        }
        else
        {
            $this->search_texts = $_SESSION['list_users']['search_texts'];
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


		$this->user = $user;
    }
    
    public function load_list()
    { 
        if (!($this->items_per_page)) $this->items_per_page = 25;
        if (isset($_REQUEST['users_items_per_page']))
        {
            $this->items_per_page = $_REQUEST['users_items_per_page'];
            $_SESSION['list_users']['items_per_page']=$this->items_per_page;
        } else
        {
            if (array_key_exists('list_users',$_SESSION) && array_key_exists('items_per_page',$_SESSION['list_users']))
            {
                $this->items_per_page = $_SESSION['list_users']['items_per_page'];
            }            
        }
        
        $this->pages=$this->user->getPageCount($this->select_filters,$this->sort_filters,$this->items_per_page,true);
        $this->total_db_items = $this->user->total_db_items;
        $this->page = 1;//default to 1 in case not provided below
        if (isset($_REQUEST['users_page']))
        {
            $this->page = $_REQUEST['users_page'];
            $_SESSION['list_users']['page']=$this->page;
        } else
        {
            if (array_key_exists('list_users',$_SESSION) && array_key_exists('page',$_SESSION['list_users']))
            {
                $this->page = $_SESSION['list_users']['page'];
            }            
        }
        if (($this->page < 1) || ($this->page > $this->pages)) $this->page = 1;

        
        $this->users = $this->user->findAll($this->select_filters,$this->sort_filters,$this->items_per_page,$this->page);
    }
    
    public function init_list()
    { 
        $this->title = 'Users';
        $this->init_list_params();
        $this->load_list();
    } 
    

    public function renderDeleteCheckbox($user)
    {
        echo '<input type="checkbox" class="select_checkbox" id="'.$user->getId().'" value="'.$user->getId().'" name="delete_selected[]" /><br>';
    }
    
    public function renderEditAction($user)
    {
        echo "<button type='button' class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Users.dir&path=Edit_User.hid.rest.php&id=".$user->getId()."&object=User' href='#' ><li class='fa fa-pencil'></li> edit</button>";
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
    
    public function renderDeleteAction($user,$next_url,$delete_url)
    {
        $mx_next_url='';
        if ($next_url)
        {
            $mx_next_url = 'mx-next="'.$next_url.'"';
        }
        echo '<button type="button" class="button mx- danger" mx-confirm="Are you sure to delete User of id='.$user->getId().'?" mx-click="'.$delete_url.'&id='.$user->getId().'" href="#" mx-container="'.$this->container.'" '.$mx_next_url.' ><li class="fa fa-trash" ></li> del</button>';
    }
    
    public function renderViewAction($user)
    {
        echo "<button type='button' class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Users.dir&path=View_User.hid.rest.php&id=".$user->getId()."&object=User' href='#' ><li class='fa fa-eye'></li> view</button>";
    }

    public function renderListAction()
    {
        echo "<button type='button' class='button mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Users.dir&path=List_Users.rest.php&object=User'  ><li class='fa fa-list'></li> List</button>";
    }

    public function renderActions($user,$class="",$edit=TRUE)
    {
        if ($edit)
        {
            $this->renderDeleteCheckbox($user);
            $this->renderEditAction($user);
            $this->renderDeleteAction($user,$this->list_delete_button_next_url,$this->list_delete_button_url);
        } else
        {
            $this->renderViewAction($user);
        }
    }
    
	public function renderEditControls()
    {
		echo '<div class="control">';		
		$this->renderSaveAction($this->button_next_url);
        $this->renderDeleteAction($this->user,$this->delete_button_next_url,$this->delete_button_url);
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
    
   public function get_icon($user)
   {
		echo '<img class="icon" src="'.$this->paths->base_url.'/img/displaypng.php?id='.$_SESSION['user_id'].'&image='.$this->paths->user_image.'/users/'.$user->getId().'_users_icon.png&key='.$this->current_user->getSecurekey().'" />';
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
		echo '<input class="search_text '.$type.'" type="text" name="users_search_texts['.$field.']" value="'.$value.'"></input><button type="button" class="search_text_button clear" ><li class="fa fa-times"></li></button>';
		echo '</div>';
    }
    
    public function get_id($user)
    {
        echo "<button type='button' class='mx-' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Users.dir&path=Edit_User.hid.rest.php&id=".$user->getId()."&object=User'  ><i class='fa fa-edit'></i>".$user->getId()."</button>";
    }
    
    public function renderPaginator()
    {?>
        <script>
            <?php
            if (!isset($this->container)) $this->container = 'div.page';
            if (!isset($this->form_list)) $this->form_list = 'form_list_users';
            ?>
            function users_paginate(page) 
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
                form.find('input.field_items_per_page').val($('#users_items_per_page').val());
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
                <select name="users_items_per_page" id="users_items_per_page" style="width:60px" onchange="users_paginate(<?php echo $this->page;?>)">
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
                        <li class=""><button type="button" onclick="users_paginate(1)">1</button></li>
                        <?php } else
                        {?>
                        <li class="inactive">&nbsp;</li>		
                        <?php
                        }
                        if ($this->page>1) {?>
                        <li class="prev"><button type="button" onclick="users_paginate(<?php echo ($this->page-1);?>)"><i class="fa fa-chevron-left"></i></button></li>
                        <?php }
                        else {
                        ?>
                        <li class="inactive"><i class="fa fa-chevron-left"></i></li>
                        <?php }?>
                        <li class="active"><?php echo $this->page;?></li>
                        <?php
                        if (($this->pages-$this->page)>0) {?>
                        <li class="next"><button type="button" onclick="users_paginate(<?php echo ($this->page+1);?>)"><i class="fa fa-chevron-right"></i></button></li>
                        <?php
                        } else {?>
                        <li class="inactive"><i class="fa fa-chevron-right"></i></li>
                        <?php
                        }?>
                        <?php
                        if (($this->pages-$this->page)>1) {?>
                        <li class="next"><button type="button" onclick="users_paginate(<?php echo $this->pages;?>)"><?php echo $this->pages;?></button></li>
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
                <input class='page_first first ' type='button' value='1' name='first' onclick='users_paginate(1)' align='center'></input> 
                <?php
                if ($this->page>2)
                {?>
                    <input type='button'  value='<?php echo ($this->page-1);?>' name='prev_page' class='left ' onclick='users_paginate(<?php echo ($this->page-1);?>)' align='center'></input>
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
                    <input class='page_next right' type='button' name='next_page' align='center' value='<?php echo ($this->page+1);?>' onclick='users_paginate(<?php echo ($this->page+1);?>)' ></input>
                <?php
                } else
                {?>
                    <input class='page_next right' type='button' name='page' align='center' value=''  ></input>
                <?php
                }?>    
                <input type='button' class='page_last last ' value='<?php echo $this->pages;?>'  name='last_page' onclick='users_paginate(<?php echo $this->pages;?>)' align='center'></input>
                <input type='hidden' page='1' name="current_page" />
                </div>
            <?php
            }
        }?>
            </div>
            <div class="column items" >
                    <?php
                    $start = ($this->page-1)*$this->items_per_page + 1;
                    $end = $start+count($this->users)-1;
                    ?>
                    <div class="span5 dataTables_info" id="tableInfo">Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $this->total_db_items;?> entries</div>            
            </div>
        </div>
<?php
    }

    public $src_;
    public $src__icon;
    public function renderUploadImage($user,$disable__icon=FALSE,$redirect_after_image_upload='',$img_attributes='',$edit_enabled=TRUE)
    {
        if (!isset($this->src_)) $this->src_=$this->paths->base_url.'/img/displaypng.php?id='.$this->current_user->getId().'&image='.$this->user->getImage('').'&key='.urlencode($this->current_user->getSecurekey()).'&rnd='.rand(1,100);    
        if (!isset($this->src__icon)) $this->src__icon='img/displaypng.php?id='.$this->current_user->getId().'&image='.$this->user->getImageIcon('').'&key='.urlencode($this->current_user->getSecurekey()).'&rnd='.rand(1,100);    
        if ($edit_enabled)
        {
            echo '<form id="upload_" action="<?php echo $this->paths->base_url;?>/?command=uploadImage&object=User&id='.$user->getId().'" class="form image round-corners" method="post" style="float:center; display:block" enctype="multipart/form-data" >';
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
            echo '<input type="hidden" name="id" value="'.$this->user->getId().'" />';
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

    public function renderImage($user)
    {
        echo '<span>'.$user->getImage().". (See upload button above this table)</span>\n";
    }
}
