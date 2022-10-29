<?php
//Agregamos las librerias
include("includes/includes.php");

// VERIFICAR QUE ESTE CONECTADO EL USUARIO
// VERIFICAR QUE EL USUARIO SEA TIPO ADMINISTRADOR
$intento_fallido = false;
$mensaje = [];
$alerta = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // VERIFICAMOS CADA DATO TENGA ALGO
    $mensaje = Recepcionista::verificar_datos_formulario($_POST);
    if (!$mensaje) {
        $BD = new BaseDeDatos();
        $recep = new Recepcionista($_POST);
        $res = $recep->agregar_BD($BD);
        $intento_fallido = !$res[0];
        if($res[0]){
            $alerta = new Alerta($res[1]);
        } else{
            $alerta = new Alerta("Error",[$res[1]]);
            $alerta->setOpcion('icon',"'error'");
            $alerta->setOpcion("confirmButtonColor","'#dc3545'");
        }
        $BD->close();
    } else {
        $intento_fallido = true;
        $alerta = new Alerta("Error",["Se encontraron los siguientes problemas en el formulario"],[$mensaje]);
        $alerta->setOpcion('icon',"'error'");
        $alerta->setOpcion("confirmButtonColor","'#dc3545'");
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Recepcionista</title>
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
                    <form method="POST" class="form d-flex row col-xl-8 col-md-12 justify-content-center formulario-registrar">
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="nombre_inpt_recepcionista"><b>*</b>Nombre(s)</label>
                                <input 
                                    id="nombre_inpt_recepcionista" 
                                    name="nombre" type="text" 
                                    class="form-control text-capitalize
                                    <?php if(isset($mensaje) && in_array("Nombre",$mensaje)) echo "is-invalid"; else if($intento_fallido) echo "is-valid"; ?>" 
                                    placeholder="Nombre(s)" 
                                    required 
                                    maxlength="20"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['nombre']  . "'" ?>
                                    
                                    >
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="apellido_pat_inpt_recepcionista"><b>*</b>Apellido Paterno</label>
                                <input 
                                    id="apellido_pat_inpt_recepcionista" 
                                    name="apellido_p" 
                                    type="text" 
                                    class="form-control text-capitalize
                                    <?php if(isset($mensaje) && in_array("Apellido Paterno",$mensaje)) echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>" 
                                    placeholder="Apellido Paterno" 
                                    required 
                                    maxlength="15"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['apellido_p']  . "'" ?>
                                    >
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="apellido_mat_inpt_recepcionista">Apellido Materno</label>
                                <!-- Se puso en comentarios lo siguiente, que serviría para verificar si el apellido materno
                                    esta correcto o no, pero como el apellido materno es opcional, entonces no es necesario
                                    agregar este tipo de validación -->
                                <?php // if(isset($mensaje) && in_array("Apellido Materno",$mensaje)) 
                                    //echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>
                                <input 
                                    id="apellido_mat_inpt_recepcionista" 
                                    name="apellido_m" 
                                    type="text" 
                                    class="form-control text-capitalize" 
                                    placeholder="Apellido Materno"
                                    maxlength="15"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['apellido_m']  . "'" ?>
                                    >
                            </div>
                            <!-- data-mask='(+00) 000-000-0000' -->
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="telefono_inpt_recepcionista"><b>*</b>Teléfono</label>
                                <input 
                                    id="telefono_inpt_recepcionista" 
                                    name="telefono" 
                                    type="tel" 
                                    class="form-control 
                                    <?php if(isset($mensaje) && in_array("Teléfono",$mensaje)) echo "is-invalid"; else if($intento_fallido) echo "is-valid"; ?>" 
                                    placeholder="(+52) 646-117-6388" 
                                    required 
                                    maxlength="13"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['telefono']  . "'" ?>
                                    >
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="correo_inpt_recepcionista"><b>*</b>Correo electrónico</label>
                                <input 
                                    id="correo_inpt_recepcionista" 
                                    name="correo" 
                                    type="email" 
                                    class="form-control 
                                    <?php if(isset($mensaje) && (in_array("No coinciden los Correos Electrónicos",$mensaje) || in_array("Correo Electrónico",$mensaje))) echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>" 
                                    placeholder="ejemplo@jireh.com" 
                                    required 
                                    maxlength="50"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['correo']  . "'" ?>
                                    >
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="correo_conf_inpt_recepcionista"><b>*</b>Confirmar correo electrónico</label>
                                <input 
                                    id="correo_conf_inpt_recepcionista" 
                                    name="correo_conf" 
                                    type="email" 
                                    class="form-control 
                                    <?php if(isset($mensaje) && (in_array('No coinciden los Correos Electrónicos',$mensaje) || in_array("Confirmación Correo Electrónico",$mensaje))) echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>" 
                                    placeholder="ejemplo@jireh.com" 
                                    required 
                                    maxlength="50"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['correo_conf']  . "'" ?>
                                    >
                            </div>
                        </div>
                        <div class="form-row row justify-content-center">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="usuario_inpt_recepcionista"><b>*</b>Usuario</label>
                                <input 
                                    id="usuario_inpt_recepcionista" 
                                    name="usuario" 
                                    type="text" 
                                    class="form-control 
                                    <?php if(isset($mensaje) && in_array("Usuario",$mensaje)) echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>" 
                                    placeholder="Usuario" 
                                    required 
                                    maxlength="10"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['usuario']  . "'" ?>
                                    >
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="contra_inpt_recepcionista"><b>*</b>Contraseña</label>
                                <input 
                                    id="contra_inpt_recepcionista" 
                                    name="contra" 
                                    type="password" 
                                    class="form-control 
                                    <?php if(isset($mensaje) && (in_array("No coinciden las Contraseñas",$mensaje) || in_array("Contraseña",$mensaje))) echo "is-invalid"; ?>" 
                                    placeholder="*******" 
                                    required
                                    maxlength="15"
                                    >
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="contra_conf_inpt_recepcionista"><b>*</b>Confirmar Contraseña</label>
                                <input 
                                    id="contra_conf_inpt_recepcionista" 
                                    name="contra_conf" 
                                    type="password" 
                                    class="form-control 
                                    <?php if(isset($mensaje) && (in_array("No coinciden las Contraseñas",$mensaje) || in_array("Confirmación Contraseña",$mensaje))) echo "is-invalid"; ?>" 
                                    placeholder="*******"
                                    required
                                    maxlength="15"
                                    >
                            </div>
                        </div>
                        <div class="form-row row justify-content-center pt-3">
                            <button type="submit" class="btn btn-primary mx-3 col-md-3 col-5">Registrar</button>
                            <a class="row btn btn-danger mx-3 col-md-3  col-5" href="index.php">Cancelar</a>
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


    <!-- LLamada a la funcion de sweet alert en caso de haber ingresado algun dato -->
    <?php 
        if($alerta){
            $alerta->activar_sweet_alert();
        }
    ?>
</body> 
</html>