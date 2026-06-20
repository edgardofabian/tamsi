<?php
if ($_POST)
{
    $server_1 = $_POST['server_1'];
    $user_1 = $_POST['user_1'];
    $password_1 = $_POST['password_1'];
    $db_1 = $_POST['db_1'];
    $table_1 = $_POST['table_1'];
    
    $mysqli_1 = new mysqli($server_1,$user_1, $password_1, $db_1);
    if ($mysqli_1->connect_errno) 
    {
        echo "Failed to connect to MySQL 1: " . $mysqli_1->connect_error;
    }
    $sql_1 ="SELECT column_name, data_type,character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table_1' and TABLE_SCHEMA='$db_1';";

    $res_1 = $mysqli_1->query($sql_1);

    $columns_1 = array();
    while ($row_1 = $res_1->fetch_array()) 
    {
        $columns_1[$row_1['column_name']] = $row_1['data_type'].'('.$row_1['character_maximum_length'].')';
    }    

    $server_2 = $_POST['server_2'];
    $user_2 = $_POST['user_2'];
    $password_2 = $_POST['password_2'];
    $db_2 = $_POST['db_2'];
    $table_2 = $_POST['table_2'];

    $mysqli_2 = new mysqli($server_2,$user_2, $password_2, $db_2);
    if ($mysqli_2->connect_errno) 
    {
        echo "Failed to connect to MySQL 2: " . $mysqli_2->connect_error;
    }
    $sql_2 ="SELECT column_name, data_type,character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table_2' and TABLE_SCHEMA='$db_2';";

    $res_2 = $mysqli_1->query($sql_2);
    
    $columns_2 = array();
    while ($row_2 = $res_2->fetch_array()) 
    {
        $columns_2[$row_2['column_name']] = $row_2['data_type'].'('.$row_2['character_maximum_length'].')';
        
    }    

    $html='<table class="data odd_even_row_alternate_color">'."\r\n";
    $html.='<tr><th>field</th><th>'.$table_1.'</th><th>common</th><th>'.$table_2.'</th></tr>'."\r\n";
    foreach($columns_1 as $field_1=>$type_1)
    {
        $html.='<tr><td>'.$field_1.'</td>';
        if (array_key_exists($field_1,$columns_2) && ($columns_1[$field_1]===$columns_2[$field_1]))
        {
            $html.='<td><i class="fa fa-cross"></i></td><td><i class="fa fa-check"></i>'.$columns_1[$field_1].'</td><td><i class="fa fa-cross"></i></td>';
        }
        else
        {
            if (array_key_exists($field_1,$columns_2) && (!($columns_1[$field_1]===$columns_2[$field_1])))
            {
                $html.='<td><i class="fa fa-check" ></i></td><td><i class="fa fa-cross" ></i>'.$columns_1[$field_1].'</td><td><i class="fa fa-cross"></i>'.$columns_2[$field_1].'</td>';
            }
            else
            {
                $html.='<td><i class="fa fa-check" >'.$columns_1[$field_1].'</i></td><td><i class="fa fa-cross" ></i></td><td><i class="fa fa-cross"></i></td>';
            }
        }
        $html.='</tr>'."\r\n";
    }
    foreach($columns_2 as $field_2=>$type_2)
    {
        if (array_key_exists($field_2,$columns_1) && ($columns_2[$field_2]===$columns_1[$field_2]))
        {
            ;
        }
        else
        {
            if (array_key_exists($field_2,$columns_1))
            {
            }
            else
            {
                $html.='<tr><td>'.$field_2.'</td><td><i class="fa fa-cross"></i></td><td><i class="fa fa-cross" ></i></td><td><i class="fa fa-check">'.$columns_1[$field_1].'</i></td></tr>'."\r\n";;
            }
        }
    }
    
    $html.='</table>'."\r\n";
    
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Difference of <?php echo $table_1;?> and <?php echo $table_2;?> Structure</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.29" />
    <link rel="stylesheet" href="../lib/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/menu.css">
</head>

<body>
	<h1>Difference</h1>
    <form method="post" action="diff_table_structure.php">
        <div id="server_1_data">
        <input type="text" name="server_1" placeholder="Server 1" value="localhost" />
        <input type="text" name="db_1" placeholder="Db 1" />
        <input type="text" name="user_1" placeholder="User 1" />
        <input type="text" name="password_1" placeholder="Password 1" />
        <input type="text" name="table_1" placeholder="Table 1" />
        </div>
        <div id="server_2_data">
        <input type="text" name="server_2" placeholder="Server 2" value="localhost"/>
        <input type="text" name="db_2" placeholder="Db 2" />
        <input type="text" name="user_2" placeholder="User 2" />
        <input type="text" name="password_2" placeholder="Password 2" />
        <input type="text" name="table_2" placeholder="Table 2" />
        </div>
        <input type="submit" name="submit" />        
    </form>
    <?php
    if ($_POST) echo $html;
    ?>
</body>

</html>
