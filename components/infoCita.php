<div class="modal-content">
    <div class="modal-header bg-custom-primary">
        <h4 class="modal-title text-white fw-bold" id="infoCitaLabel">Información de la cita</h4>
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
        <div class="col-12 mt-2">
            <h6 class="text-dark">Tratamiento a realizar</h6>
            <div class="form-floating">
                <textarea id="tratamiento" class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px" disabled></textarea>
                <label for="floatingTextarea2">Comentarios</label>
            </div>
        </div>
        <div class="col-12 mt-3">
            <h6 class="text-dark">Nombre del doctor</h6>
            <div class="input-group mb-3">
                <input id="nombreDoctor" type="text" class="form-control" placeholder="" disabled>
                <span class="input-group-text" id="basic-addon2">Doctor</span>
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
    </div>
    <div class="modal-footer d-flex">
        <div class="col-3 justify-content-start m-0 d-flex">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
        <div class="col-9 justify-content-end m-0 d-flex"> 
            <a class="btn btn-danger mx-2" id="cancelar-modal" href="CancelarAgenda.php">Cancelar</a>
            <a class="btn btn-warning mx-2" id="editar-modal" href="ModificarAgenda.php">Modificar</a>
        </div>
    </div>
</div>