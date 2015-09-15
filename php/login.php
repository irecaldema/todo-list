<?php
include("conexionPDO.php");
    if (isset($_SESSION['usuario'])){
        header("location:home.php");
    } else {
        session_start();
        $conn = conexionPDO();
        $_SESSION['usuario'] = $usuario;
        $_SESSION['conection'] = "ok";
    }
   
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    </head>
    <body>
        <form action="conexionPDO.php">
            nombre:
            <input type="text" name="nombre"/>
            contraseña:
            <input type="text" name="contrasena"/>
            <input type="submit" value="login"/>
        </form>
    </body>
</html>