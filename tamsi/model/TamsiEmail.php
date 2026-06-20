<?php
/** Object for managing emails of tamsi framework 
 * Object contaiting elements of a tamsi framework email system
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
 * @package Email
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
class TamsiEmail
{
    protected $to;
    
    protected $cc;
    
    protected $bcc;
    
    protected $from;
    
    protected $headers;
    
    protected $address;
    
    protected $subject;
    
    protected $message;
    
    
    public function __construct()
    {
        $this->to='';
        $this->cc='';
        $this->bcc='';
        $this->from='';
        $this->headers='';
        $this->address='';
        $this->subject='';
        $this->message='';
    }
    
	
	public function addTo($email)
    {

        if ($this->to)
        {
            $this->to .= ','.$email;
        } else
        {
            $this->to = $email;
        }
        return $this;

    }	
    
    public function getTo()
    {
        return $this->to;
    }
    
    public function getFrom()
    {
        return $this->from;
    }    

    public function addCc($email)
    {

        if ($this->cc)
        {
            $this->cc .= ','.$email;
        } else
        {
            $this->cc = $email;
        }
        return $this;

    }	
    
	public function addBcc($email)
    {

        if ($this->bcc)
        {
            $this->bcc .= ','.$email;
        } else
        {
            $this->bcc = $email;
        }
        return $this;

    }	
    
	public function addFrom($email)
    {

        if ($this->from)
        {
            $this->from .= ','.$email;
        } else
        {
            $this->from = $email;
        }
        return $this;

    }
    
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }
    
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }
    
    public function setMessage($msg)
    {
        $this->message = $msg;
    }    

    public function send()
    {
        if ($this->from)
        {
            $this->headers .= 'From:'. $this->from . "\r\n";
        }
        
        if ($this->cc)
        {
            $this->headers .= 'Cc:'. $this->cc . "\r\n";
        }
        
        if ($this->bcc)
        {
            $this->headers .= 'Bcc:'. $this->bcc . "\r\n";
        }
        return (mail($this->to,$this->subject,$this->message,$this->headers));
    }

    public function sendHtml()
    {

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
        if ($this->from)
        {
            $headers .= 'From:'. $this->from . "\r\n";
        }
        
        if ($this->cc)
        {
            $headers .= 'Cc:'. $this->cc . "\r\n";
        }
        
        if ($this->bcc)
        {
            $headers .= 'Bcc:'. $this->bcc . "\r\n";
        }
        
        return (mail($this->to,$this->subject,$this->message,$headers));
            

    }
    
    public function sendText()
    {
            
        if ($this->from)
        {
            $headers .= 'From:'. $this->from . "\r\n";
        }
        
        if ($this->cc)
        {
            $headers .= 'Cc:'. $this->cc . "\r\n";
        }
        
        if ($this->bcc)
        {
            $headers .= 'Bcc:'. $this->bcc . "\r\n";
        }
        
        return (mail($this->to,$this->subject,$this->message));
            
    }

}
