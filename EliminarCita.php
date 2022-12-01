<?php
    require_once('utils/sessionCheck.php');
    include("includes/includes.php");
    $id = $_POST['id'];
    $moti = $_POST['motivoC'];
    if(empty($id)){
        header('location: agenda.php');
    }



    //echo $id;
    //Verificamos si el usuario inicio sesion como recepcionista
    //if(comprobar_sesion_y_rol("Tb_Admin")){
        include("includes/funciones_BD.php");
        $con = crear_conexion();
        $query= $con->query("SELECT IdStatus FROM Tb_Cita WHERE IdCita = {$id} AND IdStatus != '2'");
        if($query->num_rows > 0){
            $baja = $con->query("UPDATE Tb_Cita SET IdStatus=2, MotivoCancelacion='{$moti}' WHERE IdCita={$id}");
            if($baja){
                $response = array("response" => "success","message" => "Se ha dado de baja la Cita");
            }else{
                $response = array("response" => "invalid", "message" => "No se pudo dar de baja la Cita");
            }
        }else{
            $response = array("response" => "invalid", "message" => "No se encontró la Cita o ya se encuentra dado de baja");
        }

        $con->close();
   // }else{
        //El usuario no ha iniciado session como recepcionista
    //     $response = array("response" => "invalid");
    //     header("location:login.php");
    // }

    //respuesta
    echo json_encode($response);

?>