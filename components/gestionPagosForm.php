<form id="formModificarPaciente" method="POST" class="">
    <h3 class="pt-2 pb-2">Pago de cita</h3>
    <div class="form-row row">
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="nombre_inpt_paciente"><b>*</b>Nombre del paciente</label>
            <input id="nombrePaciente" type="text" autocomplete="off" class="form-control text-capitalize formModificarInput" placeholder="Nombre del paciente" value="<?php print($infoPaciente["Nombre"]." ".$infoPaciente["APaterno"]." ".$infoPaciente["AMaterno"])?>">
        </div>
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="fecha_inpt_paciente"><b>*</b>Fecha y Hora</label>
            <input id="fechaHora" type="datetime-local" class="form-control formModificarInput" value="<?php print("$fecha");?>">
        </div>
    </div>
    <div class="form-row row">
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="doctor_inpt_recepcionista"><b>*</b>Nombre del doctor</label>
            <input id="nombreDoctor" type="text" autocomplete="off" class="form-control text-capitalize" placeholder="Nombre del doctor" 
            value="<?php 
            $infoDoctor["Nombre"] ? print($infoDoctor["Nombre"]) : print("");
            $infoDoctor["APaterno"] ? print(" ".$infoDoctor["APaterno"]) : print("");
            $infoDoctor["AMaterno"] ? print(" ".$infoDoctor["AMaterno"]) : print("");
            ?>"/>
        </div>
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="costo_inpt_recepcionista"><b>*</b>Costo de la cita (MXN)</label>
            <input id="CostoCita" type="number" class="form-control formModificarInput" placeholder="$00.00" value="<?php print($infoCita["Costo"]);?>">
        </div>
    </div>
    <div class="form-row row justify-content-center">
        <div class="form-group col-xl-12 col-md-12 pb-4">
            <label for="descripcion_inpt">Descripción del servicio</label>
            <textarea class="form-control" id="" rows="4" placeholder="Descripción"><?php print($infoCita["Descripcion"]);?></textarea>
        </div>
    </div>
    <div id="formPago" class="form-row row justify-content-center shadow">
        <nav id="navbarPagos" class="float-start bg-custom-gray-light mb-3 p-0 ms-0">
                <ul class="list-unstyled">
                    <li class="float-start"><a id="Stripe" onclick="selectMenu(this.id)" class="active menuItem d-block bg-btn-gray-light ps-4 pe-4 pt-2 pb-2 text-decoration-none link-dark custom-border-top-left fw-bold" href="#formPago">Stripe</a></li>
                    <li class="float-start"><a id="Tarjeta" onclick="selectMenu(this.id)" class="d-block menuItem bg-btn-gray-light ps-4 pe-4 pt-2 pb-2 text-decoration-none link-dark" href="#formPago">Tarjeta</a></li>
                    <li class="float-start"><a id="Efectivo" onclick="selectMenu(this.id)" class="d-block menuItem bg-btn-gray-light ps-4 pe-4 pt-2 pb-2 text-decoration-none link-dark" href="#formPago">Efectivo</a></li>
                    <li class="float-start"><a id="Transferencia" onclick="selectMenu(this.id)" class="d-block menuItem bg-btn-gray-light ps-4 pe-4 pt-2 pb-2 text-decoration-none link-dark custom-border-top-right" href="#formPago">Transferencia</a></li>
                </ul>
        </nav>
        <div class="form-group col-xl-12 col-md-12 pb-4 mt-4">
            <div id="pagoStripe" class="containerMethod d-flex justify-content-center align-items-center flex-wrap">
                <label class="pt-2 pb-2">Token</label>
                <input id="txtToken" type="text" maxlength="20" autocomplete="off" class="form-control text-center" placeholder="Vacio por el momento..." readonly>
                <button id="btnGenerateToken" class="btn btn-primary text-white fw-bold mt-4">Generar Token</button>
            </div>
            <div id="pagoTarjeta" class="containerMethod visually-hidden">
                <label class="pt-2 pb-2">Número de Voucher</label>
                <input id="txtVoucher" type="text" autocomplete="off" class="form-control" placeholder="Num. de Voucher">
            </div>
            <div id="pagoEfectivo" class="containerMethod visually-hidden">
                <label class="pt-2 pb-2">Total pagado</label>
                <input id="txtEfectivo" type="number" value="<?php print($infoCita["Costo"]);?>" autocomplete="off" class="form-control" placeholder="$00.00">
            </div>
            <div id="pagoTransferencia" class="containerMethod visually-hidden">
                <label class="pt-2 pb-2">Número de referencia</label>
                <input id="txtTransferencia" type="text" autocomplete="off" class="form-control" placeholder="Num. de referencia">
            </div>
            <label id="errorMessage" class="mt-4 text-danger fw-bold fs-6 visually-hidden">Etiqueta para notificaciones/errores</label>
        </div>
        <div class="form-group col-12 pb-4 d-flex justify-content-end align-items-center">
            <input id="btnPagar" disabled type="button" value="Pagar" class="btn btn-primary ms-3 me-3 ps-4 pe-4">
            <input id="btnCancelar" type="button" value="Cancelar" class="btn btn-danger ms-3 me-3">
        </div>
    </div>
</form>