<?php 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Recepcionista</title>
    <!-- CSS only -->
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <!-- Styles principal -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <main class="d-flex flex-wrap flex-row">
        <!--COMPONENTE MENU-->
        <?php
            require_once('components/menu.php');
        ?>

        <!--CONTENEDOR ALMACENAR CONTENIDO OTRAS PAGINAS-->
        <div id="displayActions" class="d-block bg-white p-4">
            <div class="row d-flex">
                <div class="col-12">
                    <h1 class="text-center">Registrar Recepcionista</h1>
                </div>
                <div class="col-12 d-flex justify-content-center pt-5">
                    <form method="POST" class="form d-flex row col-8 justify-content-center formulario-registrar-recepcionista">
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="nombre_inpt_recepcionista">Nombre(s)</label>
                                <input id="nombre_inpt_recepcionista" name="nombre" type="text" class="form-control" placeholder="Nombre(s)">
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="apellido_pat_inpt_recepcionista">Apellido Paterno</label>
                                <input id="apellido_pat_inpt_recepcionista" type="text" class="form-control" placeholder="Apellido Paterno">
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="apellido_mat_inpt_recepcionista">Apellido Materno</label>
                                <input id="apellido_mat_inpt_recepcionista" type="text" class="form-control" placeholder="Apellido Materno">
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="telefono_inpt_recepcionista">Teléfono</label>
                                <input id="telefono_inpt_recepcionista" type="text" class="form-control" placeholder="(+52) 646-117-6388">
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="correo_inpt_recepcionista">Correo electrónico</label>
                                <input id="correo_inpt_recepcionista" type="email" class="form-control" placeholder="ejemplo@jireh.com">
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="correo_conf_inpt_recepcionista">Correo electrónico</label>
                                <input id="correo_conf_inpt_recepcionista" type="email" class="form-control" placeholder="ejemplo@jireh.com">
                            </div>
                        </div>
                        <div class="form-row row justify-content-center">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="usuario_inpt_recepcionista">Usuario</label>
                                <input id="usuario_inpt_recepcionista" type="text" class="form-control" placeholder="Usuario">
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="contra_inpt_recepcionista">Contraseña</label>
                                <input id="contra_inpt_recepcionista" type="password" class="form-control" placeholder="*******">
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="contra_rep_inpt_recepcionista">Confirmar Contraseña</label>
                                <input id="contra_rep_inpt_recepcionista" type="password" class="form-control" placeholder="*******">
                            </div>
                        </div>
                        <div class="form-row row justify-content-center pt-3">
                            <button type="submit" class="btn btn-primary mx-3 col-2">Registrar</button>
                            <a class="col-2 row btn btn-danger mx-3" href="index.php">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer>
    </footer>
    
    <script src="js/systemFunctions.js"></script>
</body>
</html>