<?php
    require_once('utils/sessionCheck.php');
    require_once('includes/includes.php');
    if(!comprobar_sesion_y_rol("Tb_Recepcionista")){
        header('location: login.php');
    }
    $tipo_formulario = 'modificar';
    $idCita = $_GET['idCita'];
    $alerta = false;
    $BD = new BaseDeDatos();
    if(!empty($idCita)){
        $infoCita = $BD->getTbCita_cita($idCita);
        $infoPago = $BD->getTb_Pagos_cita($idCita);
        if(!$infoPago)
            header('location: index.php');
        $infoPago = $infoPago->fetch_assoc();
        if($infoCita){
            $infoPaciente = $BD->getTbPaciente_nombrePaciente($infoCita["IdPaciente"]);
            $infoDoctor = $BD->getTbDoctor_nombreDoctor($infoCita["IdDoctor"]);            
        }else{
            header('location: index.php');
        }
        //Guardamos los valores de los 4 id de pagos
        $temp= $BD->getTb_MetodoPago();
        if(!$temp)
            header('location: index.php');
        $pagos_id = [];
        while($p = mysqli_fetch_array($temp)){
            $pagos_id[$p['MetodoPago']] = $p['IdMetodo'];
        }

    }else{
        $BD->close();
        header('location: index.php');
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //Modificacion si es stripe
        $tipo_pago = $BD->getTb_MetodoPago_id($infoPago['IdMetodoPago']);
        if(!$tipo_pago)
            header('location: index.php');
        $tipo_pago = $tipo_pago->fetch_assoc();
        $fecha_post = explode('T',$_POST['fechaHora']);
        $sql = "UPDATE Tb_Pago SET FechaPago = '{$fecha_post[0]} {$fecha_post[1]}' ";
        $bien = true;
        switch ($tipo_pago['MetodoPago']) {
            case 'Tarjeta':
                $num_operacion = limpiar_string($_POST['txtVoucher']);
                if($num_operacion == ''){
                    $alerta = new Alerta('Llena los datos', ['El número de Voucher es requerido']);
                    $alerta->setOpcion('icon',"'error'");
                    $alerta->setOpcion("confirmButtonColor","'#dc3545'");
                    $bien = false;
                }
                else{
                    $sql .= ", NumeroOperacion = '$num_operacion' ";
                }
                break;
            case 'Transferencia':
                $num_operacion = limpiar_string($_POST['txtTransferencia']);
                if($num_operacion == ''){
                    $alerta = new Alerta('Llena los datos', ['El número de transferencia es requerido']);
                    $alerta->setOpcion('icon',"'error'");
                    $alerta->setOpcion("confirmButtonColor","'#dc3545'");
                    $bien = false;
                }
                else{
                    $sql .= ", NumeroOperacion = '$num_operacion' ";
                }
                break;
            default:
                # code...
                break;
        }
        if($bien){
            $sql .= " WHERE IdPago = {$infoPago['IdPago']}";
            $res_update = $BD->query($sql);
            
            if($res_update){
                //Verificamos el metodo de pago haya sidop efectivo
                if($tipo_pago['MetodoPago'] == "Efectivo"){
                    $nuevo_costo = $_POST['CostoCita'];
                    $sql = "UPDATE Tb_Cita SET Costo = $nuevo_costo WHERE IdCita = $idCita";
                    if($BD->query($sql)){
                        $infoCita = $BD->getTbCita_cita($idCita);
                        $alerta = $infoCita ? new Alerta('Se modificaron los datos con exito',[],[],'./listarPagos.php') : false;
                    }
                } else{
                    $alerta = $infoCita ? new Alerta('Se modificaron los datos con exito',[],[],'./listarPagos.php') : false;
                }
            }
        }
        if(!$alerta){
            $alerta = new Alerta("Error",["No se pudo modificar los datos"]);
            $alerta->setOpcion('icon',"'error'");
            $alerta->setOpcion("confirmButtonColor","'#dc3545'");
        } else{
            $infoPago = $BD->getTb_Pagos_cita($idCita);
            if(!$infoPago){
                $alerta = new Alerta("Error",["Hubo un error para mostrar los datos actualizados"]);
                $alerta->setOpcion('icon',"'error'");
                $alerta->setOpcion("confirmButtonColor","'#dc3545'");
            }
            else
                $infoPago = $infoPago->fetch_assoc();
        }
        
    }
    $BD->close();
    date_default_timezone_set('America/Tijuana');
    $fecha = date("Y-m-d H:i:s");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pagos</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <!-- JavaScript Bundle with Popper -->
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="./css/stylesGestionPagos.css">
</head>
<body>
    <main class="d-flex flex-wrap flex-row">
        <!--COMPONENTE MENU-->
        <?php
            require_once('components/menu.php');
        ?>

        <!--CONTENEDOR ALMACENAR CONTENIDO OTRAS PAGINAS-->
        <div id="displayActions" class="d-flex bg-white p-4 flex-column">
            <div class="d-flex flex-row justify-content-between" style="margin-bottom: 60px; margin-top: 30px;">
                <h1 class="text-center">Gestion de Pagos</h1>
            </div>

            <div id="containerTableActions" class="">
                <?php 
                require_once("./components/gestionPagosForm.php");
                ?>
            </div>
        </div>
    </main>
    <footer>
    </footer>
    <!-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script> -->
    <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/datatables.js"></script>
    <script src="./js/systemFunctions.js"></script>
    <?php 
        if($alerta){
            echo "<script type='text/javascript'>
            {$alerta->activar_sweet_alert()}
            </script>";
        }
    ?>
</body>
</html>