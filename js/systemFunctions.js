var menu = document.querySelector("#list-menu");
var separator = document.querySelector(".separator");
var btnMenu = document.querySelector("#btnMenu");

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

function sizeScreen(){
    if(screen.width > 1073){
        menu.style.display = "block";
        separator.style.display = "block";
    }else{
        menu.style.display = "none";
        separator.style.display = "none";
    }
}

btnMenu.addEventListener("click",desplegarMenu,false);
window.addEventListener("orientationchange",orientacionCambiada,false);
window.addEventListener("resize",sizeScreen);