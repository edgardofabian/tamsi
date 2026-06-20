<?php
if ($_POST)
{
    $servername = $_POST["servername"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $database = $_POST["database"];
    $sql_file = $_POST["sql_file"];
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $message="";
    $error = false;
    // Temporary variable, used to store current query
    $templine = '';
    // Read in entire file
    if (file_exists($sql_file))
    {
        $lines = file($sql_file);
        // Loop through each line
        $i=0;
        foreach ($lines as $line)
        {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';')
            {
                // Perform the query
                if ($conn->query($templine) === TRUE) 
                {
                    $i=$i+1;
                    $message.="Execute lin $i successfully \r\n";
                } else 
                {
                    $message.="Error on sql: " . $conn->error. "\r\n";
                    $error = true;
                }
                // Reset temp variable to empty
                $templine = '';
            }
        }
        if ($error)
        {
            $message.="Error on table import  \r\n";
        } else
        {
            $message.="Tables imported successfully \r\n";
        }
    } else
    {
        $message.="Unable to read $sql_file\r\n";
    }


    $conn->close();
} else
{
    $message="Please input database details";
}
?>
<html>
<body>
<div>
<div class="message">
<?php if ($message) echo $message; ?>
</div>
<form method="POST" action="sql.php">
<div>
<label style="width:120px;display:inline-block" >Servername</label><input type="text" name="servername" value="localhost" />
</div>
<div>
<label style="width:120px;display:inline-block" >Database</label><input type="text" name="database" value="" />
</div>
<div>
<label style="width:120px;display:inline-block">Username</label><input type="text" name="username" value="root" />
</div>
<div>
<label style="width:120px;display:inline-block">Password</label><input type="text" name="password" value="" />
</div>
<div>
<label style="width:120px;display:inline-block">Sql File</label><input type="FILE" name="sql_file" value="" />
</div>
<input type="submit" value="import" > 
</form>
</div>
</body>
</html> 
