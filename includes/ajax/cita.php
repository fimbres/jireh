<?php

session_start();
$response = [];
$id_cita = $_GET['id'];

if (isset($_SESSION['user_id'])) {
    if (!empty($id_cita)) {
        require_once("../funciones_BD.php");
        $conexion = crear_conexion();

        $query = "SELECT IdPaciente,FechaInicio,FechaFinal,IdDoctor,Descripcion,Costo FROM Tb_Cita WHERE IdCita = '{$id_cita}';";
        $res = $conexion->query($query);

        if ($res) {
            $rows = [];
            $rowsPaciente = [];
            $rowsDoctor = [];
            while ($temp = mysqli_fetch_array($res)) {
                array_push($rows, $temp);
            }

            $consPaciente = $conexion->query("SELECT Nombre,APaterno,AMaterno FROM Tb_Paciente WHERE IdPaciente = '{$rows[0]['IdPaciente']}';");

            if($consPaciente){
                while($temp = mysqli_fetch_array($consPaciente)){
                    array_push($rowsPaciente,$temp);
                }

                $consDoctor = $conexion->query("SELECT Nombre,APaterno,AMaterno FROM Tb_Doctor WHERE IdDoctor = '{$rows[0]['IdDoctor']}';");

                if($consDoctor){
                    while($temp = mysqli_fetch_array($consDoctor)){
                        array_push($rowsDoctor,$temp);
                    }

                    $response = array("respuesta" => "Exito", "cita" => $rows,"paciente" => $rowsPaciente,"doctor" => $rowsDoctor);
                }
            }
        } else {
            $response = array("respuesta" => "Error", "mensaje" => "Hubo un error al obtener los datos");
        }

        $conexion->close();
    } else {
        $response = array("respuesta" => "Error", "mensaje" => "No se encontro la cita solicitada");
    }
} else {
    $response = array("respuesta" => "Error", "mensaje" => "");
}

echo json_encode($response);
