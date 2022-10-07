<?php
    $id_doctor = false;
    if(false){
        $id_doctor = 1;
    }
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
    <link href="css/styles.css" rel='stylesheet'>
    <link href="css/styles-calendario.css" rel='stylesheet'>


</head>
<main>

    <body>
        <h2 id="titulo-calendario"></h2>
        <!-- Los atributos que tiene el calendario nos podrán ayudar a definir que 
        tipo de persona lo mostrara, si lo estará viendo la recepcionista, el doctor o el administrador
        esto con la idea de que podamos definir el calendario dependiendo el usuario que lo visualizara-->
        <div id='calendario' <?php if($id_doctor){ echo "data-doctor='$id_doctor'";} ?>></div>
        
        <!-- Este modal nos mostrara toda la informacion de una cita -->
        <div class="modal fade" id="infoCita" data-bs-keyboard="false" tabindex="-1" aria-labelledby="infoCitaLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="infoCitaLabel">Titulo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer d-flex">
                        <div class="col-3 justify-content-start m-0 d-flex">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-9 justify-content-end m-0 d-flex"> 
                            <a class="btn btn-danger mx-2">Borrar</a>
                            <a class="btn btn-warning mx-2" class="btn btn-primary">Editar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</main>
<footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src='js/fullcalendar/main.min.js'></script>
    <script src='js/fullcalendar/locales/es.js'></script>
    <script src="js/calendario.js"></script>
</footer>

</html>

<?php


?>