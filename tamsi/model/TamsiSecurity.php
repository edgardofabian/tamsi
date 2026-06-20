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
 * @package Tamsi
 * @author Edgardo Fabian <edgardo.fabian@gmail.com> 
 * @link http://tamsi.docph.net 
 */
/**
 * Class containing functions for cryptography
 * @var class TamsiSecurity
 * @since 2013.09.02. 
 * @version 2013.09.02 initial realease
 * @author edgardo fabian <edgardo.fabian@gmail.com>
 */
/*use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Nullix\JsAesPhp;
require_once 'Login.php';

*/
/**
 * IMPORTANT:
 * You must specify supported algorithms for your application. See
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
 * for a list of spec-compliant algorithms.
 */
 
class TamsiSecurity
{
    public $method;
    protected $marker;
    public $paths;
    public $csrf;
    
    public function __construct()
    {
        
        if (!isset($_SESSION['habak_name']))
        {
            $_SESSION['habak_name']=$this->random_alphabet_strings(16);
        }
        if (!isset($_SESSION['habak_value']))
        {
            $_SESSION['habak_value']=$this->generateSalt(64);
        }       
		if (!isset($_SESSION['agimat_name']))
        {
            $_SESSION['agimat_name']=$this->random_alphabet_strings(16);
        }
        if (!isset($_SESSION['agimat_value']))
        {
            $_SESSION['agimat_value']=$this->generateSalt(64);
        }  
        
        $this->method='AES-128-CBC';
        //$this->method='aes-128-cbc';
        $this->marker='efiv';
        $paths = new Paths();
    }

    function getLogin($user_id)
    {
        $log = new Login();
        $log->get(array('filter'=>array('user_id'=>$user_id)));
        if ($log->getId()>0) //existing
        {
            return $log;
        }
        
        $log->setUserId($user_id);
        $log->setCsrfName($this->random_alphabet_strings(16));  
        $log->setCsrfValue($this->generateSalt(64));
        $log->setCsrfCode($this->generateKey(64));
        $log->save();
        
        return $log;
    }
/*
    function generateJWT($user,$log)
    {
        $log_created=Datetime::createFromFormat('Y-m-d H:i:s',$log->getCreated());
        if (is_object($log_created))
        {
            $it = $log_created->format('U');
            $encrypted_csrf_value = JsAesPhp::encrypt($log->getCsrfValue(),$csrf->getCsrfCode());
            $payload = [
            'site_issued' => $paths->base_url,
            'user_id'=>$user->getId(),
            'user_name' => $user->getFirstname().' '.$user->getLastname(),
            'csrf_name' => $log->getCsrfName(),
            'csrf_value'=> $encrypted_csrf_value,
            'iat' => $it,
            'exp' => $it+30*60 //30 minutes
            ];

            $now = new DateTime('now');
            $roll = $now->format('U');
            $jwt = JWT::encode($payload, SECURITY_KEY, 'HS256');

            return $jwt;
        }
        return FALSE;
    }
  */  
    
    
    

	/**
	 * Encrypts a given input string ($input_string) using a key ($key)
	 * @param string $input_string To be encrypted
	 * @param string $key Used as a key/password in encrypting 
	 * @return string encrypted value 
	 * @since 2013.09.02. 
	 * @author edgardo fabian <edgardo.fabian@gmail.com>
	 * @version 2013.09.02 initial realease
	 */
	function encrypt($input_string, $key)
	{
		$rev_key=strrev($key);	   
        $ivlen = openssl_cipher_iv_length($this->method);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $encrypted=openssl_encrypt($input_string,$this->method,$rev_key,OPENSSL_RAW_DATA,$iv);
		return $this->marker.base64_encode(str_rot13($iv.$encrypted));
	}

	function encrypt_date($input_date, $mod)
	{
        $date = DateTime::createFromFormat('Y-m-d',$input_date);
        if (is_object($date))
        {
            $date->modify('+1 year')->modify('-'.$mod.' days');
            $out=$date->format('Y-m-d');
        }
        else
        {
            $out=$input_date;
        }
        
        return $out;
	}

	function decrypt_date($input_date, $mod)
	{
        $date = DateTime::createFromFormat('Y-m-d',$input_date);
        if (is_object($date))
        {
            $date->modify('-1 year')->modify('+'.$mod.' days');
            $out=$date->format('Y-m-d');
        } else
        {
            $out=$input_date;
        }
        return $out;
	}


	function encrypt_datetime($input_date, $mod)
	{
        $date = DateTime::createFromFormat('Y-m-d H:i:s',$input_date);
        if (is_object($date))
        {
            $date->modify('+1 year')->modify('-'.$mod.' days')->modify('+13 minutes');
            $out=$date->format('Y-m-d H:i:s');
        } else
        {
            $out=$input_date;
        }
        return $out;
	}

	function decrypt_datetime($input_date, $mod)
	{
        $date = DateTime::createFromFormat('Y-m-d H:i:s',$input_date);
        if (is_object($date))
        {
            $date->modify('-1 year')->modify('+'.$mod.' days')->modify('-13 minutes');
            $out=$date->format('Y-m-d');
        }
        else
        {
            $out=$input_date;
        }
        return $out;
	}
	/**
	 * decrypts/regenerates the input string using the encrypted string
	 * ($encrypted_input_string) using a key ($key)
	 * @param string $encrypted_input_string to be decrypted
	 * @param string $key Used as a key/password in decrypting 
	 * @since 2013.09.02 
	 * @version 2013.09.02 initial realease
	 * @author edgardo fabian <edgardo.fabian@gmail.com>
	 */ 
	function decrypt($encrypted_input_string, $key)
    {
        $efiv=substr($encrypted_input_string,0,strlen($this->marker));
        if ($efiv==$this->marker)
        {
            $encrypted_input_string=substr($encrypted_input_string,strlen($this->marker));
            $encrypted=str_rot13(base64_decode($encrypted_input_string));		
            $ivlen = openssl_cipher_iv_length($this->method);
            $iv=substr($encrypted,0,$ivlen);
            $encrypted=substr($encrypted,$ivlen);
            $rev_key=strrev($key);
            $decode=openssl_decrypt($encrypted,$this->method,$rev_key,OPENSSL_RAW_DATA,$iv);
            //die('iv='.$iv.'<br>encr='.$encrypted.'<br>ivlen='.$ivlen.'<br>decoded='.$decode);
        }
        else
        {
            $decode=str_rot13(base64_decode($encrypted_input_string));		
            $rev_key=strrev($key);
            $decode=openssl_decrypt($decode,$this->method,$rev_key);
        }
		return $decode;
	}

    function random_alphabet_strings($length_of_string) 
    { 
      
        // String of all alphanumeric character 
        $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
      
        // Shufle the $str_result and returns substring 
        // of specified length 
        return substr(str_shuffle($str_result),  
                           0, $length_of_string); 
    }     


    function generateKey($length) 
    { 
      
        // String of all alphanumeric character 
        $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'; 
      
        // Shufle the $str_result and returns substring 
        // of specified length 
        return substr(str_shuffle($str_result),  
                           0, $length); 
    }
	/**
	 * generates a random value from a given minimum ($min) and maximum ($max)
	 * ($encrypted_input_string) using a key ($key)
	 * @param int $min minimum possible value 
	 * @param int $max maximum possible value 
	 * @since 2013.09.02 
	 * @version 2013.09.02 initial realease
	 * @author edgardo fabian <edgardo.fabian@gmail.com>
	 */ 
	function random($min, $max) 
	{
	  // md5() generates a hexadecimal number, so we must convert it into base 10
	  $rand = base_convert( md5( microtime() ), 16, 10);
	  // the modulus operator doesn't work with great numbers, so we have to cut the number
	  $rand = substr($rand, 10, 6);
	  $diff = $max - $min + 1;
	  return ($rand % $diff) + $min;
	}
	
	function generateHash($password,$salt,$unique_key)
	{
		$internal_pass = $salt.$password.$unique_key;
		return password_hash($internal_pass,PASSWORD_BCRYPT);
	}
	
	function generateSalt($length)
	{
		return base64_encode(openssl_random_pseudo_bytes($length));
	}
	
	function verifyPassword($password,$salt,$unique_key,$hash)
	{
		$internal_pass = $salt.$password.$unique_key;
		return password_verify($internal_pass,$hash);
	}
    
    public function getCurrentUserId()
    {
        if (array_key_exists('user_id',$_SESSION))
        {
            return $_SESSION['user_id'];
        }
        return 0;
    }

    public function getCurrentUserRole()
    {
        if (array_key_exists('user_role',$_SESSION))
        {
            return $_SESSION['user_role'];
        }
        return 0;
    }

    public function getCurrentUserName()
    {
        if (array_key_exists('user_name',$_SESSION))
        {
            return $_SESSION['user_name'];
        }
        return 0;
    }

    public function getCurrentUserDomain()
    {
        if (array_key_exists('user_domain',$_SESSION))
        {
            return $_SESSION['user_domain'];
        }
        return 0;
    }


    public function getCsrfName()
    {
        return $_SESSION['habak_name'];
    }
    
    public function getCsrfValue()
    {
        return $_SESSION['habak_value'];
    }
    
    public function getLoggedOutCsrfName()
    {
        return $_SESSION['agimat_name'];
    }
    
    public function getLoggedOutCsrfValue()
    {
        return $_SESSION['agimat_value'];
    }    
    
    public function checkCsrf()
    {
        if (!$_SESSION['user_id'])
        {
            return $this->checkLoggedOutCsrf();
        } else if (isset($_REQUEST[$this->getCsrfName()]) && ($_REQUEST[$this->getCsrfName()]==$this->getCsrfValue()))
        {
            return TRUE;
        }
        return FALSE;
    }
    
    public function checkLoggedOutCsrf()
    {
        if (isset($_REQUEST[$this->getLoggedOutCsrfName()]) && ($_REQUEST[$this->getLoggedOutCsrfName()]==$this->getLoggedOutCsrfValue()))
        {
            return TRUE;
        }
        return FALSE;
    }

    
}
