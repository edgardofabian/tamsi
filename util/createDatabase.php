<?php
if ($_POST)
{
    $servername = $_POST["servername"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $database = $_POST["database"];
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
} else
{
    $message="<h1>Create database</h1>";
}
?>
<html>
<body>
<div>
<div class="message">
<?php if ($message) echo $message; ?>
</div>
<form method="POST" action="createDatabase.php">
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
<input type="submit" value="create" > 
</form>
</div>
</body>
</html> 
