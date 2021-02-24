<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../class/gmail/Exception.php';
require '../../class/gmail/PHPMailer.php';
require '../../class/gmail/SMTP.php';

class Email_api{

    public function send_email($msg, $email, $assunto) {
    
        $mail = new PHPMailer(true);                            // Passing `true` enables exceptions

        //Server settings
        //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                        // Set mailer to use SMTP
        $mail->CharSet="UTF-8";
        $mail->Host = 'smtp.gmail.com';                         // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                 // Enable SMTP authentication
        $mail->Username = 'unifica@unifica.page';                  // SMTP username
        $mail->Password = 'Uni5@#123';                          // SMTP password
        $mail->SMTPSecure = 'tls';                              // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                      // TCP port to connect to

        //Recipients
        $mail->setFrom('unifica@unifica.page', 'Plataforma Unifica');
        //$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
        $mail->addAddress($email);                              // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                    // Set email format to HTML
        $mail->Subject = $assunto;
        $mail->AltBody = strip_tags($msg);
        $mail->Body = "<div style=\"width:100%; background:#ebebeb; padding:20px 0;\"><div style=\"display:block; margin:0 auto; width:600px; padding:20px 20px 20px 20px; border:1px solid #ddd; border-radius:10px; background:#fff; font-family:helvetica, arial;\"><p>".$msg."</p></div></div>";
        $mail->send();

    }
}
