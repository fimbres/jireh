<form id="formModificarPaciente" method="POST" class="">
    <h3 class="pt-2 pb-2">Modificar paciente</h3>
    <div class="form-row row">
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="nombre_inpt_recepcionista"><b>*</b>Nombre(s)</label>
            <input id="Nombre" type="text" class="form-control text-capitalize formModificarInput" placeholder="Nombre(s)">
        </div>
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="apellido_pat_inpt_recepcionista"><b>*</b>Apellido Paterno</label>
            <input id="APaterno" type="text" class="form-control text-capitalize formModificarInput" placeholder="Apellido Paterno">
        </div>
    </div>
    <div class="form-row row">
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="apellido_mat_inpt_recepcionista">Apellido Materno</label>
            <input id="AMaterno" type="text" class="form-control text-capitalize" placeholder="Apellido Materno">
        </div>
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="sexo_inpt_paciente"><b>*</b>Sexo</label>
            <input id="IdSexo" type="text" class="form-control formModificarInput" placeholder="Sexo">
        </div>
    </div>
    <div class="form-row row">
        <div class="form-group col-12 pb-4">
            <label for="telefono_inpt_recepcionista"><b>*</b>Dirección</label>
            <input id="Direccion" type="text" class="form-control text-capitalize formModificarInput" placeholder="Direccion">
        </div>
    </div>
    <div class="form-row row">
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="telefono_inpt_recepcionista"><b>*</b>Teléfono</label>
            <input id="NumTelefono" type="tel" class="form-control formModificarInput" placeholder="(+52) 646-117-6388">
        </div>
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="correo_inpt_recepcionista"><b>*</b>Correo electrónico</label>
            <input id="Email" type="email" class="form-control formModificarInput" placeholder="ejemplo@jireh.com">
        </div>
    </div>
    <div class="form-row row justify-content-center">
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="fecha_inpt_paciente"><b>*</b>Fecha de nacimiento</label>
            <input id="FechaNacimiento" type="date" class="form-control formModificarInput">
        </div>
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="medico_envia_inpt_paciente">Medico que lo Envía</label>
            <input id="MedicoEnvia" type="text" class="form-control formModificarInput">
        </div>
    </div>
    <div class="form-row row justify-content-center">
        <div class="form-group col-12 pb-4">
            <label for="perso_resp_inpt_paciente">Persona Responsable</label>
            <input id="Representante" type="text" class="form-control formModificarInput">
        </div>
    </div>
    <div class="form-row row justify-content-center">
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="rfc_inpt_paciente">RFC</label>
            <input id="RFC" type="text" class="form-control formModificarInput">
        </div>
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="cp_inpt_paciente">Código Postal</label>
            <input id="CodigoPostal" type="text" class="form-control formModificarInput">
        </div>
    </div>
    <div class="form-group col-12 pb-4">
        <label for="doc_poliza_inpt_paciente">Documento de póliza</label>
        <input type="file" class="form-control formModificarInput" accept="image/*,.pdf">
    </div>
    <div class="form-row row justify-content-center">
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="doc_ant_inpt_paciente">Documento de antecedentes</label>
            <input type="file" class="form-control formModificarInput" accept="image/*,.pdf">
        </div>
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="doc_presupuesto_inpt_paciente">Documento de presupuesto</label>
            <input type="file" class="form-control formModificarInput" accept="image/*,.pdf">
        </div>
    </div>
    <div class="form-row row justify-content-center">
        <div class="form-group col-6 pb-4 d-flex justify-content-start align-items-center flex-wrap">
            <label for="doc_presupuesto_inpt_paciente">Estado</label>
            <select id="IdStatus" class="form-select formModificarInput" aria-label="Default select example">
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
            </select>
        </div>
    </div>
    <div class="form-row row justify-content-center">
        <div class="form-group col-12 pb-4 d-flex justify-content-center align-items-center">
            <input id="btnUpdateSendData" onclick="actualizarInfoPaciente();" type="button" value="Actualizar" class="btn btn-primary ms-3 me-3">
            <input id="btnCancelUpdate" onclick="cancelarActualizarInfo();" type="button" value="Cancelar" class="btn btn-danger ms-3 me-3">
        </div>
    </div>
</form>