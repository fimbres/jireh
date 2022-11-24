//CARGANDO ELEMENTOS IMPORTANTES
var formulario = document.querySelector("#formModificarPaciente");
var btnToken = document.querySelector("#btnGenerateToken");
var btnPagar = document.querySelector("#btnPagar");
var btnCancelar = document.querySelector("#btnCancelar");
var txtToken = document.querySelector("#txtToken");
let metodoPago = "Stripe";
var data = new FormData();

//OBTENER PARAMETROS DE FORMA SIMILAR A GET
function getParameterByName(name) {
  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
  return results === null
    ? ""
    : decodeURIComponent(results[1].replace(/\+/g, " "));
}

//NAVEGACION INTERFAZ METODOS DE PAGO
function selectMenu(idItem) {
  document.querySelectorAll(".menuItem").forEach(function (item) {
    item.classList.remove("active");
    item.classList.remove("fw-bold");
  });

  //CAMBIAR ESTILOS METODO DE PAGO SELECCIONADO
  event.target.classList.add("active");
  event.target.classList.add("fw-bold");

  //ESCONDER OTROS METODOS DE PAGO
  document.querySelectorAll(".containerMethod").forEach(function (item) {
    item.classList.add("visually-hidden");
  });

  //MOSTRAR METODO DE PAGO SELECCIONADO
  $("#pago" + idItem).removeClass("visually-hidden");

  //VERIFICANDO SI EL USUARIO HA GENERADO UN AUTH TOKEN
  if (idItem === "Stripe" && $("#txtToken").val() === "") {
    btnPagar.setAttribute("disabled", "");
  } else {
    btnPagar.removeAttribute("disabled");
  }

  //OBTENIENDO EL METODO DE PAGO SEGUN LA SELECCION DEL USUARIO
  metodoPago = ""+idItem;
  console.log(metodoPago);
}

formulario.addEventListener("submit", function (e) {
  e.preventDefault();
});

//INTENTO DE REGISTRAR EL PAGO
btnPagar.addEventListener("click", function () {
  obtenerData(metodoPago);

  fetch("utils/registrarPago.php", {
    method: "POST",
    body: data,
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        if (data.showFacturacion !== "Stripe") {
          Swal.fire({
            icon: "success",
            title: "Correcto!",
            text: "Se ha registrado el pago\n¿Quieres solicitar una factura?",
            showCancelButton: true,
            confirmButtonText: 'Solicitar Factura',
            denyButtonText: `Cancelar`,
          }).then((result) => {
            if(result.isConfirmed){
              window.location = "facturacion.php?idCita=" + data.idCita;
            }
            else{
              window.location = "index.php";
            }
          });
        }
        else{
          Swal.fire({
            icon: "success",
            title: "Correcto!",
            text: "El registro se realizó exitosamente",
          }).then(() => {
              window.location = "index.php";
          });
        }
      }
      else {
        $("#errorMessage").removeClass("visually-hidden");
        $("#errorMessage").text(data.message);
      }
    })
    .catch((error) => console.error(error));
});

function obtenerData(metodoP) {
  const idCita = getParameterByName("idCita");
  const authToken = $("#txtToken").val();
  const fechaPago = $("#fechaHora").val();
  const voucher = $("#txtVoucher").val();
  const transferencia = $("#txtTransferencia").val();

  data.append("idCita", idCita);

 if(metodoP == "Stripe"){
    data.append("authToken", authToken);
 }else{
    data.append("authToken", "");
 }

  data.append("fechaPago", fechaPago);

  switch(metodoP){
    case "Tarjeta":
        data.append("numOperacion", voucher);
        break;
    case "Transferencia":
        data.append("numOperacion", transferencia);
        break;
    default:
        data.append("numOperacion", "");
 }

  data.append("metodoPago", metodoPago);
}

//FUNCION PARA COPIAR EL TEXTOS AL HACER CLIC EN EL CAMPO DE AUTH TOKEN
txtToken.addEventListener("click", function () {
  if (txtToken.value != "" && txtToken.value.length >= 20) {
    txtToken.select();
    navigator.clipboard.writeText(txtToken.value);

    Swal.fire({
      icon: "info",
      title: "Correcto!",
      text: "Se ha copiado el Token en el portapapeles!",
    });
  }
});

//GENERAR HASH
btnToken.addEventListener("click", function () {
  //DATOS PARA GENERAR EL HASH
  const name = $("#nombrePaciente").val();
  const fechaHora = $("#fechaHora").val();
  const nameDoctor = $("#nombreDoctor").val();
  const fechaAuthToken = Date.now();

  //HASH
  getHash("" + fechaAuthToken + name + fechaHora + nameDoctor).then((hash) => {
    $("#txtToken").val(hash.substring(0, 20));
    btnPagar.removeAttribute("disabled");
  });
});

//FUNCIONAMIENTO INTERNO GENERAR HASH PARA AUTHTOKEN
function getHash(str, algo = "SHA-256") {
  let strBuf = new TextEncoder("utf-8").encode(str);
  return crypto.subtle.digest(algo, strBuf).then((hash) => {
    window.hash = hash;
    let result = "";
    const view = new DataView(hash);
    for (let i = 0; i < hash.byteLength; i += 4) {
      result += ("00000000" + view.getUint32(i).toString(16)).slice(-8);
    }
    //console.log(result);
    return result;
  });
}
