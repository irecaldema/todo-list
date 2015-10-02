<?php
    session_start();
    if (isset($_SESSION['usuario'])){
    } else {
        header("location:login.php");
    }
    include("conexionPDO.php");
    include("mostrar_listas.php");
    include("crear_lista.php");
    include("modificar.php");
    include("borrar.php");

    echo "Usuario: ".$_SESSION['usuario']."<br/>";
    echo " nombre:  ".$_SESSION['nombre']."<br/>";
    echo " id_usuario: ".$_SESSION['id_usuario']."<br/>";
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <!--<style type="text/css">@import "../css/home.css";</style>-->
        <link rel="stylesheet" type="text/css" href="../css/home.css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
           
        <!-- AUTOCOMPLEMENT -->
        <link rel="stylesheet" href="../css/style.css" />
        <script src="../js/jquery.js" charset="UTF-8"></script>
        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <script type="text/javascript" src="../js/script.js"></script>
        <script src="../js/jquery.autosize.input.min.js" ></script>
        
        <script type="text/javascript">
        $(document).ready(function(){
            init();
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
            			<!--<input name="gestion" hidden="true" type="text"  value="crear_lista"/> <br/>-->
            			<label> Título: </label>
            			<input name="titulo_lista" type="text" placeholder="Titulo de la lista" data-autosize-input='{ "space": 178 }'/>
            			<input type="button" name="anadir" value="+" id='anadirTarea' /><br />
            			<!--<textarea name='tarea' rows='2' cols='40' placeholder="Tarea"></textarea>-->
            			
            			Tarea: <input type="text" name="tarea1" placeholder="Añadir tarea">
            			<input type="text" name="numTareas" hidden="true" value="1"/>
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
                        $comodin=0; //variable para dividir las listas por columnas
                        //for ($i = 1; $i <= ($count+1); $i++) {
                        for ($i = $count; $i >= 0; $i--) {
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
                        for ($i = $count; $i > 0; $i--) { 
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