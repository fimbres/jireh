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
            <div class="card mb-4 w-100">
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
                                            <button class="btn btn-danger w-40 m-1 ">Eliminar</button>
                                            <button class="btn btn-warning w-40 m-1">Modificar</button>
                                            <button class="btn btn-primary m-1" onclick="mostrarInformacion(<?php echo $fila['IdPaciente'];?>);">Ver</button>
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
    <div class="modal fade show" id="modalExpediente" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Expediente del Paciente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-row row">
                        <div class="form-group col-xl-6 col-md-12 pb-4">
                            <label for="nombre_inpt_recepcionista"><b>*</b>Nombre(s)</label>
                            <input 
                                id="Nombre"
                                type="text" 
                                class="form-control text-capitalize"
                                placeholder="Nombre(s)"
                                readonly
                            >
                        </div>
                        <div class="form-group col-xl-6 col-md-12 pb-4">
                            <label for="apellido_pat_inpt_recepcionista"><b>*</b>Apellido Paterno</label>
                            <input
                                id="APaterno"
                                type="text" 
                                class="form-control text-capitalize"
                                placeholder="Apellido Paterno"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="form-group col-xl-6 col-md-12 pb-4">
                            <label for="apellido_mat_inpt_recepcionista">Apellido Materno</label>
                            <input
                                id="AMaterno"
                                type="text" 
                                class="form-control text-capitalize" 
                                placeholder="Apellido Materno"
                                readonly
                            >
                        </div>
                        <div class="form-group col-xl-6 col-md-12 pb-4">
                            <label for="sexo_inpt_paciente"><b>*</b>Sexo</label>
                            <input
                                id="IdSexo"
                                type="text" 
                                class="form-control"
                                placeholder="Sexo"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="form-group col-12 pb-4">
                            <label for="telefono_inpt_recepcionista"><b>*</b>Dirección</label>
                            <input 
                                id="Direccion"
                                type="text"
                                class="form-control text-capitalize"
                                placeholder="Direccion"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="form-group col-xl-6 col-md-12 pb-4">
                            <label for="telefono_inpt_recepcionista"><b>*</b>Teléfono</label>
                            <input
                                id="NumTelefono"
                                type="tel" 
                                class="form-control"
                                placeholder="(+52) 646-117-6388"
                                readonly
                            >
                        </div>
                        <div class="form-group col-xl-6 col-md-12 pb-4">
                            <label for="correo_inpt_recepcionista"><b>*</b>Correo electrónico</label>
                            <input
                                id="Email"
                                type="email" 
                                class="form-control"
                                placeholder="ejemplo@jireh.com"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-row row justify-content-center">
                        <div class="form-group col-xl-6 col-md-12 pb-4">
                            <label for="fecha_inpt_paciente"><b>*</b>Fecha de nacimiento</label>
                            <input 
                                id="FechaNacimiento"
                                type="date" 
                                class="form-control"
                                readonly
                            >
                        </div>
                        <div class="form-group col-xl-6 col-md-12 pb-4">
                            <label for="medico_envia_inpt_paciente">Medico que lo Envía</label>
                            <input 
                                id="MedicoEnvia"
                                type="text" 
                                class="form-control"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-row row justify-content-center">
                        <div class="form-group col-12 pb-4">
                            <label for="perso_resp_inpt_paciente">Persona Responsable</label>
                            <input
                                id="Representante"
                                type="text" 
                                class="form-control"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-row row justify-content-center">
                        <div class="form-group col-xl-6 col-md-12 pb-4">
                            <label for="rfc_inpt_paciente">RFC</label>
                            <input
                                id="RFC"
                                type="text" 
                                class="form-control"
                                readonly
                            >
                        </div>
                        <div class="form-group col-xl-6 col-md-12 pb-4">
                            <label for="cp_inpt_paciente">Código Postal</label>
                            <input
                                id="CodigoPostal"
                                type="text" 
                                class="form-control"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group col-12 pb-4">
                        <label for="doc_poliza_inpt_paciente">Documento de póliza</label>
                        <input
                            type="file" 
                            class="form-control" 
                            accept="image/*,.pdf"
                            readonly
                        >
                    </div>
                    <div class="form-row row justify-content-center">
                        <div class="form-group col-xl-6 col-md-12 pb-4">
                            <label for="doc_ant_inpt_paciente">Documento de antecedentes</label>
                            <input
                                type="file" 
                                class="form-control" 
                                accept="image/*,.pdf"
                                readonly
                            >
                        </div>
                        <div class="form-group col-xl-6 col-md-12 pb-4">
                            <label for="doc_presupuesto_inpt_paciente">Documento de presupuesto</label>
                            <input
                                type="file" 
                                class="form-control" 
                                accept="image/*,.pdf"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
    </footer>
    <!-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script> -->
    <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables.js"></script>
    <script src="js/systemFunctions.js"></script>
    <script>
        function mostrarInformacion(id){
            $.ajax({
                type: "POST",
                url: "utils/getExpediente.php",
                data: { pacienteId: id },
                dataType: 'json',
                success: function(data){
                    for(let item in data){
                        let fieldValue;

                        switch(item){
                            case 'IdSexo': fieldValue = data[item] === "1" ? "Masculino" : "Femenino"; break;
                            default: fieldValue = data[item]; break;
                        }
                        
                        let formField = document.getElementById(item);

                        if(formField && fieldValue){
                            formField.setAttribute('value', fieldValue);
                        }
                    }
                    $('#modalExpediente').modal('show');
                },
                error: function (xhr, exception) {
                    console.log("error", xhr);
                }
            });
        }
    </script>
</body>
</html>