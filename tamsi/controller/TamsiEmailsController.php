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
require_once 'Model.php';
require_once 'database.cfg.php';
require_once 'Email.php';
require_once 'paths.cfg.php';

class TamsiEmailsController
{
    public $email;
    

	public function __construct()
	{
		$this->email = new Email();
        
	}
    
	public function sendAction($method='text')
	{
		if (isset($_REQUEST['subject']))
		{
			$this->email->setSubject($_REQUEST['subject']);
		}
        if (isset($_REQUEST['from']))
		{
			$this->email->addFrom($_REQUEST['from']);
            $msg = 'Email From: '.$_REQUEST['from']."\r\n";
		} else $msg='';
        
		if (isset($_REQUEST['message']))
		{
			$this->email->setMessage($msg.$_REQUEST['message']);
		}
		if (isset($_REQUEST['to']))
		{
			$this->email->addTo($_REQUEST['to']);
		} else
        {
            $this->email->addTo('gahum.projects@gmail.com');
        }
		if (isset($_REQUEST['cc']))
		{
			$this->email->addCc($_REQUEST['cc']);
		}
		if (isset($_REQUEST['bcc']))
		{
			$this->email->addBcc($_REQUEST['bcc']);
		}
		if ($this->email->getFrom())
		{
			if (strtolower($method)=='text')
			{
				$this->email->send();
			} else
			{
				$this->email->sendHtml();
			}
			$_SESSION['tamsi_notice']='Your email was successfully sent';
			return '';
		} else
		{
			return 'Should have at least an address From ';
		}
	}
    
}

?>
