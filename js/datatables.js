$(document).ready(function() {
    var table = $('#datatablesSimple').DataTable( {
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true
    } );
} );