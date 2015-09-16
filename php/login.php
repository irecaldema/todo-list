<?php
include("conexionPDO.php");
session_start();
$conn = conexionPDO();
    if ($conn){
        //busqueda en la base de datos del usuario
        //comprobacion de contraseña
        //creacion de variables de sesion
        //si todo ok se cambia a la pagina principal
        if ($_POST){
            $nombre=$_POST["nombre"];
            $pass=$_POST["contrasena"];
            $sql="select nombre from usuarios where userid="+$usuario+"and pass="+$pass;
            $nombre=mysql_query($sql,$link);
            if ($userid!=null){
                $_SESSION['usuario']=$userid;
                $_SESSION['nombre']=$nombre;
                header("location:home.php");
            }
        }
    } else {
        //error en la conexion
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    </head>
    <body>
        <form action="" method="post">
            usuario:
            <input type="text" name="usuario"/>
            contraseña:
            <input type="text" name="contrasena"/>
            <input type="submit" value="login"/>
        </form>
    </body>
</html>