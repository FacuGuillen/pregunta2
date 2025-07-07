<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class EmailSender {
    public function send($email, $body)
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'anahilaurani@gmail.com'; // remitente que envia a otras personas
            $mail->Password = 'wsxkhjujsvrjtdib';
            $mail->SMTPSecure = 'tls';  // como se envia la info
            $mail->Port = 587;                           // o 465 para SSL

            // Remitente y destinatario
            $mail->setFrom('anahilaurani@gmail.com', 'Anahi Laurani'); // remitente nombre
            $mail->addAddress($email);                   // Destinatario pasado por parametro

            // Contenido del correo
            $mail->isHTML(true); // esto dice que sera por html
            $mail->Subject = 'validacion de mail'; // asunto del correo
            $mail->Body    = $body; // contenido del mensaje

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
