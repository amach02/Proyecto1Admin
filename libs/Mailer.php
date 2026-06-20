<?php
require_once 'libs/phpmailer/PHPMailer.php';
require_once 'libs/phpmailer/SMTP.php';
require_once 'libs/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    // ── Cambiá estos 3 valores ──────────────────────────────
    const SMTP_USER = 'amachado873@gmail.com';
    const SMTP_PASS = 'nwxc fezb gkgg rxmo'; // contraseña de aplicación
    const SMTP_FROM = 'amachado873@gmail.com';
    const SMTP_NAME = 'Sistema UCR Entomología';
    // ────────────────────────────────────────────────────────

    public static function enviar($destinatario, $nombre, $asunto, $cuerpoHtml)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = self::SMTP_USER;
        $mail->Password   = self::SMTP_PASS;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom(self::SMTP_FROM, self::SMTP_NAME);
        $mail->addAddress($destinatario, $nombre);

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpoHtml;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // TEMPORAL — borralo después de resolver el problema
        die('<b>Error Mailer:</b> ' . $e->getMessage() . '<br><b>Debug SMTP:</b> ' . $mail->ErrorInfo);
    }
}
}