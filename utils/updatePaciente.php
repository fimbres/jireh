<?php
    //API CLOUDINARY
    require_once($_SERVER["DOCUMENT_ROOT"]."/jireh-php/utils/cloudinaryFunctions.php");

    //OBTENER DATOS DEL FORMULARIO
    $idPaciente = $_POST['IdPaciente'];

    $archivos = array();
       
    $caracteres_especiales = array("'",'"',"+","-","?","¿","[","]","{","}","&","%","=","(",")");
    $datosValidados = array();
    $datos = array();

    //llenamos el arreglo
    array_push($datos,$_POST['Nombre']);
    array_push($datos,$_POST['APaterno']);
    array_push($datos,$_POST['AMaterno']);
    array_push($datos,$_POST['IdSexo']);
    array_push($datos,$_POST['Direccion']);
    array_push($datos,$_POST['CodigoPostal']);
    array_push($datos,$_POST['Email']);
    array_push($datos,$_POST['NumTelefono']);
    array_push($datos,$_POST['FechaNacimiento']);
    array_push($datos,$_POST['MedicoEnvia']);
    array_push($datos,$_POST['Representante']);
    array_push($datos,$_POST['RFC']);
    array_push($datos,$_POST['IdStatus']);


    //VALIDAR CARACTERES INGRESADOS POR LA RECEPCIONISTA
    foreach ($datos as &$valor) {
        $validado = str_replace($caracteres_especiales,"",$valor);
        array_push($datosValidados,$validado);
    }

    //VERIFICAR SI LA RECEPCIONISTA SUBIO ARCHIVOS
    if(isset($_FILES['archivoPoliza'])){
        /*$temp = explode(".", $_FILES["archivoPoliza"]["name"]);
        $newfilename = "poliza_{$datosValidados[0]}";
        move_uploaded_file($_FILES["archivoPoliza"]["tmp_name"], "../files/temp/" . $newfilename);*/
        $poliza = true;
        array_push($archivos,$_FILES['archivoPoliza']);
    }else $poliza = false;
    
    if(isset($_FILES['archivoAntecedentes'])){
        $antecedentes = true;
        array_push($archivos,$_FILES['archivoAntecedentes']);
    }else $antecedentes = false;
        
    if(isset($_FILES['archivoPresupuesto'])){
        $presupuesto = true;
        array_push($archivos,$_FILES['archivoPresupuesto']);
    }else $presupuesto = false;

    //VERIFICAR QUE EXISTE UN VALOR PARA IDPACIENTE
    if(!empty($idPaciente)){
        include("../includes/funciones_BD.php");
        $conexion = crear_conexion();

        //ASIGNAMOS EL ID CORRESPONDIENTE SEGUN EL TEXTO
        $idSexo = $datosValidados[3] === "Masculino" ? "1" : "2"; 
        $idStatus = $datosValidados[12] === "Activo" ? "3" : "4";

        //SUBIMOS LOS ARCHIVOS A CLOUDINARY
        foreach ($archivos as $contador => $arch) {
            if ($arch['error'] == 0) {
                $random = md5($datosValidados[7]);
                $pathDestino = "Pacientes/{$datosValidados[0]}_{$random}/";

                if($poliza){
                    $nombreArchivoFinal = "documento_poliza";
                    $poliza = false;
                }else if(!$poliza && $antecedentes){
                    $nombreArchivoFinal = "documento_antecedentes";
                    $antecedentes = false;
                }else if(!$poliza && !$antecedentes && $presupuesto){
                    $nombreArchivoFinal = "documento_presupuesto";
                    $presupuesto = false;
                }

                uploadFile("$arch[tmp_name]","pdf",$pathDestino,$nombreArchivoFinal);
            }
        }

        //ACTUALIZAMOS LA INFORMACION
        $consulta = $conexion->query("UPDATE Tb_Paciente SET Nombre='$datosValidados[0]',APaterno='$datosValidados[1]',AMaterno='$datosValidados[2]',IdSexo='$idSexo',Direccion='$datosValidados[4]',CodigoPostal='$datosValidados[5]',Email='$datosValidados[6]',NumTelefono='$datosValidados[7]',FechaNacimiento='$datosValidados[8]',MedicoEnvia='$datosValidados[9]',Representante='$datosValidados[10]',RFC='$datosValidados[11]',IdStatus='$idStatus' WHERE IdPaciente='$idPaciente'");
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
