<?php
    $idPaciente = $_POST['pacienteId'];
    session_start();

    //Verificamos si el usuario inicio sesion como recepcionista
    if(isset($_SESSION['user_id']) && ($_SESSION['rol'] == "Tb_Recepcionista" || $_SESSION['rol'] == "Tb_Admin")){
        include("../includes/funciones_BD.php");
        $conexion = crear_conexion();
        $consultar = $conexion->query("SELECT IdStatus FROM Tb_Paciente WHERE IdPaciente = '$idPaciente' AND IdStatus != '4'");
        
        if($consultar->num_rows > 0){
            $baja = $conexion->query("UPDATE Tb_Paciente SET IdStatus='4' WHERE IdPaciente='$idPaciente'");
            if($baja){
                $response = array("response" => "success","message" => "Se ha dado de baja al paciente");
            }else{
                $response = array("response" => "invalid", "message" => "No se pudo dar de baja al paciente");
            }
        }else{
            $response = array("response" => "invalid", "message" => "No se encontro al paciente o ya se encuentra dado de baja");
        }

        $conexion->close();
    }else{
        //El usuario no ha iniciado session como recepcionista
        $response = array("response" => "invalid");

        header("location:../login.php");
    }

    //respuesta
    echo json_encode($response);
?>