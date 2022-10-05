const mostrarInfoEvento = (info) => {
  $('#infoCita').modal('show');
}

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar;
    calendar = new FullCalendar.Calendar(calendarEl, {
        themeSystem: 'bootstrap5',
        headerToolbar: {
            left: 'dayGridMonth,timeGridWeek,listDay',
            center: 'title',
            right: 'prev,next',
        },
        initialDate: '2020-09-12',
        locale: 'es',
        height: 800,
        navLinks: true, // can click day/week names to navigate views
        editable: false,
        selectable: false,
        nowIndicator: true,
        dayMaxEvents: true, // allow "more" link when too many events
        // showNonCurrentDates: false,
        events: [
          {
            title: 'All Day Event',
            start: '2020-09-01',
            id: 80,
            // backgroundColor: 'red'
          },
          {
            title: 'Long Event',
            start: '2020-09-07',
            end: '2020-09-10'
          },
          {
            groupId: 999,
            title: 'Repeating Event',
            start: '2020-09-09T16:00:00'
          },
          {
            groupId: 999,
            title: 'Repeating Event',
            start: '2020-09-16T16:00:00'
          },
          {
            title: 'Conference',
            start: '2020-09-11',
            end: '2020-09-13'
          },
          {
            title: 'Meeting',
            start: '2020-09-12T10:30:00',
            end: '2020-09-12T12:30:00'
          },
          {
            title: 'Lunch',
            start: '2020-09-12T12:00:00'
          },
          {
            title: 'Meeting',
            start: '2020-09-12T14:30:00'
          },
          {
            title: 'Happy Hour',
            start: '2020-09-12T17:30:00'
          },
          {
            title: 'Dinner',
            start: '2020-09-12T20:00:00'
          },
          {
            title: 'Birthday Party',
            start: '2020-09-13T07:00:00'
          },
          {
            title: 'Click for Google',
            url: 'http://google.com/',
            start: '2020-09-28'
          }
        ],
        eventClick: mostrarInfoEvento,
        // eventHover: 
      });
    calendar.render();

    //Por el momento no se agregara setTimeOut(), pero es posible que
    // se agregue por si llegan a ocurrir fallos dentro del titulo
    $('.fc-toolbar-title').on('DOMSubtreeModified', function(){
      $('#titulo-calendario').text($(this).text())
    });

    $('#titulo-calendario').text($('.fc-toolbar-title').text())
});