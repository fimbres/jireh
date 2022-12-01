<?php
    require_once('utils/sessionCheck.php');
    require_once('includes/includes.php');
    if(!(comprobar_sesion_y_rol("Tb_Recepcionista") || comprobar_sesion_y_rol("Tb_Doctor"))){
        header('location: login.php');
    }
    $alertas = [];
    //$alerta = new Alerta($res[1],[],[],'./agenda.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>
    <!-- for Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <!-- Para el fullcalendar -->
    <link href='css/fullcalendar/main.min.css' rel='stylesheet' />
    <!-- Nuestros styles -->
    <link rel="stylesheet" href="css/index.css">
    <link href="css/styles.css" rel='stylesheet'>
    <link href="css/styles-calendario.css" rel='stylesheet'>
</head>
<body>
    <main class="d-flex flex-wrap flex-row">
        <!--COMPONENTE MENU-->
        <?php
            require_once('components/menu.php');
        ?>

        <!--CONTENEDOR ALMACENAR CONTENIDO OTRAS PAGINAS-->
        <div id="displayActions" class="d-flex bg-white p-4">
        <div class="w-100">
            <div class="d-flex flex-row mb-5 mt-3 justify-content-between align-items-center">
                <h2 id="titulo-calendario" class="mb-0"></h2>
                <?php if(comprobar_sesion_y_rol("Tb_Recepcionista")): ?>
                    <a class="row btn btn-primary col-md-3 col-6" style="height: 40px;" href="AgregarAgenda.php">Agendar Cita</a>
                <?php endif;?>
            </div>
            <!-- Los atributos que tiene el calendario nos podrán ayudar a definir que 
            tipo de persona lo mostrara, si lo estará viendo la recepcionista, el doctor o el administrador
            esto con la idea de que podamos definir el calendario dependiendo el usuario que lo visualizara-->
            
            <div id='calendario' <?php if(isset($_GET['IdDoctor'])) echo "data-doctor='" . $_GET["IdDoctor"] . "'"?>></div>
            
            <!-- Este modal nos mostrara toda la informacion de una cita -->
            <div class="modal fade" id="infoCita" data-bs-keyboard="false" tabindex="-1" aria-labelledby="motivolbl" aria-hidden="true">
                <div id="modalInfoContainer" class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-custom-primary">
                            <h4 class="modal-title text-white fw-bold" id="infoCitaLabel">Información de la cita</h4>
                            <button type="button" class="btn btn-close btn-danger bg-danger text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body row">
                            <div class="col-12">
                                <h6 class="text-dark">Nombre del paciente</h6>
                                <div class="input-group mb-3">
                                    <input id="nombrePaciente" type="text" class="form-control" placeholder="" disabled>
                                    <span class="input-group-text" id="basic-addon2">Paciente</span>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <h6 class="text-dark">Tratamiento a realizar</h6>
                                <div class="form-floating">
                                    <textarea id="tratamiento" class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px" disabled></textarea>
                                    <label for="floatingTextarea2">Comentarios</label>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <h6 class="text-dark">Nombre del doctor</h6>
                                <div class="input-group mb-3">
                                    <input id="nombreDoctor" type="text" class="form-control" placeholder="" disabled>
                                    <span class="input-group-text" id="basic-addon2">Doctor</span>
                                </div>
                            </div>
                            <div class="col-6 mt-2">
                                <h6 class="text-dark">Fecha</h6>
                                <div class="input-group mb-3">
                                    <input id="fechaCita" type="date" class="form-control" placeholder="" disabled>
                                    <span class="input-group-text" id="basic-addon2">Fecha</span>
                                </div>
                            </div>
                            <div class="col-6 mt-2">
                                <h6 class="text-dark">Costo de la cita</h6>
                                <div class="input-group mb-3">
                                    <input id="costoCita" type="number" class="form-control" placeholder="Costo en MXN" disabled>
                                    <span class="input-group-text" id="basic-addon2">.00</span>
                                </div>
                            </div>
                            <div class="col-6 mt-2">
                                <h6 class="text-dark">Hora inicio</h6>
                                <div class="input-group mb-3">
                                    <input id="horaInicio" type="time" class="form-control" disabled/>
                                    <span class="input-group-text" id="basic-addon2">Hora</span>
                                </div>
                            </div>
                            <div class="col-6 mt-2">
                                <h6 class="text-dark">Hora final</h6>
                                <div class="input-group mb-3">
                                    <input id="horaFinal" type="time" class="form-control" placeholder="" disabled/>
                                    <span class="input-group-text" id="basic-addon2">Hora</span>
                                </div>
                                <input type="hidden" id="id-cita" value="<?php echo $valor?>">
                                
                            </div>
                        </div>
                        <div class="modal-footer d-flex">
                            <div class="col-3 justify-content-start m-0 d-flex">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                            <div class="col-9 justify-content-end m-0 d-flex"> 
                                <?php if(comprobar_sesion_y_rol("Tb_Recepcionista")): ?>
                                    <a class="btn btn-danger mx-2" id="motivo1">Eliminar</a>
                                    <a class="btn btn-warning mx-2" id="editar-modal" href="ModificarAgenda.php">Cancelar</a>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="motivo" data-bs-keyboard="false" tabindex="-1" aria-labelledby="motivolbl" aria-hidden="true">
                <div id="modalContainer" class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-custom-primary">
                            <h4 class="modal-title text-white fw-bold" id="motivolbl">Información de la cita</h4>
                            <button type="button" class="btn btn-close btn-danger bg-danger text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body row justify-content-center">
                            <div class="col-6 mt-2">
                                <h6 class="text justify-content-center">Motivo de cancelacion</h6>
                                <div class="mb-6">
                                    <input id="motivoCancel" type="text" class="form-control" placeholder=""/>
                                </div>
                                <input type="hidden" id="id-cita" value="<?php echo $valor?>">
                                <?php
                                    $al = new Alerta("¿Deseas eliminar la cita?");
                                    $al->setOpcion('icon',"'error'");
                                    //$al->setOpcion('input');
                                    $al->setOpcion("confirmButtonColor","'#dc3545'");
                                    $al->setOpcion("confirmButtonText","'Eliminar'");
                                    $al->setOpcion('showCancelButton',"true");
                                    $al->setOpcion('cancelButtonText',"'Cancelar'");
                                    $al->setEscuchar('#eliminar-modal');
                                    $al->setThen("
                                        if(res.isConfirmed){
                                            let id_cita = parseInt($('#id-cita').val())
                                            let motivo=$('#motivoCancel').val()
                                            $.ajax({
                                            type: 'POST',
                                            url: 'EliminarCita.php',
                                            data: { id: id_cita, motivoC : motivo},
                                            dataType: 'json',
                                            success: function (data) {
                                                if (data.response === 'success') {
                                                    Swal.fire(data.message, '', 'success')
                                                    .then(e => {
                                                        location.reload();
                                                    })
                                                } else if (data.response === 'invalid') {
                                                    Swal.fire(data.message, '', 'error')
                                                }
                                            },
                                            error: function (xhr, exception) {
                                                    console.log('error', xhr);
                                            },
                                        });
                                    }       
                                    ");
                                ?>
                            </div>
                        </div>
                        <div class=" d-flex">
                            <div class="col-12 justify-content-center  d-flex"> 
                                <?php if(comprobar_sesion_y_rol("Tb_Recepcionista")): ?>
                                    <a class="btn btn-danger mx-2" id="eliminar-modal" >Eliminar</a>
                                    <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
    <footer>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
        <script src='js/fullcalendar/main.min.js'></script>
        <script src='js/fullcalendar/locales/es.js'></script>
        <script src="js/calendario.js"></script>
        <script src="js/systemFunctions.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php
        echo "
        <script type='text/javascript'>
            document.addEventListener('DOMContentLoaded',() =>{
                {$al->activar_sweet_alert()}
            })
        </script>
        "
    ?> 
</body>

</html>