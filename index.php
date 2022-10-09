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
            <div class="d-flex justify-content-center align-items-center"><a href="#"><img class="rounded" src="files/logo_jireh.jpg" width="180px" alt=""></a></div>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
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
        let viewport = window.innerHeight;

        console.log(viewport);

        let menu = document.querySelector('#sideBar');
        menu.style.height = viewport;
    </script>
</body>
</html>