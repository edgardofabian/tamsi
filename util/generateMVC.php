<?php
ini_set('display_errors', 'On');
//error_reporting(E_ALL ^ E_WARNING); 
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
require_once __DIR__.'/../config/database.cfg.php';
require_once __DIR__.'/../config/paths.cfg.php';
$paths = new Paths();
set_include_path($paths->includes);

$mysqli = new mysqli($db_host,$db_user, $db_password, $db);
if ($mysqli->connect_errno) 
{
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$sql="SHOW TABLES FROM {$db};";
$res = $mysqli->query($sql);
//die(json_encode($res));
$tables=array();
if ($mysqli->affected_rows>0)
{
    while ($row = $res->fetch_array()) 
    {   
        $tables[]=$row[0];
    }
} else
{
    print_r("No tables found....\n");
}
/*$res->close();*/
sort($tables);
/*
 * index.php
 * 
 * Copyright 2017 elline fabian <efabian@efabian-OptiPlex-3020>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

	<title>Generate Model, View and Controller</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.23.1" />
    <script src="../js/jquery.js"></script>
</head>

<body style='text-align:center;' >
    <img src='../img/tamsi.png' />


<div class="form_message" style="position:relative;display:block;width:90%;margin-left:5%;margin-right:5%;border-bottom-left-radius:5px;border-bottom-right-radius:5px;background:#77FF77;text-align:left;padding-left:20px;padding-bottom:20px"><?php echo $message;?></div>
<h2>Generate Model, View and Controller</h2>
<form id="generate" style="max-width:700px;margin-left:auto;margin-right:auto;margin-bottom:20px;font-family: ABeeZee; font-size:18px; color:#006666;" action="index.php" method="POST">
<div class="question" style="text-align:left"><label style="width:200px;display:inline-block" >Table Name</label><!--<input type="text" name="table" style="display:inline-block" value=""/>-->
<select id="table_input" name="table" table_name="<?php echo $db;?>">
    <option value=''>None</option>
<?php
foreach ($tables as $table)
{
    if ($table) echo '<option value="'.$table.'" >'.$table.'</option>'."\r\n";
}
?>
</select>
<label style="display:inline-block">* required</label></div>
<div class="question" style="text-align:left"><label style="width:200px;display:inline-block" >Unique field other than index</label><input type="text" name="no_repeat" style="display:inline-block" value=""/><label style="display:inline-block">( optional )</label></div>
<div class="question" ><div class="label" style="text-align:left;margin-top:10px"><label>Options:</label></div>
    <div class="choices" style="text-align:left;margin-left:200px">
        <label style='display:block'><input type="checkbox" style="display:inline;" value="1" name="login_register" style="width:200px">Include login and register (only for users table)</input></label>
        <label style='display:block'><input type="checkbox" style="display:inline" value="1" name="singular_table" style="width:200px">Table names are singular form</input></label>
        <label style='display:block'><input type="checkbox" style="display:inline" value="1" name="link_image" style="width:200px">Include image linking functionality</input></label>
        <label style='display:block'><input type="checkbox" style="display:inline" value="1" name="force_not_restful" style="width:200px">Generate none RESTful controller functions</input></label>
    </div>
</div>

<input id="generate_model" type="button" value="Generate Model" />
<input id="generate_view" type="button" value="Generate View" />
<input id="generate_controller" type="button" value="Generate Controller" />
<input id="generate_mvc" type="button" value="Generate MVC" />
<input id="generate_all" type="button" value="Generate All" />
</form>
</body>
<script>
var tables = <?php echo json_encode($tables);?>;
var all = 0;
var i=0
function clearMessage()
{
    $('div.form_message').html('');
}
function hideMessage()
{
    $('div.form_message').hide();
}

function appendMessage(data)
{
    var msg = $('div.form_message').html()+"\r\n"+data.replace(/\n/g,"<br />")
    $('div.form_message').html(msg);
}

function showMessage(data)
{
    var hide_button = '<button type="button" onclick="hideMessage()">close</button>';
    var msg = $('div.form_message').html()+"\r\n"+data.replace(/\n/g,"<br />")+hide_button;
    $('div.form_message').html(msg);
    $('div.form_message').show();
}

function formPost(url,form, callback)
{
    $.post(encodeURI(url),form.serialize(),function(data)
    {
        if(typeof callback === "function")
        {
            appendMessage(data);
            callback();
        }
        else
        {
            showMessage(data);
        }
    });
    return false;   
}
function empty()
{
    alert('done');
}
function generate_model()
{
    if (all) 
    {
        i=i+1;
        $('#table_input').val(tables[i]);
        if (i==(tables.length-1)) all=0;
    }
    var rnd = Math.floor((Math.random() * 1000000) + 1);
    formPost('generateModel.php?rnd='+rnd,$('#generate'),generate_controller);    
}
function generate_controller(data)
{
    var rnd = Math.floor((Math.random() * 1000000) + 1);
    formPost('generateController.php?rnd='+rnd,$('#generate'),generate_view);    
}
function generate_view()
{
    var rnd = Math.floor((Math.random() * 1000000) + 1);
    if (all)
    {
        formPost('generateView.php?rnd='+rnd,$('#generate'),generate_model);    
    }
    else
    {
        formPost('generateView.php?rnd='+rnd,$('#generate'),'');
    }
}

$(document).ready(function() 
{
    $('#generate_model').click( function()
    {
        clearMessage();
        var rnd = Math.floor((Math.random() * 1000000) + 1);
        formPost('generateModel.php?rnd='+rnd,$('#generate'),'');
    });

    $('#generate_view').click( function()
    {
        clearMessage();
        var rnd = Math.floor((Math.random() * 1000000) + 1);
        formPost('generateView.php?rnd='+rnd,$('#generate'),'');
    });

    $('#generate_controller').click( function()
    {
        clearMessage();
        var rnd = Math.floor((Math.random() * 1000000) + 1);
        formPost('generateController.php?rnd='+rnd,$('#generate'),'');
    });
    
    $('#generate_mvc').click( function()
    {
        clearMessage();
        generate_model();
    });
    
    $('#generate_all').click( function()
    {
        clearMessage();
        all=1;
        $('#table_input').val(tables[i]);
        generate_model();
    });    
    
    hideMessage();
});
</script>
</html>
