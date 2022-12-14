<?php
    require_once('utils/sessionCheck.php');
    require_once('includes/includes.php');
    if(!(comprobar_sesion_y_rol("Tb_Recepcionista") || comprobar_sesion_y_rol("Tb_Doctor"))){
        header('location: login.php');
    }
    $al = false;
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
                    <?php
                        if(comprobar_sesion_y_rol("Tb_Recepcionista")){
                            require('components/infoCita.php');
                        }
                        else{
                            require('components/citaDoctor.php');
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    </main>
    <footer>
    </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
        <?php
            if($al){
                echo "
                <script type='text/javascript'>
                    document.addEventListener('DOMContentLoaded',() =>{
                        {$al->activar_sweet_alert()}
                    })
                </script>
                ";
            }
            if(!comprobar_sesion_y_rol("Tb_Doctor")){
                echo '
                    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
                ';
            }
        ?>
        <script src='js/fullcalendar/main.min.js'></script>
        <script src='js/fullcalendar/locales/es.js'></script>
        <script src="js/calendario.js"></script>
        <script src="js/systemFunctions.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
</html>
