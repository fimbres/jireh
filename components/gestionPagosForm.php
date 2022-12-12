<!-- 
    Este formulario cambiara basado en la variable
    de php llamada "tipo_formulario".
-->
<form id="formModificarPaciente" method="POST" class="">
    <h3 class="pt-2 pb-2">Pago de cita</h3>
    <div class="form-row row">
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="nombre_inpt_paciente"><b>*</b>Nombre del paciente</label>
            <input readonly id="nombrePaciente" type="text" autocomplete="off" class="form-control text-capitalize formModificarInput" placeholder="Nombre del paciente" value="<?php print($infoPaciente["Nombre"]." ".$infoPaciente["APaterno"]." ".$infoPaciente["AMaterno"])?>">
        </div>
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="fecha_inpt_paciente"><b>*</b>Fecha y Hora</label>
            <input id="fechaHora" name="fechaHora" type="datetime-local" class="form-control formModificarInput" value="<?php $tipo_formulario == "modificar" ? print($infoPago['FechaPago']) : print("$fecha");?>">
        </div>
    </div>
    <div class="form-row row">
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="doctor_inpt_recepcionista"><b>*</b>Nombre del doctor</label>
            <input readonly id="nombreDoctor" type="text" autocomplete="off" class="form-control text-capitalize" placeholder="Nombre del doctor" 
            value="<?php 
            $infoDoctor["Nombre"] ? print($infoDoctor["Nombre"]) : print("");
            $infoDoctor["APaterno"] ? print(" ".$infoDoctor["APaterno"]) : print("");
            $infoDoctor["AMaterno"] ? print(" ".$infoDoctor["AMaterno"]) : print("");
            ?>"/>
        </div>
        <div class="form-group col-xl-6 col-md-12 pb-4">
            <label for="costo_inpt_recepcionista"><b>*</b>Costo de la cita (MXN)</label>
            <input readonly id="CostoCita" name="CostoCita" type="number" class="form-control formModificarInput" placeholder="$00.00" value="<?php print($infoCita["Costo"]);?>">
        </div>
    </div>
    <div class="form-row row justify-content-center">
        <div class="form-group col-xl-12 col-md-12 pb-4">
            <label for="descripcion_inpt">Descripción del servicio</label>
            <textarea <?php if(isset($infoCita["Descripcion"])) echo "readonly"?> class="form-control" id="" rows="4" placeholder="Descripción"><?php print($infoCita["Descripcion"]);?></textarea>
        </div>
    </div>
    <div id="formPago" class="form-row row justify-content-center shadow">
        <nav id="navbarPagos" class="float-start bg-custom-gray-light mb-3 p-0 ms-0">
                <ul class="list-unstyled">
                    <li class="float-start">
                        <a  
                            <?php echo ($tipo_formulario == "modificar" ? "style='pointer-events: none'" : "")?> 
                            id="Stripe" 
                            onclick="selectMenu(this.id)" 
                            class="<?php echo ($tipo_formulario == "modificar" ? ($pagos_id['Stripe'] == $infoPago['IdMetodoPago'] ? "active fw-bold" : "") : "active fw-bold")?> menuItem d-block bg-btn-gray-light ps-4 pe-4 pt-2 pb-2 text-decoration-none link-dark custom-border-top-left " 
                            href="#formPago">Stripe
                        </a>
                    </li>
                    <li class="float-start">
                        <a  
                        <?php echo ($tipo_formulario == "modificar" ? "style='pointer-events: none'" : "")?> 
                        id="Tarjeta" 
                        onclick="selectMenu(this.id)" 
                        class="<?php echo ($tipo_formulario == "modificar" && $pagos_id['Tarjeta'] == $infoPago['IdMetodoPago'] ? "active fw-bold" : "")?> d-block menuItem bg-btn-gray-light ps-4 pe-4 pt-2 pb-2 text-decoration-none link-dark" 
                        href="#formPago">Tarjeta
                    </a>
                </li>
                    <li class="float-start"><a  <?php echo ($tipo_formulario == "modificar" ? "style='pointer-events: none'" : "")?> id="Efectivo" onclick="selectMenu(this.id)" class="<?php echo ($tipo_formulario == "modificar" && $pagos_id['Efectivo'] == $infoPago['IdMetodoPago'] ? "active fw-bold" : "dd")?> d-block menuItem bg-btn-gray-light ps-4 pe-4 pt-2 pb-2 text-decoration-none link-dark" href="#formPago">Efectivo</a></li>
                    <li class="float-start"><a  <?php echo ($tipo_formulario == "modificar" ? "style='pointer-events: none'" : "")?> id="Transferencia" onclick="selectMenu(this.id)" class="<?php echo ($tipo_formulario == "modificar" && $pagos_id['Transferencia'] == $infoPago['IdMetodoPago'] ? "active fw-bold" : "") ?> d-block menuItem bg-btn-gray-light ps-4 pe-4 pt-2 pb-2 text-decoration-none link-dark custom-border-top-right" href="#formPago">Transferencia</a></li>
                </ul>
        </nav>
        <div class="form-group col-xl-12 col-md-12 pb-4 mt-4">
            <div id="pagoStripe" class="containerMethod d-flex justify-content-center align-items-center flex-wrap <?php echo ($tipo_formulario == "modificar" ?( $pagos_id['Stripe'] != $infoPago['IdMetodoPago'] ? "visually-hidden" : "")  : "")?>">
                <label class="pt-2 pb-2">Token</label>
                <input <?php echo ($tipo_formulario == "modificar" ? "disabled value='{$infoPago['AuthToken']}'" : "")?> id="txtToken" type="text" maxlength="20" autocomplete="off" class="form-control text-center" placeholder="Vacio por el momento..." readonly>
                <?php 
                 if($tipo_formulario != "modificar"){
                ?>
                <button id="btnGenerateToken" class="btn btn-primary text-white fw-bold mt-4">Generar Token</button>
                <?php }?>
            </div>
            <div id="pagoTarjeta" class="containerMethod <?php echo ($tipo_formulario == "modificar" ?( $pagos_id['Tarjeta'] != $infoPago['IdMetodoPago'] ? "visually-hidden" : "")  : "visually-hidden")?>">
                <label class="pt-2 pb-2">Número de Voucher</label>
                <input id="txtVoucher" name="txtVoucher" type="text" autocomplete="off" class="form-control" placeholder="Num. de Voucher" value="<?php echo $tipo_formulario == "modificar" ? $infoPago['NumeroOperacion'] : ""?>">
            </div>
            <div id="pagoEfectivo" class="containerMethod <?php echo ($tipo_formulario == "modificar" ?( $pagos_id['Efectivo'] != $infoPago['IdMetodoPago'] ? "visually-hidden" : "")  : "visually-hidden")?>">
                <label class="pt-2 pb-2">Total pagado</label>
                <input id="txtEfectivo" type="number" readonly value="<?php print($infoCita["Costo"]);?>" autocomplete="off" class="form-control" placeholder="$00.00">
            </div>
            <div id="pagoTransferencia" class="containerMethod <?php echo ($tipo_formulario == "modificar" ?( $pagos_id['Transferencia'] != $infoPago['IdMetodoPago'] ? "visually-hidden" : "")  : "visually-hidden")?>">
                <label class="pt-2 pb-2">Número de referencia</label>
                <input id="txtTransferencia" name="txtTransferencia" type="text" autocomplete="off" class="form-control" placeholder="Num. de referencia" value="<?php echo $tipo_formulario == "modificar" ? $infoPago['NumeroOperacion'] : ""?>">
            </div>
            <label id="errorMessage" class="mt-4 text-danger fw-bold fs-6 visually-hidden">Etiqueta para notificaciones/errores</label>
        </div>
        <div class="form-group col-12 pb-4 d-flex justify-content-end align-items-center">
            <input  
            <?php if($tipo_formulario == "modificar"){?>
                type="submit" 
                value="Modificar"
            <?php } else{?>
                type="button" 
                id="btnPagar"
                value="Pagar"
                disabled
            <?php }?>
            class="btn btn-primary ms-3 me-3 ps-4 pe-4">
            <?php if($tipo_formulario == "modificar"){?>
                <a id="btnCancelar" class="btn btn-danger ms-3 me-3" href="listarPagos.php">Cancelar</a>

            <?php } else {?>
                <input id="btnCancelar" type="button" value="Cancelar" class="btn btn-danger ms-3 me-3">
            <?php }?>
        </div>
    </div>
</form>