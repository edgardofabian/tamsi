<?php
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
}
session_start();
/** index.php 
 * Main entry point of tamsi framework sample code. 
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
 * @link https://tamsi.docph.net 
 */
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING); 
require_once __DIR__.'/../config/paths.cfg.php';

$mathjs = FALSE;
require_once __DIR__.'/../tamsi/Tamsi.php';
$tamsi = new Tamsi();
$role= new Role();?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Tamsi PHP Framework</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicons -->
  <link href="img/favicon.ico" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">


 <?php 
 //add all css paths 
 //$paths is a global variable where you can access specific paths
 $tamsi->view->addCss($paths->base_url.'/lib/bootstrap/css/bootstrap.min.css');
 $tamsi->view->addCss($paths->base_url.'/lib/owlcarousel/assets/owl.carousel.min.css');
 $tamsi->view->addCss($paths->base_url.'/lib/owlcarousel/assets/owl.theme.default.min.css');
 $tamsi->view->addCss($paths->base_url.'/lib/font-awesome/css/font-awesome.min.css');
 $tamsi->view->addCss($paths->base_url.'/lib/animate/animate.min.css');
 $tamsi->view->addCss($paths->base_url.'/lib/modal-video/css/modal-video.min.css');
 
 
 /*default css and jquery are the following 
$tamsi->view->css=array($paths->base_url.'/css/menu.css',$paths->base_url.'/css/style.css');
$tamsi->view->jquery['js']=$paths->base_url.'/js/jquery-1.12.4.js';
$tamsi->view->jquery['js_ui']=$paths->base_url.'/js/jquery-ui.js';
$tamsi->view->jquery['js_ui_touch']=$paths->base_url.'/js/jquery.ui.touch-punch.min.js';
$tamsi->view->jquery['js_css']=$paths->base_url.'/js/jquery-ui.css';
//note: the above jquery scripts and css can be replaced by replacing $tamsi->view->jquery elements 
*/

 $tamsi->view->initializeCssAndJquery(26);?>
 
</head>

<body>  
  
  <header id="header" class="header header-hide">
    <div class="container">
      <div id="logo" class="pull-left">
        <img src="img/tamsi_logo.png" />
      </div>
      <nav id="nav-menu-container">
       <div class="sitemap"><?php $tamsi->view->display('#sitemap');?></div>
      </nav><!-- #nav-menu-container -->
    </div>
  </header><!-- #header -->

<?php
   //$tamsi->view->generateLoginForm("user_login",'email',"style='display:block'",'',32,'login_form',FALSE); 
   //$tamsi->view->generateNoticeForm('rgb(139, 118, 75)');
   $tamsi->view->generateLoginAndNotice(array('login'=>array('registration'=>$paths->public.'/register_user.pub.hid.php'))); 
?>
  <!--==========================
    Hero Section
  ============================-->
  <!--==========================
    Features Section
  ============================-->

  <section class="<?php echo ($role->isUserLoggedIn()?'padd-section':'');?> text-center wow fadeInUp"  >
    <div class="container page">
    <?php
    $tamsi->view->display(MAIN_CONTAINER);
    ?>
    </div>
  </section>

  <!--==========================
    Footer
  ============================-->
  <footer class="footer">
    <div class="copyrights">
      <div class="container">
        <p>&copy; Copyrights Tamsi. All rights reserved. Author: Edgardo Fabian      Since: 2007</p>
        <div class="credits">
          Framework powered by <a href="https://tamsi.docph.net">Tamsi</a>
          Html Template by <a href="https://bootstrapmade.com/">BootstrapMade</a> 
        </div>
      </div>
    </div>
  </footer>



  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

  <!-- JavaScript Libraries -->
  <!--<script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/jquery/jquery-migrate.min.js"></script>-->
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="lib/superfish/hoverIntent.js"></script>
  <script src="lib/superfish/superfish.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/modal-video/js/modal-video.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>

  <!-- Template Main Javascript File -->
  <script src="js/main.js"></script>

 <script>
 $(function()
 {
    $('#tamsi_notice').click( function()
    {
        $(this).hide();
    });
 });
 </script>
</body>
<?php 
$tamsi->view->initializeScripts();
?>
</html>
