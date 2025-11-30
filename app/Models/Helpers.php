<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



function SendEmail($to_email, $subject, $Message)
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = 'mail.onlinejobs.my';  //SMTP server
    $mail->Username = 'test@onlinejobs.my';  //email
    $mail->Password = 'FoovHy][0pT7';   //Email Password
    $mail->Port = 465;                    //SMTP port
    $mail->SMTPSecure = "ssl";
    $mail->setFrom($mail->Username, 'Job Finder');
    $mail->addAddress($to_email);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $Message;
    $mail->send();

    return True;
}