<?php
    require_once('utils/sessionCheck.php');
    if(!comprobar_sesion_y_rol("Tb_Recepcionista")){
        header('location: login.php');
    }
    $tipo_formulario = 'modificar';
    $idCita = $_GET['idCita'];

    if(!empty($idCita)){
        require_once('./includes/classes/bd.php');
        $BD = new BaseDeDatos();
        $infoCita = $BD->getTbCita_cita($idCita);

        if($infoCita){
            $infoPaciente = $BD->getTbPaciente_nombrePaciente($infoCita["IdPaciente"]);
            $infoDoctor = $BD->getTbDoctor_nombreDoctor($infoCita["IdDoctor"]);            
        }else{
            header('location: index.php');
        }

    }else{
        header('location: index.php');
    }

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
    <script src="./js/gestionPagos.js" type="text/javascript"></script> 
</body>
</html>