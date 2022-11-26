<?php
    require_once('utils/sessionCheck.php');
    if(!comprobar_sesion()){
        header('location: login.php');
    }

    switch($_SESSION['rol']) {
        case 'Tb_Doctor': $bienvenida = "Bienvenido, Doctor"; break;
        case 'Tb_Recepcionista': $bienvenida = "Bienvenido, Recepcionista"; break;
        case 'Tb_Administrador': $bienvenida = "Bienvenido, Administrador"; break;
        default: $bienvenida = "Bienvenido";
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Jireh</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</head>
<body>
    <main class="d-flex flex-wrap flex-row">
        <!--COMPONENTE MENU-->
        <?php
            require_once('components/menu.php');
        ?>

        <!--CONTENEDOR ALMACENAR CONTENIDO OTRAS PAGINAS-->
        <div id="displayActions" class="d-flex bg-white p-4">
            <div class="container">
                <div class="mt-3 mb-5">
                    <h1 class="text-center display-4"><?php echo $bienvenida; ?></h1>
                    <h3 class="text-center mt-3 lead">"Cuidado dental de alta calidad, de manera fácil y segura."</h3>
                </div>
                <section>
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 order-lg-2">
                                <img class="img-fluid" src="./files/home1.jpg" alt="first image" style="max-height: 380px; border-radius: 20px;"/>
                            </div>
                            <div class="col-lg-6 order-lg-1">
                                <h2 class="display-4">Nuestra Visión</h2>
                                <p>Somos una clínica dental comprometida con brindar el mejor servicio posible a nuestros clientes, con los mayores estándares de higiene y salubridad, además de los mejores productos para su cuidado dental.</p>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Content section 2-->
                <section>
                    <div class="container mt-5 mb-3">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <img class="img-fluid" src="./files/home2.jpg" alt="second image" style="max-height: 380px; border-radius: 20px;"/>
                            </div>
                            <div class="col-lg-6">
                                    <h2 class="display-4">Nuestro Compromiso</h2>
                                    <p>Calidad dental al alcance de todos.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
    <footer>
    </footer>
    <script src="js/systemFunctions.js"></script>
</body>
</html>