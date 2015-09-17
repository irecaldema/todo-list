<?php
    $servername = "localhost";
    $usuario = "zubiri";
    $password = "";
    
    function conexionPDO(){
        try {
    	    $conn = new PDO('mysql:host=localhost;dbname=TODO_BD', $usuario, $password);
    	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	    return $conn;
        }
        catch(PDOException $e){
            echo "Connection failed: " . $e->getMessage();
        }
    }
?>