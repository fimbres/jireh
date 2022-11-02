let idPaciente = 0;

function tablaPacientesAcciones(id, event, objeto) {
  idPaciente = id;

  //Obtenemos los nodos del elemento clickeado
  if (event === "Eliminar") {
    level1 = objeto.parentNode;
    level2 = level1.parentNode;
    level3 = level2.parentNode;
    level4 = level3.parentNode;
    botonEliminar = objeto;
  }

  if (event === "Modificar" || event === "Ver" || event === "Eliminar") {
    $.ajax({
      type: "POST",
      url: "utils/getExpediente.php",
      data: { pacienteId: id },
      dataType: "json",
      beforeSend: function (data) {
        //CARGAR ESTRUCTURA FORMULARIO
        if (event === "Modificar")
          $("#containerTableActions").load(
            "components/gestionPacientesForm.php"
          );
        else if (event === "Ver")
          $("#containerTableActions").load(
            "components/gestionPacientesModalForm.php"
          );
        else if (event === "Eliminar")
          $("#containerTableActions").load(
            "components/gestionPacientesModalForm.php"
          );
      },
      success: function (data) {
        //LLENAR EL FORMULARIO CON LOS DATOS
        for (let item in data) {
          let fieldValue;

          switch (item) {
            case "IdSexo":
              fieldValue = data[item] === "1" ? "Masculino" : "Femenino";
              break;
            default:
              fieldValue = data[item];
              break;
          }

          let formField = document.getElementById(item);

          if (formField && fieldValue) {
            formField.setAttribute("value", fieldValue);
          }
        }

        //MOSTRAR FORMULARIO LLENADO
        $("#containerTableActions").removeClass("visually-hidden");

        if (event === "Modificar") {
          $("#tablaPacientes").addClass("visually-hidden");
        } else if (event === "Ver") {
          $("#modalExpediente").modal("show");
        } else if (event === "Eliminar") {
          $("#modalExpediente").modal("show");
          $("#formBajaPaciente").removeClass("visually-hidden");
        }
      },
      error: function (xhr, exception) {
        console.log("error", xhr);
      },
    });
  }
}

function actualizarInfoPaciente() {
  console.log(idPaciente);
}

function bajaPaciente() {
  $.ajax({
    type: "POST",
    url: "utils/bajaPaciente.php",
    data: { pacienteId: idPaciente },
    dataType: "json",
    beforeSend: function (data) {},
    success: function (data) {
      if (data.response === "success") {
        statusPaciente = level3.children[3];
        statusPaciente.innerHTML = "Inactivo";
        botonEliminar.setAttribute("disabled","");
        $("#btnCloseModal").trigger("click");
      } else if (data.response === "invalid") {
        $(".modal-body").addClass("visually-hidden");
        $(".modal-messages").removeClass("visually-hidden");
        $("#messagesModal").text(data.message);
        console.log(data.message);
      }
    },
    error: function (xhr, exception) {
      console.log("error", xhr);
    },
  });
}
