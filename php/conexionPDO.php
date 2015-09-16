<?php
    session_start();
    $servername = "localhost";
    $usuario = "zubiri";
    $password = "";
        
    function conexionPDO(){
        try {
    	    $conn = new PDO('mysql:host=localhost;dbname=TODO_BD', $usuario, $password);
    	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	    //echo "Conexion establecida"; 
    	    return true;
        }
        catch(PDOException $e){
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    }
?>