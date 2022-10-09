<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</head>
<body>
    <main class="d-flex flex-wrap flex-row">
        <div id="sideBar" class="d-flex flex-column flex-shrink-0 p-3">
            <div id="top-sideBar" class=""><a href="#"><img class="rounded" src="files/logo_jireh.jpg" width="180px" alt=""></a><ul class=""><li><a href="#" onclick="desplegarMenu()" class="link-light pt-2 pb-2 ps-4 pe-4">Menu</a></li></ul></div>
            <hr class="separator">
            <ul id="list-menu" class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link link-light mt-2 text-center" aria-current="page">
                    Agregar Cliente
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link link-light mt-2 text-center">
                    Administrar Clientes
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link link-light mt-2 text-center">
                    Administrar Citas
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link link-light mt-2 text-center">
                    Registrar Pago
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link link-light mt-2 text-center">
                    Ver Calendario
                    </a>
                </li>
            </ul>
        </div>

        <div id="displayActions" class="d-flex bg-white p-4">
            <?php
            require_once('Calendario.php');
            ?>
        </div>
    </main>
    <footer>

    </footer>
    <script>
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
    </script>
</body>
</html>