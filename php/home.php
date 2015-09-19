<?php
include("conexionPDO.php");
//$conn=conexionPDO();
session_start();
    if (isset($_SESSION['usuario'])){
    } else {
        header("location:login.php");
    }
    echo "Usuario: ".$_SESSION['usuario']."</br>";
    echo " nombre:  ".$_SESSION['nombre']."</br>";
    echo " id_usuario: ".$_SESSION['id_usuario']."</br>";
    
    //Busqueda de las listas del usuario
        
        //$sql="SELECT id_lista FROM usuario_lista WHERE id_usuario='".$_SESSION['id_usuario']."'and archivado=".$archivadas;
        $sql="SELECT * FROM usuario_lista WHERE id_usuario='".$_SESSION['id_usuario']."'";
        
        //echo $sql;
        echo "</br>";
        $count = false;
        $count = 1; 
        foreach ($conn->query($sql) as $row) {
            $id_lista=$row["id_lista"];
            $archivadas=$row["archivado"];
            //echo $archivadas;
            
            //Busqueda de la tarea por id de la tarea
                $sql="SELECT titulo FROM listas WHERE id_lista='".$id_lista."'";
                foreach ($conn->query($sql) as $row) {
                    $titulo_lista=$row["titulo"];
                    
                    if ($archivadas == 1){
                        //Muestro tambien las listas archivadas
                        $listasArchivadas = "form_lista_archivadas".$count."";
                        echo "Cuantas archivadas:   ".$listasArchivadas;
                        echo "</br>";
                    }else {
                        $listasArchivadas = "form_lista".$count."";
                        echo "Cuantas NO archivadas:  ".$listasArchivadas;
                        echo "</br>";
                    }
                    $tabla = "<td><table border=1>";
                    $varForm = $tabla."<tr><td><form name='".$listasArchivadas."' method='post' action='home.php'>";
                    $varForm1 = "</br>";
                    //echo "titulo ".$titulo_lista;
                    
                    $varForm2 = "<input name='titulo_lista' type='text' value='".$titulo_lista."' />";
        			//echo "<input type='submit' name='modificar' id='submit' value='Modificar titulo'>";
        			$varForm3  =" <button onclick='alert('You are clicking on me');'>Modificar titulo</button></td></tr>";
                    
                    //Busqueda de las tareas de la lista
                    $sql="SELECT descripcion FROM tareas WHERE id_lista='".$id_lista."'";
                    foreach ($conn->query($sql) as $row) {
                        $tarea=$row["descripcion"];
                        $varForm4 ="</br>";
                        $varForm5 = "<tr><td><textarea rows='12' cols='40'>$tarea</textarea>";
                    }
                    $varForm6 = "</form>";
                    $varForm7 = "</br></td></tr></table></td>";
                    
                    if ($archivadas == 1){
                         $formTotalArchivadas[$count]= $varForm.$varForm1.$varForm2.$varForm3.$varForm4.$varForm5.$varForm6.$varForm7;
                         //print_r($formTotalArchivadas[$count]);
                    }else {
                         $formTotal[$count]= $varForm.$varForm1.$varForm2.$varForm3.$varForm4.$varForm5.$varForm6.$varForm7;
                         //var_dump($formTotal[1]);
                    }
                    $count++;
                }
        }//foreach
        $archivadas=false;
      //} //if
      
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <style type="text/css">@import "../css/login.css";</style>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script src="../jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
        <script src="../js/controlador.js"></script>
        <script type="text/javascript">
        $(document).ready(function(){
            $('#divListasArchivadas').hide();
            $('.ocultarArchivados').hide();
            
		    $(".mostrarArchivados").on( "click", function() {
		    	$('#divListasArchivadas').show("slow"); //muestro mediante clase
		    	$('.mostrarArchivados').hide();
		    	$('.ocultarArchivados').show("slow");
		     });
		     $(".ocultarArchivados").on( "click", function() {
		    	$('#divListasArchivadas').hide(); //muestro mediante clase
		    	$('.mostrarArchivados').show("slow");
		    	$('.ocultarArchivados').hide();
		     });
        });
        </script>
    </head>
    <body>
        <div><p>Bienvenido <?php echo $_SESSION['usuario'] ?> <a href='salir.php'> Cerrar sesión <br> </a></p></div>
        <table border=2>
            <tr>
                <div id="divListas">
                     <?php for ($i = 0; $i <= $count; $i++) { ;?>
                        <div><?php print_r( $formTotal[$i]); ?></div>
                    <?php };?>
                </div>
            </tr>
        </table>
        <br/>
        <div class="mostrarArchivados">Listas Archivadas <img src="../img/iconos/chevron-down.png" height="20px" width="20px" id="ico_mostrar" /> </div>
        <div class="ocultarArchivados">Listas Archivadas <img src="../img/iconos/chevron-up.png" height="20px" width="20px" id="ico_ocultar"/> </div>
        <div id="divListasArchivadas">
             <?php for ($i = 1; $i <= $count; $i++) { ;?>
                <div><?php echo $formTotalArchivadas[$i]; ?></div>
            <?php };?>
        </div>
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