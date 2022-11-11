<?php 
require_once('utils/sessionCheck.php');
if(!comprobar_sesion_y_rol("Tb_Recepcionista")){
    header('location: login.php');
}

include("includes/includes.php");
// VERIFICAR QUE ESTE CONECTADO EL USUARIO
// VERIFICAR QUE EL USUARIO SEA TIPO RECEPCIONISTA
$intento_fallido = false;
$mensaje = [];
$alerta = false;
$BD = new BaseDeDatos();
//Agarammos los datos necesarios para poder mostrar el select del SEXO
$sexo_inpt = $BD->getTb_Sexo();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // print_r($_FILES);
    //Verificamos que los datos obtenidos por el formulario esten de manera correcta
    $mensaje = Paciente::verificar_datos_formulario($_POST);
    //$mensaje = array_merge($mensaje,Paciente::verificar_archivos_formulario($_FILES));
    if(!$mensaje){
        $paciente = new Paciente($_POST);
        //Agregamos al paciente a la base de datos
        $res = $paciente->agregar_BD($BD,$_FILES);
        if($res[0]){
            $alerta = new Alerta($res[1],[],[],'./gestionPacientes.php');
        } else{
            $alerta = new Alerta("Error",[$res[1]]);
            $alerta->setOpcion('icon',"'error'");
            $alerta->setOpcion("confirmButtonColor","'#dc3545'");
        }
    } else {
        //Si entra aquí encontró un error al 
        $intento_fallido = true;
        $alerta = new Alerta("Error",["Se encontraron los siguientes problemas en el formulario"],[$mensaje]);
        $alerta->setOpcion('icon',"'error'");
        $alerta->setOpcion("confirmButtonColor","'#dc3545'");
    }

}
$BD->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Paciente</title>
    <!-- CSS only -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <!-- Styles principal -->
    <link rel="stylesheet" href="css/styles.css">
    <!-- Font awesome -->
    <link rel="stylesheet" href="css/font-awesome/css/all.min.css">
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
                    <h1 class="text-center">Registrar Paciente</h1>
                </div>
                <div class="col-12 d-flex justify-content-center pt-5">
                    <form method="POST" enctype="multipart/form-data" class="form d-flex row col-xl-8 col-md-12 justify-content-center formulario-registrar">
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="nombre_inpt_paciente"><b>*</b>Nombre(s)</label>
                                <input 
                                    id="nombre_inpt_paciente" 
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
                                <label for="apellido_pat_inpt_paciente"><b>*</b>Apellido Paterno</label>
                                <input 
                                    id="apellido_pat_inpt_paciente" 
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
                                <label for="apellido_mat_inpt_paciente">Apellido Materno</label>
                                <input 
                                    id="apellido_mat_inpt_paciente" 
                                    name="apellido_m" 
                                    type="text" 
                                    class="form-control text-capitalize
                                    <?php if(isset($mensaje) && in_array("Apellido Materno",$mensaje)) echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>" 
                                    placeholder="Apellido Materno" 
                                    maxlength="15"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['apellido_m']  . "'" ?>
                                    >
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="sexo_inpt_paciente"><b>*</b>Sexo</label>
                                <select id="sexo_inpt_paciente" class="form-control" name="sexo">
                                    <?php while($fila = $sexo_inpt->fetch_assoc()){?>
                                        <option value="<?php echo $fila['IdSexo']?>" <?php if($intento_fallido && $fila['IdSexo'] == $_POST['sexo']) echo "selected";?>>
                                            <?php echo $fila['Sexo']?> 
                                        </option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-md-12 pb-4">
                                <label for="direccion_inpt_paciente"><b>*</b>Dirección</label>
                                <input 
                                    id="direccion_inpt_paciente"
                                    name="direccion"
                                    type="text"
                                    class="form-control
                                    <?php if(isset($mensaje) && in_array("Direccion",$mensaje)) echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>" 
                                    placeholder="Direccion"
                                    required
                                    maxlength="100"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['direccion']  . "'" ?>
                                    >
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="correo_inpt_paciente"><b>*</b>Correo electrónico</label>
                                <input 
                                    id="correo_inpt_paciente" 
                                    name="correo" 
                                    type="email" 
                                    class="form-control 
                                    <?php if(isset($mensaje) && in_array("Correo Electrónico",$mensaje)) echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>" 
                                    placeholder="ejemplo@jireh.com" 
                                    required 
                                    maxlength="50"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['correo']  . "'" ?>
                                    >
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="telefono_inpt_paciente"><b>*</b>Teléfono</label>
                                <input 
                                    id="telefono_inpt_paciente" 
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
                        <div class="form-row row justify-content-center">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="fecha_inpt_paciente"><b>*</b>Fecha de nacimiento</label>
                                <div class="input-group">
                                    <div class="input-group-prepend input-group-text" for="fecha_inpt_paciente">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </div>
                                    <input 
                                    id="fecha_inpt_paciente" 
                                    name="fecha" 
                                    type="date" 
                                    class="form-control 
                                    <?php if(isset($mensaje) && in_array("Fecha",$mensaje)) echo "is-invalid"; else if($intento_fallido)  echo "is-valid"; ?>" 
                                    required 
                                    <?php if ($intento_fallido) echo "value='" . $_POST['fecha']  . "'" ?>
                                    >
                                </div>
                                
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="medico_envia_inpt_paciente">Medico que lo Envía</label>
                                <input 
                                    id="medico_envia_inpt_paciente" 
                                    name="medico_envia" 
                                    type="text" 
                                    class="form-control" 
                                    maxlength="50"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['medico_envia']  . "'" ?>
                                    >
                            </div>
                        </div>
                        <div class="form-row row justify-content-center">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="perso_resp_inpt_paciente">Persona Responsable</label>
                                <input 
                                    id="perso_resp_inpt_paciente" 
                                    name="persona_responsable" 
                                    type="text" 
                                    class="form-control" 
                                    maxlength="50"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['persona_responsable']  . "'" ?>
                                    >
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="doc_poliza_inpt_paciente">Documento de póliza</label>
                                <input 
                                    id="doc_poliza_inpt_paciente" 
                                    name="documento_poliza" 
                                    type="file" 
                                    class="form-control" 
                                    accept="image/*,.pdf"
                                    >
                            </div>
                        </div>
                        <div class="form-row row justify-content-center">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="doc_ant_inpt_paciente">Documento de antecedentes</label>
                                <input 
                                    id="doc_ant_inpt_paciente" 
                                    name="documento_antecedentes" 
                                    type="file" 
                                    class="form-control" 
                                    accept="image/*,.pdf"
                                    >
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="doc_presupuesto_inpt_paciente">Documento de presupuesto</label>
                                <input 
                                    id="doc_presupuesto_inpt_paciente" 
                                    name="documento_presupuesto" 
                                    type="file" 
                                    class="form-control" 
                                    accept="image/*,.pdf"
                                    >
                            </div>
                        </div>
                        <div class="form-row row justify-content-center">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="rfc_inpt_paciente">RFC</label>
                                <input 
                                    id="rfc_inpt_paciente" 
                                    name="rfc" 
                                    type="text" 
                                    class="form-control" 
                                    maxlength="15"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['rfc']  . "'" ?>
                                    >
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="cp_inpt_paciente">Código Postal</label>
                                <input 
                                    id="cp_inpt_paciente" 
                                    name="codigo_postal" 
                                    type="text" 
                                    class="form-control"
                                    <?php if ($intento_fallido) echo "value='" . $_POST['codigo_postal']  . "'" ?> 
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
            echo "<script>
            {$alerta->activar_sweet_alert()}
            </script>";
        }
    ?>
</body> 
</html>
