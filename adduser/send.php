<?php
require 'PHPMailer/PHPMailerAutoload.php';

$email = $argv[1];
$body = $argv[2];

sendmail($body, $email);

function sendmail($body, $email)
{
    $mail = new PHPMailer;

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'eliza.verify@gmail.com';        // SMTP username
    $mail->Password = '4#L@XBzrzPr5&zo,ttJZPHXq$SC|MN+RKLtSq.\|I\9jDKKTx2;Rw\p?M~_e&Jv8:/j*?w';
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    $mail->setFrom('eliza.verify@gmail.com', 'Eliza');
    $mail->addBCC($email);
    //$mail->addBCC('');

    $mail->Subject = 'Eliza Verification';
    $mail->Body = $body;

    $mail->send();
}
?>