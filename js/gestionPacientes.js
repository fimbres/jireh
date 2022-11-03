let idPaciente = 0;

function tablaPacientesAcciones(id, event, objeto) {
  idPaciente = id;

  //OBTENEMOS LOS NODOS DEL PACIENTE CLICKEADO, PARA MOSTRAR LOS DATOS DE LA RESPUESTA
  if (event === "Eliminar") {
    level1 = objeto.parentNode;
    level2 = level1.parentNode;
    level3 = level2.parentNode;
    level4 = level3.parentNode;
    botonEliminar = objeto;
  }

  //VERIFICAMOS QUE SEA UNA ACCION VALIDA POR EL SISTEMA
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
        //RECORREMOS EL EXPEDIENTE
        for (let item in data) {
          let fieldValue;

          switch (item) {
            case "IdSexo":
              fieldValue = data[item] === "1" ? "Masculino" : "Femenino";
              break;
            case "IdStatus":
              if (data[item] >= "3")
                fieldValue = data[item] === "3" ? "Activo" : "Inactivo";
              break;
            default:
              fieldValue = data[item];
              break;
          }

          //ASIGNAMOS UN VALOR DIFERENTE CADA VEZ QUE RECORREMOS
          if (fieldValue) {
            if (
              item === "ArchivoAntecedentes" ||
              item === "ArchivoPresupuesto" ||
              item === "Archivo"
            ) {
              if (fieldValue !== "NULL") {
                $("#" + item).attr("src", fieldValue);
                $("#" + item).removeClass("visually-hidden");
              }
            } else $("#" + item).val(fieldValue);
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
  var dataPaciente = new Array();
  var inputValues = $(".formModificarInput"),
    namesValues = [].map.call(inputValues, function (dataInput) {
      dataPaciente.push(dataInput.value);
    });

  console.log(dataPaciente);
  console.log(dataPaciente[1].length);

  if (dataPaciente[0].length !== 0) {
    if (dataPaciente[1].length !== 0 || dataPaciente[2].length !== 0) {
      if (dataPaciente[3].length !== 0) {
        if (dataPaciente[15].length !== 0) {
          $.ajax({
            type: "POST",
            url: "utils/updatePaciente.php",
            data: { idPaciente: idPaciente,datos: dataPaciente },
            dataType: "json",
            success: function (data) {
              if (data.response === "success") {
                console.log(data.message);
              } else {
                swal("Error: Petición", data.message, "error");
              }
            },
            error: function (xhr, exception) {
              swal("Error: Petición", exception.toString(), "error");
              console.error(xhr);
            },
          });
        } else {
          swal(
            "Error: Campos Vacíos",
            "El paciente debe tener un estado valido",
            "error"
          );
        }
      } else {
        swal(
          "Error: Campos Vacíos",
          "Debes ingresar el sexo de la persona",
          "error"
        );
      }
    } else {
      swal(
        "Error: Campos Vacíos",
        "Debes ingresar al menos un apellido",
        "error"
      );
    }
  } else {
    swal("Error: Campos Vacíos", "El campo nombre es requerido", "error");
  }

  /*if (username && password && rol) {
      $.ajax({
        type: "POST",
        url: "utils/login.php",
        data: { username, password, rol },
        dataType: "json",
        success: function (data) {
          if (data.response === "success") {
            window.location = "index.php";
          } else {
            swal("Error: Petición", data.message, "error");
          }
        },
        error: function (xhr, exception) {
          swal("Error: Petición", exception.toString(), "error");
          console.error(xhr);
        },
      });
    } else {
      swal("Error: Campos Vacíos", "Todos los campos son necesarios", "error");
    }
    */
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
        botonEliminar.setAttribute("disabled", "");
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
