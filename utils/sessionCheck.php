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
?>