<?php
include("conexionPDO.php");
session_start();
    if (isset($_SESSION['usuario'])){
    } else {
        header("location:login.php");
    }
    echo "usuario ".$_SESSION['usuario'];
    echo " nombre ".$_SESSION['nombre'];
    echo " id_usuario ".$_SESSION['id_usuario'];
    
    //Busqueda de las listas del usuario
    //function lectura_listas(){
        echo "</br>";
        echo "funcion lectura de listas";
        $sql="SELECT id_lista FROM usuario_lista WHERE id_usuario='".$_SESSION['id_usuario']."'";
        
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
        }
    //}
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
        <div><p>Bienvenido <?php echo $_SESSION['usuario'] ?> <a href='salir.php'> Cerrar sesión <br> </a></p></div>
        <form action="home.php">
            <input type="submit" name="lectura" value="lectura de listas" onclick="lectura_listas()" />
        </form>
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