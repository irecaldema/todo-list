<?php
    $servername = "localhost";
    $usuariobd = "zubiri";
    $passwordbd = "";
    global $conn;
    //function conexionPDO(){
        try {
    	    $conn = new PDO('mysql:host=localhost;dbname=TODO_BD', $usuariobd, $passwordbd);
    	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	    $comprobacion_conexion=true;
    	    return $conn;
        }
        catch(PDOException $e){
            echo "Connection failed: " . $e->getMessage();
            $comprobacion_conexion=false;
        }
    //}
?>