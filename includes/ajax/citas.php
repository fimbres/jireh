<?php 
    $conexion = mysqli_connect('blhfarhgxzvfusb9hkvg-mysql.services.clever-cloud.com','ulsqbptq4rtdnjup','VERq8gpiuRHtBGSFFcaO','blhfarhgxzvfusb9hkvg');
    $response = [];

    $query = "SELECT IdCita, td.IdDoctor, FechaInicio, FechaFinal, CONCAT('DR ',td.Nombre,' ',td.APaterno) as Doctor, 
    tc.Descripcion  
    FROM Tb_Cita tc, Tb_Doctor td, Tb_Paciente tp, Tb_Status ts  
    WHERE tc.IdPaciente = tp.IdPaciente and tc.IdDoctor = td.IdDoctor and tc.Status = ts.IdStatus;";

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
    mysqli_close($conexion);
    echo json_encode($response);

?>