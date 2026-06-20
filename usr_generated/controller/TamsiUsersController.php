<?php 

require_once 'TamsiSecurity.php';
require_once 'security.cfg.php';
require_once __DIR__.'/../../lib/vendor/autoload.php';
require_once 'Configurations.php';
require_once 'Role.php';
require_once 'TamsiImage.php';
require_once 'User.php';
require_once 'Next.php';
class TamsiUsersController 
{
    public $views;
	
    public $security;
    
    public function __construct($views)
    {
        $this->views = $views;
        $this->security = new TamsiSecurity();
    }

    
    public function loginAction($check_field)
    {
        $check_value = $_POST[$check_field];
        $password = $_POST['password'];    
        if ($_SESSION['user_id']==0)
        {
            $user = new User();
            $user->setId(0);
            $user = $user->find(array($check_field=>$check_value));
            if ($user->getId()>0)
            {
                $secure = new Secure_config();
                
                if ($this->security->verifyPassword($password,$user->getSalt(),$secure->secret_key,$user->getPassword()))
                {
                    $user=$this->login($user,$secure);
                    return $user;
                } else
                {
                    $_SESSION['tamsi_notice']='Sorry invalid credentials!';
                }
            } else
            {
                $_SESSION['tamsi_notice']='Identity not found!';
            }
        } else 
        {
            $_SESSION['tamsi_notice']='';
        }
        
        $view_config = new Views(); 
        $views = $view_config->views;
        return $views;
	}

    public function loginJwtAction($check_field)
    {
        $secret_key = JWT_KEY; 
        $issued_at = time();
        $expiration_time = $issued_at + 3600; // Valid for 1 hour

        $check_value = $_POST[$check_field];
        $password = $_POST['password'];    
        $success = false;
        $message = '';
        if ($_SESSION['user_id']==0)
        {
            $user = new User();
            $user->setId(0);
            $user = $user->find(array($check_field=>$check_value));
            if ($user->getId()>0)
            {
                $secure = new Secure_config();
                
                if ($this->security->verifyPassword($password,$user->getSalt(),$secure->secret_key,$user->getPassword()))
                {
                    $success = true;
                    $payload = [
                            "iat" => $issued_at,
                            "exp" => $expiration_time,
                            "data" => [
                                "id" => $user->id
                            ]
                        ];
                    $jwt = JWT::encode($payload, $secret_key, 'HS256');
                } else
                {
                    $message='Sorry invalid credentials!';
                }
            } else
            {
                $message='Identity not found!';
            }
        } else 
        {
            $message='';
        }
        if ($success)
        {
            $ret = array('success'=>$success,'token'=>$jwt);
            echo json_encode($ret);
        } else
        {
            $ret = array('success'=>$success,'message'=>$message);
            http_response_code(401);
            echo json_encode($ret);
        }
        exit;

	}

    public function login($user,$secure)
    {
        $now = new DateTime('NOW');
        $user->setLogin($now->format('Y-m-d H:i:s'));
        $user->setLoginip($_SERVER['REMOTE_ADDR']);          
        $user->save();
              
        $_SESSION['user_id']=$user->getId();
        $_SESSION['user_name']=$user->getFirstname().' '.$user->getLastname();
        $_SESSION['user_email']=$user->getEmail();
        $_SESSION['user_role']=$user->getRole();
        $_SESSION['user_domain']=$secure->domain;
        $_SESSION['tamsi_notice']='Welcome '.$_SESSION['user_name'].'!';
        $_SESSION['site_map']='';
        return $user;
    }
    
    public function logoutAction()
    {
        $_SESSION['user_id']=0;
        $_SESSION['user_name']=0;
        $_SESSION['user_email']=0;
        $_SESSION['user_role']=0;
        $_SESSION['user_domain']='';
        $_SESSION['tamsi_notice']="Successful logout!";
        $view_config = new Views(); 
        $views = $view_config->views;
        return $views;
	} 
    
    public function registerRestAction()
    {
		$user= new User();
		$user= $this->getPostNew($user);
        $response = $this->save_new_user($user);
        if ($response['success'])
        {
            $message='Please monitor your email to confirm your registration.';
            $response['message']=$response['message']."\r\n".$message;
            
            $next = new Next();
            $next->silent = 0;
            $next->url = '?command=registerConfirmSendLinkRest&object=User&id='.$response['id'];
            $next->container = 'div.page';
            $next->form = '#register_user';
            $response['next']=$next;
        }
        $this->json_response($response);
    }
    
    protected function sendLink($user,$command,$msg)
    {
        require_once 'Email.php';
        $config = new Configurations();
        $response=array();
        if ($user->getId())
        {
            
            
            if (trim($user->getResetKey()))
            {
                $key=$user->getResetKey();
            }
            else
            {
                $key = $this->security->generateKey(32);
                $user->setResetkey($key);
            }            
            
            $email = new Email();
            $email->setSubject('Password Reset');
            if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off')) 
            {
                $ht = 'https';
            } else
            {
                $ht='http';
            }
            $config_paths = new Paths();
            
            $link = $ht.'://'.$config->domain.'/?command='.$command.'&object=User&email='.$user->getEmail().'&resetkey='.urlencode($user->getResetkey());            
            $msg.=$link;
            $email->setMessage($msg);
            $email->addTo($user->getEmail());
            if ($config->office_email)
			{
				$email->addFrom($config->office_email);
			}
            if ($user->save())
            {
                if ($email->send())
                {
                    
                    $message='Reset details emailed to your primary email account.';
                    $success=TRUE;
                } else
                {
                    $message='Email send error!';
                    $success=false;
                }
            } else
            {
                $message='Unable to update user!';
                $success=false;
            }
        } else
        {
            $message='Unknown user!';
            $success=false;
        }    
        return array('success'=>$success,'message'=>$message);
    }

    public function registerConfirmSendLinkRestAction()
    {
        $id = $_GET['id'];
        if (!$id)
        {
            $message='User Id not provided!';
            $success=false;
        }
        $user= new User();
        $user = $user->find(array('id'=>$id));
        $msg="To confirm your account, please visit the link below:\r\n";
        $response = $this->sendLink($user,'registrationConfirm',$msg);    
        $this->json_response($response);
    }
    

    public function forgotPasswordSendLinkAction()
    {
        $email = $_REQUEST['email'];
        if (!$email)
        {
            $_SESSION['tamsi_notice']='Email not provided!';
            return false;
        }
        $user= new User();
        $user = $user->find(array('email'=>$email));
        $msg="To reset your password please visit the link below:\r\n";
        $response = $this->sendLink($user,'forgotPasswordReceive',$msg);    
        if ($response['success'])
        {
            $_SESSION['tamsi_notice']=$response['message'];
            return $user;
        }    
        else
        {
            $_SESSION['tamsi_notice']=$response['message'];
            return FALSE;
        }
    }
    
    public function forgotPasswordReceiveActionGet()
    {
		$email = $_REQUEST['email'];
		$key = $_REQUEST['resetkey'];
		$user= new User();
        $user->find(array('email'=>$email));
        $user_key = $user->getResetkey();
        if (($key==$user_key) || ($user->getId()==$_SESSION['user_id'])) 
        {
            $secure = new Secure_config();
            $user->setResetkey('');
            $user=$this->login($user,$secure);
            $_SESSION['user_required_action']='change_password';
            $_SESSION['tamsi_notice']='Please update your password through your profile!';
            
            $paths = new Paths();
            $_GET['id']=$user->getId();
			return $paths->admin.'/Users.dir/Edit User.hid.rest.php';
        } else
        {
			$_SESSION['user_id']=0;
			$_SESSION['user_name']=0;
			$_SESSION['user_email']=0;
			$_SESSION['user_role']=0;
			$_SESSION['user_required_action']='';
			$_SESSION['tamsi_notice']='Invalid Credentials or link may have been used already!';
			return false;			
        }
	}

    public function registrationConfirmActionGet()
    {
		$email = $_REQUEST['email'];
		$key = $_REQUEST['resetkey'];
		$user= new User();
        $user->find(array('email'=>$email));
        $user_key = $user->getResetkey();
        if (($key==$user_key) || ($user->getId()==$_SESSION['user_id'])) 
        {
            $role = new Role();
            $role_value=0;
            if (defined('DEFAULT_REGISTRATION_ROLE'))
            {
                $role_value = $role->getValueFromName(DEFAULT_REGISTRATION_ROLE);
            }
            $user->setRole($role_value);
            $user->setResetkey('');
            $secure = new Secure_config();
            
            $user=$this->login($user,$secure);
            $_SESSION['user_required_action']='change_password';
            $_SESSION['tamsi_notice']='Please update your password through your profile!';
            
            $paths = new Paths();
            $_GET['id']=$user->getId();
			$views = array();
            $views['MAIN_CONTAINER']=$paths->admin.'/Users.dir/Edit User.hid.rest.php';
            return $views;
        } else
        {
			$_SESSION['user_id']=0;
			$_SESSION['user_name']=0;
			$_SESSION['user_email']=0;
			$_SESSION['user_role']=0;
			$_SESSION['user_required_action']='';
			$_SESSION['tamsi_notice']='Invalid Credentials or link may have been used already!';
            $_SESSION['site_map']='';
			return false;			
        }
	}

	public function updatePasswordAction()
    {
		$user= new User();

		$user = $user->find(array('id'=>$_REQUEST['id']));
		$password = $_REQUEST['password'];
        $confirm_password = $_REQUEST['confirm_password'];
        if ((strlen($user->getEmail())>0) && (strcmp($password,$confirm_password)==0) && (strlen($password)>3))
        {
            $secure = new Secure_config();
            $user->setSalt($this->security->generateSalt(10));
            $user->setPassword($this->security->generateHash($confirm_password,$user->getSalt(),$secure->secret_key));
            $now = new DateTime('NOW');
            $user->setModified($now->format('Y-m-d H:i:s'));
            $user->setUpdateip($_SERVER['REMOTE_ADDR']);
            $user->setResetkey('');
            if ($user->save()>0)
            {
                $_SESSION['tamsi_notice']='Password successfully updated!';
                return $user;
            } else
            {
                $_SESSION['tamsi_notice']='Password update error!';
                return false;
            }
        } else
        {
            $_SESSION['tamsi_notice']='Password mismatched!';
            return false;
        }
    }
    
    public function testDriveAction()
    {
        $config = new Configurations();
        $role = new Role();
        if (defined('ENABLE_TEST_DRIVE') && ENABLE_TEST_DRIVE)
        {
            if (array_key_exists('email',$_POST) && trim($_POST['email']))
            {
                $message='';
                $test_role = strtolower(ENABLE_TEST_DRIVE);
                $user = new User();
                $email_add = strip_tags(trim($_POST['email']));
                
                $user->find(array('email'=>$email_add));
                
                $key = $this->security->generateKey(32);
                if ($user->getId())
                {
                    $message="You already have an existing account. Now setting to $test_role. ";
                    $user->setRole($role->$test_role);
                    if (!trim($user->getResetKey()))
                    {
                        $user->setResetkey($key);
                    }
                }
                else
                {
                    $user->setEmail($email_add);
                    $names=array('test',ENABLE_TEST_DRIVE);
                    $pos_at = strpos($email_add,'@');
                    if ($post_at)
                    {
                        $name=substr($email_add,0,$post_at+1);
                        $names=array($name,ENABLE_TEST_DRIVE);
                        if (strpos($name,'.'))
                        {
                            $names=explode('.',$name);
                        }
                        else if (strpos($name,'_'))
                        {
                            $names=explode('.',$name);
                        }
                    }
                    $user->setFirstname($names[0]);
                    if (count($names)>1)
                    {
                        $user->setLastname($names[1]);
                    }
                    
                    $user->setResetkey($key);
                }    
                
                
                $config = new Configurations();
                $email = new Email();
                $email->setSubject('Test as doctor');
                if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off')) 
                {
                    $ht = 'https';
                } else
                {
                    $ht='http';
                }
                $config_paths = new Paths();
                
                $link = $ht.'://'.$config->domain.'/?command=forgotPasswordReceive&object=User&email='.$user->getEmail().'&resetkey='.urlencode($user->getResetkey());            
                
                $msg="To automatically login please click the one-time login link below:<br>".$link;
                $email->setMessage($msg);
                $email->addTo($email_add);
                if ($config->office_email)
                {
                    $email->addFrom($config->office_email);
                }
                if ($user->save())
                {
                    if ($email->send())
                    {
                        
                        $message.='Login details emailed to your '.$email_add.' email account.';
                        $success=TRUE;
                    } else
                    {
                        $message.='Email send error!';
                        $success=FALSE;
                    }
                } else
                {
                    $message='Unable to update user! '.$_SESSION['tamsi_notice'];
                    $success=FALSE;
                }
        
                $response = array('success'=>$success,'message'=>$message);
            }
            else
            {
                $response = array('success'=>FALSE,'message'=>'Missing email!');
            }
        }
        else
        {
            $response = array('success'=>FALSE,'message'=>'Unsupported method!');
        }
        $_SESSION['site_map']='';
        return $this->json_response($response);
    }  

    public function googleLoginActionGet()
    {
        // create Client Request to access Google API
        $client = new Google_Client();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URL);
        $client->addScope("email");
        $client->addScope("profile");
        $user = new User();
        $role = new Role();
        $msg='';
        if ($role->isUserLoggedIn())
        {
            $paths = new Paths();
            return $paths->home;                
        }
        $email='';
        $secure = new Secure_config();
        
        // authenticate code from Google OAuth Flow
        if (isset($_GET['code'])) 
        {
            try
            {
                $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                $client->setAccessToken($token['access_token']);

                // get profile info
                $google_oauth = new Google_Service_Oauth2($client);
                $google_account_info = $google_oauth->userinfo->get();
                $email =  $google_account_info->email;
                $name =  $google_account_info->name;

                
                $user->find(array('email'=>$email));
                
                if ($user->getId()==0)
                {
                    $user->setEmail($google_account_info->email);
                    $user->setFirstname($google_account_info->given_name);
                    $user->setLastname($google_account_info->family_name);
                }
                
            } catch (Exception $e) 
            {
                $msg='Caught exception: '.$e->getMessage()."\n";
            }
            if ($user->getId())
            {
                $user=$this->login($user,$secure);
                $paths = new Paths();
                header("Location: ".$paths->base_url);
                exit(); 
            } else
            {
                if (($email) && (defined('GOOGLE_REGISTRATION_ROLE')))
                {
                    $user->setRole($role->getValueFromName(GOOGLE_REGISTRATION_ROLE));
                    
                    $user=$this->login($user,$secure);
                    $_SESSION['tamsi_notice']='Your email '.$email.' does not exist in our system. So we are registering you as '.GOOGLE_REGISTRATION_ROLE.'. Pls fill your profile and password.';
                    
                    $paths = new Paths();
                    $_GET['id']=$user->getId();
                    return $paths->admin.'/Users.dir/Edit User.hid.rest.php';
                }
                else
                {
                    $_SESSION['tamsi_notice']='Invalid Credentials! :'.$msg;
                    $_SESSION['user_id']=0;
                    $_SESSION['user_name']='';
                    $_SESSION['user_email']='';
                    $_SESSION['user_role']='';
                    $_SESSION['user_domain']='';
                    $_SESSION['site_map']='';
                }
            }        
        }
        else
        {
            if (array_key_exists('google',$_GET) && $_GET['google'])
            {
                header('Location:'.$client->createAuthUrl());
                exit;
            }
        }
        
    }

    
    public function facebookLoginActionGet()
    {
        $fb = new Facebook\Facebook([
          'app_id' => FACEBOOK_CLIENT_ID,
          'app_secret' => FACEBOOK_CLIENT_SECRET,
          'default_graph_version' => 'v2.10',
          ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $fb_login_url = $helper->getLoginUrl(FACEBOOK_REDIRECT_URL, $permissions);
        $role=new Role();
        $user = new User();
        $msg='';
        if ($role->isUserLoggedIn())
        {
            $paths = new Paths();
            return $paths->home;                
        }

        $email='';
        if (array_key_exists('facebook',$_GET) && $_GET['facebook'])
        {
            header('Location:'.$fb_login_url);
            exit;
        } else
        {
            
            try 
            {
                $accessToken = $helper->getAccessToken();
            } catch(Facebook\Exception\ResponseException $e) 
            {
                // When Graph returns an error
                $msg='Graph returned an error: ' . $e->getMessage();
            } catch(Facebook\Exception\SDKException $e) {
              // When validation fails or other local issues
                $msg='Facebook SDK returned an error: ' . $e->getMessage();
            }

            if (! isset($accessToken)) 
            {
                if ($helper->getError()) 
                {
                    
                    $msg.="Error: " . $helper->getError() . "\n";
                    $msg.="Error Code: " . $helper->getErrorCode() . "\n";
                    $msg.="Error Reason: " . $helper->getErrorReason() . "\n";
                    $msg.="Error Description: " . $helper->getErrorDescription() . "\n";
                } else 
                {
                    $msg.='Bad request';
                }
                
            }

            // Logged in
            //echo '<h3>Access Token</h3>';
            //var_dump($accessToken->getValue());

            // The OAuth 2.0 client handler helps us manage access tokens
            $oAuth2Client = $fb->getOAuth2Client();

            // Get the access token metadata from /debug_token
            $tokenMetadata = $oAuth2Client->debugToken($accessToken);
            //echo '<h3>Metadata</h3>';
            //var_dump($tokenMetadata);

            // Validation (these will throw FacebookSDKException's when they fail)
            $tokenMetadata->validateAppId(FACEBOOK_CLIENT_ID);
            // If you know the user ID this access token belongs to, you can validate it here
            //$tokenMetadata->validateUserId('123');
            $tokenMetadata->validateExpiration();

            if (! $accessToken->isLongLived()) 
            {
              // Exchanges a short-lived access token for a long-lived one
                try 
                {
                    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                } catch (Facebook\Exception\SDKException $e) 
                {
                    $msg.="<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
                }

            }

            $_SESSION['fb_access_token'] = (string) $accessToken;
            
            
            try 
            {
                // Get the \Facebook\GraphNodes\GraphUser object for the current user.
                // If you provided a 'default_access_token', the '{access-token}' is optional.
                $response = $fb->get('/me?fields=email,first_name,last_name,address,gender',$accessToken);
            } catch(\Facebook\Exceptions\FacebookResponseException $e) 
            {
                // When Graph returns an error
                $msg.='Graph returned an error: ' . $e->getMessage();
            } catch(\Facebook\Exceptions\FacebookSDKException $e) 
            {
                // When validation fails or other local issues
                $msg.='Facebook SDK returned an error: ' . $e->getMessage();
            }

            if (!$msg)
            {
                $me = $response->getGraphUser();
                $email=$me->getProperty('email');
                $user->find(array('email'=>$email));
                if ($user->getId()==0)
                {
                    $user->setEmail($me->getProperty('email'));
                    $user->setFirstname($me->getProperty('first_name'));
                    $user->setLastname($me->getProperty('last_name'));
                }                
            }
            $secure = new Secure_config();
            if ($user->getId())
            {
                $user=$this->login($user,$secure);
                $paths = new Paths();
                header("Location: ".$paths->base_url);
                exit(); 
            } else
            {
                if (($email) && (defined('FACEBOOK_REGISTRATION_ROLE')))
                {
                    $user->setRole($role->getValueFromName(FACEBOOK_REGISTRATION_ROLE));
                    $user=$this->login($user,$secure);
                    $_SESSION['tamsi_notice']='Your email '.$email.' does not exist in our system. So we are registering you as '.FACEBOOK_REGISTRATION_ROLE.'. Pls fill your profile and password.';

                    $paths = new Paths();
                    $_GET['id']=$user->getId();
                    return $paths->admin.'/Users.dir/Edit_User.hid.rest.php';
                }
                else
                {
                    $_SESSION['tamsi_notice']='Invalid Credentials! :'.$msg;
                    $_SESSION['user_id']=0;
                    $_SESSION['user_name']='';
                    $_SESSION['user_email']='';
                    $_SESSION['user_role']='';
                    $_SESSION['user_domain']='';
                    $_SESSION['site_map']='';
                }
            }        
        }
    }


    protected $requirements;
    
    protected function setRequirements($reqs)
    {
        $this->requirements = $reqs;
    }
    
    protected function validate()
    {
        $success=TRUE;
        $message='';
        if (isset($this->requirements))
        {
        
            foreach($this->requirements as $field=>$req)
            {
                if (in_array('required',$req))
                {
                    if (!((array_key_exists($field,$_REQUEST)) && trim($_REQUEST[$field]))) 
                    {
                        $success = FALSE;
                        $message.= "Missing required $field.\r\n";
                    }
                }
                if (in_array($field,$_POST))
                {
                    if (in_array('email',$req))
                    {
                        if (!filter_var(trim($_POST[$field]), FILTER_VALIDATE_EMAIL))
                        {
                            $success = FALSE;
                            $message.= 'Your $field:'.$_POST[$field]." appears to be invalid email.\r\n";
                        }
                    }
                    if (in_array('numeric',$req))
                    {
                        if (!is_numeric(trim($_POST[$field])))
                        {
                            $success = FALSE;
                            $message.= "$field is not numeric .\r\n";
                        }
                    }            
                }
            }
        }
        
        $response = array('message'=>$message,'success'=>$success);
        
        return $response;
    }
    
    protected function getPost($user)
    {
        
        if (array_key_exists("firstname",$_POST))  $user->setFirstname($_POST["firstname"]);
        if (array_key_exists("lastname",$_POST))  $user->setLastname($_POST["lastname"]);
        if (array_key_exists("email",$_POST))  $user->setEmail($_POST["email"]);
        if (array_key_exists("profile",$_POST))  $user->setProfile($_POST["profile"]);
        $now=new DateTime();
        $user->setModified($now->format("Y-m-d H:i:s"));
        $user->setLogin($now->format("Y-m-d H:i:s"));
        if (array_key_exists("role",$_POST))
        {
            $role=0;
            if (is_array($_POST["role"]))
            {
               $rol = new Role();
               $roles = array();
               foreach($_POST["role"] as $r)
               {
                   if ($r<$_SESSION["user_role"]) $roles[] = $r;
               }
               $role = $rol->arrayToIntegerEncoded($roles);
            }
            $user->setRole($role);
        }
        if (array_key_exists("ip",$_POST))  $user->setIp($_POST["ip"]);
        if (array_key_exists("updateip",$_POST))  $user->setUpdateip($_POST["updateip"]);
        if (array_key_exists("loginip",$_POST))  $user->setLoginip($_POST["loginip"]);
        if (array_key_exists("resetkey",$_POST))  $user->setResetkey($_POST["resetkey"]);
        if (array_key_exists("securekey",$_POST))  $user->setSecurekey($_POST["securekey"]);
;
        return $user;
    }
        
    protected function getPostNew($user)
    {
        return $this->getPost($user);
    }
        
    public function create()
    {
        $response = $this->validate();
        if ($response['success'])
        {
            $user= new User();
            $user = $this->getPostNew($user);
            $response = $this->save_new_user($user);
        }
        if (array_key_exists('id',$response))
        {
            $next = new Next();
            $next->url = '?command=display_rest&path=Admin.dir/Users.dir/Edit User.hid.rest.php&id='.$response['id'];
            $next->container = 'div.page';
            $response['next']=$next;
        }
        return $response;
    }
    
    protected function save_new_user($user)
    {
        $old_user = new User();
        $old_user = $old_user->find(array('email'=>$user->getEmail()));
        $confirm_password=$_POST['confirm_password'];
        if ($old_user->getId()>0)
        {
            $message='User with the same email='.$user->getEmail().'  already exist!';
            $success =false;
        } else 
        if (strcmp($_POST["password"],$confirm_password)===0)
        {
            if (strlen($confirm_password)<50)
            {
                $secure = new Secure_config();
                $user->setSalt($this->security->generateSalt(10));
                $user->setPassword($this->security->generateHash($confirm_password,$user->getSalt(),$secure->secret_key));
                $user->setIp($_SERVER['REMOTE_ADDR']);
                $message.= "Profile and password updated! \r\n";
                if ($user->save()>0)
                {

                   $message='Successfully created '.$user->getName().'.';
                   $success=true;
                   
                } else
                {
                   $message='SQL Error! '.$_SESSION['error'].' '.$_SESSION['tamsi_notice'];
                   $success=false;
                }
            } else
            {
                $message.='Password too long! '.$_SESSION['error'];
                $success=false;
            }
        } else
        {
            $message='Password did not match!';
            $success =false;
        }        
        $response = array('message'=>$message,'success'=>$success,'id'=>$user->getId());
        return $response;
    }
    
    protected function getUser()
    {
        $id = $_REQUEST['id'];
		$user= new User();
		$user = $user->find(array('id'=>$id));
        return $user;
    }

    protected function setDefaultIdRequirements()
    {
        if (!isset($this->requirements))
        {
            $this->requirements=array('id'=>array('required'));
        }
        if (!array_key_exists('id',$this->requirements))
        {
            $this->requirements = array_merge($this->requiremenst, array('id'=>array('required')));
        }
    }

    protected function setEditRequirements()
    {
        $this->setDefaultIdRequirements();
        $edit_user_requirements = array('firstname'=>array('required'),'lastname'=>array('required'),'email'=>array('required','email'));
        $this->requirements = array_merge($this->requirements, $edit_user_requirements);
    }
    
    protected function setNewRequirements()
    {
        $this->requirements = array('firstname'=>array('required'),'lastname'=>array('required'),'password'=>array('required'),'email'=>array('required','email'));
    }  
    
    protected function edit()
    {
        $response = $this->validate();
        if ($response['success']) 
        {
            $user = $this->getUser();
            if (is_object($user) && ($user->getId()))
            {
                $user=$this->getPost($user);
                $response = $this->update_user($user);
            }
            else
            {
                $message='Cannot find $user! '.$_SESSION['tamsi_notice'];
                $success=false;
                $response = array('message'=>$message,'success'=>$success);
            }
        }
        return $response;
    }
    
    protected function update_user($user)
    {
        $user->setIp($_SERVER['REMOTE_ADDR']);
        
        $secure = new Secure_config();
        $confirm_password = $_POST["confirm_password"];
        $message = '';
        $success = true;
        if ((strlen($confirm_password)>0) || (strlen($_POST["password"])>0))
        {
            if (strcmp($_POST["password"],$confirm_password)===0)
            {
                if (strlen($confirm_password)<50)
                {
                    $user->setSalt($this->security->generateSalt(10));
                    $user->setPassword($this->security->generateHash($confirm_password,$user->getSalt(),$secure->secret_key));
                    $user->setIp($_SERVER['REMOTE_ADDR']);
                    $message.= "Profile and password updated! \r\n";
                } else
                {
                    $message.='Password too long! '.$_SESSION['error'];
                    $success=false;
                }
            } else
            {
                $message.='Password Error! '.$_SESSION['error'];
                $success=false;
            }
        } else
        {
            $message.="Profile updated!, password preserved. \r\n";
        }
            

        if ($success)
        {
            if ($user->save()>0)
            {
               $message.='Successfully saved '.$user->getName();
               $success=true;
            } else
            {
               $message.='SQL Error! '.$_SESSION['error'].' '.$_SESSION['tamsi_notice'];
               $success=false;
            }
        }
        $response = array('message'=>$message,'success'=>$success);
        return $response;         
    }
    
    protected function remove($user)
    {
        if ($user->getId()>0)
        {
            if ($user->delete()==1)
            {
                
                $message='Successfully deleted '.$id;
                $success=true;
            } else
            {
                $message='SQL Error! '.$_SESSION['tamsi_notice'];
                $success=false;
            }
        } else
        {
            $message='Cannot find user with id='.$id;
            $success=false;            
        }
        $response = array('message'=>$message,'success'=>$success);
        return $response;
    }    

    public function delete()     
    {
        $response = $this->validate();
        if ($response['success']) 
        {
            $user = $this->getUser();
            $response=$this->remove($user);
        }
        return $response;
    }   
    
    public function newRestAction()
    {
        $this->setNewRequirements();
        $response = $this->create();
        $this->json_response($response);
    }    
    
    public function editRestAction()     
    {
        $this->setEditRequirements();
        $response = $this->edit();
        $this->json_response($response);
    }    

    public function deleteRestAction()     
    {
        $this->setDefaultIdRequirements();
        $response=$this->delete();
        $this->json_response($response);        
    }

    public function newAction()
    {
        $this->setNewRequirements();
        $response = $this->create();
        return $this->bool_response($response);
    }

    public function editAction()     
    {
        $this->setEditRequirements();
        $response = $this->edit();
        return $this->bool_response($response);
    }
    
    public function deleteAction()     
    {
        $this->setDefaultIdRequirements();
        $response = $this->delete();
        return $this->bool_response($response);
    }   
    

    public function uploadImageAction()
    {
		$user = $this->getUser();;
        $this->uploadImage($user);
        if (isset($_POST['redirect']) && $_POST['redirect'])
        {
            $url = $_POST['redirect'];
            header('Location: '.$url);
            exit();
        }        
    }
    
    public function generateImageName($user,$folder)
    {
        $name = str_replace(',','_',str_replace(' ','',strip_tags($user->getId().'_users')));
        $name=strtolower($name).($folder?'_'.$folder:'');    
        return $name;
    }
    
    protected function uploadImage($user,$folder='',$force_png=TRUE,$max_width=480)
    {
        $current_image = $user->getImage($folder);
        if (!(strpos($current_image,'none.png')>0))  unlink($current_image);
        if (!$folder) 
        {
            $current_icon_image = $user->getImageIcon($folder);
            if (!(strpos($current_icon_image,'none_icon.png')>0))  unlink($current_icon_image);
        }
        $filename = $this->generateImageName($user,$folder);
        
        $paths = new Paths();
        $ext = strtolower(strrchr($_FILES["file"]["name"], '.'));
        $image = new TamsiImage();
        $image->setFilename($filename.$ext);
        $image->setPath($paths->user_image."users/");
        
        if ($image->save())
        {
            $method='';
            if ($max_width && ($image->getWidth() > $max_width))
            {
                $image->setWidth(480);
                $method='force_width';
            }
            if (($ext!='.png')&&($force_png)&&($ext!='.svg')||($method)) //allow only png and svg
            {
                $image->resize($method); //all image must be stored as png
                $ext='.png';
            }
            if (!$folder) $this->createIcon($image,$filename);
            $_SESSION['tamsi_notice'].='Successfully uploaded image';
            return $filename.$ext;
        } else
        {
            $_SESSION['tamsi_notice'].='Cannot save image!';
            return false;
        }
        
    }
    
    protected function createIcon($image,$name)
    {
        $image->createIcon('64','64',$name.'_icon.png');    
    }



    public function save($user)
    {
        if ($user->save()>0)
        {

           $message="Successfully saved ".$user->getName();
           $success=true;
           
        } else
        {
           $message='SQL Error! '.$_SESSION['tamsi_notice'];
           $success=false;
        }
        
        $response = array('message'=>$message,'success'=>$success,'id'=>$user->getId());    
        return $response;
    }

    public function deleteSelectedRestAction()
    {
        $ret = 0;
        $message = 'No id selected.';
        if (array_key_exists('delete_selected',$_POST))
        {
            $delete_selected = $_POST['delete_selected'];

            if (count($delete_selected)>0)
            {
                $user = new User();
                $filter=array('id'=>$delete_selected);
                $ret = $user->deleteAll($filter);
                $message = $_SESSION['tamsi_notice'];
            }
        }
        $response = array('message'=>$message,'success'=>$ret);
        $this->json_response($response);
    }

    public function json_response($response)
    {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();           
    }
    
    public function bool_response($response)
    {
        $_SESSION['tamsi_notice']=$response['message'];
        return $response['success'];
    }
}

