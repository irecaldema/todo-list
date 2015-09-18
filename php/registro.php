<?php
include("conexionPDO.php");
session_start();

if ($comprobacion_conexion) {
    //echo "conexion correcta";
    if ($_POST) {
       echo "if post";
        
        $usuario = $_POST['usuario'];
        $pass = $_POST['pass'];
        $nombre = $_POST['nombre'];
        
        $encrypted_pass=md5($pass);
        
        //echo "<br>" . $usuario . "<br>" . $encrypted_pass . "<br>" . $nombre;
        
        $sql="SELECT id_usuario FROM usuarios WHERE usuario='$usuario'";
        
        foreach ($conn->query($sql) as $row) {
            $id_usuario=$row["id_usuario"];
        }
        //echo $id_usuario;
        if($id_usuario == ''){
            $sql = "INSERT INTO usuarios (usuario, pass, nombre) VALUES ('".$usuario."', '".$encrypted_pass."', '".$nombre."')";
            $conn->exec($sql);
            //echo $affected_rows.' Introducido correctamente';
        }
        else {
            echo "Existe usuario" ;
            //echo "<script type=\"text/javascript\">alert(\"El usuario ya existe\");</script>"; 
        }
    }
} else {
    echo "mal";
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title> Registro </title>
    <style type="text/css">@import "../css/login.css";</style>
</head>
<body>
    <div id="envoltura">
        <div id="mensaje"></div>
            <div id="contenedor" class="curva">
                <div id="cabecera" class="tac">
                    <p> Registro </p>
                </div>
                <div id="cuerpo">
                    <form id="form-login" action="registro.php" method="post" autocomplete="off">
                        <p>
                            <label for="usuario">Usuario:</label>
                        </p>
                        <p class="mb10">
                            <input name="usuario" type="text" id="usuario" autofocus required />
                        </p>
                        <p>
                        <p>
                            <label for="nombre">Nombre:</label>
                        </p>
                        <p class="mb10">
                            <input name="nombre" type="text" id="nombre" autofocus required />
                        </p>
                        <p>
                            <label for="pass">Contrase&ntilde;a:</label>
                        </p>
                        <p class="mb10">
                            <input name="pass" type="password" id="pass" required />
                        </p>
                        <p>
                            <input name="submit" type="submit" id="submit" value="Enviar" class="boton" />
                            <a href='login.php'> <input name="submit" type="submit" id="submit" value="Iniciar Sesión" class="boton" /></a>
                        </p>
                    </form>
                </div>
                <div id="pie" class="tac">
                   <!-- PIE DE LA VENTANA REGISTRO-->
                </div>
            </div>
        </div>
    </div>
</body>
</html>