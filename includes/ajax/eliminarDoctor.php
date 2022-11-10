<?php
    $extra_diagonal_path = "../../";
    require_once('../../utils/sessionCheck.php');
    include("../includes.php");
    $id = $_POST['id'];


    if(comprobar_sesion_y_rol("Tb_Admin")){
        $BD = new BaseDeDatos();
        $doct = Doctor::crear_Doctor($id,$BD);
        $status = $BD->getTb_Status('Inactivo');
        if(gettype($status) != 'boolean'){
            if($status->num_rows > 0){
                $status = $status->fetch_assoc();
            }
        }
        if($doct->getIdStatus() != $status['IdStatus']){
            $baja = $BD->query("UPDATE Tb_Doctor SET IdStatus={$status['IdStatus']} WHERE IdDoctor={$doct->getId()}");
            if($baja){
                $response = array("response" => "success","message" => "Se ha dado de baja al recepcionista");
            }else{
                $response = array("response" => "invalid", "message" => "No se pudo dar de baja al recepcionista");
            }
        }else{
            $response = array("response" => "invalid", "message" => "No se encontró al recepcionista o ya se encuentra dado de baja");
        }

        $BD->close();
    }else{
        //El usuario no ha iniciado session como recepcionista
        $response = array("response" => "invalid");
        header("location:../login.php");
    }

    //respuesta
    echo json_encode($response);
?>