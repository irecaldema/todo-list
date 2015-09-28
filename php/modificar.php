<?php
    include("conexionPDO.php");
    session_start();
    //MODIFICAR LISTAS
        
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
?>        