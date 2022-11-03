<?php
    $idPaciente = $_POST['pacienteId'];
    include("../includes/funciones_BD.php");
    $conexion = crear_conexion();

    $query = "SELECT * FROM Tb_Paciente LEFT JOIN Tb_Polizas ON Tb_Polizas.IdPoliza = Tb_Paciente.IdPoliza WHERE IdPaciente = " . $idPaciente . ";";
    $res = mysqli_query($conexion,$query); 

    $conexion->close();

    echo json_encode(mysqli_fetch_array($res));
?>