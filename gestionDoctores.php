<?php
    require_once('utils/sessionCheck.php');
    if(!comprobar_sesion_y_rol("Tb_Admin")){
        header('location: login.php');
    }
    require_once('includes/includes.php');
    $alertas = [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Doctores</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <!-- JavaScript Bundle with Popper -->
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
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
                <h1 class="text-center">Gestión de Doctores</h1>
                <a href="./Registrardoctor.php"><button class="btn btn-success w-40 m-1">Agregar</button></a>
            </div>
            <div class="card mb-4 w-100">
                <div class="card-header">
                    Doctores
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
                                $query = "SELECT Tb_Doctor.IdDoctor as IdDoctor,Tb_Doctor.Nombre as Nombre, Tb_Doctor.APaterno as APaterno, Tb_Doctor.Email as Email, Tb_Status.Descripcion as Status FROM Tb_Doctor, Tb_Status 
                                WHERE Tb_Doctor.IdStatus = Tb_Status.IdStatus;";
                                $res = mysqli_query($conexion,$query);
                                $conexion->close();
                                while($fila = mysqli_fetch_array($res))
                                {
                                    $al = new Alerta("¿Deseas eliminar a este Doctor?");
                                    $al->setOpcion('icon',"'error'");
                                    $al->setOpcion("confirmButtonColor","'#dc3545'");
                                    $al->setOpcion("confirmButtonText","'Eliminar'");
                                    $al->setOpcion('showCancelButton',"true");
                                    $al->setOpcion('cancelButtonText',"'Cancelar'");
                                    $al->setEscuchar("#sweet-eliminar-{$fila['IdDoctor']}");
                                    $al->setThen("
                                        if(res.isConfirmed){
                                            $.ajax({
                                                type: 'POST',
                                                url: 'includes/ajax/eliminarDoctor.php',
                                                data: { id: '{$fila['IdDoctor']}' },
                                                dataType: 'json',
                                                success: function (data) {
                                                  if (data.response === 'success') {
                                                    Swal.fire(data.message, '', 'success')
                                                    .then(e => {
                                                        location.reload();
                                                    })
                                                  } else if (data.response === 'invalid') {
                                                    Swal.fire(data.message, '', 'error');
                                                  }
                                                },
                                                error: function (xhr, exception) {
                                                  console.log('error', xhr);
                                                },
                                              });
                                        }
                                    ");
                                    array_push($alertas, $al);
                            ?>
                                <tr>
                                    <td><?php echo $fila['Nombre'];?></td>
                                    <td><?php echo $fila['APaterno'];?></td>
                                    <td><?php echo $fila['Email'];?></td>
                                    <td><?php echo $fila['Status'];?></td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-danger w-40 m-1 btn-eliminar-doct" id='<?php echo "sweet-eliminar-{$fila['IdDoctor']}"?>'>Eliminar</button>
                                            <a class="btn btn-warning w-40 m-1" href="modificarDoctor.php?id=<?php echo $fila['IdDoctor'];?>">Modificar</a>
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
        </div>
    </main>
    <footer>
    </footer>
    <!-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/datatables.js"></script>
    <script src="js/systemFunctions.js"></script>

    <?php
        $imprimir =  "<script> 
        document.addEventListener('DOMContentLoaded', function() {
        ";
        foreach ($alertas as $al) {
            $imprimir .= "
            {$al->activar_sweet_alert()}
            ";
        }
        $imprimir .= "
        })
        </script>";
        echo $imprimir;
    ?>
</body>
</html>