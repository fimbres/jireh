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
      console.log(res.cita[0]);
      console.log(res.paciente[0]);
      console.log(res.doctor[0]);

      var NombreCompletoP = "";//PACIENTE
      var NombreCompletoD = "";//DOCTOR

      paciente.Nombre ? NombreCompletoP += paciente.Nombre : "";
      paciente.APaterno ? NombreCompletoP += " "+paciente.APaterno : "";
      paciente.AMaterno ? NombreCompletoP += " "+paciente.AMaterno : "";

      $("#nombrePaciente").val(NombreCompletoP);
      $("#tratamiento").val(cita.Descripcion);

      doctor.Nombre ? NombreCompletoD += doctor.Nombre : "";
      doctor.APaterno ? NombreCompletoD += " "+doctor.APaterno : "";
      doctor.AMaterno ? NombreCompletoD += " "+doctor.AMaterno : "";

      $("#nombreDoctor").val(NombreCompletoD);

      $("#costoCita").val(cita.Costo);


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
const coloresDoctores = (citas) => {
  let doc = [...new Set(citas.map(item => parseInt(item.IdDoctor)))];
  let doc_ordenado = doc.sort()
  let array_colores = {}
  let colores = ['#E74C3C','#9B59B6','#2980B9','#1ABC9C','#2ECC71','#F1C40F','#E67E22']
  for (let i = 0; i < doc_ordenado.length; i++) {
    if(i >= 6)
      array_colores[doc_ordenado[i]] = colores[i];
    else
      array_colores[doc_ordenado[i]] = colores[0];
  }
  return array_colores;
}

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendario');
    var calendar;
    const doctor = $('#calendario').attr('data-doctor') 
    calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'UTC',
        themeSystem: 'bootstrap5',
        headerToolbar: {
            left: doctor ? 'dayGridMonth,timeGridWeek,timeGridDay,listDay' : 'dayGridMonth,timeGridDay,listDay' ,
            center: 'title',
            right: 'prev,next',
        },
        initialDate: '2022-08-01',
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
        const colores = coloresDoctores(citas)
        citas.forEach(element => {
          let objeto_cita = {
            title: element.Descripcion,
            start: element.FechaInicio,
            end: element.FechaFinal,
            id: element.IdCita,
            backgroundColor: colores[element.IdDoctor],
            borderColor: colores[element.IdDoctor]
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