<?php
    $servername = "localhost";
    $username = "zubiri";
    $password = "";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    echo "Connected successfully";
?>

<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    </head>
    <body>
        <form>
            nombre:
            <input type="text" name="nombre"/>
            contraseña:
            <input type="text" name="contrasena"/>
            <input type="submit" value="login"/>
        </form>
    </body>
</html>