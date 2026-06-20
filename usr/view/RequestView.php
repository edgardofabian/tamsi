<?php

require_once 'RequestViewBase.php';
class RequestView extends RequestViewBase
{
    //functions you can override
    /*
    public function render_id()
    {
        $class='';
        $attrs='';
        $request_field='id';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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

        $value=($this->request->getId())?htmlentities($this->request->getId()):'';
        if ($this->fields[$request_field]['type']=='boolean')
        {
            $this->render_boolean($request_field,$value);
        }
        else if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='id'  value='$value' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
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
	
    public function render_user_id_client()
    {
        $sids_str=$this->request->getUserIdClient();
        
        $class='';
        $attrs='';
        $request_field='user_id_client';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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
        $value=($this->request->getUserIdClient())?htmlentities($this->request->getUserIdClient()):'';
        
        if ($this->fields[$request_field]['type']=='boolean')
        {
            $this->render_boolean($request_field,$value);
        }
        else if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input type='hidden' $attrs name='user_id_client'  value='$sids_str' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span >".$request->getClient()."</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
            {
                echo "<select $attrs class='$class edit_input'  name='user_id_client' >\n";
            } else
            {
                echo "<select $attrs class='$class'  name='user_id_client' >\n";
            }
            $selected_ids = explode(",",$sids_str);
            
            if (array_key_exists('[FILTER_FIELD]',$this->fields) && array_key_exists('options',$this->fields['[FILTER_FIELD]']) && ($this->fields['[FILTER_FIELD]']['options']))
            {
                $options = $this->fields['[FILTER_FIELD]']['options'];
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
            
                $client = new User();
                $param=array();
                if (array_key_exists('option_filters',$this->fields[$request_field]) && $this->fields[$request_field]['option_filters'])
                {
                    $param['filter'] = $this->fields[$request_field]['option_filters'];
                }
                if (array_key_exists('option_sort',$this->fields[$request_field]) && $this->fields[$request_field]['option_sort'])
                {
                    $param['sort'] = $this->fields[$request_field]['option_sort'];
                }
                if (array_key_exists('option_select',$this->fields[$request_field]) && $this->fields[$request_field]['option_select'])
                {
                    $param['select'] = $this->fields[$request_field]['option_select'];
                }    
                
                $objs=$client->getAll($param);
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
            if ($this->fields[$request_field]['mode']=='edit') echo "<span class='view_input'>".implode(',',$data_names)."</span>";
       }
    }
    public function render_user_id_client_filter()
    {
		echo "<select  class='filter' name='[TABLE_NAME]_select_filters[user_id_client]' value='' > \r\n";
		echo "<option value=''>All</option> \r\n";
	
		if (in_array('0',$this->user_id_client_lst)) 
		{
			if (array_key_exists('user_id_client',$this->select_filters) && ($this->select_filters['user_id_client']=='0'))
			{
				echo "<option selected='selected' value='0' >Unassigned</option>";
			} else
			{
				echo "<option value='0' >Unassigned</option>";
			}
		}
		$user_handle = new User();
		$users = $user_handle->findAll(array('id'=>$this->user_id_client_lst),array('lastname'=>'ASC'));
		foreach ($users as $user)
		{
			if (($this->select_filters['user_id_client']) && ($this->select_filters['user_id_client']==$user->getId()))
			{
				echo "<option selected='selected' value='".$user->getId()."' >".$user->getName()."</option>";
			} else
			{
				echo "<option value='".$user->getId()."' >".$user->getName()."</option>";
			}
		}
		echo "</select> \r\n";		
	}
	
    public function render_user_id_provider()
    {
        $sids_str=$this->request->getUserIdProvider();
        
        $class='';
        $attrs='';
        $request_field='user_id_provider';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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
        $value=($this->request->getUserIdProvider())?htmlentities($this->request->getUserIdProvider()):'';
        
        if ($this->fields[$request_field]['type']=='boolean')
        {
            $this->render_boolean($request_field,$value);
        }
        else if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input type='hidden' $attrs name='user_id_provider'  value='$sids_str' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span >".$request->getProvider()."</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
            {
                echo "<select $attrs class='$class edit_input'  name='user_id_provider' >\n";
            } else
            {
                echo "<select $attrs class='$class'  name='user_id_provider' >\n";
            }
            $selected_ids = explode(",",$sids_str);
            
            if (array_key_exists('[FILTER_FIELD]',$this->fields) && array_key_exists('options',$this->fields['[FILTER_FIELD]']) && ($this->fields['[FILTER_FIELD]']['options']))
            {
                $options = $this->fields['[FILTER_FIELD]']['options'];
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
            
                $provider = new User();
                $param=array();
                if (array_key_exists('option_filters',$this->fields[$request_field]) && $this->fields[$request_field]['option_filters'])
                {
                    $param['filter'] = $this->fields[$request_field]['option_filters'];
                }
                if (array_key_exists('option_sort',$this->fields[$request_field]) && $this->fields[$request_field]['option_sort'])
                {
                    $param['sort'] = $this->fields[$request_field]['option_sort'];
                }
                if (array_key_exists('option_select',$this->fields[$request_field]) && $this->fields[$request_field]['option_select'])
                {
                    $param['select'] = $this->fields[$request_field]['option_select'];
                }    
                
                $objs=$provider->getAll($param);
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
            if ($this->fields[$request_field]['mode']=='edit') echo "<span class='view_input'>".implode(',',$data_names)."</span>";
       }
    }
    public function render_user_id_provider_filter()
    {
		echo "<select  class='filter' name='[TABLE_NAME]_select_filters[user_id_provider]' value='' > \r\n";
		echo "<option value=''>All</option> \r\n";
	
		if (in_array('0',$this->user_id_provider_lst)) 
		{
			if (array_key_exists('user_id_provider',$this->select_filters) && ($this->select_filters['user_id_provider']=='0'))
			{
				echo "<option selected='selected' value='0' >Unassigned</option>";
			} else
			{
				echo "<option value='0' >Unassigned</option>";
			}
		}
		$user_handle = new User();
		$users = $user_handle->findAll(array('id'=>$this->user_id_provider_lst),array('lastname'=>'ASC'));
		foreach ($users as $user)
		{
			if (($this->select_filters['user_id_provider']) && ($this->select_filters['user_id_provider']==$user->getId()))
			{
				echo "<option selected='selected' value='".$user->getId()."' >".$user->getName()."</option>";
			} else
			{
				echo "<option value='".$user->getId()."' >".$user->getName()."</option>";
			}
		}
		echo "</select> \r\n";		
	}
	
    public function render_status()
    {
        $class='';
        $attrs='';
        $request_field='status';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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

        $value=($this->request->getStatus())?htmlentities($this->request->getStatus()):'';
        if ($this->fields[$request_field]['type']=='boolean')
        {
            $this->render_boolean($request_field,$value);
        }
        else if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='status'  value='$value' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='status' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='status' value='".$value."' ></input>";
            }
        }
    }
    public function render_status_filter()
    {
        $this->renderSearchText('status','text');
	}
	
    public function render_date()
    {
        $class='';
        $attrs='';
        $request_field='date';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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

        $value=($this->request->getDate())?htmlentities($this->request->getDate()):'';
        if ($this->fields[$request_field]['type']=='boolean')
        {
            $this->render_boolean($request_field,$value);
        }
        else if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='date'  value='$value' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class date edit_input' name='date' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class date' name='date' value='".$value."' ></input>";
            }
        }
    }
    public function render_date_filter()
    {
        $this->renderSearchText('date','date');
	}
	
    public function render_output()
    {
        $class='';
        $attrs='';
        $request_field='output';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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

        $value=($this->request->getOutput())?htmlentities($this->request->getOutput()):'';
        if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='output'  value='$value' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_output' $attrs class='$class  edit_input' name='output' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_output' $attrs type='text' class='$class ' name='output' >".$value."</textarea>";
            }
        }
    }
    public function render_output_filter()
    {
        $this->renderSearchText('output','textarea');
	}
	
    public function render_requirements()
    {
        $class='';
        $attrs='';
        $request_field='requirements';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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

        $value=($this->request->getRequirements())?htmlentities($this->request->getRequirements()):'';
        if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='requirements'  value='$value' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_requirements' $attrs class='$class  edit_input' name='requirements' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_requirements' $attrs type='text' class='$class ' name='requirements' >".$value."</textarea>";
            }
        }
    }
    public function render_requirements_filter()
    {
        $this->renderSearchText('requirements','textarea');
	}
	
    public function render_products_ids()
    {
        $sids_str=$this->request->getProductsIds();
        
        $class='';
        $attrs='';
        $request_field='products_ids';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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
        
        if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input type='hidden' $attrs name='products_ids[]'  value='$sids_str' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span >".$this->request->getProduct()."</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
            {
                echo "<select $attrs class='$class edit_input' multiple='' name='products_ids[]' >\n";
            } else
            {
                echo "<select $attrs class='$class' multiple='' name='products_ids[]' >\n";
            }
            $selected_ids = explode(",",$sids_str);

            if (array_key_exists('[FILTER_FIELD]',$this->fields) && array_key_exists('options',$this->fields['[FILTER_FIELD]']) && ($this->fields['[FILTER_FIELD]']['options']))
            {
                $options = $this->fields['[FILTER_FIELD]']['options'];
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
                $product = new Product();
                $param = array();
                if (array_key_exists('option_filters',$this->fields[$request_field]) && $this->fields[$request_field]['option_filters'])
                {
                    $param['filter'] = $this->fields[$request_field]['option_filters'];
                }
                if (array_key_exists('option_sort',$this->fields[$request_field]) && $this->fields[$request_field]['option_sort'])
                {
                    $param['sort'] = $this->fields[$request_field]['option_sort'];
                }
                if (array_key_exists('option_select',$this->fields[$request_field]) && $this->fields[$request_field]['option_select'])
                {
                    $param['select'] = $this->fields[$request_field]['option_select'];
                }    
                
                $objs=$product->getAll($param);
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
            if ($this->fields[$request_field]['mode']=='edit') echo "<span class='view_input'>".implode(',',$data_names)."</span>";
       }
    }
    public function render_products_ids_filter()
    {
		echo "<select  class='filter' name='[TABLE_NAME]_select_filters[products_ids]' value='' > \r\n";
		echo "<option value=''>All</option> \r\n";
	
		if (in_array('0',$this->products_ids_lst)) 
		{
			if (array_key_exists('products_ids',$this->select_filters) && ($this->select_filters['products_ids']=='0'))
			{
				echo "<option selected='selected' value='0' >Unassigned</option>";
			} else
			{
				echo "<option value='0' >Unassigned</option>";
			}
		}
		$product_handle = new Product();
		$products = $product_handle->findAll(array('id'=>$this->products_ids_lst),array('name'=>'ASC'));
		foreach ($products as $product)
		{
			if (($this->select_filters['products_ids']) && ($this->select_filters['products_ids']==$product->getId()))
			{
				echo "<option selected='selected' value='".$product->getId()."' >".$product->getName()."</option>";
			} else
			{
				echo "<option value='".$product->getId()."' >".$product->getName()."</option>";
			}
		}
		echo "</select> \r\n";		
	}
	
    public function render_services_ids()
    {
        $sids_str=$this->request->getServicesIds();
        
        $class='';
        $attrs='';
        $request_field='services_ids';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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
        
        if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input type='hidden' $attrs name='services_ids[]'  value='$sids_str' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span >".$this->request->getService()."</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
            {
                echo "<select $attrs class='$class edit_input' multiple='' name='services_ids[]' >\n";
            } else
            {
                echo "<select $attrs class='$class' multiple='' name='services_ids[]' >\n";
            }
            $selected_ids = explode(",",$sids_str);

            if (array_key_exists('[FILTER_FIELD]',$this->fields) && array_key_exists('options',$this->fields['[FILTER_FIELD]']) && ($this->fields['[FILTER_FIELD]']['options']))
            {
                $options = $this->fields['[FILTER_FIELD]']['options'];
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
                $service = new Service();
                $param = array();
                if (array_key_exists('option_filters',$this->fields[$request_field]) && $this->fields[$request_field]['option_filters'])
                {
                    $param['filter'] = $this->fields[$request_field]['option_filters'];
                }
                if (array_key_exists('option_sort',$this->fields[$request_field]) && $this->fields[$request_field]['option_sort'])
                {
                    $param['sort'] = $this->fields[$request_field]['option_sort'];
                }
                if (array_key_exists('option_select',$this->fields[$request_field]) && $this->fields[$request_field]['option_select'])
                {
                    $param['select'] = $this->fields[$request_field]['option_select'];
                }    
                
                $objs=$service->getAll($param);
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
            if ($this->fields[$request_field]['mode']=='edit') echo "<span class='view_input'>".implode(',',$data_names)."</span>";
       }
    }
    public function render_services_ids_filter()
    {
		echo "<select  class='filter' name='[TABLE_NAME]_select_filters[services_ids]' value='' > \r\n";
		echo "<option value=''>All</option> \r\n";
	
		if (in_array('0',$this->services_ids_lst)) 
		{
			if (array_key_exists('services_ids',$this->select_filters) && ($this->select_filters['services_ids']=='0'))
			{
				echo "<option selected='selected' value='0' >Unassigned</option>";
			} else
			{
				echo "<option value='0' >Unassigned</option>";
			}
		}
		$service_handle = new Service();
		$services = $service_handle->findAll(array('id'=>$this->services_ids_lst),array('name'=>'ASC'));
		foreach ($services as $service)
		{
			if (($this->select_filters['services_ids']) && ($this->select_filters['services_ids']==$service->getId()))
			{
				echo "<option selected='selected' value='".$service->getId()."' >".$service->getName()."</option>";
			} else
			{
				echo "<option value='".$service->getId()."' >".$service->getName()."</option>";
			}
		}
		echo "</select> \r\n";		
	}
	
    public function render_external_products()
    {
        $class='';
        $attrs='';
        $request_field='external_products';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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

        $value=($this->request->getExternalProducts())?htmlentities($this->request->getExternalProducts()):'';
        if ($this->fields[$request_field]['type']=='boolean')
        {
            $this->render_boolean($request_field,$value);
        }
        else if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='external_products'  value='$value' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='external_products' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='external_products' value='".$value."' ></input>";
            }
        }
    }
    public function render_external_products_filter()
    {
        $this->renderSearchText('external_products','text');
	}
	
    public function render_questionnaire_id()
    {
        $class='';
        $attrs='';
        $request_field='questionnaire_id';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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

        $value=($this->request->getQuestionnaireId())?htmlentities($this->request->getQuestionnaireId()):'';
        if ($this->fields[$request_field]['type']=='boolean')
        {
            $this->render_boolean($request_field,$value);
        }
        else if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='questionnaire_id'  value='$value' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='questionnaire_id' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='questionnaire_id' value='".$value."' ></input>";
            }
        }
    }
    public function render_questionnaire_id_filter()
    {
		echo "<select  class='filter' name='[TABLE_NAME]_select_filters[questionnaire_id]' value='' > \r\n";
		echo "<option value=''>All</option> \r\n";
	
		if (in_array('0',$this->questionnaire_id_lst)) 
		{
			if (array_key_exists('questionnaire_id',$this->select_filters) && ($this->select_filters['questionnaire_id']=='0'))
			{
				echo "<option selected='selected' value='0' >Unassigned</option>";
			} else
			{
				echo "<option value='0' >Unassigned</option>";
			}
		}
		$questionnaire_handle = new Questionnaire();
		$questionnaires = $questionnaire_handle->findAll(array('id'=>$this->questionnaire_id_lst),array('id'=>'ASC'));
		foreach ($questionnaires as $questionnaire)
		{
			if (($this->select_filters['questionnaire_id']) && ($this->select_filters['questionnaire_id']==$questionnaire->getId()))
			{
				echo "<option selected='selected' value='".$questionnaire->getId()."' >".$questionnaire->getName()."</option>";
			} else
			{
				echo "<option value='".$questionnaire->getId()."' >".$questionnaire->getName()."</option>";
			}
		}
		echo "</select> \r\n";		
	}
	
    public function render_answers()
    {
        $class='';
        $attrs='';
        $request_field='answers';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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

        $value=($this->request->getAnswers())?htmlentities($this->request->getAnswers()):'';
        if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='answers'  value='$value' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_answers' $attrs class='$class  edit_input' name='answers' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_answers' $attrs type='text' class='$class ' name='answers' >".$value."</textarea>";
            }
        }
    }
    public function render_answers_filter()
    {
        $this->renderSearchText('answers','textarea');
	}
	
    public function render_notes()
    {
        $class='';
        $attrs='';
        $request_field='notes';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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

        $value=($this->request->getNotes())?htmlentities($this->request->getNotes()):'';
        if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='notes'  value='$value' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<textarea id='edit_notes' $attrs class='$class  edit_input' name='notes' >".$value."</textarea>";                
            } else
            {
                echo "<textarea id='edit_notes' $attrs type='text' class='$class ' name='notes' >".$value."</textarea>";
            }
        }
    }
    public function render_notes_filter()
    {
        $this->renderSearchText('notes','textarea');
	}
	
    public function render_payment()
    {
        $class='';
        $attrs='';
        $request_field='payment';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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

        $value=($this->request->getPayment())?htmlentities($this->request->getPayment()):'';
        if ($this->fields[$request_field]['type']=='boolean')
        {
            $this->render_boolean($request_field,$value);
        }
        else if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='payment'  value='$value' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class  edit_input' name='payment' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class ' name='payment' value='".$value."' ></input>";
            }
        }
    }
    public function render_payment_filter()
    {
        $this->renderSearchText('payment','number');
	}
	
    public function render_completed()
    {
        $class='';
        $attrs='';
        $request_field='completed';
        foreach($this->fields[$request_field]['attributes'] as $name=>$value)
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

        $value=($this->request->getCompleted())?htmlentities($this->request->getCompleted()):'';
        if ($this->fields[$request_field]['type']=='boolean')
        {
            $this->render_boolean($request_field,$value);
        }
        else if (($this->fields[$request_field]['type']=='hidden') || ($this->fields[$request_field]['type']=='readonly'))
        {
            echo "<input $attrs type='hidden' name='completed'  value='$value' />";
            if ($this->fields[$request_field]['type']=='readonly')
            {
                echo "<span>$value</span>";
            }
        } else
        {
            if ($this->fields[$request_field]['mode']=='edit')
            {
                echo "<span class='view_input'>".$value."</span>";
                echo "<input $attrs type='text' class='$class datetime edit_input' name='completed' value='".$value."' ></input>";
            } else
            {
                echo "<input $attrs type='text' class='$class datetime' name='completed' value='".$value."' ></input>";
            }
        }
    }
    public function render_completed_filter()
    {
        ;
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

        
        
        $this->fields["id"]=array('type'=>"number",'label'=>"Id",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->fields["user_id_client"]=array('type'=>"select",'label'=>"UserIdClient",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->fields["user_id_provider"]=array('type'=>"select",'label'=>"UserIdProvider",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->fields["status"]=array('type'=>"text",'label'=>"Status",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->fields["date"]=array('type'=>"date",'label'=>"Date",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->fields["output"]=array('type'=>"textarea",'label'=>"Output",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->fields["requirements"]=array('type'=>"textarea",'label'=>"Requirements",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->fields["products_ids"]=array('type'=>"select_multiple",'label'=>"ProductsIds",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->fields["services_ids"]=array('type'=>"select_multiple",'label'=>"ServicesIds",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->fields["external_products"]=array('type'=>"text",'label'=>"ExternalProducts",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->fields["questionnaire_id"]=array('type'=>"number",'label'=>"QuestionnaireId",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->fields["answers"]=array('type'=>"textarea",'label'=>"Answers",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->fields["notes"]=array('type'=>"textarea",'label'=>"Notes",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->fields["payment"]=array('type'=>"number",'label'=>"Payment",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());
        $this->fields["completed"]=array('type'=>"datetime",'label'=>"Completed",'mode'=>"list",'attributes'=>array(),'option_filters'=>array());

        $this->headers = array("#"=>array("label"=>"#","sort"=>0,"class"=>"","attributes"=>array()),"action"=>array("label"=>"Action","sort"=>0,"class"=>"","attributes"=>array()),"id"=>array("label"=>"Id ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"user_id_client"=>array("label"=>"Client ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"user_id_provider"=>array("label"=>"Provider ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"status"=>array("label"=>"Status ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"date"=>array("label"=>"Date ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"output"=>array("label"=>"Output ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"requirements"=>array("label"=>"Requirements ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"products_ids"=>array("label"=>"Products ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"services_ids"=>array("label"=>"Services ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"external_products"=>array("label"=>"External Products ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"questionnaire_id"=>array("label"=>"Questionnaire Id ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"answers"=>array("label"=>"Answers ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"notes"=>array("label"=>"Notes ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"payment"=>array("label"=>"Payment ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()),"completed"=>array("label"=>"Completed ","sort"=>1,"search"=>1,"class"=>"","attributes"=>array()));
   } 
    
   public function setLabels($labels)
   {
      foreach($labels as $field=>$label)
      {
         if (array_key_exists($field,$this->fields) && array_key_exists('label',$this->fields[$field]))
         {
             $this->fields[$field]['label']=$label;
         } else
         if (array_key_exists($field,$this->headers))
         {
             $this->headers[$field]['label']=$label;
         }
         
      }
   }
    
    public function init_edit()
    { 
        $this->title = 'Edit Request';
        $this->edit_fields = array("id","user_id_client","user_id_provider","status","date","output","requirements","products_ids","services_ids","external_products","questionnaire_id","answers","notes","payment","completed");
        $this->security=new TamsiSecurity();
        if (array_key_exists("id",$_GET))
        {
            $this->id=$_GET["id"];
        } else
        {
            $this->id=$_REQUEST["id"];
        }
        $this->request = new Request();
        $this->request->find(array("id"=>$this->id));
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
        $this->edit_field_header = 'Field';
        $this->edit_field_value_header = 'Value';
        $this->command = 'edit';
        
        $this->edit_form_attributes=''; //attributes added to the form
        $this->edit_button_name='Update'; //Visible name of the Edit submit button
        $this->edit_button_url='?command=editRest&object=Request'; //url of the controller where edit form will be posted by ajax
        $this->edit_button_next_url='?command=display_rest&path=Admin.dir/Requests.dir/Edit Request.hid.rest.php&id='.$this->request->getId(); //url that will be visited after completing the edit submission
        $this->edit_delete_button_url='?command=deleteRest&object=Request'; //url of the controller where delete form will be submitted
        $this->edit_delete_button_next_url='?command=display_rest&path=Admin.dir/Requests.dir/List Requests.rest.php'; //url that will be visited after completing the delete submission

        
        $this->fields["id"]['mode']="edit";
        $this->fields["user_id_client"]['mode']="edit";
        $this->fields["user_id_client"]["attributes"]["class"]="chosen";
        $this->fields["user_id_provider"]['mode']="edit";
        $this->fields["user_id_provider"]["attributes"]["class"]="chosen";
        $this->fields["status"]['mode']="edit";
        $this->fields["date"]['mode']="edit";
        $this->fields["output"]['mode']="edit";
        $this->fields["requirements"]['mode']="edit";
        $this->fields["products_ids"]['mode']="edit";
        $this->fields["products_ids"]["attributes"]["class"]="chosen";
        $this->fields["services_ids"]['mode']="edit";
        $this->fields["services_ids"]["attributes"]["class"]="chosen";
        $this->fields["external_products"]['mode']="edit";
        $this->fields["questionnaire_id"]['mode']="edit";
        $this->fields["answers"]['mode']="edit";
        $this->fields["notes"]['mode']="edit";
        $this->fields["payment"]['mode']="edit";
        $this->fields["completed"]['mode']="edit";

        
        
    } 
    public function init_new()
    { 
        $this->title = 'New Request';
        $this->new_fields = array("id","user_id_client","user_id_provider","status","date","output","requirements","products_ids","services_ids","external_products","questionnaire_id","answers","notes","payment","completed");
        $this->security=new TamsiSecurity();
        $this->request = new Request();
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
        $this->new_field_header = 'Field';
        $this->new_field_value_header = 'Value';
        $this->command = 'new';

        $this->new_form_attributes=''; //attributes added to the new form
        $this->new_button_name='Create'; //Label of the new submit button
        $this->new_button_url='?command=newRest&object=Request'; //url of the controller where the new form will be submitted through ajax
        $this->new_button_next_url='?command=display_rest&path=Admin.dir/Requests.dir/List Requests.rest.php'; //url of the controller that will be visited after completing the new submission

        
        $this->fields["id"]['mode']="new";
        $this->fields["user_id_client"]['mode']="new";
        $this->fields["user_id_client"]["attributes"]["class"]="chosen";
        $this->fields["user_id_provider"]['mode']="new";
        $this->fields["user_id_provider"]["attributes"]["class"]="chosen";
        $this->fields["status"]['mode']="new";
        $this->fields["date"]['mode']="new";
        $this->fields["output"]['mode']="new";
        $this->fields["requirements"]['mode']="new";
        $this->fields["products_ids"]['mode']="new";
        $this->fields["products_ids"]["attributes"]["class"]="chosen";
        $this->fields["services_ids"]['mode']="new";
        $this->fields["services_ids"]["attributes"]["class"]="chosen";
        $this->fields["external_products"]['mode']="new";
        $this->fields["questionnaire_id"]['mode']="new";
        $this->fields["answers"]['mode']="new";
        $this->fields["notes"]['mode']="new";
        $this->fields["payment"]['mode']="new";
        $this->fields["completed"]['mode']="new";

        
    } 
    public function init_list_params()
    { 
        $this->security=new TamsiSecurity();
        $valid_filters=array("id","user_id_client","user_id_provider","status","date","output","requirements","products_ids","services_ids","external_products","questionnaire_id","answers","notes","payment","completed");
        if ($_REQUEST)
        {
            if (array_key_exists("requests_selected",$_REQUEST))
            {
                $this->page=$_REQUEST["requests_selected"];
            }
            if (array_key_exists("requests_path",$_REQUEST))
            {
                $this->path=$_REQUEST["requests_path"];
            }
        }
        $this->list_fields = array_merge(array('#','action'),$valid_filters);
        $this->list_form_attributes='';
        $this->custom_html_list='';
        $this->command = 'list';
        $this->list_controls = array('new'=>array('label'=>'New','url'=>'?command=display_rest&path=Admin.dir/Requests.dir/New Request.rest.php','container'=>$this->container));
		$this->form_list='form_list_requests';
		$this->action='?command=display_php&path=Admin.dir/Requests.dir/List Requests.rest.php';
        $this->list_delete_button_url = '?command=deleteRest&object=Request'; //url of the controller where delete form will be submitted
        $this->list_delete_button_next_url = '?command=display_rest&base_path=Admin.dir/Requests.dir&path=List Requests.rest.php';
        $this->list_delete_selected_url = '?command=deleteSelectedRest&object=Request'; //url of the controller where delete of selected objects will be submitted
        $this->enable_paginator = true;

        if (!$this->container) $this->container='div.page';
        
        $request = new Request();
        if (!isset($_SESSION['list_requests']))
        {
            $_SESSION['list_requests']=array();
            $_SESSION['list_requests']['sort_filters']=array();
            $_SESSION['list_requests']['sort_order']='';
            $_SESSION['list_requests']['select_filters']=array();
            $_SESSION['list_requests']['search_texts']=array();
            $_SESSION['list_requests']['page']='1';
        }

        if (isset($_REQUEST['requests_sort_filters']))
        {
            $sort_lists = $_REQUEST['requests_sort_filters'];
            $_SESSION['list_requests']['sort_filters']=$sort_lists;
        }
        else
        {
            $sort_lists=$_SESSION['list_requests']['sort_filters'];
        }

        if (isset($_REQUEST['requests_sort_order']))
        {
            $sort_order = $_REQUEST['requests_sort_order'];
            $_SESSION['list_requests']['sort_order']=$sort_order;
        }
        else
        {
            $sort_order = $_SESSION['list_requests']['sort_order'];
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
        if (isset($_REQUEST['requests_select_filters']))
        {
            foreach($_REQUEST['requests_select_filters'] as $key=>$value)
            {
                if (!(is_numeric($key)) && in_array($key,$valid_filters))
                {
                    $this->select_filters[$key] = $value;
                }
            }
            $_SESSION['list_requests']['select_filters']=$this->select_filters;
        }
        else
        {
            $this->select_filters=array_merge($_SESSION['list_requests']['select_filters'],$this->select_filters);
        }
        
        





        $this->user_id_client_lst=array();

		$res = $request->query('SELECT DISTINCT user_id_client from requests');

        foreach ($res as $item)
        {
			$user_id_client=$item['user_id_client'];
			$this->user_id_client_lst[$user_id_client]=$user_id_client;
        }

        $this->user_id_provider_lst=array();

		$res = $request->query('SELECT DISTINCT user_id_provider from requests');

        foreach ($res as $item)
        {
			$user_id_provider=$item['user_id_provider'];
			$this->user_id_provider_lst[$user_id_provider]=$user_id_provider;
        }

        $this->products_ids_lst=array();

		$res = $request->query('SELECT DISTINCT products_ids from requests');

        foreach ($res as $item)
        {
			$products_ids=$item['products_ids'];
			$this->products_ids_lst[$products_ids]=$products_ids;
        }

        $this->services_ids_lst=array();

		$res = $request->query('SELECT DISTINCT services_ids from requests');

        foreach ($res as $item)
        {
			$services_ids=$item['services_ids'];
			$this->services_ids_lst[$services_ids]=$services_ids;
        }

        $this->questionnaire_id_lst=array();

		$res = $request->query('SELECT DISTINCT questionnaire_id from requests');

        foreach ($res as $item)
        {
			$questionnaire_id=$item['questionnaire_id'];
			$this->questionnaire_id_lst[$questionnaire_id]=$questionnaire_id;
        }




        $search='';
        if (isset($_REQUEST['requests_search_texts']))
        {
            $this->search_texts = $_REQUEST['requests_search_texts'];
            $_SESSION['list_requests']['search_texts']=$this->search_texts;
        }
        else
        {
            $this->search_texts = $_SESSION['list_requests']['search_texts'];
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


		$this->request = $request;
    }
    
    public function load_list()
    { 
        if (!($this->items_per_page)) $this->items_per_page = 25;
        if (isset($_REQUEST['requests_items_per_page']))
        {
            $this->items_per_page = $_REQUEST['requests_items_per_page'];
            $_SESSION['list_requests']['items_per_page']=$this->items_per_page;
        } else
        {
            if (array_key_exists('list_requests',$_SESSION) && array_key_exists('items_per_page',$_SESSION['list_requests']))
            {
                $this->items_per_page = $_SESSION['list_requests']['items_per_page'];
            }            
        }
        
        $this->pages=$this->request->getPageCount($this->select_filters,$this->sort_filters,$this->items_per_page,true);
        $this->total_db_items = $this->request->total_db_items;
        $this->page = 1;//default to 1 in case not provided below
        if (isset($_REQUEST['requests_page']))
        {
            $this->page = $_REQUEST['requests_page'];
            $_SESSION['list_requests']['page']=$this->page;
        } else
        {
            if (array_key_exists('list_requests',$_SESSION) && array_key_exists('page',$_SESSION['list_requests']))
            {
                $this->page = $_SESSION['list_requests']['page'];
            }            
        }
        if (($this->page < 1) || ($this->page > $this->pages)) $this->page = 1;

        
        $this->requests = $this->request->findAll($this->select_filters,$this->sort_filters,$this->items_per_page,$this->page);
    }
    
    public function init_list()
    { 
        $this->title = 'Requests';
        $this->init_list_params();
        $this->load_list();
    } 
    
    

    public function renderActions($request,$class="",$edit=TRUE)
    {
        if ($edit)
        {
            echo '<input type="checkbox" class="select_checkbox" id="'.$request->getId().'" value="'.$request->getId().'" name="delete_selected[]" /><br>';
            echo "<button type='button' class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Requests.dir&path=Edit Request.hid.rest.php&id=".$request->getId()."&object=Request' href='#' ><li class='fa fa-pencil'></li> edit</button>";
            echo '<button type="button" class="button mx- danger" mx-confirm="Are you sure to delete Request of id='.$request->getId().'?" mx-click="'.$this->list_delete_button_url.'&id='.$request->getId().'" href="#" mx-container="'.$this->container.'" mx-next="'.$this->list_delete_button_next_url.'" ><li class="fa fa-trash" ></li> Delete </button>';
        } else
        {
            echo "<button type='button' class='mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Requests.dir&path=View Request.hid.rest.php&id=".$request->getId()."&object=Request' href='#' ><li class='fa fa-eye'></li> view</button>"; 
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
		echo '<div class="control">';
		echo '<button type="button" class="button mx-" mx-click="'.$this->edit_button_url.'"  href="#" mx-container="div.page" '.$next_after_update.' ><li class="fa fa-save"></li> '.$this->edit_button_name.'</button>';
		echo '<button type="button" class="button mx- danger" mx-confirm="Are you sure to delete Request of id='.$this->request->getId().'?" mx-click="'.$this->edit_delete_button_url.'" href="#" mx-container="'.$this->container.'" '.$next_after_delete.' ><li class="fa fa-trash" ></li> Delete </button>';
        echo "<button type='button' class='button mx- $class' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Requests.dir&path=List Requests.rest.php&object=Request'  ><li class='fa fa-list'></li> List</button>";
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
		echo '<div class="control" >';
		echo '<button type="button" class="button mx-" mx-click="'.$this->new_button_url.'" href="#" '.$next_after_create.' mx-container="'.$this->container.'"><li class="fa fa-plus"></li> '.$this->new_button_name.' </button>';
        echo '<button type="button" class="button mx-" mx-container="'.$this->container.'" mx-click="?command=display_rest&base_path=Admin.dir/Requests.dir&path=List Requests.rest.php&object=Request"  ><li class="fa fa-list"></li> List</button>';
		echo '</div>';
	}
    
   public function renderListControls($new_after_new='')
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
    
   public function get_icon($request)
   {
		echo '<img class="icon" src="'.$this->paths->base_url.'/img/displaypng.php?id='.$_SESSION['user_id'].'&image='.$this->paths->user_image.'/[TABLE_NAME]/'.$request->getId().'_[TABLE_NAME]_icon.png&key='.$this->current_user->getSecurekey().'" />';
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
    
    public function get_id($request)
    {
        echo "<button type='button' class='mx-' mx-container='".$this->container."' mx-click='?command=display_rest&base_path=Admin.dir/Requests.dir&path=Edit Request.hid.rest.php&id=".$request->getId()."&object=Request'  ><i class='fa fa-edit'></i>".$request->getId()."</button>";
    }
    
    public function renderPaginator()
    {?>
        <script>
            <?php
            if (!isset($this->container)) $this->container = 'div.page';
            if (!isset($this->form_list)) $this->form_list = 'form_list_requests';
            ?>
            function requests_paginate(page) 
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
                form.find('input.field_items_per_page').val($('#requests_items_per_page').val());
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
                <select name="requests_items_per_page" id="requests_items_per_page" style="width:60px" onchange="requests_paginate(<?php echo $this->page;?>)">
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
                        <li class=""><button type="button" onclick="requests_paginate(1)">1</button></li>
                        <?php } else
                        {?>
                        <li class="inactive">&nbsp;</li>		
                        <?php
                        }
                        if ($this->page>1) {?>
                        <li class="prev"><button type="button" onclick="requests_paginate(<?php echo ($this->page-1);?>)"><i class="fa fa-chevron-left"></i></button></li>
                        <?php }
                        else {
                        ?>
                        <li class="inactive"><i class="fa fa-chevron-left"></i></li>
                        <?php }?>
                        <li class="active"><?php echo $this->page;?></li>
                        <?php
                        if (($this->pages-$this->page)>0) {?>
                        <li class="next"><button type="button" onclick="requests_paginate(<?php echo ($this->page+1);?>)"><i class="fa fa-chevron-right"></i></button></li>
                        <?php
                        } else {?>
                        <li class="inactive"><i class="fa fa-chevron-right"></i></li>
                        <?php
                        }?>
                        <?php
                        if (($this->pages-$this->page)>1) {?>
                        <li class="next"><button type="button" onclick="requests_paginate(<?php echo $this->pages;?>)"><?php echo $this->pages;?></button></li>
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
                <input class='page_first first ' type='button' value='1' name='first' onclick='requests_paginate(1)' align='center'></input> 
                <?php
                if ($this->page>2)
                {?>
                    <input type='button'  value='<?php echo ($this->page-1);?>' name='prev_page' class='left ' onclick='requests_paginate(<?php echo ($this->page-1);?>)' align='center'></input>
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
                    <input class='page_next right' type='button' name='next_page' align='center' value='<?php echo ($this->page+1);?>' onclick='requests_paginate(<?php echo ($this->page+1);?>)' ></input>
                <?php
                } else
                {?>
                    <input class='page_next right' type='button' name='page' align='center' value=''  ></input>
                <?php
                }?>    
                <input type='button' class='page_last last ' value='<?php echo $this->pages;?>'  name='last_page' onclick='requests_paginate(<?php echo $this->pages;?>)' align='center'></input>
                <input type='hidden' page='1' name="current_page" />
                </div>
            <?php
            }
        }?>
            </div>
            <div class="column items" >
                    <?php
                    $start = ($this->page-1)*$this->items_per_page + 1;
                    $end = $start+count($this->requests)-1;
                    ?>
                    <div class="span5 dataTables_info" id="tableInfo">Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $this->total_db_items;?> entries</div>            
            </div>
        </div>
<?php
    }

*/

}