<?php
/** Main controller of tamsi framework 
 * Object contaiting controller functions for main tamsi actions. It passes to user controller when not in the scope of actions
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
 * @package TamsiEmailsController 
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
require_once 'database.cfg.php';
require_once 'FileProperties.php';
require_once 'paths.cfg.php';

class TamsiFilesController
{
    protected $path;
    protected $files;
    protected $dirs;
    
    

	public function __construct()
	{
        
	}
    
	public function listDirsRestAction()
	{
		if (isset($_REQUEST['file_path']))
		{
            $_SESSION['upload_directory']=$_REQUEST['file_path'];
            $path = $_SESSION['upload_directory'].'/';
            $dir_levels = explode('/',$_SESSION['upload_directory']);
            $up='';
            if (count($dir_levels)>0)
            {
                for($i=0;$i<(count($dir_levels)-1);$i++)
                {
                    if ($dir_levels[$i])
                    {
                        $up.='/'.$dir_levels[$i]; 
                    }
                }
            }
            $dirs = array();
            if ($handle = opendir($path.'.')) 
            {
                while (false !== ($file = readdir($handle))) 
                {
                    if ($file != "." && $file != "..")
                    {
                        if (is_dir($path.$file) && (strpos($file,'.git')===FALSE))
                        {
                            $dirs[$file]=$path.$file;
                        } 
                    }
                }
                if ($up)
                {
                    $dirs['..'] = $up;
                }
                asort($dirs);
                closedir($handle);
                $success = true;
                $message = count($dirs).' directories found.';
            } else
            {
                $success = false;
                $message = 'Unable to open directory '.$path.'!';
                $_SESSION['upload_directory']=getcwd();
            }
            $response = array('message'=>$message,'success'=>$success,'dirs'=>$dirs,'path'=>$path);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();     			
		}        
	}


    public function overwriteSetRestAction()
	{
		if (isset($_REQUEST['overwrite']))
		{
            $_SESSION['upload_overwrite']=$_REQUEST['overwrite'];
            $success = true;
            if ($_SESSION['upload_overwrite'])
            {
                $message = 'Successfully set overwrite mode.';
            }
            else
            {
                $message = 'Successfully set overwrite disable.';
            }
            $response = array('message'=>$message,'success'=>$success);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();     			
		}        
	}
    
}

?>
