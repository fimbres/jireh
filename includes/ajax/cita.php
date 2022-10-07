<?php

    $id_cita = $_GET['id'];
    $conexion = mysqli_connect('blhfarhgxzvfusb9hkvg-mysql.services.clever-cloud.com','ulsqbptq4rtdnjup','VERq8gpiuRHtBGSFFcaO','blhfarhgxzvfusb9hkvg');
    $response = [];

    $query = "SELECT * FROM Tb_Cita WHERE IdCita = " . $id_cita;
    $res = $conexion->query($query);
    if($res){
        $rows = [];
        while($temp = mysqli_fetch_array($res)){
            array_push($rows,$temp);
        }
        $response = array("respuesta" => "Exito","resultados"=>$rows);
    } else{
        $response = array("respuesta" => "Error","mensaje" => "Hubo un error al obtener los datos");
    }
    $conexion->close();
    echo json_encode($response);

?>