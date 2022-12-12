<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $comentarios = $_POST['observaciones'];
        $IdCita = $_POST['IdCita'];

        $query = "INSERT INTO Tb_Comentario VALUES (1, '" . $comentarios . "', " . $IdCita . ");";
        include("includes/funciones_BD.php");
        $conexion = crear_conexion();
        $res = mysqli_query($conexion,$query);
        if(isset($res)){
            $alerta = new Alerta("Comentarios registrados con éxito");
        }
    }
?>
<form method="POST" class="modal-content">
    <input id="rol" class="visually-hidden" value="Tb_Doctor"/>
    <input id="IdCita" name="IdCita" class="visually-hidden" value=""/>
    <div class="modal-header bg-custom-primary">
        <h4 class="modal-title text-white fw-bold" id="infoCitaLabel">Historial Clínico</h4>
        <button type="button" class="btn btn-close btn-danger bg-danger text-white" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body row">
        <div class="col-12">
            <h6 class="text-dark">Nombre del paciente</h6>
            <div class="input-group mb-3">
                <input id="nombrePaciente" type="text" class="form-control" placeholder="" disabled>
                <span class="input-group-text" id="basic-addon2">Paciente</span>
            </div>
        </div>
        <div class="col-12 pb-4">
            <label for="sexo_inpt_paciente"><b>*</b>Sexo</label>
            <input id="IdSexo" type="text" class="form-control" placeholder="Sexo" readonly>
        </div>
        <div class="form-row row justify-content-center w-100">
            <div class="form-group col-xl-6 col-md-12 pb-4">
                <label for="fecha_inpt_paciente"><b>*</b>Fecha de nacimiento</label>
                <input id="FechaNacimiento" type="date" class="form-control" readonly>
            </div>
            <div class="form-group col-xl-6 col-md-12 pb-4">
                <label for="medico_envia_inpt_paciente">Medico que lo Envía</label>
                <input id="MedicoEnvia" type="text" class="form-control" readonly>
            </div>
        </div>
        <div class="form-group col-12 pb-4" id="container-Archivo">
            <label for="doc_poliza_inpt_paciente">Documento de póliza</label>
            <input type="file" class="form-control" accept="image/*,.pdf" readonly disabled>
            <iframe id="Archivo" class="visually-hidden" src="" style="width:100%; height:500px;" frameborder="0" ></iframe>
        </div>
        <div class="form-group col-12 pb-4" id="container-ArchivoAntecedentes">
            <label for="doc_ant_inpt_paciente">Documento de antecedentes</label>
            <input type="file" class="form-control" accept="image/*,.pdf" readonly disabled>
            <iframe id="ArchivoAntecedentes" class="visually-hidden" src="" style="width:100%; height:500px;" frameborder="0" ></iframe>
        </div>
        <div class="form-group col-12 pb-4" id="container-ArchivoPresupuesto">
            <label for="doc_presupuesto_inpt_paciente">Documento de presupuesto</label>
            <input type="file" class="form-control" accept="image/*,.pdf" readonly disabled>
            <iframe id="ArchivoPresupuesto" class="visually-hidden" src="" style="width:100%; height:500px;" frameborder="0" ></iframe>
        </div>
        <div class="col-12 mt-2">
            <h6 class="text-dark">Tratamiento a realizar</h6>
            <div class="form-floating">
                <textarea id="tratamiento" class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px" disabled></textarea>
                <label for="floatingTextarea2">Comentarios</label>
            </div>
        </div>
        <div class="col-6 mt-2">
            <h6 class="text-dark">Fecha</h6>
            <div class="input-group mb-3">
                <input id="fechaCita" type="date" class="form-control" placeholder="" disabled>
                <span class="input-group-text" id="basic-addon2">Fecha</span>
            </div>
        </div>
        <div class="col-6 mt-2">
            <h6 class="text-dark">Costo de la cita</h6>
            <div class="input-group mb-3">
                <input id="costoCita" type="number" class="form-control" placeholder="Costo en MXN" disabled>
                <span class="input-group-text" id="basic-addon2">.00</span>
            </div>
        </div>
        <div class="col-6 mt-2">
            <h6 class="text-dark">Hora inicio</h6>
            <div class="input-group mb-3">
                <input id="horaInicio" type="time" class="form-control" disabled/>
                <span class="input-group-text" id="basic-addon2">Hora</span>
            </div>
        </div>
        <div class="col-6 mt-2">
            <h6 class="text-dark">Hora final</h6>
            <div class="input-group mb-3">
                <input id="horaFinal" type="time" class="form-control" placeholder="" disabled/>
                <span class="input-group-text" id="basic-addon2">Hora</span>
            </div>
            <input type="hidden" id="id-cita" value="<?php echo $valor?>">
        </div>
        <div class="col-12 mt-2">
            <h6 class="text-dark">Observaciones de la cita</h6>
            <div class="form-floating">
                <textarea required name="observaciones" id="observaciones" class="form-control" placeholder="Recomendaciones para el paciente, medicamentos, etc." style="height: 100px"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer d-flex">
        <div class="col-3 justify-content-start m-0 d-flex">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
        <div class="col-9 justify-content-end m-0 d-flex"> 
            <button type="submit" class="btn btn-success mx-2" id="registrar-comentarios">Registrar Observaciones</button>
        </div>
    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript">
    
</script>