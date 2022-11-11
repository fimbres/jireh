<?php
    require_once('utils/sessionCheck.php');
    if(!comprobar_sesion_y_rol("Tb_Recepcionista")){
        header('location: login.php');
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pacientes</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <!-- JavaScript Bundle with Popper -->
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet" />
</head>
<body>
    <main class="d-flex flex-wrap flex-row">
        <!--COMPONENTE MENU-->
        <?php
            require_once('components/menu.php');
        ?>

        <!--CONTENEDOR ALMACENAR CONTENIDO OTRAS PAGINAS-->
        <div id="displayActions" class="d-flex bg-white p-4 flex-column">
            <div class="d-flex flex-row justify-content-between" style="margin-bottom: 60px; margin-top: 30px;">
                <h1 class="text-center">Gestión de Pacientes</h1>
                <a href="./RegistrarPaciente.php"><button class="btn btn-success w-40 m-1">Agregar</button></a>
            </div>
            <div id="tablaPacientes" class="card mb-4 w-100">
                <div class="card-header">
                    Pacientes
                </div>
                <div class="card-body">
                    <table 
                        id="datatablesSimple"
                        class="display nowrap"
                        style="width:100%"
                    >
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido Paterno</th>
                                <th>Correo</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido Paterno</th>
                                <th>Correo</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                                include("includes/funciones_BD.php");
                                $conexion = crear_conexion();
                                $query = "SELECT Tb_Paciente.IdPaciente as IdPaciente, Tb_Paciente.Nombre as Nombre, Tb_Paciente.APaterno as APaterno, Tb_Paciente.Email as Email, Tb_Status.Descripcion as Status FROM Tb_Paciente, Tb_Status WHERE Tb_Paciente.IdStatus = Tb_Status.IdStatus;";
                                $res = mysqli_query($conexion,$query);
                                $conexion->close();
                                while($fila = mysqli_fetch_array($res))
                                {
                            ?>
                                <tr>
                                    <td><?php echo $fila['Nombre'];?></td>
                                    <td><?php echo $fila['APaterno'];?></td>
                                    <td><?php echo $fila['Email'];?></td>
                                    <td><?php echo $fila['Status'];?></td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <?php if($fila['Status'] == 'Activo'): ?>
                                                <input type="button" class="btn btn-danger w-40 m-1"  onclick="tablaPacientesAcciones(<?php echo $fila['IdPaciente'];?>,this.value,this);" value="Eliminar"/>
                                            <?php elseif($fila['Status'] == 'Inactivo'): ?>
                                                <input type="button" class="btn btn-danger w-40 m-1"  onclick="tablaPacientesAcciones(<?php echo $fila['IdPaciente'];?>,this.value,this);" value="Eliminar" disabled/>
                                            <?php endif;?>
                                            <input type="button" class="btn btn-warning w-40 m-1" onclick="tablaPacientesAcciones(<?php echo $fila['IdPaciente'];?>,this.value,0);" value="Modificar"/>
                                            <input type="button" class="btn btn-primary m-1" onclick="tablaPacientesAcciones(<?php echo $fila['IdPaciente'];?>,this.value,0);" value="Ver">
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="containerTableActions" class="visually-hidden">
                
            </div>
        </div>
    </main>
    <footer>
    </footer>
    <!-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script> -->
    <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/datatables.js"></script>
    <script src="js/systemFunctions.js"></script>
    <script src="js/gestionPacientes.js"></script> 
</body>
</html>