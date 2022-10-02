<?php
    require_once('utils/sendMail.php');
    require_once('utils/cloudinaryFunctions.php');

    //PRUEBA ENVIAR CORREO ELECTRONICO
    //enviarMail("ariel.incgarcia@gmail.com","Hola este es un mensaje de prueba para recordarle su proxima cita el dia 02 de octubre a las 14:00 con el doctor Jose Lopez");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
    <div>
        <h1>Documento</h1>
        
        <?php 
            //PRUEBA SUBIR ARCHIVO PDF
            //uploadFile("files/archivo.pdf","pdf");

            //PRUEBA SUBIR ARCHIVO PNG
            //uploadFile("files/image.png","png");

            //CREAMOS LA PILA CON LAS RUTAS DE LOS ARCHIVOS PDF
            $pilaArchivosPDF = callFile("pdf");

        ?>
        <!--Mostramos el archivo PDF con el visor de google-->
        <iframe src="http://docs.google.com/gview?url=<?php echo $pilaArchivosPDF->current(), PHP_EOL;?>
        &embedded=true" style="width:100%; height:700px;" frameborder="0" ></iframe>
    </div>
</body>
</html>