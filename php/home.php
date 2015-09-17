<?php
include("conexionPDO.php");
//$conn = conexionPDO();
session_start();
    if (isset($_SESSION['usuario'])){
    } else {
        header("location:login.php");
    }
    echo "usuario ".$_SESSION['usuario'];
    echo " nombre ".$_SESSION['nombre'];
    
    
   
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    </head>
    <body>
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