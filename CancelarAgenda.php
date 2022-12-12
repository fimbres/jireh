<?php 
    require_once('utils/sessionCheck.php');
    require_once('includes/includes.php');
    
    if(!comprobar_sesion_y_rol("Tb_Recepcionista")){
        header('location: login.php');
    }

    if(empty($_GET['id'])){
        header('location: index.php');
    }
    $idCita = $_GET['id'];

    $al = false;
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $motivoC = $_POST['motivoCancel'];
        $idCitaCancelar = $_POST['idCita'];

        $al = new Alerta("¿Deseas cancelar la cita?");
        $al->setOpcion('icon',"'error'");
        $al->setOpcion("confirmButtonColor","'#dc3545'");
        $al->setOpcion("confirmButtonText","'Eliminar'");
        $al->setOpcion('showCancelButton',"true");
        $al->setOpcion('cancelButtonText',"'Cancelar'");
        $al->setThen("
            if(res.isConfirmed){
                $.ajax({
                    type: 'POST',
                    url: 'EliminarCita.php',
                    data: { id: " . $idCitaCancelar .", motivoC : '" . $motivoC ."' },
                    dataType: 'json',
                    success: function (data) {
                        if (data.response === 'success') {
                            Swal.fire(data.message, '', 'success')
                            .then(e => {
                                window.location = 'agenda.php';
                            })
                        } else if (data.response === 'invalid') {
                            Swal.fire(data.message, '', 'error');
                        }
                    },
                    error: function (xhr, exception) {
                            console.log('error', xhr);
                    },
                });
            }
            else{
                window.location = 'agenda.php';
            }
        ");
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelar Cita</title>
    <!-- CSS Only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <!-- Styles principal -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <main class="d-flex flex-wrap flex-row">
        <!-- COMPONENTE MENU -->
        <?php
            require_once('components/menu.php')
        ?>
        <!-- CONTENEDOR ALMACENAR CONTENIDO OTRAS PAGINAS -->
        <div id="displayActions" class="d-block bg-white p-4">
            <div class="row d-flex">
                <div class="col-12">
                    <h1 class="text-center">Cancelar Cita</h1>
                </div>
                <div class="col-12 d-flex justify-content-center pt-5">
                    <form method="POST" class="form d-flex row col-xl-8 col-md-12 justify-content-center formulario-registrar-recepcionista">
                        <div class="form-row row">
                            <div class="form-group col-12 pb-4">
                                <label for="tbCosto">*Motivo de cancelación</label>&nbsp;
                                <input id="motivoCancel" name="motivoCancel" type="text" class="form-control" required placeholder="Ingresa un motivo"/>
                            </div>
                            <input type="hidden" id="idCita" name="idCita" value="<?php echo $idCita?>">
                        </div>
                        <div class="form-row row justify-content-center pt-3">
                            <button type="submit" class="btn btn-danger mx-3 col-md-3 col-5" id="eliminar-modal">Cancelar</button>
                            <a class="row btn btn-secondary mx-3 col-md-3 col-5" href="agenda.php">Regresar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <!-- <script src="js/jquery.mask.js"></script> -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/systemFunctions.js"></script>
    <script src="js/calendario.js"></script>
    <!-- LLamada a la funcion de sweet alert en caso de haber ingresado algun dato -->
    <?php 
        if($al){
            echo "<script>
            {$al->activar_sweet_alert()}
            </script>";
        }
    ?>
</body>
</html>