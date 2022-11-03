<?php
    //OBTENER DATOS DEL FORMULARIO
    $idPaciente = $_POST['idPaciente'];
    $archivos = array($_FILES);

    $caracteres_especiales = array("'",'"',"+","-","?","¿","[","]","{","}","&","%","=","(",")");
    $datosValidados = array();

    //VALIDAR CARACTERES INGRESADOS POR LA RECEPCIONISTA
    foreach ($_POST['datos'] as &$valor) {
        $validado = str_replace($caracteres_especiales,"",$valor);
        array_push($datosValidados,$validado);
    }

    //VERIFICAR QUE EXISTE UN VALOR PARA IDPACIENTE
    if(!empty($idPaciente)){
        include("../includes/funciones_BD.php");
        $conexion = crear_conexion();

        //ASIGNAMOS EL ID CORRESPONDIENTE SEGUN EL TEXTO
        $idSexo = $datosValidados[3] === "Masculino" ? "1" : "2"; 
        $idStatus = $datosValidados[15] === "Activo" ? "3" : "4";

        require_once('../includes/classes/bd.php');


        //ACTUALIZAMOS LA INFORMACION
        $consulta = $conexion->query("UPDATE Tb_Paciente SET Nombre='$datosValidados[0]',APaterno='$datosValidados[1]',AMaterno='$datosValidados[2]',IdSexo='$idSexo',Direccion='$datosValidados[4]',CodigoPostal='$datosValidados[11]',Email='$datosValidados[6]',NumTelefono='$datosValidados[5]',FechaNacimiento='$datosValidados[7]',MedicoEnvia='$datosValidados[8]',Representante='$datosValidados[9]',RFC='$datosValidados[10]',IdStatus='$idStatus' WHERE IdPaciente='$idPaciente'");
        if($consulta){
            $response = array("response" => "success","message" => $archivos);
        }else{
            $response = array("response" => "invalid", "message" => "No se ha podido actualizar los datos");
        }
        
        $conexion->close();
    }else{
        $response = array("response" => "invalid", "message" => "Error");
    }

    echo json_encode($response);
