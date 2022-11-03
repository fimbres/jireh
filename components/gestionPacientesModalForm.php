<div class="modal fade show" id="modalExpediente" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Expediente del Paciente</h5>
                <button id="btnCloseModal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-row row">
                    <div class="form-group col-xl-6 col-md-12 pb-4">
                        <label for="nombre_inpt_recepcionista"><b>*</b>Nombre(s)</label>
                        <input id="Nombre" type="text" class="form-control text-capitalize" placeholder="Nombre(s)" readonly>
                    </div>
                    <div class="form-group col-xl-6 col-md-12 pb-4">
                        <label for="apellido_pat_inpt_recepcionista"><b>*</b>Apellido Paterno</label>
                        <input id="APaterno" type="text" class="form-control text-capitalize" placeholder="Apellido Paterno" readonly>
                    </div>
                </div>
                <div class="form-row row">
                    <div class="form-group col-xl-6 col-md-12 pb-4">
                        <label for="apellido_mat_inpt_recepcionista">Apellido Materno</label>
                        <input id="AMaterno" type="text" class="form-control text-capitalize" placeholder="Apellido Materno" readonly>
                    </div>
                    <div class="form-group col-xl-6 col-md-12 pb-4">
                        <label for="sexo_inpt_paciente"><b>*</b>Sexo</label>
                        <input id="IdSexo" type="text" class="form-control" placeholder="Sexo" readonly>
                    </div>
                </div>
                <div class="form-row row">
                    <div class="form-group col-12 pb-4">
                        <label for="telefono_inpt_recepcionista"><b>*</b>Dirección</label>
                        <input id="Direccion" type="text" class="form-control text-capitalize" placeholder="Direccion" readonly>
                    </div>
                </div>
                <div class="form-row row">
                    <div class="form-group col-xl-6 col-md-12 pb-4">
                        <label for="telefono_inpt_recepcionista"><b>*</b>Teléfono</label>
                        <input id="NumTelefono" type="tel" class="form-control" placeholder="(+52) 646-117-6388" readonly>
                    </div>
                    <div class="form-group col-xl-6 col-md-12 pb-4">
                        <label for="correo_inpt_recepcionista"><b>*</b>Correo electrónico</label>
                        <input id="Email" type="email" class="form-control" placeholder="ejemplo@jireh.com" readonly>
                    </div>
                </div>
                <div class="form-row row justify-content-center">
                    <div class="form-group col-xl-6 col-md-12 pb-4">
                        <label for="fecha_inpt_paciente"><b>*</b>Fecha de nacimiento</label>
                        <input id="FechaNacimiento" type="date" class="form-control" readonly>
                    </div>
                    <div class="form-group col-xl-6 col-md-12 pb-4">
                        <label for="medico_envia_inpt_paciente">Medico que lo Envía</label>
                        <input id="MedicoEnvia" type="text" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-row row justify-content-center">
                    <div class="form-group col-12 pb-4">
                        <label for="perso_resp_inpt_paciente">Persona Responsable</label>
                        <input id="Representante" type="text" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-row row justify-content-center">
                    <div class="form-group col-xl-6 col-md-12 pb-4">
                        <label for="rfc_inpt_paciente">RFC</label>
                        <input id="RFC" type="text" class="form-control" readonly>
                    </div>
                    <div class="form-group col-xl-6 col-md-12 pb-4">
                        <label for="cp_inpt_paciente">Código Postal</label>
                        <input id="CodigoPostal" type="text" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group col-12 pb-4">
                    <label for="doc_poliza_inpt_paciente">Documento de póliza</label>
                    <input type="file" class="form-control" accept="image/*,.pdf" readonly>
                    <iframe id="Archivo" class="visually-hidden" src="" style="width:100%; height:500px;" frameborder="0" ></iframe>
                </div>
                <div class="form-row row justify-content-center">
                    <div class="form-group col-xxl-6 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 pb-4">
                        <label for="doc_ant_inpt_paciente">Documento de antecedentes</label>
                        <input type="file" class="form-control" accept="image/*,.pdf" readonly>
                        <iframe id="ArchivoAntecedentes" class="visually-hidden" src="" style="width:100%; height:500px;" frameborder="0" ></iframe>
                    </div>
                    <div class="form-group col-xxl-6 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 pb-4">
                        <label for="doc_presupuesto_inpt_paciente">Documento de presupuesto</label>
                        <input type="file" class="form-control" accept="image/*,.pdf" readonly>
                        <iframe id="ArchivoPresupuesto" class="visually-hidden" src="" style="width:100%; height:500px;" frameborder="0" ></iframe>
                    </div>
                </div>
                <div id="formBajaPaciente" class="form-row row justify-content-center visually-hidden">
                    <div class="form-group col-12 pb-4 d-flex justify-content-center align-items-center">
                        <input id="btnCancelar" type="button" class="btn btn-warning ms-3 me-2 text-white" value="Cancelar" data-bs-dismiss="modal"/>
                        <input id="btnEliminar" type="button" class="btn btn-danger ms-2 me-3" onclick="bajaPaciente()" value="Eliminar"/>
                    </div>
                </div>
            </div>
            <div class="modal-messages visually-hidden d-flex justify-content-start align-items-center flex-wrap">
                <p class="fw-bold fs-4 pt-4 ps-3 pe-3">Ha ocurrido un error</p>
                <span id="messagesModal" class="ps-3 pe-3 pb-4 mb-4"></span>
            </div>
        </div>
    </div>
</div>