<?php 
    require_once("classes/bd.php");
    require_once("classes/usuarios.php");
    require_once("classes/alerta.php");

    function limpiar_string($data,$BD = null){
        $data = trim($data);
        if($BD)
            $data = mysqli_real_escape_string($BD,$data);
        return $data;
    }
?>