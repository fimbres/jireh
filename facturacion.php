<?php 
    require_once('vendor/autoload.php');
    require_once('includes/includes.php');
    require_once('./utils/sendMail.php');
    
    if(!isset($_GET['idCita'])){
        header('location: index.php');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $message = 'Solicitud de Factura, Jireh ';
        foreach($_POST as $nombre_campo => $valor)
        {
            $message .= $nombre_campo . ' = ' . $valor . ', '; 
        }
        
        $destinatario = 'arielg.incgarcia@gmail.com';

        $resultado = enviarMail($destinatario, $message, 'Solicitud de factura');

        if($resultado == true){
            $alerta = new Alerta("Se ha enviado la factura",[],[],'./index.php');
        }
        else{
            $alerta = new Alerta("Error",["Error al enviar el correo"]);
            $alerta->setOpcion('icon',"'error'");
            $alerta->setOpcion("confirmButtonColor","'#dc3545'");
        }
    }
    $BD = new BaseDeDatos();

    $queryIDPaciente = "SELECT Tb_Cita.IdPaciente AS IdPaciente, Tb_Cita.Descripcion AS Descripcion, Tb_Cita.Costo AS Costo FROM Tb_Cita WHERE Tb_Cita.IdCita = " . $_GET['idCita'] . ";";
    $res = $BD->query($queryIDPaciente);
    if(mysqli_num_rows($res) > 0){
        $fetch = mysqli_fetch_array($res);
    } else {
        header('location: index.php');
    }
    $queryInfoPaciente = "SELECT Tb_Paciente.Nombre AS Nombre, Tb_Paciente.APaterno AS APaterno, Tb_Paciente.AMaterno AS AMaterno, Tb_Paciente.RFC AS RFC, Tb_Paciente.CodigoPostal AS CodigoPostal, Tb_Paciente.Email AS Email, Tb_Paciente.NumTelefono AS NumTelefono FROM Tb_Paciente WHERE Tb_Paciente.IdPaciente = " . $fetch[0] . ";";
    $pacienteRes = $BD->query($queryInfoPaciente);
    $pacienteFetch = mysqli_fetch_assoc($pacienteRes);
    $query = "SELECT Tb_MetodoPago.MetodoPago as MetodoPago, Tb_Pago.FechaPago as FechaPago, Tb_Pago.NumeroOperacion as NumeroOperacion FROM Tb_Pago, Tb_MetodoPago WHERE Tb_MetodoPago.IdMetodo = Tb_Pago.IdMetodoPago AND Tb_Pago.IdCita = " . $_GET['idCita'] . ";";
    $facturaRes = $BD->query($query);
    $fetchFactura = mysqli_fetch_assoc($facturaRes);
    $BD->close();
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Facturacion</title>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/styles.css">

    </head>
    <body class="bg-light">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <form method="POST">
                            <div class="row justify-content-center">
                                <div class="col-lg-5 stripe-card">
                                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                                        <div class="card-header w-100 bg-white d-flex justify-content-center">
                                            <img class="rounded my-3" src="files/logo_jireh.jpg" width="250px" alt="Logo" />
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Nombre</label>
                                                <input name="nombre" type="text" class="form-control mb-2" required id="name" <?php echo "value='" . $pacienteFetch['Nombre'] . " " . $pacienteFetch['APaterno']  . " " . $pacienteFetch['AMaterno'] . "'"; ?>>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Correo Electrónico</label>
                                                <input name="correo" type="email" class="form-control mb-2" required id="email" <?php echo "value='" . $pacienteFetch['Email'] . "'"; ?>>
                                            </div>
                                            <div class="form-group">
                                                <label for="cellphone">Número De Celular</label>
                                                <input name="celular" type="text" class="form-control mb-2" required id="cellphone" <?php echo "value='" . $pacienteFetch['NumTelefono'] . "'"; ?>>
                                            </div>
                                            <div class="form-group">
                                                <label for="codigo-postal">Codigo Postal</label>
                                                <input name="cp" type="text" class="form-control mb-2" required id="codigo-postal" <?php echo "value='" . $pacienteFetch['CodigoPostal'] . "'"; ?>>
                                            </div>
                                            <div class="form-group">
                                                <label for="rfc">RFC</label>
                                                <input name="rfc" type="text" class="form-control mb-2" required id="rfc" <?php echo "value='" . $pacienteFetch['RFC'] . "'"; ?>>
                                            </div>
                                            <div class="form-group">
                                                <label for="regimen">Regimen</label>
                                                <input name="regimen" type="text" class="form-control mb-2" required id="regimen" placeholder="Ingresa tu regimen fiscal">
                                            </div>
                                            <div class="form-group">
                                                <label for="concepto">Concepto</label>
                                                <input name="concepto" type="text" class="form-control mb-2" required id="concepto" readonly <?php echo "value='Cita dental: " . $fetch[1] . "'"; ?>>
                                            </div>
                                            <div class="form-group">
                                                <label for="importe">Importe</label>
                                                <input name="importe" type="text" class="form-control mb-2" required id="importe" readonly <?php echo "value='" . $fetch[2] . "'"; ?>>
                                            </div>
                                            <div class="form-group">
                                                <label for="metodo-pago">Metodo de pago</label>
                                                <input name="metodo" type="text" class="form-control mb-2" required id="metodo-pago" readonly <?php echo "value='" . $fetchFactura['MetodoPago'] . "'"; ?>>
                                            </div>
                                            <!-- <div class="form-check">
                                                <input type="checkbox" class="form-check-input mb-2" id="guardar-datos">
                                                <label class="form-check-label" for="guardar-datos">Guardar datos</label>
                                            </div> -->
                                            <input type="submit" value="Solicitar Facturación" class="btn btn-primary mt-5 w-100"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <?php 
            if($alerta){
                echo "<script>
                {$alerta->activar_sweet_alert()}
                </script>";
            }
        ?>
    </body>
</html>