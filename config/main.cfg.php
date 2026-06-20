<?php
if (file_exists(__DIR__.'/main.cfg.php.home'))
{
    require_once __DIR__.'/main.cfg.php.home';
} else
{
    
define("GOOGLE_CLIENT_ID",'<your google client id>');
define("GOOGLE_CLIENT_SECRET",'<your google client secret>');
define("GOOGLE_REDIRECT_URL",'https://local.tamsi.net/?command=googleLogin&object=User');
define("GOOGLE_REGISTRATION_ROLE",'client');

define("FACEBOOK_CLIENT_ID",'<your facebook client id>');
define("FACEBOOK_CLIENT_SECRET",'<your facebook client secret');
define("FACEBOOK_REDIRECT_URL",'https://local.tamsi.net/?command=facebookLogin&object=User');
define("FACEBOOK_REGISTRATION_ROLE",'patient');

define("PROJECT",'tamsi');
define("MAIN_CONTAINER","div.page");
}
