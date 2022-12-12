$('#motivo1').on('click',function(){
  $('#motivo').modal('show');
})

const mostrarInfoEvento = (info) => {
  const cita = info.event;
  fetch('includes/ajax/cita.php?id=' + cita.id)
  .then(res => res.json())
  .then(res => {
    const tipo = res.respuesta;
    
    if(tipo == 'Exito'){
      const cita = res.cita[0];
      const paciente = res.paciente[0];
      const doctor = res.doctor[0];

      var NombreCompletoP = "";//PACIENTE
      var NombreCompletoD = "";//DOCTOR

      paciente.Nombre ? NombreCompletoP += paciente.Nombre : "";
      paciente.APaterno ? NombreCompletoP += " "+paciente.APaterno : "";
      paciente.AMaterno ? NombreCompletoP += " "+paciente.AMaterno : "";

      $("#rol").val() === "Tb_Doctor" && $.ajax({
        type: "POST",
        url: "utils/getExpediente.php",
        data: { pacienteId: cita.IdPaciente },
        dataType: "json",
        success: function (data) {
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
            if (
                item === "ArchivoAntecedentes" ||
                item === "ArchivoPresupuesto" ||
                item === "Archivo"
            ) {
                if (fieldValue) {
                    $("#" + item).attr("src", fieldValue);
                    $("#" + item).removeClass("visually-hidden");
                    $("#container-" + item).removeClass("visually-hidden");
                }
                else{
                    $("#container-" + item).addClass("visually-hidden");
                }
            } else {
                $("#" + item).val(fieldValue);
            }
            }
          },
          error: function (xhr, exception) {
          console.log("error", xhr);
          },
        });

      $("#IdCita").val(cita.IdCita);
      cita.IdStatus == 2 ? $("#cancelar-modal").addClass("visually-hidden") : $("#cancelar-modal").removeClass("visually-hidden");
      cita.IdStatus == 2 ? $("#editar-modal").addClass("visually-hidden") : $("#editar-modal").removeClass("visually-hidden");

      res.comentarios ? $("#observaciones").val(res.comentarios) : $("#observaciones").val('');
      res.comentarios ? $("#registrar-comentarios").addClass('visually-hidden') : $("#registrar-comentarios").removeClass('visually-hidden');
      res.comentarios ? $("#observaciones").attr('readonly', true) : $("#observaciones").attr('readonly', false);

      $("#nombrePaciente").val(NombreCompletoP);
      $("#tratamiento").val(cita.Descripcion);

      doctor.Nombre ? NombreCompletoD += doctor.Nombre : "";
      doctor.APaterno ? NombreCompletoD += " "+doctor.APaterno : "";
      doctor.AMaterno ? NombreCompletoD += " "+doctor.AMaterno : "";

      $("#nombreDoctor").val(NombreCompletoD);

      $("#costoCita").val(cita.Costo);
      $("#id-cita").val(cita.IdCita);
      $('#editar-modal').attr('href', 'ModificarAgenda.php?id=' + cita.IdCita);
      $('#cancelar-modal').attr('href', 'CancelarAgenda.php?id=' + cita.IdCita);

      let fechaCompletaInicio = ""+cita.FechaInicio;
      let fechaCompletaFinal = ""+cita.FechaFinal;

      let fechaInicioDividida = fechaCompletaInicio.split(" ");
      let fechaFinalDividida = fechaCompletaFinal.split(" ");

      $("#fechaCita").val(fechaInicioDividida[0]);
      $("#horaInicio").val(fechaInicioDividida[1]);
      $("#horaFinal").val(fechaFinalDividida[1]);

      $('#infoCita').modal('show');

    }

  })
  
}

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendario');
    var calendar;
    const doctor = $('#calendario').attr('data-doctor');
    calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'UTC',
        themeSystem: 'bootstrap5',
        headerToolbar: {
            left: doctor ? 'dayGridMonth,timeGridWeek,timeGridDay,listDay' : 'dayGridMonth,timeGridDay,listDay' ,
            center: 'title',
            right: 'prev,next',
        },
        initialDate: new Date(Date.now()),
        locale: 'es',
        height: 800,
        navLinks: true, // can click day/week names to navigate views
        editable: false,
        selectable: false,
        nowIndicator: true,
        dayMaxEvents: true, // allow "more" link when too many events
        eventClick: mostrarInfoEvento,
      });
    
    fetch('includes/ajax/citas-calendario.php',{
      method: 'POST',
      body: JSON.stringify({
        id_doctor: doctor ? parseInt(doctor) : false
      }),
      headers: {
        'Content-Type': 'application/json'
      }
    })
    .then(res => res.json())
    .then(res => {
      let exito = res.respuesta
      if(exito == 'Exito'){
        let citas = res.resultados;
        citas.forEach(element => {
          let objeto_cita = {
            title: element.Descripcion,
            start: element.FechaInicio,
            end: element.FechaFinal,
            id: element.IdCita,
            backgroundColor: "fff",
            borderColor: element.IdStatus == 2 ? "red" : "fff"
          }
          calendar.addEvent(objeto_cita)
        });
      } else{
        console.log(res.mensaje)
      }
    })
    calendar.render();

    //Por el momento no se agregara setTimeOut(), pero es posible que
    // se agregue por si llegan a ocurrir fallos dentro del titulo
    $('.fc-toolbar-title').on('DOMSubtreeModified', function(){
      $('#titulo-calendario').text($(this).text())
    });

    $('#titulo-calendario').text($('.fc-toolbar-title').text())

});