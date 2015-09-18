<?php
include("conexionPDO.php");
//$conn=conexionPDO();
session_start();
    if (isset($_SESSION['usuario'])){
    } else {
        header("location:login.php");
    }
    echo "usuario ".$_SESSION['usuario'];
    echo " nombre ".$_SESSION['nombre'];
    echo " id_usuario ".$_SESSION['id_usuario'];
    
    //Busqueda de las listas del usuario
    $archivadas=true;
    
    if(isset($_POST["lectura1"])||isset($_POST["lectura2"])){
        if(isset($_POST["lectura1"])){ 
            //echo "le diste al boton no archivadas";
            $archivadas=0;
        }
        if(isset($_POST["lectura2"])){
            //echo "le diste al boton archivadas";
            $archivadas=1;
        }    
    //function lectura_listas($archivadas){
        
        echo "</br>";
        //echo "funcion lectura de listas";
        echo "</br>";
        echo "</br>";
        
        $sql="SELECT id_lista FROM usuario_lista WHERE id_usuario='".$_SESSION['id_usuario']."'and archivado=".$archivadas;
        //$sql="SELECT id_lista FROM usuario_lista WHERE id_usuario='".$_SESSION['id_usuario']."'and archivado=true";
        echo $sql;
        echo "</br>";
        
        foreach ($conn->query($sql) as $row) {
            $id_lista=$row["id_lista"];

        
            //Busqueda de la tarea por id de la tarea
                $sql="SELECT titulo FROM listas WHERE id_lista='".$id_lista."'";
                foreach ($conn->query($sql) as $row) {
                    $titulo_lista=$row["titulo"];
                    echo "</br>";
                    echo "titulo ".$titulo_lista;
                    
                    //Busqueda de las tareas de la lista
                    $sql="SELECT descripcion FROM tareas WHERE id_lista='".$id_lista."'";
                    foreach ($conn->query($sql) as $row) {
                        $tarea=$row["descripcion"];
                        echo "</br>";
                        echo $tarea;
                    }
                    echo "</br>";
                }
        }//foreach
    //} function
      } //if
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script src="jquery/jquery-1.11.3.min.js"></script>
        <script src="js/controlador.js"></script>
    </head>
    <body>
        <div><p>Bienvenido <?php echo $_SESSION['usuario'] ?> <a href='salir.php'> Cerrar sesión <br> </a></p></div>
        <form action="" method="post">
            <input type="submit" name="lectura1" value="Lectura de listas" />
            <input type="submit" name="lectura2" value="Listas archivadas" />
        </form>
        
        	<!--boton crear lista-->
        	<fieldset>
        		<legend> Añadir lista </legend>
        	<div class="divspoiler">
        		<input type="button" value="Añadir lista" onclick="
        			if (this.parentNode.nextSibling.childNodes[0].style.display != '') { 
        				this.parentNode.nextSibling.childNodes[0].style.display = ''; 
        				this.value = 'Ocultar añadir lista'; 
        			} else { 
        				this.parentNode.nextSibling.childNodes[0].style.display = 'none'; 
        				this.value = 'Añadir lista'; 
        			}
        		">
        	</div><div><div class="spoiler" style="display: none;">
        		<form name="crear_lista" method="post" action="home.php"> 
        			<input name="gestion" hidden="true" type="text"  value="crear_lista"/> <br>
        			<label> Crear lista: </label>
        			<input name="titulo_lista" type="text" placeholder="Titulo de la lista"/>
        			<input type="submit" name="titulo_lista" value="Crear" onclick="crear_lista()">
        		</form>
        	</div></div>
        	</fieldset>
        	<!--boton cerrado-->
        	
        	<!--boton modificar lista-->
        	<fieldset>
        		<legend> Modificar lista </legend>
        	<div class="divspoiler">
        		<input type="button" value="Modificar lista" onclick="
        			if (this.parentNode.nextSibling.childNodes[0].style.display != '') { 
        				this.parentNode.nextSibling.childNodes[0].style.display = ''; 
        				this.value = 'Ocultar añadir lista'; 
        			} else { 
        				this.parentNode.nextSibling.childNodes[0].style.display = 'none'; 
        				this.value = 'Modificar lista'; 
        			}
        		">
        	</div><div><div class="spoiler" style="display: none;">
        		<form name="modificar_lista" method="post" action="home.php"> 
        			<input name="gestion" hidden="true" type="text"  value="modificar_lista"/> <br>
        			<label> Modificar lista: </label>
        			<input name="titulo_lista" type="text" placeholder="Titulo de la lista"/>
        			<input type="submit" id="submit" value="Modificar">
        		</form>
        	</div></div>
        	</fieldset>
        	<!--boton cerrado-->
        
        <table>
            <tr>
                <td>lista</td>
                <td>titulo</td>
            </tr>
            <!--for(select * from listas numero de resultados){ -->
            <tr>
                <td>listaid</td>
                <td>titulo</td>
            </tr>
            <!--}-->
        </table>
    </body>
</html>