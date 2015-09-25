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
        $sql="SELECT * FROM usuario_lista WHERE id_usuario=".$_SESSION['id_usuario'];
        
        echo "<br/>";
        $count = 0; 
        foreach ($conn->query($sql) as $row) {
            $id_lista=$row["id_lista"];
            $archivadas=$row["archivado"];
            //echo $archivadas;
            
            //Busqueda de todos los titulos segun los id listas obtenidos anteriormente
                $sql="SELECT titulo FROM listas WHERE id_lista=$id_lista";
                foreach ($conn->query($sql) as $row) {
                    $titulo_lista=$row["titulo"];
                    
                    if ($archivadas == 1){
                        //creacion de contador de listas
                        $tituloFormulario = "form_lista_archivadas".$count;
                    } else {
                        $tituloFormulario = "form_lista".$count;
                    }
                    //creacion de un formulario por lista
                    $aperturaForm = "<table border=1><tr><td>";
                    $aperturaForm .= "<form name='".$tituloFormulario."' method='post' action='home.php'>";
                    $aperturaForm .= "<input name='id_lista' hidden='true' type='text'  value=$id_lista />";
                    
        //BORRAR ID LISTA ANTES DE FINALIZAR
                    // cada lista sera una celda de la tabla de listas
                    $titulo = "<b>".$id_lista." Titulo:  </b>";
                    $titulo .= "<input name='titulo' class='input_titulo' id=$count type='text' value='".$titulo_lista."' />";
                    //Busqueda de las tareas de la lista
                    $titulo .= "<input type='submit' name='borrar_lista' value='X'/>";
                    
                    $sql="SELECT * FROM tareas WHERE id_lista=$id_lista";
                    
                //row count
                    $contareas = $conn->prepare($sql);
                    $contareas->execute();
                    $contareas = $contareas->rowCount();
                //en construccion
                //echo $id_lista." numero de tareas ".$contareas." ".$sql."<br/>";
                    //$contareas=2;
                    //$a_ids = array($contareas);
                    //vaciado de tareas
                    $task="";
                    //$contareas=1;
                    foreach ($conn->query($sql) as $row) {
                        $tarea=$row["tarea"];
                        $id_tarea=$row["id_tarea"];
                    //prueba array IosuR
                        //array_push($a_ids, $id_tarea);
                        //tarea anterior mas tarea
                        $comodin = "<tr><td>";
                        //$numtareas="tarea".$contareas; //tarea1
                        //$numtareas2="tareas".$contareas;
                        //$comodin .= "<input name=$numtareas type='text' rows='2' cols='40' value='$tarea'/>";
                    //PRUEBA ID
                        $sql="SELECT terminado FROM tareas WHERE id_lista=$id_lista and id_tarea=$id_tarea";
                        foreach ($conn->query($sql) as $row) {
                            $tachado=$row["terminado"];
                        }
                        if ($tachado){
                            $comodin .= "<input name='tachado[]' type='checkbox' rows='2' cols='40' value=$tachado checked />";
                        }else{
                            $comodin .= "<input name='tachado[]' type='checkbox' rows='2' cols='40' value=$tachado />";
                        }
                        
                        $comodin .= "<input name='a_tareas[]' type='text' rows='2' cols='40' value='$tarea'/>";
                        $comodin .= "<input name='a_ids[]' type='text' hidden='true' value=$id_tarea />";
                        $comodin .= "<input type='submit' name='borrar_tarea' value='X'/>";
                        $comodin .= "</td></tr>";
                        
                        $task .= $comodin;


                    }
                    //se añade input de numero de tareas
                    $task .= "<input name='numTareas' hidden='true' type='text' value=$contareas />";
                    //$task .= "<input name='a_ids' type='text' hidden='true' value=$a_ids />";
                    
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
                    
                    $botonera="<tr><td align='right'>";
                    $botonera.="<input type='submit' name='archivar' onclick='archivar($id_lista)' value='ARCHIVAR'/>";
                    $botonera.="<input type='submit' name='compartir' onclick='compartir()' value='COMPARTIR'/>";
                    $botonera.="<input type='submit' name='anadir_tarea' value='AÑADIR TAREA'/>";
                    $botonera.="<input type='submit' name='modificar' value='MODIFICAR'/>";
                    $botonera.="</td></tr>";
                    
                    $cierre = "</form></table>";
                        
                    //Dependiendo de si son archivadas o no concatenacion de las partes del formulario en una array
                    if ($archivadas == 1){
                        $formTotalArchivadas[$count]= $aperturaForm.$titulo.$task.$listusers.$botonera.$cierre;
                    }else {
                        $formTotal[$count]= $aperturaForm.$titulo.$task.$listusers.$botonera.$cierre;
                    }
                    $count++;
                }//foreach titulos de las listas
        }//foreach listas del usuario conectado
        
        //CREACION DE LISTAS 0.2
         
        $formulario=$_POST["gestion"];
        if ($formulario == 'crear_lista') {
            $nombre_lista = $_POST["titulo_lista"];
            //$tarea = $_POST["tarea"];
            
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
                //num tareas
                $numTareas = $_POST["numTareas"];
                for($i=1; $i<($numTareas+1); $i++){
                    //variable con nombre concatenado
                    $tarea = $_POST["tarea".$i];
                    //echo "tarea: ".$tarea."<br />";
                    $sqlTareas = "INSERT INTO tareas (id_lista, tarea, terminado) VALUES ($lastid, '$tarea', 0)";
                    //echo $sqlTareas."<br /><br />";
                    $conn->exec($sqlTareas);
                }
                // echo $affected_rows.' Introducido correctamente';
                // header("Refresh: 3; URL=home.php");
                //sleep(2);
                header('location:home.php');
            }
        } 
        
        //MODIFICAR ID LISTA
        if ($_POST['modificar']){
            $pidlista = $_POST["id_lista"];
            $ptitulo = $_POST["titulo"];
            $a_ids = $_POST["a_ids"];
            $a_tareas = $_POST["a_tareas"];
            $numTareas = $_POST["numTareas"];
 
        //UPDATE LISTAS
            $sqlUpdateListas="UPDATE listas SET titulo='$ptitulo' WHERE id_lista=$pidlista";
            $q = $conn->prepare($sqlUpdateListas);
            $q->execute(array($ptitulo,$pidlista));
            //echo "<br/> update de titulo $sqlUpdateListas <br/>";
            //print_r($a_ids);
            //print_r($a_tareas);
            
            for ($i=0; $i<$numTareas; $i++) {
                //echo "<br>ss: " .$a_ids[$i]." ".$a_tareas[$i];
                
                $id_tarea=$a_ids[$i];
                $tarea=$a_tareas[$i];
                
                $sqlUpdateTareas="UPDATE tareas SET tarea='$tarea' WHERE id_tarea=$id_tarea";
                $q = $conn->prepare($sqlUpdateTareas);
                $q->execute(array($a_tareas[$i],$a_ids[$i]));
                //echo "<br/> update tareas: $i $sqlUpdateTareas";
                
            }
             
            header('location:home.php');
        }    
        
        //ARCHIVAR LISTAS
        if ($_POST["archivar"]){
            $id_lista=$_POST["id_lista"];
            $tarea=$_POST["id_tarea"];
            $id_usu=$_SESSION["id_usuario"];
            $sql="SELECT archivado FROM usuario_lista WHERE id_usuario=$id_usu and id_lista=$id_lista";
            foreach ($conn->query($sql) as $row) {
                $archivado=$row["archivado"];
            }    
            if($archivado){
                $sqlTareas = "UPDATE usuario_lista SET archivado=0 WHERE id_usuario=$id_usu and id_lista=$id_lista";
                $conn->exec($sqlTareas);
            }else{
                $sqlTareas = "UPDATE usuario_lista SET archivado=1 WHERE id_usuario=$id_usu and id_lista=$id_lista";
                $conn->exec($sqlTareas);
            }
            header('location:home.php');
        }
        
        //AÑADIR TAREAS
        /*if ($_POST['anadir_tarea']){
            $id_lista=$_POST['id_lista'];
            $tarea=$_POST['id_tarea'];
            $id_usu=$_SESSION['id_usuario'];
            $sql="SELECT archivado FROM usuario_lista WHERE id_usuario=$id_usu and id_lista=$id_lista";
            foreach ($conn->query($sql) as $row) {
                $archivado=$row["archivado"];
            }    
            if($archivado){
                $sqlTareas = "UPDATE usuario_lista SET archivado=0 WHERE id_usuario=$id_usu and id_lista=$id_lista";
                $conn->exec($sqlTareas);
            }else{
                $sqlTareas = "UPDATE usuario_lista SET archivado=1 WHERE id_usuario=$id_usu and id_lista=$id_lista";
                $conn->exec($sqlTareas);
            }
            header('location:home.php');
        }*/
        
        //BORRAR TAREAS
        if ($_POST["borrar_tarea"]){
            $id_lista=$_POST["id_lista"];
            //$id_tarea=$_POST["id_tarea"];
            
            //select numero de rows tareas
            $numtareas = $conn->exec("SELECT * FROM tareas WHERE id_lista=$id_lista");
            /*for($i=1; $i<$numtareas; $i++){
                //variable con nombre concatenado
                ${"id_tarea".$i} = $_POST["id_tarea"];
            }*/
            
            //en busca de solucion proque id tarea solo recoge el ultimo id tarea de la lista :(
            $sql = "DELETE FROM tareas WHERE id_tarea=$id_tarea";
            $conn->exec($sql);
            //contasor de la tarea borrada si son 0 se borro 
            $numtareas2 = $conn->exec("SELECT * FROM tareas WHERE id_tarea=$id_tarea and id_lista=$id_lista");
            if($numtareas2){
                //error en el borrado    
            }else{
                //no encontro la tarea se borro bien
                header('location:home.php');
            }
            
            
            /*try {
                //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                
                $count=$conn->prepare("DELETE FROM tareas WHERE id_tarea=:id_tarea");
                $count->bindParam(":id_tarea",$id_tarea,PDO::PARAM_INT);
                $count->execute();
                //$result = $count->fetchAll(PDO::FETCH_ASSOC);
                
                $value = $count->fetchColumn(1);
                
                var_dump($value);
                
                //$count->closeCursor();
    
                //$conn->commit();

            } catch(PDOException $e) {
                echo $e->getMessage();
            }*/
                
        }
        
        //BORRAR LISTAS
        if ($_POST["borrar_lista"]){
            $id_lista=$_POST["id_lista"];
            $id_usu=$_SESSION['id_usuario'];
            $sql="SELECT * FROM usuario_lista WHERE id_lista=$id_lista and id_usuario=$id_usu";
            
            $numUsuarios = $conn->prepare($sql);
            $numUsuarios->execute();
            $numUsuarios = $numUsuarios->rowCount();
            
            //solo un usuario se borra la lista y se relacion
            if($numUsuarios==1){ 
                $sql = "DELETE FROM listas WHERE id_lista=$id_lista ";
                $conn->exec($sql);
                $sql = "DELETE FROM usuario_lista WHERE id_lista=$id_lista and id_usuario=$id_usu";
                $conn->exec($sql);
            } else {
                //se borra la relacion de 1 usuario con la lista
                $sql = "DELETE FROM usuario_lista WHERE id_lista=$id_lista and id_usuario=$id_usu ";
                $conn->exec($sql);
            }
                
            //contasor de la tarea borrada si son 0 se borro 
            $sql="SELECT * FROM usuario_lista WHERE id_usuario=$id_usu and id_lista=$id_lista";
            $numlistas = $conn->prepare($sql);
            $numlistas->execute();
            $numlistas = $numlistas->rowCount();
            
            echo "<br/> codigo comprobar estado ".$sql;
            echo "<br/>".$numlistas;
            if($numlistas!=0){
                echo "errrrorrr";
            }else{
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
            $('#divCrearLista').hide();
            $('.ocultarCrearLista').hide();
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
     	    $(".mostrarCrearLista").on( "click", function() {
		    	$('#divCrearLista').show("slow"); //muestro mediante clase
		    	$('.mostrarCrearLista').hide();
		    	$('.ocultarCrearLista').show("slow");
		     });
 		     $(".ocultarCrearLista").on( "click", function() {
		    	$('#divCrearLista').hide(); //muestro mediante clase
		    	$('.mostrarCrearLista').show("slow");
		    	$('.ocultarCrearLista').hide();
		     });
		     
		   $(document).on("click", '.label_titulo', function() {
                var valorId = $(this).attr("id");
                alert(valorId);
                $("div#"+valorId).hide();
                $("input#"+valorId).show();
                //$(this).fadeOut(); 
            });
            $(function() {
                var count=2;
                $("#anadirTarea").click( function(){
                    $("#masTareas").append("Tarea: <input type='text' name='tarea"+count+"' placeholder='Añadir tarea' /><br/>");
                    $("#masTareas").append("<input name='numTareas' hidden='true' type='text' value='"+count+"'/>");
                count++;
                }
                );
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
    	        <!-- ACCESO A LAS LISTAS ARCHIVADAS -->
                <div class="mostrarCrearLista">Mostrar<img src="../img/iconos/chevron-down.png" height="20px" width="20px" id="ico_mostrar" /> </div> 
                <div class="ocultarCrearLista">Ocultar<img src="../img/iconos/chevron-up.png" height="20px" width="20px" id="ico_ocultar"/> </div>
            <div id="divCrearLista">
                <div align=center>
        		    <form name="crear_lista" method="post" action="home.php"> 
            			<input name="gestion" hidden="true" type="text"  value="crear_lista"/> <br/>
            			<label> Título: </label>
            			<input name="titulo_lista" type="text" placeholder="Titulo de la lista"/>
            			<input type="button" name="anadir" value="+" id='anadirTarea' /><br />
            			<!--<textarea name='tarea' rows='2' cols='40' placeholder="Tarea"></textarea>-->
            			Tarea: <input type="text" name="tarea1" placeholder="Añadir tarea">
            			<div id='masTareas'></div>
            			<br />
            			<input type="submit" name="crear" value="Crear" />
        		    </form>
            	</div>
            </div>
        	</fieldset>
            <!-- LISTAS NO ARCHIVADAS -->
            <!-- tabla para mostrar las listas en varias columnas-->
            <div id="divListas">
                <fieldset>
        		<legend> Listas </legend>
                    <table align="center"><tr>
                     <?php 
                        $comodin=0;
                        for ($i = 1; $i <= ($count+1); $i++) { 
                            if($formTotal[$i]==null){
                            }else{
                                if($comodin==0){
                                    echo "<tr><td>".$formTotal[$i]."</td>";
                                    //echo "<td>$comodin".$formTotal[$i]."</td></tr>";
                                    $comodin++;
                                }else if ($comodin==1){
                                    echo "<td>".$formTotal[$i]."</td>";
                                    $comodin++;
                                }else{
                                    echo "<td>".$formTotal[$i]."</td></tr>";
                                    $comodin=0;
                                }
                            }
                        }    
                     ?>
                    </tr></table>
                </fieldset>
            </div>
        </div>
        <br/>
    
        <!-- LISTAS ARCHIVADAS -->
            <fieldset>
            	<legend> Listas archivadas</legend>
            	    <!-- ACCESO A LAS LISTAS ARCHIVADAS -->
                    <div class="mostrarArchivados">Mostrar<img src="../img/iconos/chevron-down.png" height="20px" width="20px" id="ico_mostrar" /> </div> 
                    <div class="ocultarArchivados">Ocultar<img src="../img/iconos/chevron-up.png" height="20px" width="20px" id="ico_ocultar"/> </div>
                <div id="divListasArchivadas">
                    <table align="center"><tr>
                     <?php 
                        $comodin=0;
                        for ($i = 0; $i <= $count; $i++) { 
                            if($formTotalArchivadas[$i]==null){
                            }else{
                                if($comodin%2==0){
                                    echo "<tr><td>".$formTotalArchivadas[$i]."</td>";
                                }else{
                                    echo "<td>".$formTotalArchivadas[$i]."</td></tr>";
                                }
                                $comodin++;
                            }
                        }    
                     ?>
                    </tr></table>
                </div>
            </fieldset>
        <br/>
    </body>
</html>