<?php
    require_once('utils/sendMail.php');
    require_once('utils/cloudinaryFunctions.php');

    //Prueba con mi correo
    //enviarMail("ariel.incgarcia@gmail.com","Hola este es un mensaje de prueba para recordarle su proxima cita el dia 02 de octubre a las 14:00 con el doctor Jose Lopez");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <h1>Imagen</h1>
        
        <?php 
            $pilaArchivos = callFile();
        ?>
        <img src="<?php echo $pilaArchivos->current(), PHP_EOL;?>" alt="">
    </div>
</body>
</html>