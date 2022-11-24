<?php 
require_once('utils/sessionCheck.php');
if(!comprobar_sesion_y_rol("Tb_Admin")){
    header('location: login.php');
}
//"nombre" / "apellido_p" / "apellido_m" / "telefono" / "correo" 
//Librerias
 include("includes/includes.php");

 $intento_fallido = false;
 $mensaje=[];
 $alerta = false;
 if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $DB = new BaseDeDatos();
    $mensaje = Doctor::verificar_datos_formulario($_POST, $DB);
    if(!$mensaje){
        $doctor = new Doctor($_POST);
        $res = $doctor->agregar_BD($DB);
        $intento_fallido = !$res[0];
        if($res[0]){
            $alerta = new Alerta($res[1],[],[],'./gestionDoctores.php');
        }else{
            $alerta = new Alerta("Error",[$res[1]]);
            $alerta->setOpcion('icon',"'error'");
            $alerta->setOpcion("ConfirmButtonColor","'#dc3545'");
        }
        $DB->close();
    }else{
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
    <title>Registrar Doctor</title>
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
                    <h1 class="text-center">Registrar Doctor</h1>
                </div>
                <div class="col-12 d-flex justify-content-center pt-5">
                <form method="POST" class="form d-flex row col-xl-8 col-md-12 justify-content-center formulario-registrar-recepcionista">
                        <div class="form-row row">
                        <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="tbNombre">*Nombre(s)</label>
                                <input id="tbNombre"
                                    name="nombre" type="text"
                                    class="form-control text-capitalize 
                                    <?php if(isset($mensaje) && in_array("Nombre",$mensaje)) echo "is-invalid";
                                    else if($intento_fallido) echo "is-valid";?>"
                                    placeholder="Nombre(s)"
                                    required
                                    maxlength="20"
                                    <?php if($intento_fallido) echo "value='". $_POST['nombre'] . "'"?>
                                >
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="tbAPaterno">*Apeliido Paterno</label>
                                <input id="tbAPaterno"
                                    name="apellido_p" type="text"
                                    class="form-control text-capitalize
                                    <?php if(isset($mensaje) && in_array("Apellido Paterno",$mensaje)) echo "is-invalid";
                                    else if($intento_fallido) echo "is-valid";?>"
                                    placeholder="Apellido Paterno"
                                    required
                                    maxlength="15"
                                    <?php if($intento_fallido) echo "value='". $_POST['apellido_p'] . "'"?>
                                >
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="tbAMaterno">Apeliido Materno</label>
                                <input id="tbAMaterno"
                                    name="apellido_m" type="text"
                                    class="form-control text-capitalize" 
                                    placeholder="Apellido Materno"
                                    maxlength="15"
                                    <?php if($intento_fallido) echo "value='". $_POST['apellido_m'] . "'"?>
                                >
                            </div>
                            <!-- Data-mask '(+00) 000-000-0000'-->
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="tbTelefono">Telefono</label>
                                <input id="tbTelefono"
                                name="telefono" type="tel"
                                class="form-control 
                                    <?php if(isset($mensaje) && in_array("Telefono",$mensaje)) echo "is-invalid";
                                    else if($intento_fallido) echo "is-valid";?>"
                                    placeholder="(+52) 646-111-1234"
                                    required
                                    maxlength="13"
                                    <?php if($intento_fallido) echo "value= '". $_POST['telefono'] . "'"?>
                                >
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="tbCorreo">Correo electrónico</label>
                                <input 
                                    id="tbCorreo" 
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
                                <label for="tbCorreoConf">Confirmar correo electrónico</label>
                                <input 
                                    id="tbCorreoConf" 
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
                                    <label for="tbusuario">Usuario</label>
                                    <input 
                                        id="tbusuario" 
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
                                    <label for="tbContra">Contraseña</label>
                                    <input 
                                        id="tbContra" 
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
                                    <label for="tbContraConfi">Confirmar Contraseña</label>
                                    <input 
                                        id="tbContraConfi" 
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
                        </div>
                        <div class="form-row row justify-content-center pt-3">
                            <button type="submit" class="btn btn-primary mx-3 col-md-3 col-5">Agregar</button>
                            <a class="row btn btn-danger mx-3 col-md-3 col-5" href="index.php">Cancelar</a>
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
    echo "<script>
    {$alerta->activar_sweet_alert()}
    </script>";
 }
 ?>
</body>
</html>