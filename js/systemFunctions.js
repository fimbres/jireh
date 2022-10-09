var menu = document.querySelector("#list-menu");
var separator = document.querySelector(".separator");

function desplegarMenu(){

    if(menu.style.display == "block"){
        menu.style.display = "none";
        separator.style.display = "none";
    }else{
        menu.style.display = "block";
        separator.style.display = "block";
    }
}

function orientacionCambiada(){
    if(screen.width > 1073){
        menu.style.display = "block";
        separator.style.display = "block";
    }else{
        menu.style.display = "none";
        separator.style.display = "none";
    }
}

window.addEventListener("orientationchange",orientacionCambiada,false);