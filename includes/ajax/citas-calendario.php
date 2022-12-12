<?php 
    //Datos basicos para poder obtener la informacion del fetch
    header("Content-type: application/json; charset=utf-8");
    $input = json_decode(file_get_contents("php://input"), true);
    //Conexion a la base de datos
    $conexion = mysqli_connect('blhfarhgxzvfusb9hkvg-mysql.services.clever-cloud.com','ulsqbptq4rtdnjup','VERq8gpiuRHtBGSFFcaO','blhfarhgxzvfusb9hkvg');
    $response = [];

    //Query hacia la base de datos
    $query = "SELECT IdCita, td.IdDoctor, FechaInicio, ts.IdStatus, FechaFinal, CONCAT('DR ',td.Nombre,' ',td.APaterno) as Doctor, 
    tc.Descripcion  
    FROM Tb_Cita tc, Tb_Doctor td, Tb_Paciente tp, Tb_Status ts  
    WHERE tc.IdPaciente = tp.IdPaciente and tc.IdStatus = ts.IdStatus ";
    $input['id_doctor'] ? $query .= "and tc.IdDoctor = " . $input['id_doctor'] : $query .= "and tc.IdDoctor = td.IdDoctor";
    $query .= " group by tc.IdCita";
    $res = $conexion->query($query);
    
    //Vemos si la respuesta fue exitosa
    if($res){
        $rows = [];
        while($temp = mysqli_fetch_array($res)){
            array_push($rows,$temp);
        }
        $response = array("respuesta" => "Exito","resultados"=>$rows);
    } else{
        $response = array("respuesta" => "Error","mensaje" => "Hubo un error al obtener los datos");
    }
    //Cerramos la conexion y enviamos los datos de vuelta
    mysqli_close($conexion);
    echo json_encode($response);

?>