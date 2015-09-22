<?php
include("conexionPDO.php");
//$conn=conexionPDO();
session_start();
    if (isset($_SESSION['usuario'])){
    } else {
        header("location:login.php");
    }
    echo "Usuario: ".$_SESSION['usuario']."<br/>";
    echo " nombre:  ".$_SESSION['nombre']."<br/>";
    echo " id_usuario: ".$_SESSION['id_usuario']."<br/>";
    
    //Busqueda de las listas del usuario
        
        //$sql="SELECT id_lista FROM usuario_lista WHERE id_usuario='".$_SESSION['id_usuario']."'and archivado=".$archivadas;
        $sql="SELECT * FROM usuario_lista WHERE id_usuario='".$_SESSION['id_usuario']."'";
        
        //echo $sql;
        echo "<br/>";
        //$count = false;
        $count = 1; 
        foreach ($conn->query($sql) as $row) {
            $id_lista=$row["id_lista"];
            $archivadas=$row["archivado"];
            //echo $archivadas;
            
            //Busqueda de todos los titulos segun los id listas obtenidos anteriormente
                $sql="SELECT titulo FROM listas WHERE id_lista='".$id_lista."'";
                foreach ($conn->query($sql) as $row) {
                    $titulo_lista=$row["titulo"];
                    
                    if ($archivadas == 1){
                        //creacion de formulario por lista
                        $tituloFormulario = $count."form_lista_archivadas";
                        /*echo "Cuantas archivadas:   ".$tituloFormulario;
                        echo "<br/>";*/
                    } else {
                        $tituloFormulario = "form_lista".$count."";
                        /*echo "Cuantas NO archivadas:  ".$tituloFormulario;
                        echo "<br/>";*/
                    }
                    // cada lista sera una celda de la tabla de listas
                    //creacion de la celda
                    //$tabla = "<td><table border=1>"; //quitando tablas
                    //$aperturaForm = $tabla."<tr><td><form name='".$tituloFormulario."' method='post' action='home.php'>";
                    $aperturaForm = "<form name='".$tituloFormulario."' method='post' action='home.php'>";
                    
                    /*cambio de linea en espera de condicion
                    if(){
                        $aperturaForm = "<tr><td><form name='".$tituloFormulario."' method='post' action='home.php'>";
                    }else{
                        
                    }*/
                    
                    //echo "titulo ".$titulo_lista;
        //BORRAR ID LISTA ANTES DE FINALIZAR
                    $titulo = 
                    "<table border=1><tr><td><b>".$id_lista." Titulo:  </b><input class='input_titulo' id='$count' type='text' value='".$titulo_lista."' />";
                    //Busqueda de las tareas de la lista
                    //print($id_lista);
                    $sql="SELECT tarea FROM tareas WHERE id_lista='".$id_lista."'";
                    //vaciado de tareas
                    $task="";
                    foreach ($conn->query($sql) as $row) {
                        $tarea=$row["tarea"];
                        //tarea anterior mas tarea
                        $comodin = "<tr><td><textarea rows='2' cols='40'>$tarea</textarea></td></tr>";
                        $task.= $comodin;
                    }
                    $listusers="<tr><td>Usuarios: ";
                    //foreach EN CONSTRUCCION IOSU
                    $sql="SELECT id_usuario FROM usuario_lista WHERE id_lista='".$id_lista."'";
                    foreach ($conn->query($sql) as $row) {
                        $listUserId=$row["id_usuario"];
                        $sql="SELECT usuario FROM usuarios WHERE id_usuario='".$listUserId."'";
                        foreach ($conn->query($sql) as $row) {
                            $listUser=$row["usuario"];
                            //usuario anterior mas tarea
                            $comodin = "<b>*^".$listUser."^*<b>";
                            $listusers.= $comodin;
                        }    
                    } 
                    $listusers.="</tr></td>";
                    
                    $cierre = "</table></form></td></tr></table>";
                        
                    //Dependiendo de si son archivadas o no voy guardando todo lo anterior en una array diferente
                    if ($archivadas == 1){
                        $formTotalArchivadas[$count]= $aperturaForm.$titulo.$task.$listusers.$cierre;
                        //print_r($formTotalArchivadas[$count]);
                    }else {
                        $formTotal[$count]= $aperturaForm.$titulo.$task.$listusers.$cierre;
                        //var_dump($formTotal[1]);
                    }
                    $count++;
                }//foreach 2
        }//foreach 1
        
        //$formulario=$_POST[""];
        $formulario="crear_lista";
        if ($formulario == 'crear_lista') {
            $nombre_lista = $_POST["titulo_lista"];
            $tarea = $_POST["tarea"];
            echo "Creando lista<br>";
            
            if ($nombre_lista == '') {
                echo "Vacio, se te redirccionará. Espere.";
                header('location:home.php');
                //header("Refresh: 3; URL=home.php");
            } else {
                //echo $nombre_lista;
                $sqlLista = "INSERT INTO listas (titulo) VALUES ('".$nombre_lista."')";
                $conn->exec($sqlLista);
                $lastid= $conn -> lastInsertId();
                
                //echo $lastid;

                $id_usu = $_SESSION['id_usuario'];
                
                $sqlUsuLista = "INSERT INTO usuario_lista VALUES ($id_usu, $lastid, 0)";
                $conn->exec($sqlUsuLista);
                
                // TAREAS
                $sqlTareas = "INSERT INTO tareas (id_lista, tarea, terminado) VALUES ($lastid, '$tarea', 0)";
                $conn->exec($sqlTareas);
                
               // echo $affected_rows.' Introducido correctamente';
               // header("Refresh: 3; URL=home.php");
                sleep(2);
                header('location:home.php');
            }
       }
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <style type="text/css">@import "../css/home.css";</style>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
          <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
          <script src="//code.jquery.com/jquery-1.10.2.js"></script>
          <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
                
        <script src="../js/controlador.js"></script>
        <script type="text/javascript">
        $(document).ready(function(){
            //Oculto lo que no quiero ver de primeras
            $('#divListasArchivadas').hide();
            $('.ocultarArchivados').hide();
           // $(".input_titulo").hide();
            
            //El pequeño menu para mostrar y quitar los archivados
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
		    
		   //Esto hace posible mover las listas
		   //La idea es poder moverlas por separado, pero no lo consigo
          
          //Intento hacer posible que al pulsar en el titulo de la lista aparezca el input para poder modificarla
          //He conseguido identificar que titulo estoy pulsando, pero no consigo hacer aparecer el input...
		   $(document).on("click", '.label_titulo', function() {
                var valorId = $(this).attr("id");
                alert(valorId);
                $("div#"+valorId).hide();
                $("input#"+valorId).show();
                //$(this).fadeOut(); 
            });
        });
        </script>
    </head>
    <body>
        <div><p>Bienvenido <?php echo $_SESSION['usuario'] ?> <a href='salir.php'> Cerrar sesión <br> </a></p></div>
        <div>
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
        			<input name="titulo_lista" type="text" placeholder="Titulo de la lista"/> <br>
        			Tarea: <br>
        			<textarea name='tarea' rows='2' cols='40'>  </textarea> <br>
        			<input type="submit" name="crear" value="Crear" onclick="crear_lista()">
        		</form>
        	</div></div>
        	</fieldset>
            <!-- LISTAS NO ARCHIVADAS - Hago bucle para mostrarlas -->
            <div id="divListas">
                 <?php for ($i = 0; $i <= $count; $i++) { ;?>
                    <div><?php echo( $formTotal[$i]); ?></div>
                <?php };?>
            </div>
        </div>
        <br/>
        
        <!-- ACCESO A LAS LISTAS ARCHIVADAS -->
        <div class="mostrarArchivados">Listas Archivadas <img src="../img/iconos/chevron-down.png" height="20px" width="20px" id="ico_mostrar" /> </div> 
        <div class="ocultarArchivados">Listas Archivadas <img src="../img/iconos/chevron-up.png" height="20px" width="20px" id="ico_ocultar"/> </div>
        
        <!-- LISTAS ARCHIVADAS - Hago bucle para mostrarlas -->
        <div id="divListasArchivadas">
             <?php for ($i = 1; $i <= $count; $i++) { ;?>
                <div><?php echo $formTotalArchivadas[$i]; ?></div>
            <?php };?>
        </div>
        <br/>
        	
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
        
    </body>
</html>