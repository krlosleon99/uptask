<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($nombre, $email, $token)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('confirmar@uptask.com');
        $mail->addAddress('confirmar@uptask.com', 'UpTask');

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Confirma tu cuenta';

        $contenido = "<html>";
        $contenido .= "<p>Hola, <strong>" . $this->nombre . "</strong></p>";
        $contenido .= "<p>Para poder continuar con tu registro, confirma tu email mediante el siguiente enlace:</p>";
        $contenido .= "<p>Confirmar cuenta: <a href='". $_ENV['APP_URL'] ."/confirmar?token=" . $this->token . "'>Confirmar cuenta</a></p>";
        $contenido .= "Si no fuiste tú, puedes ignorar el mensaje";

        $mail->Body = $contenido;

        $mail->send();
    }

    public function enviarInstrucciones() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('reestablece@uptask.com');
        $mail->addAddress('reestablece@uptask.com', 'UpTask');

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Reestablece tu password';

        $contenido = "<html>";
        $contenido .= "<p>Hola, <strong>" . $this->nombre . "</strong></p>";
        $contenido .= "<p>Parece que tienes problemas con tu login. Ingresa al siguiente link para reestablecer tu contraseña.</p>";
        $contenido .= "<p>Reestablecer contraseña: <a href='" . $_ENV['APP_URL'] . "/reestablecer?token=" . $this->token . "'>Reestablecer contraseña</a></p>";
        $contenido .= "Si no fuiste tú, puedes ignorar el mensaje";

        $mail->Body = $contenido;

        $mail->send();
    }
}
