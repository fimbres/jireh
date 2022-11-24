<?php 
require_once('utils/sessionCheck.php');
if(!comprobar_sesion_y_rol("Tb_Recepcionista")){
    header('location: login.php');
}
//"nombre" / "apellido_p" / "apellido_m" / "telefono" / "correo" 
//Librerias
 include("includes/includes.php");

 $intento_fallido = false;
 $mensaje=[];
 $alerta = false;
 if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //echo $_POST['paciente'];
    $BD = new BaseDeDatos();
    $mensaje = Citas::verificar_datos_formulario($_POST,$BD);
    if(!$mensaje){
        $cita = new Citas($_POST);
        $res = $cita->agregar_BD($BD);
        $intento_fallido = !$res[0];
        if($res[0]){
            $alerta = new Alerta($res[1],[],[],'./index.php');
        } else{
            $alerta = new Alerta("Error",[$res[1]]);
            $alerta->setOpcion('icon',"'error'");
            $alerta->setOpcion("confirmButtonColor","'#dc3545'");
        }
        $BD->close();
    }else {
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
    <title>Agendar Cita</title>
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
                    <h1 class="text-center">Agendar Cita</h1>
                </div>
                <div class="col-12 d-flex justify-content-center pt-5">
                <form method="POST" class="form d-flex row col-xl-8 col-md-12 justify-content-center formulario-registrar-recepcionista">
                        <div class="form-row row">
                            <div class="form-group col-xl-12 col-md-12 pb-4">
                                <label for="tbNombre">*Elige un paciente</label><br>
                                <select id="tbNombre" name="paciente" class="form-select 
                                <?php if(isset($mensaje) &&in_array("Nombre",$mensaje)) echo "is-invalid";else if($intento_fallido) echo "is-valid";?>" aria-label=".form-select-lg example"
                                required>
                                    <option value="">Eliga un paciente</option>
                                    <?php
                                        include("includes/funciones_BD.php");
                                        $conexion = crear_conexion();
                                        $query = "SELECT IdPaciente, CONCAT(Nombre,' ',APaterno,' ',AMaterno) AS NombreP FROM Tb_Paciente WHERE IdStatus = 3;";
                                        $res = mysqli_query($conexion,$query);
                                        while($datos = mysqli_fetch_array($res))
                                        {
                                    ?>
                                        <option <?php if($intento_fallido && $_POST['paciente'] == $datos['IdPaciente']) echo "selected"?> value="<?php echo $datos['IdPaciente'];?>"><?php echo $datos['NombreP']?></options>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-12 col-md-12 pb-4">
                                <label for="taTratamiento">Tratamiento</label>
                                <textarea 
                                    id="taTratamiento" 
                                    name="tratamiento"  
                                    class="form-control 
                                    <?php if(isset($mensaje) &&in_array("Tratamiento",$mensaje)) echo "is-invalid";else if($intento_fallido) echo "is-valid";?>" 
                                    aria-label="With textarea"
                                    placeholder="Describa el tratamiento" 
                                    required 
                                    ><?php if($intento_fallido) echo $_POST['tratamiento']?></textarea>
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-12 col-md-12 pb-4">
                                <label for="sDoctor">*Elige un doctor</label><br>
                                <select id="sDoctor" name="doctor" class="form-select <?php if(isset($mensaje) &&in_array("Doctor",$mensaje)) echo "is-invalid";else if($intento_fallido) echo "is-valid";?>" required>
                                    <option value="">Eliga un doctor</option>
                                    <?php
                                        $query = "SELECT IdDoctor, CONCAT(Nombre,' ',APaterno) AS NombreD FROM Tb_Doctor WHERE IdStatus = 3;";
                                        $res = mysqli_query($conexion,$query);
                                        while($datos = mysqli_fetch_array($res))
                                        {
                                    ?>
                                        <option <?php if($intento_fallido && $_POST['doctor'] == $datos['IdDoctor']) echo "selected"?> value="<?php echo $datos['IdDoctor']?>"><?php echo $datos['NombreD']?></options>
                                    <?php
                                        }
                                        
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="tbfecha">Fecha</label>
                                <input 
                                    id="tbfecha" 
                                    name="fecha" 
                                    type="date" 
                                    class="form-control
                                    <?php if(isset($mensaje) &&in_array("Fecha",$mensaje)) echo "is-invalid";else if($intento_fallido) echo "is-valid";?>" 
                                    placeholder="mm/dd/yy" 
                                    required 
                                    maxlength="50"
                                    <?php if($intento_fallido) echo "value='".$_POST['fecha'] ."'"?>
                                    >
                            </div>
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                    <label for="tbCosto">Costo de la cita</label>&nbsp;
                                    <input 
                                        name="costo"
                                        id="tbCosto"
                                        type="text" 
                                        placeholder="$0.00"
                                        required
                                        class="form-control
                                        <?php if(isset($mensaje) &&in_array("Costo",$mensaje)) echo "is-invalid";else if($intento_fallido) echo "is-valid";?> " 
                                        onkeypress="return /[0-9.]/i.test(event.key)" aria-label="Amount (to the nearest dollar)"
                                        <?php if($intento_fallido) echo "value='".$_POST['costo'] ."'"?>
                                    />
                                </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                    <label for="tbHorainicio">Hora Inicio</label>
                                    <input 
                                        id="tbHorainicio" 
                                        name="horainicio" 
                                        type="time" 
                                        class="form-control
                                        <?php if(isset($mensaje) &&in_array("Hora",$mensaje)) echo "is-invalid";else if($intento_fallido) echo "is-valid";?>" 
                                        placeholder="--:--" 
                                        required 
                                        <?php if($intento_fallido) echo "value='".$_POST['horainicio'] ."'"?>
                                        />
                                </div>
                                <div class="form-group col-xl-6 col-md-12 pb-4">
                                    <label for="tbHorafin">Hora Fin</label>
                                    <input 
                                        id="tbHorafin"
                                        name="horafin" 
                                        type="time" 
                                        class="form-control
                                        <?php if(isset($mensaje) &&in_array("HoraFinal",$mensaje)) echo "is-invalid";else if($intento_fallido) echo "is-valid";?>"  
                                        placeholder="--:--" 
                                        required 
                                        <?php if($intento_fallido) echo "value='".$_POST['horafin'] ."'"?>
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