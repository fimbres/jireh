function pagarCita(IdCita){
    console.log(IdCita);
    location.href = "gestionPagos.php?idCita="+IdCita;
}