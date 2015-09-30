<?php
    include("conexionPDO.php");
    session_start();
    if (isset($_SESSION['usuario'])){
    } else {
        header("location:login.php");
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
    //BORRAR TAREAS
    if ($_POST["borrar_tarea"]){
        $id_tarea = $_POST["borrar_tarea"];
        //echo $id_tarea."<br/>";

        try {
            $sqlDelete = "DELETE FROM tareas WHERE id_tarea=$id_tarea";
            $conn->exec($sqlDelete);
            //echo "Record deleted successfully";
            header('location:home.php');
        } catch(PDOException $e) {
            echo "Error borrando tarea: ".$sqlDelete . "<br>" . $e->getMessage();
        }
    }
?>    