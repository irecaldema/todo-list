<?php
include("conexionPDO.php");
session_start();
//$conn = conexionPDO();
    //$conn=0;
    try {
	    $conn = new PDO('mysql:host=localhost;dbname=TODO_BD', $usuario, $password);
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    //echo "Connection ok";
	    $comprobacion_conexion=true;
    }
    catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
        
        $comprobacion_conexion=false;
    }
    if ($comprobacion_conexion){
        //busqueda en la base de datos del usuario
        //comprobacion de contraseña
        //creacion de variables de sesion
        //si todo ok se cambia a la pagina principal
        if ($_POST){
            $usuario=$_POST["usuario"];  //echo "usuario ".$_POST["usuario"];
            $pass=$_POST["pass"];  //echo "pass ".$_POST["pass"];
            //$sql="SELECT nombre FROM usuarios WHERE usuario='$usuario' and pass='$pass";
            //$sql="SELECT nombre FROM usuarios WHERE usuario='nohtrim' and pass=1234";
            $sql="SELECT nombre FROM usuarios WHERE usuario='".$usuario."'and pass='".$pass."'";
            
            //$stmt = $db->query('SELECT * FROM table');
            /*foreach ($conn->query($sql) as $row) {
                $nombre=$row["nombre"];
            }*/ 
            foreach ($conn->query($sql) as $row) {
                $nombre=$row["nombre"];
            }
            
            /*$sth = $conn->prepare($sql);
            $sth->execute();
            $array = $sth->fetchAll();
            $nombre=$array[0];
            print_r($array);*/
            
            //$stmt = $conn->query($sql); 
            //$nombre =$stmt->fetchObject();
            //echo $nombre->nombre;
            //echo "nombre ".$nombre;
            
           if ($nombre!=null){
                $_SESSION['usuario']=$usuario;
                $_SESSION['nombre']=$nombre;
                header("location:home.php");
            }
        }
    } else {
        //error en la conexion
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
    	<meta charset="utf-8" />
    	<title> Login </title>
    	<style type="text/css">@import "../css/login.css";</style>
    </head>
    <body>
    	<div id="envoltura">
    		<div id="mensaje"></div>
    			<div id="contenedor" class="curva">
    				<div id="cabecera" class="tac">
                        <p> Login </p>
    				</div>
    				<div id="cuerpo">
    					<form id="form-login" action="login.php" method="post" autocomplete="off">
    						<p>
    							<label for="usuario">Usuario:</label>
    						</p>
    						<p class="mb10">
    							<input name="usuario" type="text" id="usuario" autofocus required />
    						</p>
    						<p>
    							<label for="pass">Contrase&ntilde;a:</label>
    						</p>
    						<p class="mb10">
    							<input name="pass" type="password" id="pass" required />
    						</p>
    						<p>
    							<input name="submit" type="submit" id="submit" value="Ingresar" class="boton" />
    							<a href='register.php'> <input name="submit" type="submit" id="submit" value="Registrar" class="boton" /></a>
    						</p>
    					</form>
    				</div>
    				<div id="pie" class="tac">Sistema de Gesti&oacute;n de Contenidos
    				</div>
    			</div>
    		</div>
    	</div>
    </body>
</html>