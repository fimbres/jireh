<?php
//Agregamos las librerias
include("includes/funciones_BD.php");

// VERIFICAR QUE ESTE CONECTADO EL USUARIO
// VERIFICAR QUE EL USUARIO SEA TIPO ADMINISTRADOR

$exito = false;
$intento_fallido = false;
$mensaje_principal = false;
$mensaje = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vacio = 0;
    // VERIFICAMOS CADA DATO TENGA ALGO
    //nombre
    !isset($_POST['nombre']) ? array_push($mensaje, 'Nombre') : $vacio++;
    !isset($_POST['apellido_p']) ? array_push($mensaje, 'Apellido Paterno') : $vacio++;
    !isset($_POST['apellido_m']) ? array_push($mensaje, 'Apellido Materno') : $vacio++;
    !isset($_POST['telefono']) ? array_push($mensaje, 'Teléfono') : $vacio++;
    !isset($_POST['correo']) ? array_push($mensaje, 'Correo Electrónico') : $vacio++;
    !isset($_POST['correo_conf']) ? array_push($mensaje, 'Confirmación Correo Electrónico') : $vacio++;
    !isset($_POST['usuario']) ? array_push($mensaje, 'Usuario') : $vacio++;
    !isset($_POST['contra']) ? array_push($mensaje, 'Contraseña') : $vacio++;
    !isset($_POST['contra_conf']) ? array_push($mensaje, 'Confirmación Contraseña') : $vacio++;
    strcmp($_POST['correo'], $_POST['correo_conf']) != 0 ? array_push($mensaje, 'No coinciden los Correos Electrónicos') : false;
    strcmp($_POST['contra'], $_POST['contra_conf']) != 0 ? array_push($mensaje, 'No coinciden las Contraseñas') : false;
    if (!$mensaje) {
            //Guardamos todos los valores de POST en las variables, para que a la
            // hora de hacer el script sea mas sencillo de realizar
            //Guardamos los datos de post en una variable para que sea mas sencillo de entender
            $nombre = $apellido_p = $apellido_m = $telefono = $correo = '';
            $correo_conf = $usuario = $contra = $contra_conf = '';
            $nombre = $_POST['nombre'];
            $apellido_p = $_POST['apellido_p'];
            $apellido_m = $_POST['apellido_m'];
            $telefono = $_POST['telefono'];
            $correo = $_POST['correo'];
            $correo_conf = $_POST['correo_conf'];
            $usuario = $_POST['usuario'];
            $contra = $_POST['contra'];
            $contra_conf = $_POST['contra_conf'];
            //Una vez entramos aqui estamos seguros que todos los datos estan de manera correcta
            // y podemos agregarlos a la base de datos
            $BD = crear_conexion();
            $sql = "INSERT INTO Tb_Recepcionista(Nombre,APaterno,AMaterno,NumTelefono,Email,Usuario,Contrasena,IdStatus) 
                values('$nombre','$apellido_p','$apellido_m','$telefono','$correo','$usuario','$contra',3)";
            if ($BD->query($sql)) {
                $exito = true;
                
                $mensaje_principal = "Se han guardado los datos correctamente" ;
            } else {
                $intento_fallido = true;
                $mensaje_principal = "Hubo un error al intentar guardar los datos, vuelve a intentarlo" . $BD->error;
            }
            $BD->close();
    } else {
        $intento_fallido = true;
        $mensaje_principal = "Se encontraron los siguientes problemas en el formulario";
        print_r($mensaje);
    }
}
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
                    <form method="POST" class="form d-flex row col-xl-8 col-md-12 justify-content-center formulario-registrar-recepcionista">
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="nombre_inpt_recepcionista">Nombre(s)</label>
                                <input id="nombre_inpt_recepcionista" name="nombre" type="text" class="form-control <?php if(isset($mensaje) && in_array("Nombre",$mensaje)) echo "is-invalid"; else if($intento_fallido) echo "is-valid"; ?>" placeholder="Nombre(s)" required <?php if ($intento_fallido) echo "value='" . $_POST['nombre']  . "'" ?>>
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="apellido_pat_inpt_recepcionista">Apellido Paterno</label>
                                <input id="apellido_pat_inpt_recepcionista" name="apellido_p" type="text" class="form-control <?php if(isset($mensaje) && in_array("Apellido Paterno",$mensaje)) echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>" placeholder="Apellido Paterno" required <?php if ($intento_fallido) echo "value='" . $_POST['apellido_p']  . "'" ?>>
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="apellido_mat_inpt_recepcionista">Apellido Materno</label>
                                <input id="apellido_mat_inpt_recepcionista" name="apellido_m" type="text" class="form-control <?php if(isset($mensaje) && in_array("Apellido Materno",$mensaje)) echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>" placeholder="Apellido Materno" required <?php if ($intento_fallido) echo "value='" . $_POST['apellido_m']  . "'" ?>>
                            </div>
                            <!-- data-mask='(+00) 000-000-0000' -->
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="telefono_inpt_recepcionista">Teléfono</label>
                                <input id="telefono_inpt_recepcionista" name="telefono" type="text" class="form-control <?php if(isset($mensaje) && in_array("Teléfono",$mensaje)) echo "is-invalid"; else if($intento_fallido) echo "is-valid"; ?>" placeholder="(+52) 646-117-6388" required <?php if ($intento_fallido) echo "value='" . $_POST['telefono']  . "'" ?>>
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="correo_inpt_recepcionista">Correo electrónico</label>
                                <input id="correo_inpt_recepcionista" name="correo" type="email" class="form-control <?php if(isset($mensaje) && (in_array("No coinciden los Correos Electrónicos",$mensaje) || in_array("Correo Electrónico",$mensaje))) echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>" placeholder="ejemplo@jireh.com" required <?php if ($intento_fallido) echo "value='" . $_POST['correo']  . "'" ?>>
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="correo_conf_inpt_recepcionista">Confirmar correo electrónico</label>
                                <input id="correo_conf_inpt_recepcionista" name="correo_conf" type="email" class="form-control <?php if(isset($mensaje) && (in_array('No coinciden los Correos Electrónicos',$mensaje) || in_array("Confirmación Correo Electrónico",$mensaje))) echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>" placeholder="ejemplo@jireh.com" required <?php if ($intento_fallido) echo "value='" . $_POST['correo_conf']  . "'" ?>>
                            </div>
                        </div>
                        <div class="form-row row justify-content-center">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="usuario_inpt_recepcionista">Usuario</label>
                                <input id="usuario_inpt_recepcionista" name="usuario" type="text" class="form-control <?php if(isset($mensaje) && in_array("Usuario",$mensaje)) echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>" placeholder="Usuario" required <?php if ($intento_fallido) echo "value='" . $_POST['usuario']  . "'" ?>>
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="contra_inpt_recepcionista">Contraseña</label>
                                <input id="contra_inpt_recepcionista" name="contra" type="password" class="form-control <?php if(isset($mensaje) && (in_array("No coinciden las Contraseñas",$mensaje) || in_array("Contraseña",$mensaje))) echo "is-invalid"; ?>" placeholder="*******" required>
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="contra_conf_inpt_recepcionista">Confirmar Contraseña</label>
                                <input id="contra_conf_inpt_recepcionista" name="contra_conf" type="password" class="form-control <?php if(isset($mensaje) && (in_array("No coinciden las Contraseñas",$mensaje) || in_array("Confirmación Contraseña",$mensaje))) echo "is-invalid"; ?>" placeholder="*******" required>
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
    
    <script src="js/systemFunctions.js"></script>

</body> 
</html>