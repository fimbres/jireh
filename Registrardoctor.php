<?php 
//Librerias
 include("includes/funciones_BD.php");

 $exito = false;
 $intento_fallido = false;
 $mensaje_principañ =false;
 $mensaje=[];
 if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $vacio = 0;
    //Verificacion de los datos
    !isset($_POST['nombre']) ? array_push($mensaje, 'Nombre') : $vacio ++;
    !isset($_POST['apaterno']) ? array_push($mensaje, 'Apellido Paterno') : $vacio ++;
    !isset($_POST['amaterno']) ? array_push($mensaje, 'Apellido Materno') : $vacio ++;
    !isset($_POST['Telefono']) ? array_push($mensaje, 'Telefono') : $vacio ++;
    !isset($_POST['correo']) ? array_push($mensaje, 'Correo Electronico') : $vacio ++;
    !isset($_POST['correoConf']) ? array_push($mensaje, 'Confirmacion Correo Electronico') : $vacio ++;
    !isset($_POST['usuario']) ? array_push($mensaje, 'Usuario') : $vacio ++;
    !isset($_POST['contraseña']) ? array_push($mensaje, 'Contraseña') : $vacio ++;
    !isset($_POST['contraseñaconf']) ? array_push($mensaje, 'Confirmacion Contraseña') : $vacio ++;
    //Validacion de que concuerde la contraseña y el correo
    strcmp($_POST['correo'], $_POST['correoConf']) != 0 ? array_push($mensaje, 'No coinciden los Correos Electrónicos') : false;
    strcmp($_POST['contraseña'], $_POST['contraseñaconf']) != 0 ? array_push($mensaje, 'No coinciden las Contraseñas') : false;
    if(!$mensaje){
            $nombre = $aPaterno = $aMaterno = $telefono = $correo = "";
            $correoConfirmacion = $usuario = $contraseña = $contraseñaConfirmacion = "";
            $nombre = $_POST['nombre'];
            $aPaterno = $_POST['apaterno'];
            $aMaterno = $_POST['amaterno'];
            $telefono = $_POST['Telefono'];
            $correo = $_POST['correo'];
            $correoConfirmacion = $_POST['correoConf'];
            $usuario = $_POST['usuario'];
            $contraseña = $_POST['contraseña'];
            $BD = crear_conexion();
            $sql = "INSERT INTO Tb_Doctor
            (Nombre, APaterno, AMaterno, Email, NumTelefono, Usuario, Contrasena, IdStatus)
            VALUES('$nombre', '$aPaterno', '$aMaterno', '$correo', '$telefono', '$usuario', '$contraseña', 3)";
            if($BD->query($sql)){
                $exito = true;
                $mensaje_principañ = "Se han guardado los datos correctamente";
            }else {
                $intento_fallido = true;
                $mensaje_principañ = "Hubo un error al intentar guardar los datos, vuelve a intentarlo" . $BD->error;
             }
             $BD->close();
    }else{
            $intento_fallido = true;
            $mensaje_principañ = "Se encontraron los siguientes problemas en el formulario";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Doctor</title>
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
                                <label for="tbNombre">Nombre(s)</label>
                                <input id="tbNombre"
                                    name="nombre" type="text"
                                    class="form-control 
                                    <?php if(isset($mensaje) && in_array("Nombre",$mensaje)) echo "is-invalid";
                                    else if($intento_fallido) echo "is-valid";?>"
                                    placeholder="Nombre(s)"
                                    required
                                    maxlength="20"
                                    <?php if($intento_fallido) echo "value='". $_POST['nombre'] . "'"?>
                                >
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="tbAPaterno">Apeliido Paterno</label>
                                <input id="tbAPaterno"
                                    name="apaterno" type="text"
                                    class="form-control 
                                    <?php if(isset($mensaje) && in_array("Apellido Paterno",$mensaje)) echo "is-invalid";
                                    else if($intento_fallido) echo "is-valid";?>"
                                    placeholder="Apellido Paterno"
                                    required
                                    maxlength="15"
                                    <?php if($intento_fallido) echo "value='". $_POST['apaterno'] . "'"?>
                                >
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="tbAMaterno">Apeliido Materno</label>
                                <input id="tbAMaterno"
                                    name="amaterno" type="text"
                                    class="form-control 
                                    <?php if(isset($mensaje) && in_array("Apellido Materno",$mensaje)) echo "is-invalid";
                                    else if($intento_fallido) echo "is-valid";?>"
                                    placeholder="Apellido Materno"
                                    required
                                    maxlength="15"
                                    <?php if($intento_fallido) echo "value='". $_POST['amaterno'] . "'"?>
                                >
                            </div>
                            <!-- Data-mask '(+00) 000-000-0000'-->
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="tbTelefono">Telefono</label>
                                <input id="tbTelefono"
                                name="Telefono" type="tel"
                                class="form-control 
                                    <?php if(isset($mensaje) && in_array("Telefono",$mensaje)) echo "is-invalid";
                                    else if($intento_fallido) echo "is-valid";?>"
                                    placeholder="(+52) 646-111-1234"
                                    required
                                    maxlength="13"
                                    <?php if($intento_fallido) echo "value= '". $_POST['Telefono'] . "'"?>
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
                                    name="correoConf" 
                                    type="email" 
                                    class="form-control 
                                    <?php if(isset($mensaje) && (in_array('No coinciden los Correos Electrónicos',$mensaje) || in_array("Confirmación Correo Electrónico",$mensaje))) echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>" 
                                    placeholder="ejemplo@jireh.com" 
                                    required 
                                    maxlength="50"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['correoConf']  . "'" ?>
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
                                        name="contraseña" 
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
                                        name="contraseñaconf" 
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
    <?php if($intento_fallido) { ?>
        <script>
            let html_er = "<?php echo $mensaje_principañ?>"
            html_er += '<ul></ul>' + `<?php foreach ($mensaje as $val) {
                echo "<li>" . $val . "</li>";
            }?> ` + "<ul></ul>"
            swal.fire({
                title: 'Hubo un error',
                icon: 'error',
                html: html_er,
                showCloseButton: true,
                showCancelButton: false,
                showConfirmButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Aceptar",
            })
        </script>
    <?php } ?>
    <?php if ($exito) { ?>
        <script>
            Swal.fire({
                title: '<?php echo $mensaje_principañ?>',
                icon: 'success',
                showCloseButton: true,
                showCancelButton: false,
                showConfirmButton: true,
                confirmButtonColor: "#28a745",
                confirmButtonText: "Aceptar",
            })
        </script>
        <?php }?>
</body>
</html>