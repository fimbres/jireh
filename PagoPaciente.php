<?php 
    require_once('vendor/autoload.php');
    require_once('includes/includes.php');
    $formulario_token = true;
    $alerta = false;
    $token = false;
    $dinero_pagar_stripe = '';
    $resultado_pago = '';
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Si entramos aqui, significa que el usuario ya dio un posible token
        // asi que necesitamos verificar que el token exista
        $BD = new BaseDeDatos();
        $token = limpiar_string($_POST['tokenPago'],$BD);
        $query = "SELECT * FROM Tb_Pago WHERE AuthToken = '$token'";
        $res = false;
        
        if(!empty($token))
            $res = $BD->query($query);
        
        if($res){
            if($res->num_rows > 0){
                $res = $res->fetch_assoc();
                if(!empty($res['NumeroOperacion'])){
                    $alerta = new Alerta('El token que ingresaste ya ha sido pagadao');
                }
            } else {
                $alerta = new Alerta('El token que ingresaste no existe');
            }
        } else{
            $alerta = new Alerta('El token que ingresaste no existe');
        }
        $BD->next_result();
        if(!$alerta){
            //Comprobamos de que el token no haya sido pagado
            $cita = $res['IdCita'];
            $query = "SELECT * FROM Tb_Cita WHERE IdCita = $cita;";
            $res_cita = $BD->query($query);
            $BD->next_result();
            if($res_cita && $res_cita->num_rows > 0){
                $cita = $res_cita->fetch_assoc();
                $dinero_pagar_stripe = $cita['Costo'];
                //Comprobamos de que no este en el estatus pagado
                $res_status = $BD->getTb_Status('Pagado');
                if($res_status){
                    $status = $res_status->fetch_assoc();
                    if($cita['IdStatus'] == $status['IdStatus']){
                        //Si entramos aqui significa que la cita ya ha sido
                        // pagada
                        $alerta = new Alerta('La cita ya ha sido pagada');
                    }
                    else{
                        $pacienteId = $cita['IdPaciente'];
                        $queryPaciente = "SELECT * FROM Tb_Paciente WHERE IdPaciente = $pacienteId;";
                        $res_paciente = $BD->query($queryPaciente);
                        $infoPaciente = $res_paciente->fetch_assoc();
                        $pacienteNombre = $infoPaciente['Nombre'];
                    }
                } else{
                    $alerta = new Alerta('Error al consultar los datos, inténtalo de nuevo');
                }
            } else{
                $alerta = new Alerta('Error al consultar los datos, inténtalo de nuevo');
            }
            if(!$alerta){
                $formulario_token = false;
            }
        }
        if($alerta){
            $alerta->setOpcion('icon',"'error'");
            $alerta->setOpcion("confirmButtonColor","'#dc3545'");
        }
        $BD->close();
    } 
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        //Agarramos el numero de operacion
        if(!empty($_GET['payment_intent'])){
            $operacion = limpiar_string($_GET['payment_intent']);
            $token = limpiar_string($_GET['token']);
            $BD = new BaseDeDatos();
            $query = "UPDATE Tb_Pago SET NumeroOperacion = '{$operacion}' WHERE AuthToken = '{$token}'";
            $BD->query($query);
            $BD->close();
            $alerta = new Alerta('Se ha hecho el pago correctamente');
            $alerta->setRedireccion('login.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <?php  if($formulario_token){?>
        <title>Ingresar token</title>
        <?php } else {?>
        <title>Hacer pagos</title>
        <?php } ?>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/styles.css">
        <?php 
            if(!$formulario_token){
                echo "<link rel='stylesheet' href='css/stripe.css' />";
            }
        ?>

    </head>
    <body class="bg-light">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5 stripe-card">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header w-100 bg-white d-flex justify-content-center">
                                        <img class="rounded my-3" src="files/logo_jireh.jpg" width="250px" alt="Logo" />
                                    </div>
                                    <div class="card-body">
                                        <?php 
                                        $formulario_token ?
                                        require('components/formularioToken.php')
                                        :
                                        require('components/formularioTarjeta.php');
                                        
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <?php 
        if(!$formulario_token){
            echo "<script src='https://js.stripe.com/v3/'></script>
            <script src='js/stripe.js' defer></script>";
        }
        ?>
        <?php 
        if($alerta){
            echo "<script>
            {$alerta->activar_sweet_alert()}
            </script>";
        }
    ?>
    </body>
</html>