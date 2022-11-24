<?php
$data = ["success" => false];
$idCita = $_POST['idCita'];
$error = false;
$metodoPago = 0;
$authToken = "";
$numOperacion = 0;

if($_POST['authToken'] == ""){
    $authToken = null;
}else{
    $authToken = $_POST['authToken'];
}

if($_POST['numOperacion'] == ""){
    $numOperacion = null;
}else{
    $numOperacion = $_POST['numOperacion'];
}


//LISTA DE CARACTERES PERMITIDOS
$caracteres_especiales = array("'", '"', "+", "?", "¿", "[", "]", "{", "}", "&", "%", "=", "(", ")");

//INICIALIZANDO ARREGLOS
$datosValidados = array();
$datos = array();

switch ($_POST['metodoPago']) {
    case "Stripe":
        $metodoPago = 1;
        break;
    case "Paypal":
        $metodoPago = 2;
        break;
    case "Efectivo":
        $metodoPago = 3;
        break;
    case "Tarjeta":
        $metodoPago = 4;
        break;
    case "Transferencia":
        $metodoPago = 5;
        break;
}

//llenamos el arreglo
array_push($datos, $idCita);
array_push($datos, $metodoPago);
array_push($datos, $_POST['fechaPago']);
array_push($datos, $numOperacion);
array_push($datos, $authToken);

//VALIDAR CARACTERES INGRESADOS POR LA RECEPCIONISTA
foreach ($datos as &$valor) {
    $validado = str_replace($caracteres_especiales, "", $valor);
    array_push($datosValidados, $validado);
}

//VERIFICAMOS QUE EL ARREGLO TENGA LOS DATOS NECESARIOS
empty($datosValidados[4]) && $_POST['metodoPago'] === "Stripe" ? $error = true : "";
empty($datosValidados[3]) && $_POST['metodoPago'] === "Transferencia" ? $error = true : "";
empty($datosValidados[3]) && $_POST['metodoPago'] === "Tarjeta" ? $error = true : "";

if ($error == false) {
    require_once("../includes/funciones_BD.php");
    $conexion = crear_conexion();

    $buscaPago = mysqli_num_rows($conexion->query("SELECT IdPago FROM Tb_Pago WHERE IdCita='{$datosValidados[0]}';"));

    if ($buscaPago <= 0) {
        if ($_POST['metodoPago'] === "Stripe") {
            $encontrados = mysqli_num_rows($conexion->query("SELECT IdPago FROM Tb_Pago WHERE AuthToken='{$datosValidados[4]}';"));
            if ($encontrados > 0) {
                $data = ["success" => false, "message" => "El Token generado ya existe"];
                die(json_encode($data));
                exit();
            }

            $datosValidados[3] = "";
        }

        $registrar = $conexion->query("INSERT INTO Tb_Pago (IdCita,IdMetodoPago,FechaPago,NumeroOperacion,AuthToken) VALUES ('{$datosValidados[0]}','{$datosValidados[1]}','{$datosValidados[2]}','{$datosValidados[3]}','{$datosValidados[4]}');");
        if ($registrar) {
            $data = ["success" => true, "message" => "Se ha registrado el pago"];
        }else{
            $data = ["success" => false, "message" => "No fue posible registrar el pago"];
        }
    }else{
        $data = ["success" => false, "message" => "La cita ya se encuentra pagada"];
    }

    $conexion->close();
} else {
    $data = ["success" => false, "message" => "No has ingresado los datos necesarios"];
}

die(json_encode($data));
