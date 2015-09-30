<?php
    include("conexionPDO.php");
    session_start();
    if (isset($_SESSION['usuario'])){
    } else {
        header("location:login.php");
    }
    //MODIFICAR LISTAS
        
    //UPDATE LISTAS    
    if ($_POST['modificar']){
        $pidlista = $_POST["id_lista"];
        $ptitulo = $_POST["titulo"];
        $a_ids = $_POST["a_ids"];
        $a_tareas = $_POST["a_tareas"];
        $a_tachados = $_POST["a_tachados"];
        $numTareas = $_POST["numTareas"];

    
        $sqlUpdateListas="UPDATE listas SET titulo='$ptitulo' WHERE id_lista=$pidlista";
        $q = $conn->prepare($sqlUpdateListas);
        $q->execute(array($ptitulo,$pidlista));
        //echo "<br/> update de titulo $sqlUpdateListas <br/>";
        //echo " array ids ";
        //print_r($a_ids);
        //echo " array tareas ";
        //print_r($a_tareas);
        //echo " array terminadas ";
        //print_r($a_tachados);
        
        //terminar tareas marcadas
        for ($i=0; $i<$numTareas; $i++) {
            //echo "<br>ss: " .$a_ids[$i]." ".$a_tareas[$i];
            
            $id_tarea=$a_ids[$i];
            $tarea=$a_tareas[$i];
            
            // todas las tareas sin terminar 
            $sqlUpdateTareas="UPDATE tareas SET terminado=0 WHERE id_tarea=$a_ids[$i]";
            $q = $conn->prepare($sqlUpdateTareas);
            $q->execute(array($a_tareas[$i],$a_ids[$i]));
            //echo "<br/> update para vaciar: $i $sqlUpdateTareas";
            
            //  SIN CONFIRMAR
            $sqlUpdateTareas="UPDATE tareas SET tarea='$tarea' WHERE id_tarea=$id_tarea";
            $q = $conn->prepare($sqlUpdateTareas);
            $q->execute(array($a_tareas[$i],$a_ids[$i]));
            //echo "<br/> update tareas: $i $sqlUpdateTareas";
        }
        
        $tachados_cont=count($a_tachados);
        for($i=0; $i<$tachados_cont; $i++){
            $sqlUpdateTareas="UPDATE tareas SET terminado=1 WHERE id_tarea=$a_tachados[$i]";
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
        
    //AÑADIR TAREAS SIN CONFIRMAR
    if ($_POST['anadir_tarea']){
        $id_lista=$_POST['id_lista'];
        try{
            $sqlTareas = "INSERT INTO tareas (id_lista, tarea, terminado) VALUES ($id_lista, '', 0)";
            $conn->exec($sqlTareas);
            header('location:home.php');
        }catch(PDOException $e){
            echo "Error insertando tarea: ".$sqlDelete . "<br>" . $e->getMessage();
        }
    }
    
    //AÑADIR USUARIOS A LISTA
    if ($_POST['compartir']){
        $id_lista=$_POST['id_lista'];
        $receptor=$_POST['receptor'];
        
        //echo " id lista $id_lista receptor $receptor <br/>";
        try{

            //$sql = "SELECT id_usuario FROM usuarios WHERE usuario=".$receptor;
            $sql = "SELECT id_usuario FROM usuarios WHERE usuario='".$receptor."'";
            echo "usuario $receptor id usuario $receptor_id <br/>";
            echo "<br/>".$sql;
            foreach ($conn->query($sql) as $row) {
                $receptor_id=$row["id_usuario"];
            }
            
            $sqlTareas = "INSERT INTO usuario_lista (id_usuario, id_lista) VALUES ($receptor_id, $id_lista)";
            $conn->exec($sqlTareas);
            echo "lista $id_lista compartida";
            echo "<br/>".$sqlTareas;
            //header('location:home.php');
        }catch(PDOException $e){
            echo "Error al compartir: ".$sqlDelete . "<br>" . $e->getMessage();
            echo "<br/>buena suerte<br/>";
        }
    }
?>        