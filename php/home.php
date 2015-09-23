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
    
        //MOSTRAR LISTAS 0.1
        
        //Busqueda de las listas del usuario
        //$sql="SELECT id_lista FROM usuario_lista WHERE id_usuario='".$_SESSION['id_usuario']."'and archivado=".$archivadas;
        $sql="SELECT * FROM usuario_lista WHERE id_usuario='".$_SESSION['id_usuario']."'";
        
        echo "<br/>";
        $count = 0; 
        foreach ($conn->query($sql) as $row) {
            $id_lista=$row["id_lista"];
            $archivadas=$row["archivado"];
            //echo $archivadas;
            
            //Busqueda de todos los titulos segun los id listas obtenidos anteriormente
                $sql="SELECT titulo FROM listas WHERE id_lista='".$id_lista."'";
                foreach ($conn->query($sql) as $row) {
                    $titulo_lista=$row["titulo"];
                    
                    if ($archivadas == 1){
                        //creacion de contador de listas
                        $tituloFormulario = $count."form_lista_archivadas";
                    } else {
                        $tituloFormulario = "form_lista".$count."";
                    }
                    //creacion de un formulario por lista
                    $aperturaForm = "<form name='".$tituloFormulario."' method='post' action='home.php'>";
                    
        //BORRAR ID LISTA ANTES DE FINALIZAR
                    // cada lista sera una celda de la tabla de listas
                    $titulo = 
                    "<table border=1><tr><td><b>".$id_lista." Titulo:  </b><input class='input_titulo' id='$count' type='text' value='".$titulo_lista."' />";
                    //Busqueda de las tareas de la lista
                    
                    $sql="SELECT tarea FROM tareas WHERE id_lista='".$id_lista."'";
                    //vaciado de tareas
                    $task="";
                    foreach ($conn->query($sql) as $row) {
                        $tarea=$row["tarea"];
                        //tarea anterior mas tarea
                        $comodin = "<tr><td><textarea rows='2' cols='40'>$tarea</textarea></td></tr>";
                        $task.= $comodin;
                    }
                    
                    //foreach busqueda de usuarios relacionados con la lista
                    $listusers="<tr><td>Usuarios: ";
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
                    
                    $cierre = "</table></form>";
                        
                    //Dependiendo de si son archivadas o no concatenacion de las partes del formulario en una array
                    if ($archivadas == 1){
                        $formTotalArchivadas[$count]= $aperturaForm.$titulo.$task.$listusers.$cierre;
                    }else {
                        $formTotal[$count]= $aperturaForm.$titulo.$task.$listusers.$cierre;
                    }
                    $count++;
                }//foreach titulos de las listas
        }//foreach listas del usuario conectado
        
        //CREACION DE LISTAS 0.2
         
        //$formulario=$_POST[""]; //pendiente añadir un input en el formulario
        $formulario="crear_lista";
        if ($formulario == 'crear_lista') {
            $nombre_lista = $_POST["titulo_lista"];
            $tarea = $_POST["tarea"];
            echo "Creando lista<br>";
            
            if ($nombre_lista == '') {
                echo "Vacio, se te redirccionará. Espere.";
                //header('location:home.php'); //pendiente de REVISAAAR
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
            <!--<div id="divListas">-->
                <!--<table border=1><!-- tabla para mostrar las listas en varias columnas-->
                <!--<?php //for ($i = 0; $i <= $count; $i++) { ;?>
                    <div><td><?php //echo( $formTotal[$i]); ?></td></div>
                    <?php //};?>
                -->
            <!--// PENDIENTE DE REVISAR LAS COLUMNAS-->
            <!-- tabla para mostrar las listas en varias columnas-->
            <div id="divListas">
                <fieldset>
        		<legend> Listas </legend>
                    <table align="center"><tr>
                     <?php 
                        $comodin=0;
                        for ($i = 0; $i <= $count; $i++) { 
                            if($formTotal[$i]==null){
                            }else{
                                if($comodin%2==0){
                                    echo "<tr><td>".$formTotal[$i]."</td>";
                                }else{
                                    echo "<td>".$formTotal[$i]."</td></tr>";
                                }
                                $comodin++;
                            }
                        }    
                     ?>
                    </tr></table>
                </fieldset>
            </div>
        </div>
        <br/>
    
        <!-- LISTAS ARCHIVADAS - Hago bucle para mostrarlas -->
            <fieldset>
            	<legend> Listas archivadas</legend>
            	<!-- ACCESO A LAS LISTAS ARCHIVADAS -->
                <div class="mostrarArchivados">Mostrar <img src="../img/iconos/chevron-down.png" height="20px" width="20px" id="ico_mostrar" /> </div> 
                <div class="ocultarArchivados">No mostrar <img src="../img/iconos/chevron-up.png" height="20px" width="20px" id="ico_ocultar"/> </div>
                <div id="divListasArchivadas">
                     <?php for ($i = 0; $i <= $count; $i++) { ;?>
                        <div><?php echo $formTotalArchivadas[$i]; ?></div>
                    <?php };?>
                </div>
            </fieldset>
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