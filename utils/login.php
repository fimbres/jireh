<?php 
    include("../includes/funciones_BD.php");
    $conexion = crear_conexion();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    $caracteres_especiales = array("'",'"',"+","-","?","¿","[","]","{","}"," ");
    $username_validado = str_replace($caracteres_especiales,"",$username);
    $password_validado = str_replace($caracteres_especiales,"",$password);

    $query = "SELECT * FROM " . $rol . " WHERE Usuario = '" . $username_validado . "' AND Contrasena = '" . $password_validado . "' AND IdStatus = 3;";
    $res = mysqli_query($conexion,$query); 
    $row = mysqli_num_rows($res);

    $conexion->close();

    if($row > 0){
        session_start();
        $info = mysqli_fetch_array($res);
    
        $_SESSION['user_id'] = $info[0];
        $_SESSION['rol'] = $rol;

        $response = array("response" => "success");
    }
    else
    {
        $response = array("response" => "invalid", "message" => "Usuario o Contraseña invalida");
    }
    
    echo json_encode($response);
?>