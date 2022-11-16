function selectMenu(idItem){
    document.querySelectorAll('.menuItem').forEach(function(item){
        item.classList.remove('active');
        item.classList.remove('fw-bold');
    });

    event.target.classList.add('active');
    event.target.classList.add('fw-bold');

    document.querySelectorAll('.containerMethod').forEach(function(item){
        item.classList.add('visually-hidden');
    });

    $("#pago"+idItem).removeClass("visually-hidden");
}