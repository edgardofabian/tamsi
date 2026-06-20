<?php
require_once __DIR__.'/../config/database.cfg.php';
$db = new db;
if (($db->host) && ($db->user) && ($db->password) && ($db->name))
{
    $servername = $db->host;
    $username = $db->user;
    $password = $db->password;
    $database = $db->name;
    // Create connection
    $conn = new mysqli($servername, $username, $password);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create database
    $sql = "CREATE DATABASE ".$database;
    if ($conn->query($sql) === TRUE) {
        $message="Database $database created successfully";
    } else {
        $message="Error creating database: " . $conn->error;
    }
    $conn->close();
    
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
    if (file_exists('tamsi_db.sql'))
    {
        $lines = file('tamsi_db.sql');
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
                    $message.="Execute line $i successfully \r\n";
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
        $message.="Unable to read tamsi_db.sql\r\n";
    }
    print_r($message);

    $conn->close();
} else
{
    $message="Please input first the details at config/database.cfg.php\r\n";
    print_r($message);
}
?>
