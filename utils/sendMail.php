<?php
    //ESTO SE UTILIZARA PROXIMAMENTE CUANDO SE HAGA EL DISEÑO DEL FORMULARIO
    /*if($_SERVER['REQUEST_METHOD'] != 'POST' ){
        header("Location: ../index.html" );
    }*/


    require('vendor/autoload.php');
    require "vendor/phpmailer/phpmailer/src/PHPMailer.php";
    require "vendor/phpmailer/phpmailer/src/Exception.php";
    require "vendor/phpmailer/phpmailer/src/SMTP.php";
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    function enviarMail($destino,$mensaje){
        $body = <<< HTML
            <h1>Clinica dental Jireh</h1>
            <p>Estimado $destino</p>
            <h3>$mensaje</h3>
            <p>WhatsApp: +516463238495</p>
            <p>Numero telefonico: 6452935483</p>
            <p>Facebook: Clinica dental jireh</p>
        HTML;

        $mailer = new PHPMailer();
        $mailer->isSMTP();
        $mailer->SMTPAuth = true;
        $mailer->SMTPSecure = 'tls';
        $mailer->SMTPAutoTLS = false;
        $mailer->Port = 25;
        $mailer->Host = "smtp.gmail.com";
        $mailer->Username = "metafusion.sp.tech@gmail.com";
        $mailer->Password = "bafnaylfbuztirbp";
        $mailer->isHTML(true);

        $mailer->AddAddress($destino);
        $mailer -> setFrom($destino, "Clinica dental Jireh");
    
        $mailer->Subject = "Recordatorio Clinica dental Jireh";
        $mailer->msgHTML($body);
        $mailer->AltBody = strip_tags($body);
        $res = $mailer->send();

        var_dump($res); //regresa true si el email se envio correctamente de lo contrario retorna false
    }