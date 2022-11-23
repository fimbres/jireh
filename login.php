<?php
    require_once('utils/sessionCheck.php');
    //Si ya ha iniciado sesion, no lo dejamos volver a iniciar sesion hasta
    // que cierre la otra sesion
    if(comprobar_sesion()){
        header('location: index.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body class="bg-light">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header w-100 bg-white d-flex justify-content-center">
                                        <img class="rounded my-3" src="files/logo_jireh.jpg" width="250px" alt="Logo" />
                                    </div>
                                    <div class="card-body">
                                        <form method="post" id="loginForm">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="Username" name="Username" type="text" placeholder="Example69" />
                                                <label for="Username">Username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="Password" name="Password" type="password" placeholder="Password" />
                                                <label for="Password">Password</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <select class="form-control" id="Rol" name="Rol">
                                                    <option value="">Selecciona una opción</option>
                                                    <option value="Tb_Admin">Administrador</option>
                                                    <option value="Tb_Recepcionista">Recepcionista</option>
                                                    <option value="Tb_Doctor">Doctor</option>
                                                </select>
                                                <label for="Password">Rol</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <input class="btn btn-primary w-100" type="submit" value="Iniciar Sesión">    
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small"><a href="#">Sistema de Pagos</a></div>
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
        <script>
            $('#loginForm').on('submit', function(e){
                e.preventDefault();
                const username = $('#Username').val();
                const password = $('#Password').val();
                const rol = $('#Rol').val();

                if(username && password && rol){
                    $.ajax({
                        type: "POST",
                        url: "utils/login.php",
                        data: { username, password, rol },
                        dataType: 'json',
                        success: function(data){
                            if(data.response === "success"){
                                window.location="index.php";
                            }
                            else {
                                swal("Error: Petición", data.message, "error");
                            }
                        },
                        error: function (xhr, exception) {
                            swal("Error: Petición", exception.toString(), "error");
                            console.error(xhr);
                        }
                    });
                }
                else{
                    swal("Error: Campos Vacíos", "Todos los campos son necesarios", "error");
                }
            })
        </script>
    </body>
</html>