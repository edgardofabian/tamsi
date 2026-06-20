<?php
/** Main model of tamsi framework 
 * Object contaiting model objects for main tamsi framework. 
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
 * @package TamsiUploader
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
/**
 * Class containing functions to receive uploads by chunck. Works in tander with uploader.js.php
 * @var class TamsiSecurity
 * @since 2013.09.02. 
 * @version 2013.09.02 initial realease
 * @author edgardo fabian <edgardo.fabian@gmail.com>
 */
class TamsiUploader
{
    public $path;
    public function __construct()
    {
    }
	/**
	 * Encrypts a given input string ($input_string) using a key ($key)
	 * @param string $input_string To be encrypted
	 * @param string $key Used as a key/password in encrypting 
	 * @return string encrypted value 
	 * @since 2013.09.02. 
	 * @author edgardo fabian <edgardo.fabian@gmail.com>
	 * @version 2013.09.02 initial realease
	 */
	public function import()
	{

		$msg='';
		$message='';
		$ret=array('success'=>false,'message'=>'');
		$data['title']='Import Pdf';
		$data['user_info'] = $this->ion_auth->user()->row();
        $data['path']='';
        $data['file']='';
        $data['text']='';
        $import_detail_id=0;
		
        $success=false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') && ( $_SERVER['REQUEST_METHOD'] == 'POST' )) 
		{
            if (array_key_exists('first',$_POST) && ($_POST['first']))
            {
                $now = new DateTime('now');
                $_SESSION['path']=$this->path;
                $_SESSION['filename']=$_REQUEST['filename'];
                $_SESSION['file_date']=$now->format('Ymd_His').'_';
                $_SESSION['total_size']=0;
                $_SESSION['verified']=false;
            } 
            $size=0;
            if (array_key_exists('done',$_POST) && ($_POST['done']))
            {
                if (array_key_exists('filesize',$_POST) && ($_POST['filesize']))
                {
                    if ($_POST['filesize']==$_SESSION['total_size']) 
                    {
                        $_SESSION['verified']=true;
                        $success=true;
                        $msg='Uploaded='.$_POST['filesize'].' bytes = '.'Received='.$_SESSION['total_size'].' bytes' ;
                    } else
                    {
                        $success=false;
                        $msg='Uploaded='.$_POST['filesize'].' bytes != '.'Received='.$_SESSION['total_size'].' bytes';
                    }
                    
                } else
                {
                        $success=false;
                        $msg='No filesize provided! '.'Received='.$_SESSION['total_size'].' bytes' ;                    
                }
            } else if (array_key_exists('file',$_POST) && ($_POST['file']))
            {
                $file_data = explode(';base64,',$_POST['file']);
                
                if (is_array($file_data) && (count($file_data)==2))
                {
                    $size = file_put_contents($this->path.$_SESSION['file_date'].$_REQUEST['filename'],base64_decode($file_data[1]),FILE_APPEND);
                }
                if ($size)
                {
                    $_SESSION['total_size']=$_SESSION['total_size']+$size;
                    $success=true;
                    $msg=$_SESSION['total_size'].' bytes';
                } else
                {
                    $success=false;
                    $msg='Error writing to '.$this->path.$_SESSION['file_date'].$_REQUEST['filename'];
                }
            } else
            {
                $success=false;
                $msg='No data '.$this->path.$_SESSION['file_date'].$_REQUEST['filename'];                
            }
            echo json_encode(array('success'=>$success,'message'=>$msg,'size'=>$size,'import_id'=>$import_detail_id));
            exit();
        } else
        {   
            echo json_encode(array('success'=>false,'message'=>'Method unsupported','size'=>0,'import_id'=>0));
            exit();
        }
        
	}    
}
