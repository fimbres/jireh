const mostrarInfoEvento = (info) => {
  const cita = info.event;
  fetch('includes/ajax/cita.php?id=' + cita.id)
  .then(res => res.json())
  .then(res => {
    const tipo = res.respuesta;
    if(tipo == 'Exito'){
      const cita = res.resultados[0];
      $('.modal-body').text('')
      $('.modal-body').append(`
        <br>
        <p>Fecha inicio: ${cita.FechaInicio}</p>
        <br>
        <br>
        <p>Fecha final: ${cita.FechaFinal}</p>
        <br>
        <br>
        <p>id_doctor: ${cita.IdDoctor}</p>
        <br>
        <br>
        <p>id_paciente: ${cita.IdPaciente}</p>
        <br>
      `)
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
    array_colores[doc_ordenado[i]] = colores[i];
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