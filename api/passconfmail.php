<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../config_default.php';

$env = parse_ini_file('../.env');
//Load Composer's autoloader
require '../vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = SMTP_HOST;                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = SMTP_USERNAME;                     //SMTP username
    $mail->Password   = SMTP_PASSWORD;                               //SMTP password
    $mail->SMTPSecure = SMTP_ENCRIPTION;            //Enable implicit TLS encryption
    $mail->Port       = SMTP_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('info@securelogin.com', 'Secure Login');
    $mail->addAddress($_POST['email']);  // Add a recipient
   // $mail->addAddress('ellen@example.com');               //Name is optional
   // $mail->addReplyTo('info@example.com', 'Information');
   // $mail->addCC('cc@example.com');
   // $mail->addBCC('bcc@example.com');

    //Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content

    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Your Password Has Been Changed';
    $mail->Body = 'Your password has been changed.';
    $mail->AltBody = 'Your SLA team';

    $mail->send();
    header('Location: ../front/reset-password.html');
    exit();
} catch (Exception $e) {
    return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>