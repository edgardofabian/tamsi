<?php
/** Main controller of Tamsi framework 
 * Object contaiting controller functions for main Tamsi actions. It passes to user controller when not in the scope of actions
 * 
 * Copyright (C) 20014-2020 Edgardo Fabian <edgardo.fabian@gahum.com> 
 * LICENSE: This program is free software; you can redistribute it and/or 
 * modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation; either version 3 
 * of the License, or (at your option) any later version. 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU General Public License for more details. 
 * You should have received a copy of the GNU General Public License 
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;. 
 * @package TamsiController 
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
require_once 'Controller.php';
require_once 'Model.php';
require_once 'database.cfg.php';
require_once 'FileProperties.php';
require_once 'TamsiMenuElement.php';
require_once 'MenuElement.php';
require_once 'Role.php';
require_once 'SearchResult.php';

require_once 'paths.cfg.php';
require_once 'Config.php';
require_once 'User.php';
require_once 'generate.php';
require_once 'Security.php';
require_once 'UsersController.php';

class TamsiController extends CoreController
{
	public $model;
    protected $nest;
    protected $paths;
    public $role;
    

	public function __construct(Model $model)
	{
		$this->model = $model;
        
		parent::__construct();
	}
    
    public function downloadAction()
    {
      require_once 'Role.php'; 
      $allowed=array("student","admin","teacher");
      $role = new Role();
      if ($role->isUserAnyOf($allowed))
      {
         $filename = $_REQUEST['file'];
         // ensure the file exists
         if(isset($filename) && file_exists($filename)){

           // Read the file some.zip
           $file = file_get_contents($filename);
           $names = explode("/",$filename);
           $name = $names[count($names)-1];
           // Set headers to serve the file for download
           header("Content-type: application/octet-stream");
           header("Content-Disposition: attachment; filename=\"$name\""); // name file here
           header('Content-Length: ' . strlen($file)); // length of the file

           // echo the file
           echo $file;
         } else
         {
            echo "file not found";
         }
      }
      else
      {
         echo "<h1>Unauthorized! user_id=".$_SESSION['user_id']."</h1>";
      }   
       
    }

    public function prepareMenu($include_menu=array(),$enable_external_links=TRUE)
    {
        $files = array();
        $this->model->setMenus($this->generateMenu('',true,$include_menu,$enable_external_links),$include_menu);
    } 


    public function generateMenu($path,$main,$include_menu=array(),$enable_external_links=TRUE,$base_path='')
    {
        $files = array();
        $site_menu = array();
        if (empty($path))
        {
            $src_path="";
        } else
        {
            $src_path=$path.'/';
        }
        if ($handle = opendir($src_path.'.')) 
        {
            while (false !== ($file = readdir($handle))) 
            {
                if ( !in_array($file,$this->model->getFileFilters()))
                {
                    $files[] = $file;
                }
            }
            closedir($handle);
        }
        sort($files);

        foreach ($files as $file)
        {
            $file_proc = new FileProperties($file);
            $unique_name = preg_replace('/_[0-9][0-9]/','',str_replace('.dir','',strtolower(str_replace(' ','_',str_replace('/','_',$path.'_'.$file_proc->getName())))));
            if (is_dir($src_path.$file))
            {
                if ($file_proc->getHidden())
                {
                    //do not include
                } else
                {
                    if ($file_proc->isDisplayDir($file))
                    {
                        if ((count($include_menu)==0) || (in_array(strtolower($file_proc->getName()),$include_menu)))
                        {

                            if (empty($path))
                            {
                                $menu = new MenuElement($file_proc->getName(),$this->generateMenu($file,false,$include_menu,$enable_external_links));
                            } else
                            {
                                $menu = new MenuElement($file_proc->getName(),$this->generateMenu($path.'/'.$file,false,$include_menu,$enable_external_links));
                            }
                            $menu->setAction('display_dir');
                            $menu->setRoleRequired($file_proc->getRoleRequired());
                            $menu->setId($unique_name);
                            $site_menu[] = $menu;
                        }
                    } else
                    {                                
                        $action=$file_proc->getLinkPath($file);
                        
                        if ($file_proc->isLink())
                        {
                            if ($enable_external_links)
                            {
                                $menu = new MenuElement($file_proc->getName(),$action);
                                $menu->setId($unique_name);
                                $menu->setExternalLink(TRUE);
                                $site_menu[] = $menu;
                            }
                            //die('con='.json_encode($menu));
                        }
                        else
                        {
                            $menu = new MenuElement($file_proc->getName(),$action);
                            $menu->setId($unique_name);
                            $menu->setAction('redirect');
                            $site_menu[] = $menu;
                        }
                        
                    }

                    
                }
            } else
            if (is_file($src_path.$file))
            {
                if (in_array($file_proc->getExtension(), $this->model->getFileExtensions()))
                {
                    if ($file_proc->getRest())
                    {
                        $url='?command=display_rest&base_path='.$path.'&path='.$file;
                        $action='display_rest';
                    } else
                    {
                        $url='?command=display_php&base_path='.$path.'&path='.$file;
                        $action='display_php';
                    }
                    if ($file_proc->getHidden())
                    {
                        //do not include
                    } else
                    {
                        $name=$file_proc->getName();                            
                        $menu = new MenuElement($name,$url);
                        $menu->setAction($action);
                        $menu->setId($unique_name);
                        $menu->setRoleRequired($file_proc->getRoleRequired());
                        $site_menu[] = $menu;
                        
                    }
                }
            }
        }
        return $site_menu;
	}	   
    
   
    static function sort_objects_by_rank($a, $b) 
    {
        if ($a->getRank() == $b->getRank())
        { 
            return 0 ; 
        }
        return ($b->getRank() < $a->getRank()) ? -1 : 1;
    }
    
    public function uploadImageAction()
    {
        if ($_SESSION['user_id']>0)
        {
            $path=$_POST['filename'];
            $data = $_POST['upload_content'];
            file_put_contents($path,$data);
        
            $this->model->setData($data);
            $this->model->setCommand(MenuElement::ACTION_DISPLAY);
            
            if ( ($_FILES["file"]["size"] < 500000))
            {
                if ($_FILES["file"]["error"] > 0)
                {
                    $msg="Return Code: " . $_FILES["file"]["error"] . "<br />";
                    $status= 0;
                }	else
                {
                    $fullpath = $this->paths->images;
                    
                    if (file_exists($fullpath.$_FILES["file"]["name"]))
                    {
                        if ($status==0)
                        {
                            $msg=$_FILES["file"]["name"] . " already exists. ";
                            $status=1;
                        } else 
                        {
                            $status=2; $back=2;
                        }
                    } else $status=2;
                    if($status==2)	
                    {
                        $msg="Upload: " . $_FILES["file"]["name"] . "<br />";
                        $msg.="Type: " . $_FILES["file"]["type"] . "<br />";
                        $msg.="Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
                        $msg.="Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
                        
                        
                        if (!file_exists($fullpath)) 
                        {
                            $msg.="creating $fullpath<br>";
                            mkdir("$fullpath", 0777, true);
                        }

                        if (copy($_FILES["file"]["tmp_name"],$fullpath.$_FILES["file"]["name"]))
                        {
                            $msg.="Stored in: " . $fullpath . $_FILES["file"]["name"];
                            $status=2;
                        } else 
                        {
                            $msg.="Move upload file failed.";
                            $status=0;
                        }
                    }
                }
            } else
            {
              $msg="Invalid file";
            }
            $_SESSION['tamsi_notice']="$msg!";
        }        
    }

    public function searchAction()
    {
        $search_string = $_REQUEST['search'];
        $results = array();
        
        if (trim($search_string))
        {
        
            //convert all white space to comma
            $string = preg_replace("/[\s_]/", ",", trim($search_string));
            
            $searches = explode(',',$string);
            
            
            //get all tables
            $db = new db();
            $mysqli = new mysqli($db->host,$db->user, $db->password, $db->name);
            if ($mysqli->connect_errno) 
            {
                $_SESSION['tamsi_notice'].="Connect failed: ".$mysqli->connect_error." \r\n";
                return false;
            }
                    
            $sql="SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '".$db->name."'"; 
            $res = $mysqli->query($sql);
            $tables = array();
            while ($row = $res->fetch_array()) 
            {
                $tables[]=$row['TABLE_NAME'];
            }
            //die(json_encode($tables));
            //die(json_encode($searches));
            foreach ($searches as $search)
            {            
                foreach ($tables as $table)
                {
                    
                    if (strpos($table,'ies')==(strlen($table)-3))
                    {
                        $object = substr($table,0,strlen($table)-3).'y';
                    } 
                    else if (strpos($table,'sses')==(strlen($table)-2))
                    {
                        $object = substr($table,0,strlen($table)-2);
                    } 
                    else
                    {
                        $object = substr($table,0,strlen($table)-1);
                    }
                    $controller_name = ucfirst($table);
                    $class_name = ucfirst($object);            
                    
                    //match searches with table name

                    if (!(stripos($table,$search)===false))
                    {
                        $index = $table.'_table';
                        if ($results[$index])
                        {
                            $results[$index]->incrementRank();
                            $results[$index]->addMatches($table);
                        } else
                        {
                            $results[$index] = new SearchResult();
                            $results[$index]->incrementRank();
                            $results[$index]->addMatches($table);
                            $results[$index]->setName('Table '.$table);
                            $results[$index]->setHref("?command=display_rest&base_path=usr/view&path=List ".ucfirst($table).".rest.php");
                        }
                    }
                    if (!(stripos($search,$table)===false))
                    {
                        $index = $table.'_table';
                        if ($results[$index])
                        {
                            $results[$index]->incrementRank();
                            $results[$index]->addMatches($table);
                        } else
                        {
                            $results[$index] = new SearchResult();
                            $results[$index]->incrementRank();
                            $results[$index]->addMatches($table);
                            $results[$index]->setName('Table '.$table);
                            $results[$index]->setHref("?command=display_rest&base_path=usr/view&path=List ".ucfirst($table)."rest.php");
                        }
                    }
                        
                    
                    //get  primary key
                    $sql = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY';";


                    $res = $mysqli->query($sql);
                    $primary = 'id';
                    if ($mysqli->affected_rows>0)
                    {
                        $row = $res->fetch_array();
                        $primary = $row['Column_name'];
                    } else
                    {
                        print_r("No primary key");
                        die();
                    }
                    $db_name = $db->name;
                    $sql ="SELECT column_name, data_type,character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' and TABLE_SCHEMA='$db_name';";

                    $res = $mysqli->query($sql);
                    $search_sql = "SELECT * from $table WHERE";
                    $start=true;
                    while ($row = $res->fetch_array()) 
                    {
                        $data_type = $row['data_type'];
                        $data = $row['column_name'];
                        if ($start)
                        {
                            $search_sql.=" ($data like '%".$search."%')";
                            $start = false;
                        } else
                        {
                            $search_sql.=" OR ($data like '%".$search."%')";
                        }
                        
                    }
                    $search_sql.=';';
                    $resf = $mysqli->query($search_sql);
                    if ($resf)
                    {
						while ($row = $resf->fetch_array()) 
						{
							$index = $table.'_'.$row['id'];
							if ($results[$index])
							{
								foreach ($row as $field)
								{
                                    $match_pos = stripos($field,$search);
									if (!($match_pos===false))
									{
                                        if ($match_pos > 50)
                                        {
                                            $start_pos = $match_pos - 50;
                                        } else
                                        {
                                            $start_pos = 0;
                                        }
										$results[$index]->addMatches(substr($field,$start_pos,50));
										$results[$index]->incrementRank();
									}
								}
							} else
							{
								$results[$index] = new SearchResult();
								$results[$index]->setName($class_name.' '.$row['id']);
								foreach ($row as $field)
								{
                                    $match_pos = stripos($field,$search);
									if (!($match_pos===false))
									{
                                        if ($match_pos > 50)
                                        {
                                            $start_pos = $match_pos - 50;
                                        } else
                                        {
                                            $start_pos = 0;
                                        }
										$results[$index]->addMatches(substr($field,$start_pos,50));
										$results[$index]->incrementRank();
									}
								}
								$results[$index]->setHref("?command=display_rest&base_path=usr/view&path=Edit ".$class_name.".rest.php".'&id='.$row['id']);    
							}
						}
					}
				}
            }
            $mysqli->close();
            $html="<h1>Search Results</h1>\n";
            if (count($results))
            {
                usort($results,array('TamsiController','sort_objects_by_rank')); 
                $html.= "<table class='odd_even_row_alternate_color full_width float_scroll data' > \n";
                $html.= "<thead><tr><th>Name</th><th>Matches</th><th>Rank</th><th>Links</th></tr></thead> \n";
                $html.= "<tbody> \n";
                foreach ($results as $result)
                {
                    $html.="<tr><td>".$result->getName()."</td><td>".$result->getMatches()."</td><td>".$result->getRank()."</td><td><input type='button' class='mx-' href='#' mx-container='div.page' mx-click='".$result->getHref()."'  value='show' ></input></td></tr> \n";
                }
                $html.= "</tbody></table> \n";
            } else
            {
                $html.='<p>No results found.</p>';
                
            }
            echo $html;
        } else
        {
            if ($_POST)
            {
                $html='<p>No results found.</p>';
            } else
            {
                header('Location: ?clear=x');
                exit;
            }
        }
        exit();
    }

	/*private function getExtensionOfFile($file)
	{
		$ext_pos = strrpos($file,".")+1;
		if ($ext_pos<strlen($file))
		{
			return (strtolower(substr($file,$ext_pos)));
		} else return "";
	}*/	

	/*private function getDefinedPage($path)
	{
		if (strpos($path,'/')>0)
		{ 
			$filenames = explode('/',$file);
			$num = count($filenames)-1;
			$file = $filenames[$num];
		} else $file = $path;
		$page_start = strpos($file,".")+1;
		if ($page_start)
		{
			$page_end = strpos($file,".",$page_start);
			if ($page_end>$page_start)
			{
				return (substr($file,$page_start,$page_end-$page_start));
			} else return '1000';
		} else
		{
			return '1000';
		}
	}*/
    

	
	
	

	
	private function getName($file)
	{
		$name = str_replace('->','/',$file);
		$end_pos = strpos($file,".");
		if (!(strpos($name,'www.')===false))
		{ 
			return (substr($file,$end_pos+1));
		} else
		{
			if ($end_pos)
			{
				return (substr($file,0,$end_pos));
			} else 
			{
				return $name;
			}
		}
	}
    
    public function getNest()
    {
        return $this->nest;
    }
    
    public function tamsiDelete($path)
    {
        if (is_dir($path) === true)
        {
            $files = array_diff(scandir($path), array('.', '..'));

            foreach ($files as $file)
            {
                tamsiDelete(realpath($path) . '/' . $file);
            }

            return rmdir($path);
        }
        else if (is_file($path) === true)
        {
            return unlink($path);
        }

        return false;
    }    
    
    public function getModel()
    {
        return $this->model;
    }
}

?>
