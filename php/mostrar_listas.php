<?php
    include("conexionPDO.php");
    session_start();
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
            $sql="SELECT titulo FROM listas WHERE id_lista=$id_lista ";
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
                
                $sql="SELECT * FROM tareas WHERE id_lista=$id_lista ORDER BY id_tarea DESC";
                
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
                        $comodin .= "<input name='a_tachados[]' type='checkbox' rows='2' cols='40' value=$id_tarea checked />";
                    }else{
                        $comodin .= "<input name='a_tachados[]' type='checkbox' rows='2' cols='40' value=$id_tarea />";
                    }
                    
                    $comodin .= "<input name='a_tareas[]' type='text' rows='2' cols='40' value='$tarea'/>";
                    $comodin .= "<input name='a_ids[]' type='text' hidden='true' value='$id_tarea' />";
                    //$comodin .= "<input type='submit' name='borrar_tarea' value='X' />";
                    $comodin .= "<button type='submit' name='borrar_tarea' value='$id_tarea'>X</button>";
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
                $botonera.="<div class='input_container'>
                                <input type='text' id='country_id' onkeyup='autocomplet()' />
                                <ul id='country_list_id'></ul>
                            </div>";
                $botonera.="</td></tr>";
                
                $cierre = "</form></table>";
                    
                //Dependiendo de si son archivadas o no concatenacion de las partes del formulario en una array
                if ($archivadas == 1){
                    $formTotalArchivadas[$count]= $aperturaForm.$titulo.$task.$listusers.$botonera.$cierre;
                    /*
                        ${formTotalArchivadas.$count}[0]=$aperturaForm;
                        ${formTotalArchivadas.$count}[1]=$titulo;
                        ${formTotalArchivadas.$count}[2]=$task;
                        ${formTotalArchivadas.$count}[3]=$listusers;
                        ${formTotalArchivadas.$count}[4]=$botonera;
                        ${formTotalArchivadas.$count}[5]=$cierre;
                    */
                }else {
                    $formTotal[$count]= $aperturaForm.$titulo.$task.$listusers.$botonera.$cierre;
                }
                $count++;
            }//foreach titulos de las listas
    }//foreach listas del usuario conectado
    
    //mostrar listas
?>        