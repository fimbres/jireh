<?php
    session_start();

    function comprobar_sesion(){
        if(!empty($_SESSION['user_id'])){
            return true;
        }
        else{
            return false;
        }
    }

    function comprobar_sesion_y_rol($rol){
        if(!empty($_SESSION['user_id'])){
            if($_SESSION['rol'] == $rol){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
?>