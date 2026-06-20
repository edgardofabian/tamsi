<section id="hero" class="wow fadeIn text-center">
    <div class="title-page">
      <h1>Welcome to Tamsi Again</h1>
      <h2>Database centered PHP Framework</h2>
    
    </div>
    <div class="hero-container">
      <img src="img/tamsi_main.png?x=1" alt="Tamsi">
      <a href="#get-started" class="btn-get-started scrollto">Get Started</a>
      <div class="btns">
        <a href="https://gitlab.com/efabian/tamsi.git"><i class="fa fa-bitbucket fa-2x"></i> Public Repo</a>
        <a href="https://tamsi.docph.net"><i class="fa fa-book fa-2x"></i> Api Documentation</a>
        <a href="#" class="mx-" mx-container="div.page"  mx-click="?command=display_rest&path=advance.3.htm"><i class="fa fa-star fa-2x"></i> Advance Topic </a>
      </div>
    </div>
  </section><!-- #hero -->
  
      
    <!--==========================
    Get Started Section
  ============================-->
  <section  class="padd-section wow fadeInUp">

    <div class="container">
      <div class="section-title text-center">

        <h2>Develop Websites in 3 Easy Steps</h2>
        <p class="separator"></p>

      </div>
    </div>

    <div class="container">
      <div class="row">

        <div class="col-md-6 col-lg-4">
          <div class="feature-block">

            <img src="img/svg/cloud.svg" alt="img" class="img-fluid">
            <h4>1. Design your database</h4>
            <p>This should follow specific tamsi db rules in order for automatic generate of mvc to work.</p>
            <a href="#design_db">read more</a>

          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="feature-block">

            <img src="img/svg/planet.svg" alt="img" class="img-fluid">
            <h4>2. Generate MVC Automatically</h4>
            <p>Using the database tables, Tamsi can generate automatically the model, view and controller.</p>
            <a href="#generate_mvc">read more</a>

          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="feature-block">

            <img src="img/svg/asteroid.svg" alt="img" class="img-fluid">
            <h4>3. Override Generated Codes</h4>
            <p>To customize your application you need to override generated codes.</p>
            <a href="#modify_mvc">read more</a>

          </div>
        </div>

      </div>
    </div>

  </section>
  <section  class="padd-section wow fadeInUp">
    <div style="height:80px"></div>
    <div id="get-started" class="art-content-layout" >
        <div style="display:inline-block;vertical-align:top" >
            <h2>Installation</h2>
            <p>note: This assumes that you have basic knowledge of installing web servers with php support such as apache,nginx,lighttpd etc.
            Therefore the installation assumes you have a mysql db and php support already installed.</p>
            <p>1) Download or clone the files from https://gitlab.com/efabian/tamsi.git and make sure you put it in your server</p>
            <p>2) You may rename the 'tamsi' folder to your desired application directory.</p>
            <p>3) Open the file config/database.cfg.php and change the database host $this->host = "localhost", database name $this->name = "[db name]",
                user name for db $this->user   = "root" and password for db $this->password = "[db password]";</p>
            <p>4) If needed you may change the default roles needed by your users at app/model/Role.php by overriding the default roles of user and admin.</p>
            <p>5) If needed you may also change the extensions or files that will be ignored by the auto menu generation of tamsi at app/model/TamsiModel.php using 
            the $this->file_filters="".</p>
            <p>Now you are ready to design the db tables</p>
            <h2>Methods to load test db data</h2>
            <p>1) If you want to try the sample db data, first fill the $db->host, $db->name, $db->user, $db->password at config/database.cfg.php first with the right data. Then go to util directory and execute <pre>php init_sample_db.php</pre>
            or point your browser to your [server path]/util/init_sample_db.php.
            </p>
            <p>This will create the database tables described below and an initial user with email=tamsi.admin@gmail.com and default password=tamsi</p>
        </div>
    </div>
    <div style="height:40px"></div>
    <div class="art-content-layout" id="design_db" >
        <div style="display:inline-block" >
			<h2 >Initializations</h2>
            <ul>
			<li>Rename the cloned tamsi to a folder of your project name</li>
			<li>Download an html template that you want to be your main theme.</li>
			<li>Rename the index.html file to index.php and place inside the [project] folder.</li>
			<li>Copy all the images from the template into [project]/images</li>
			<li>Copy all the css from the template into [project]/mages</li>
            
            <?php
            //$code = '$this->home = home.pub.htm';
            ?>
            <li>Open the [project]/config/paths.cfg.php and make sure replace the field <?php echo highlight_string('$this->home')[0];?> with the intended home <?php echo highlight_string($code)[0];?></li>
            </ul>
			
            <h2 >Step 1. Design Database</h2>
            <h3>Sample users table</h3>
            <table class="data odd_even_row_alternate_color" >
            <thead><tr><th>field</th><th>type</th><th>description</th></tr></thead>
            <tbody>
                <tr><td>id</td><td>unsigned int</td><td>Primary key</td></tr>
                <tr><td>firstname</td><td>varchar</td><td>Firstname of the user</td></tr>
                <tr><td>lastname</td><td>varchar</td><td>Lastname of the user</td></tr>
                <tr>
                    <td>role</td><td>int</td>
                    <td>Integer values corresponding to certain user roles tied to their rights. A value 0 is always the default assigned rule typically for unauthenticated user. Higher values means higher rights.
                    Details of rights are defined in app/model/Role.php.</td>
                </tr>
                <tr><td>salt</td><td>varchar</td><td>Salt in hashing the password</td></tr>
                <tr><td>password</td><td>varchar</td><td>Hashed password</td></tr>
                <tr><td>created</td><td>timestamp</td><td>Defaults to timestamp as db insertion</td></tr>
            </tbody>
            </table>
            <div style="height:20px" ></div>
            <h3>Sample configurations table</h3>
            <table class="data odd_even_row_alternate_color" >
            <thead><tr><th>field</th><th>type</th><th>description</th></tr></thead>
            <tbody>
                <tr><td>id</td><td>unsigned int</td><td>Primary key</td></tr>
                <tr><td>name</td><td>varchar</td><td>Name of this configuration</td></tr>
                <tr><td>value</td><td>varchar</td><td>Value of this particular configuration</td></tr>
                <tr><td>description</td><td>varchar</td><td>Description of this subject</td></tr>
                <tr><td>created</td><td>timestamp</td><td>Defaults to timestamp as db insertion</td></tr>
            </tbody>
            </table>
            <div style="height:20px" ></div>
            <h3>Sample subjects table linked to users</h3>
            <table class="data odd_even_row_alternate_color" >
            <thead><tr><th>field</th><th>type</th><th>description</th></tr></thead>
            <tbody>
                <tr><td>id</td><td>unsigned int</td><td>Primary key</td></tr>
                <tr><td>name</td><td>varchar</td><td>Name of this subject</td></tr>
                <tr><td>description</td><td>varchar</td><td>Description of this subject</td></tr>
                <tr><td>user_id_teacher</td><td>unsigned int</td><td>This is a foreign key from the 'users' table, which will be named/labeled 'teacher' in tables and inputs</td>
                </tr>
                <tr><td>status</td><td>unsigned int</td><td>active=1 or not_active=0</td></tr>
                <tr><td>created</td><td>timestamp</td><td>Defaults to timestamp as db insertion</td></tr>
            </tbody>
            </table>
            <div style="height:20px" ></div>            
            <h3>Sample enrolees table linked to users and subjects</h3>
            <table class="data odd_even_row_alternate_color" >
            <thead><tr><th>field</th><th>type</th><th>description</th></tr></thead>
            <tbody>
                <tr><td>id</td><td>unsigned int</td><td>Primary key</td></tr>
                <tr><td>subject_id</td><td>unsigned int</td><td>This is a foreign key pointing to the subjects table</td>
                </tr>
                <tr><td>users_ids_enrollees</td><td>unsigned int</td><td>An array of enrollee id pointing to the users table</td></tr>
                <tr><td>created</td><td>timestamp</td><td>Defaults to timestamp as db insertion</td></tr>
            </tbody>
            </table>            
            <div style="height:20px" ></div>
            <h3>Rules in db creation.</h3>
            <p>1) All names of database table names must be in small letters and plural form. For example users, billboards, subjects etc. This is because the model created by the generateModel.php utility will be singular.</p>
            <p>2) Foreign key relation shall be linked according to the name of the field for example 'user_id_teacher' means an id that points to the a user in the 'users' which is named as 'teacher'.
            Setting of foreign key relation in db is not required.</p>
            <p>3) Foreign key relation n to n relation shall be linked according to the name of the field but plural form such as 'users_ids_enrollees'. 
            This means any number of users id (array) that points to users in 'users' table that should be named 'enrollees' in tables and forms.
            </p>
            <p>4) All tables should have an 'id' as a primary key which must be an unsigned integer.</p>
            <p>5) All fields must be in small letters like id, name, description etc.</p>
            <p>6) All tamsi projects should have at least configurations and users tables.</p>
            <p>7) The configurations table should at least have one entry configuration.name='domain' configuration.value='<domain name>' without http:// or https:// like www.gahum.com or docph.net</p>
            
        </div>
    </div>
    <div style="height:40px"></div>
    <div class="art-content-layout" id="generate_mvc" >
        <div style="display:inline-block" >
            <h2>Step 2. Generate Model, View, Controllers</h2>
            <div style="height:20px" ></div>
            <h3>Generate Model</h3>
            <p>1) Open your linux terminal </p>
            <p>2) Go to  ../util folder </p>
            <p>3) run 'php generateModel.php (table name) [link_image] </p>
            <p>For example 'php generateModel.php users link_image', if the users table exist, this command will generate three files namely ../usr_generated/model/UserProtected.php, ../usr_generated/model/UserPublic.php
            and ../usr/model/User.php . The ../user/model/User.php includes a User class that will extend the UserProtected class at ../usr_generated/model/UserProtected.php/.
            The UserProtected class will contain all the get and set methods to access the db fields.
            </p>
            <div style="height:20px" ></div>
            <h3>Generate Controllers</h3>
            <p>1) Open your linux terminal </p>
            <p>2) Go to ../util folder </p>
            <p>3) run 'php generateController.php (table name) [link_image] [login_register]</p>
            <p>For example 'php generateController.php users link_image login_register', if the users table exist, this command will generate two files namely ../usr_generated/controller/TamsiUsersController.php, and ../usr/controller/UsersController.php . The ../usr/controllers/UsersController.php includes a UsersController class that will extend the TamsiUsersController class at ../usr_generated/controller/TamsiUsersController.php/.
            The UsersController will no longer be generated if it already existed to prevent ovewriting user codes.
            </p>
            <p>link_image is an optional parameter to include uploadImageAction()</p>
            <p>login_register is an optional parameter to include loginRestAction and registerRestAction which are only applicable for users table</p>

            <div style="height:20px" ></div>
            <h3>Generate View</h3>
            <p>1) Open your linux terminal </p>
            <p>2) Go to ../util folder </p>
            <p>3) run 'php generateView.php (table name) [link_image] [login_register]</p>
            <p>For example 'php generateView.php users link_image login_register', if the users table exist, this command will generate several files namely 
            <ul>
                <li>[admin dir]/Users.dir/List Users.rest.php - Created if it does not currently exist and simply includes ../usr/view/users/list_users.rest.php </li>
                <li>[admin dir]/Users.dir/Edit User.hid.rest.php - Created if it does not currently exist and simply includes ../usr/view/users/edit_user.rest.php</li>
                <li>[admin dir]/Users.dir/New User.rest.php - Created if it does not currently exist and simply includes ../usr/view/users/new_user.rest.php</li>
                <li>usr/view/users/list_users.rest.php - Created if it does not currently exist and simply includes ../usr_generated/view/users/list_users.tamsi.php</li>
                <li>usr/view/users/edit_user.rest.php - Created if it does not currently exist and simply includes ../usr_generated/view/users/edit_user.tamsi.php</li>
                <li>usr/view/users/new_user.rest.php - Created if it does not currently exist and simply includes ../usr_generated/view/users/new_user.tamsi.php</li>
                <li>usr/view/UserView.php - Created if it does not currently exist and simply extends ../usr_generated/view/UserViewBase.php</li>
                <li>usr_generated/view/UserViewBase.php - Always generated based on table and performs rendering functions for list,edit and new pages</li>
                <li>usr_generated/view/users/list_users.tamsi.php - Always generated based on table and is the primary template html for displaying the list of users</li>
                <li>usr_generated/view/users/edit_user.tamsi.php - Always generated based on table and is the primary template html for showing the edit user form</li>
                <li>usr_generated/view/users/new_user.tamsi.php - Always generated based on table and is the primary template html for showing the new user form</li>
            </ul>
            <p>You should only edit files within usr/view folder.</p>
            <p>link_image is an optional parameter to include uploadImageAction() view support</p>
            <p>login_register is an optional parameter to include login  and register view support</p>
        </div>
    </div>
    <div style="height:40px"></div>
    <div class="art-content-layout" id="modify_mvc" >
        <div style="display:inline-block" >
            <div style="height:20px" ></div>
            <h2>Step 3. Modify generated code to fit your needs</h2>
            <h3>Folder Structure</h3>
<div><pre>
tamsi
├── app
│   ├── controller
│   ├── model
│   └── view
├── composer.json
├── config
│   ├── Config.php
│   ├── database.cfg.php
│   ├── database.cfg.php.gahum
│   ├── main.cfg.php
│   ├── paths.cfg.php
│   ├── security.cfg.php
│   └── views.cfg.php
├── lib
│   └── vendor
├── public_html
│   ├── Admin.dir
│   ├── advance.pub.hid.htm
│   ├── contact_us.php
│   ├── css
│   ├── home.pub.hid.php
│   ├── images
│   ├── img
│   ├── index.php
│   ├── js
│   ├── lib
│   ├── menu.hid.php
│   ├── notice.pub.hid.php
│   └── register_user.pub.hid.php
├── tamsi
│   ├── controller
│   ├── Core.php
│   ├── generate.php
│   ├── graphics.php
│   ├── model
│   ├── polygon.php
│   ├── Tamsi.php
│   └── view
├── usr
│   ├── controller
│   ├── model
│   └── view
└── usr_generated
    ├── controller
    ├── model
    └── view
    </pre></div>
            <div style="height:10px"></div>
            <h3>Rules which files should be edited</h3>
            <p> 1) Files from <span style="font-style:bold">index.php</span> down to the <span style="font-style:bold">images</span> are the files that can be edited</p>
            <p> 2) Foldes from <span style="font-style:bold">img</span>,<span style="font-style:bold">usr_generated</span>,<span style="font-style:bold">tamsi</span> and <span style="font-style:bold">util</span> should not be edited</p>
            <p>3) <span style="font-style:bold">usr_generated</span> are files automatically generated by the utilities which will be discussed later.</p>
            <div style="height:20px"></div>
            <h3>Modify Basic Structure of Page</h3>
            <p>The index.php determines the basic structure of the page. You can basically placed tamsi in any html theme structure. 
            <h4>Quick Summary of Process to Integrate Tamsi into a theme index.php</h4>
            <p>1. Call the core tamsi files and instatiate core objects on top of the file</p> 
            <p>2. Call the tamsi framework to load css/jquery inside the header section of the html</p>
            <p>3. Configure menu based on login conditions</p>
            <p>4. Render hidden login form and notice form after the menu section</p>
            <p>5. Prepare the div.page where to display the dynamically loaded pages</p>
            <p>6. Call display controller and all other javascript before the end marker of the html body section.</p>
            <p>A very simple structure is shown below:</p>
            <div style="background-color:#DDDDDD">
            <code>
            <?php
            //$code=file_get_contents(__DIR__.'/lib/index_structure.php');
            //echo highlight_string($code)[0];?>
            </code>
            </div>
            <br><br>
            <h4>Detailed Process to Integrate Tamsi into a theme index.php</h4>
            <p>1) You must insert the following on the top most part of the index.php the calls of tamsi core files</p>
            <div style="background-color:#DDDDDD">
            <code><span style="color: #000000"><span style="color: #0000BB">&lt;?php
            <br></span><span style="color: #0000BB">session_start</span><span style="color: #007700">();
            <br>
            <br></span><span style="color: #0000BB">ini_set</span><span style="color: #007700">(</span><span style="color: #DD0000">'display_errors'</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">'On'</span><span style="color: #007700">);
            <br></span><span style="color: #0000BB">error_reporting</span><span style="color: #007700">(</span><span style="color: #0000BB">E_ALL&nbsp;</span><span style="color: #007700">^&nbsp;</span><span style="color: #0000BB">E_NOTICE&nbsp;</span><span style="color: #007700">^&nbsp;</span><span style="color: #0000BB">E_WARNING</span><span style="color: #007700">);&nbsp;
            <br>require_once&nbsp;</span><span style="color: #DD0000">'tamsi/Tamsi.php'</span><span style="color: #007700">;
            <br>
            <br></span><span style="color: #0000BB">$tamsi&nbsp;</span><span style="color: #007700">=&nbsp;new&nbsp;</span><span style="color: #0000BB">Tamsi</span><span style="color: #007700">();
            <br></span><span style="color: #0000BB">$user</span><span style="color: #007700">=&nbsp;new&nbsp;</span><span style="color: #0000BB">User</span><span style="color: #007700">()//Optional if you need User object to test for login status;
            <br></span><span style="color: #0000BB">$role</span><span style="color: #007700">=&nbsp;new&nbsp;</span><span style="color: #0000BB">Role</span><span style="color: #007700">()//Optional if you need to check roles;
            <br></span><span style="color: #0000BB">?&gt;</span>
            </code>
            </div>
            <br>
            <p>2) You must insert the following right befor end of head section <code><?php echo  htmlentities('</head>');?></code><br> 
            <div style="background-color:#DDDDDD">
            <code>
            <?php
            //echo highlight_string($code)[0];?>
            </code>
            </div>
            <br>
            <p>3) You may have to include the default login and notice form right after the headers, but you could also make your own</p>
            <code>
            <?php
            //echo highlight_string($code)[0];?>
            </code>
            <p>4) You may override some menus like</p>
            <div style="background-color:#DDDDDD">
            <?php
            $code ='    <ul class="nav-menu">
            <li class="menu-active"><a href="#hero">Home</a></li>
        <?php 
        if (!$role->isUserLoggedIn())
        {
        ?>
            <li class="dropdown"> <a data-toggle="dropdown" href="#" onclick="showLogin()" ><i class="fa fa-user-plus"></i> LOGIN</a></li>
        <?php
        } else 
        {
        ?>
            <li><a href="#" class="mx-" mx-container="div.page" mx-click="?command=display_rest&path=Admin.dir/Informations.dir/List Informations.rest.php">Informations</a></li>
            <li><a href="#" class="mx-" mx-container="div.page" mx-click="?command=display_rest&path=Admin.dir/Users.dir/List Users.rest.php">Users</a></li>
            <li><a href="?command=logout" class="" style=""><i class="fa fa-user-times"></i>LOGOUT</a></li>
        <?php 
        }?> 
        </ul>';
            echo highlight_string($code)[0];?>
            </div>
            <br>
            <p>5) You must insert this code inside the div.page where you need to load dynamically loaded pages. The else portion is the default home page like /home1.php</p>
            <div style="background-color:#DDDDDD">
            <?php 
            echo highlight_string($code)[0];?>
            </div>
            <br>
            <p>6. All other js files should be called at the end of the html before the &lt;/body&gt; as shown below</p>
            <div style="background-color:#DDDDDD">
            <?php
            $code = '<?php $tamsi->view->initializeScripts(array("mathjax"=>false,"speak"=>false,"chosen"=>true,"wysiwyg"=>true,"datetimepicker"=>true));?>';
            echo highlight_string($code)[0];?></div>
            <br>
            <p>The resulting integration of Tamsi into a more complex theme index.php is shown below:</p>
            <div style="background-color:#DDDDDD" >
            <?php 
            $index = file_get_contents(__DIR__.'/lib/index_sample.php');   
            echo highlight_string($index)[0];
            ?> 
            </div>      
            <p>9) All php codes marked by <?php echo highlight_string("<?php ....?>")[0]; ?> are required</p>
            <p>10) There should always be a <?php echo highlight_string("<div class='page'> </div>")[0]; ?> because this is where other pages are loaded via restful process.</p>
            <div style="height:20px" ></div>
            <h3>Modify Models</h3>
            <p>1) You should only modify the extended versions of models at usr/model folder. This is to insure that when you change your db and update your models
            the changes you added will not be overwritten by generaModel.php. The generateModel.php will only overwrite the models at usr_generate/model folders</p>
            <p>2) You may override functions from the usr_generated/model folder</p>
            <p>3) You should add user functions only to the models at usr/model folder.</p>
            <div style="height:20px" ></div>
            <h3>Modify Controllers</h3>
            <p>1) You should only modify the extended versions of controllers at usr/controller folder. This is to insure that when you change your db and update your controllers the changes you added will not be overwritten by generaController.php. The generateController.php will only overwrite the controllers at usr_generate/model folders</p>
            <p>2) You may override functions from the usr_generated/controller folder</p>
            <p>3) You should add user controller functions only to the controllers at usr/controller folder.</p>
            <div style="height:20px" ></div>
            <h3>Modify Views</h3>
            <p>1) Views (List, Edit, New) and View models are automatically generated at usr_generated/view folder by generateView.php.  </p>
            <p>2) generateView.php also places a default extensions of view models and files at usr/views if not yet available. This is the portion that you can edit.</p>
            <p>3) generateView.php also places a default file at the Administration directory for listing, adding and editing of table items. These views simple include the views at usr/view/.</p>
            <p>The above is terminal command in linux that assumes the folder structure defined above executed at ../Admin.dir/Users.dir/ directory</p>
            <div style="height:20px" ></div>
            
        </div>
    </div>    
    </section>
