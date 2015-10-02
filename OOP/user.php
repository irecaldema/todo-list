<?php
class User{
    private $user_id;
    
    public function _construct($name, $email){
        //create the user and return the created user from database
        require_once("ConexionPDO.php");
        
        //db::
        $conn = ConexionPDO::();
        
        $sql = "INSERT INTO usuarios (usuario, email) VALUES ('".$usuario."', '"$email."')";
        $conn->exec($sql);
        $this->user_id = $conn -> lastInsertId();
    }
    
    public function getUsuario() {
        require_once("ConexionPDO.php");
        
        $conn = ConexionPDO::();
        
        $sql="SELECT id_usuario FROM usuarios WHERE usuario='$user_id'";
        $conn->exec($sql);
    }
}
?>