<?php
    include("conexionPDO.php");
    session_start();
    //CREACION DE LISTAS 0.2
        
        //$formulario=$_POST["gestion"];
        if ($_POST["crear"]) {
        //if ($formulario == 'crear_lista') {
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
                header('location:home.php');
            }
        }

?>