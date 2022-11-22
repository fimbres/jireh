<?php 
    require_once('vendor/autoload.php');
    $formulario_token = true;
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Si entramos aqui, significa que el usuario ya dio un posible token
        // asi que necesitamos verificar que el token exista


        $formulario_token = false;
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Ingresar token</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/styles.css">
        <?php 
            if(!$formulario_token){
                echo "<link rel='stylesheet' href='css/stripe.css' />";
            }
        ?>

    </head>
    <body class="bg-light">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5 stripe-card">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header w-100 bg-white d-flex justify-content-center">
                                        <img class="rounded my-3" src="files/logo_jireh.jpg" width="250px" alt="Logo" />
                                    </div>
                                    <div class="card-body">
                                        <?php 
                                        $formulario_token ?
                                        require('components/formularioToken.php')
                                        :
                                        require('components/formularioTarjeta.php');
                                        
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <?php 
        if(!$formulario_token){
            echo "<script src='https://js.stripe.com/v3/'></script>
            <script src='js/stripe.js' defer></script>";
        }
        ?>
    </body>
</html>