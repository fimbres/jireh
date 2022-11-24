<?php 
require_once('utils/sessionCheck.php');
if(!comprobar_sesion_y_rol("Tb_Recepcionista")){
    header('location: login.php');
}
//"nombre" / "apellido_p" / "apellido_m" / "telefono" / "correo" 
//Librerias
if(empty($_GET['id'])){
    header('location: index.php');
}
require_once("includes/includes.php");
// echo $_GET['id'];
$BD = new BaseDeDatos();
$id_cita = $_GET['id'];
$cita = Citas::crear_Cita($_GET['id'],$BD);
if(!$cita ){
    $BD->close();
    header('location: index.php');
}
 $intento_fallido = false;
 $mensaje=[];
 $alerta = false;
 if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //$mod = ($cita->getIdPaciente() == $_POST['paciente'] ? "modificar_cita_igual" :"modificar");
    $mensaje = Citas::verificar_datos_formulario2($_POST,$BD);
    if(!$mensaje){
        $res = $cita->modificar_BD($_POST,$BD);
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
    <title>Modificar Cita</title>
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
                    <h1 class="text-center">Modificar Cita</h1>
                </div>
                <div class="col-12 d-flex justify-content-center pt-5">
                <form method="POST" class="form d-flex row col-xl-8 col-md-12 justify-content-center formulario-registrar-recepcionista">
                        <div class="form-row row">
                            <div class="form-group col-xl-12 col-md-12 pb-4">
                                <label for="tbNombre">*Elige un paciente</label><br>
                                <select id="tbNombre" name="paciente" class="form-select form-select-lg mb-3
                                <?php if(isset($mensaje) &&in_array("Nombre",$mensaje)) echo "is-invalid";else if($intento_fallido) echo "is-valid";?>" aria-label=".form-select-lg example">
                                <?php
                                        include("includes/funciones_BD.php");
                                        $conexion = crear_conexion();
                                        $query = "SELECT tc.IdPaciente, CONCAT(tp.Nombre,' ',tp.APaterno,' ',tp.AMaterno) AS NombreP, tp.IdStatus FROM (Tb_Cita tc
                                        INNER JOIN Tb_Paciente tp ON tc.IdPaciente = tp.IdPaciente) WHERE tp.IdStatus = 3 AND tc.IdCita=".$id_cita;
                                        $res = mysqli_query($conexion,$query);
                                        $datos = mysqli_fetch_array($res)
                                    ?>
                                <option value="<?php echo $datos['IdPaciente']?>"><?php echo $datos['NombreP']?></option>
                                    <?php
                                        $query = "SELECT IdPaciente, CONCAT(Nombre,' ',APaterno,' ',AMaterno) AS NombreP FROM Tb_Paciente WHERE IdStatus = 3;";
                                        $res = mysqli_query($conexion,$query);
                                        while($datos = mysqli_fetch_array($res))
                                        {
                                    ?>
                                        <option value="<?php echo $datos['IdPaciente']?>"><?php echo $datos['NombreP']?></options>
                                    <?php
                                        }
                                    ?>

                                </select>
                                
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-12 col-md-12 pb-4">
                                <label for="taTratamiento">Tratamiento</label>
                                <!--<?php
                                        //$query = "SELECT * FROM Tb_Cita WHERE IdCita =". $id_cita;
                                        //$res = mysqli_query($conexion,$query);
                                        //$datos = mysqli_fetch_array($res)
                                    ?> -->
                                <input 
                                    id="taTratamiento" 
                                    name="tratamiento"  
                                    class="form-control
                                    <?php if(isset($mensaje) &&in_array("Tratamiento",$mensaje)) echo "is-invalid";else if($intento_fallido) echo "is-valid";?>" 
                                    aria-label="With textarea"
                                    placeholder="Describa el tratamiento" 
                                    required 
                                    <?php echo "value='{$cita->getTratamiento()}'"?>>
                                    </input>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-12 col-md-12 pb-4">
                                <label for="sDoctor">*Elige un doctor</label><br>
                                <select id="sDoctor" name="doctor" class="form-select form-select-lg mb-3
                                <?php if(isset($mensaje) &&in_array("Doctor",$mensaje)) echo "is-invalid";else if($intento_fallido) echo "is-valid";?>" 
                                require
                                <?php if($intento_fallido) echo "value'".$_POST['doctor'] ."'"?>>
                                <?php
                                        $query = "SELECT tc.IdDoctor, CONCAT(td.Nombre,' ',td.APaterno) AS NombreD, td.IdStatus FROM (Tb_Cita tc
                                        INNER JOIN Tb_Doctor td ON tc.IdDoctor = td.IdDoctor) WHERE td.IdStatus = 3 AND tc.IdCita=".$id_cita;
                                        $res = mysqli_query($conexion,$query);
                                        $datos = mysqli_fetch_array($res)
                                    ?>
                                <option value="<?php echo $datos['IdDoctor']?>"><?php echo $datos['NombreD']?></option>
                                    <?php
                                        $query = "SELECT IdDoctor, CONCAT(Nombre,' ',APaterno) AS NombreD FROM Tb_Doctor WHERE IdStatus = 3;";
                                        $res = mysqli_query($conexion,$query);
                                        while($datos = mysqli_fetch_array($res))
                                        {
                                    ?>
                                        <option value="<?php echo $datos['IdDoctor']?>"><?php echo $datos['NombreD']?></options>
                                    <?php
                                        }
                                        
                                    ?>

                                </select>
                                
                            </div>
                        </div>
                        <div class="form-row row">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                <label for="tbfecha">Fecha</label>
                                <?php
                                        $query = "SELECT CAST(FechaInicio as DATE) as Fecha, Costo FROM Tb_Cita WHERE IdCita =". $id_cita;
                                        $res = mysqli_query($conexion,$query);
                                        $datos = mysqli_fetch_array($res)
                                    ?>
                                <input 
                                    id="tbfecha" 
                                    name="fecha" 
                                    type="date" 
                                    class="form-control
                                    <?php if(isset($mensaje) &&in_array("Fecha",$mensaje)) echo "is-invalid";else if($intento_fallido) echo "is-valid";?>" 
                                    placeholder="mm/dd/yy" 
                                    required 
                                    maxlength="50"
                                    <?php echo "value='{$datos['Fecha']}'"?>
                                    >
                            </div>
                            <div class="input-group col-xl-6 col-md-6 pb-4 w-50">
                                <label for="tbCosto">Costo de la cita</label>&nbsp;
                                    <div id="tbCosto" class="input-group col-xl-6 col-md-6 pb-4 w-75">
                                        <div class="input-group col-xl-6 col-md-6 pb-4 w-50"><span class="input-group-text">$</span>
                                        <input  name="costo" 
                                            type="text" 
                                            class="form-control
                                            <?php if(isset($mensaje) &&in_array("Costo",$mensaje)) echo "is-invalid";else if($intento_fallido) echo "is-valid";?> " 
                                            onkeypress="return /[0-9.]/i.test(event.key)" aria-label="Amount (to the nearest dollar)"
                                            <?php echo "value='{$datos['Costo']}'"?>
                                        >
                                    </div>
                            </div>
                        </div>
                        <div class="form-row row justify-content-center">
                            <div class="form-group col-xl-6 col-md-12 pb-4">
                                    <label for="tbHorainicio">Hora Inicio</label>
                                    <?php
                                        $query = "SELECT CAST(FechaInicio as TIME) as horaI, CAST(FechaFinal as TIME) as horaIF FROM Tb_Cita WHERE IdCita =". $id_cita;
                                        $res = mysqli_query($conexion,$query);
                                        $datos = mysqli_fetch_array($res)
                                    ?>
                                    <input 
                                        id="tbHorainicio" 
                                        name="horainicio" 
                                        type="time" 
                                        class="form-control
                                        <?php if(isset($mensaje) &&in_array("Hora",$mensaje)) echo "is-invalid";else if($intento_fallido) echo "is-valid";?>" 
                                        placeholder="--:--" 
                                        required 
                                        <?php echo "value='{$datos['horaI']}'"?>
                                        >
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
                                        <?php echo "value='{$datos['horaIF']}'"?>
                                        >
                                </div>
                            </div>
                        </div>
                        <div class="form-row row justify-content-center pt-3">
                            <button type="submit" class="btn btn-primary mx-3 col-md-3 col-5">Actualizar</button>
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