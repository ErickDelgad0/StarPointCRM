<?php

$envPath = __DIR__ . '/../.env';
$config = parse_ini_file($envPath);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

if (isset($_POST['send'])){

}

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = $config['GMAIL_USER'];
$mail->Password = $config['GMAIL_PASSWORD'];
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;

$mail->setFrom('StarPoint.Insurance@gmail.com');

$mail->addAddress('carlosdelgado943@gmail.com');

$mail->isHTML(true);

$mail->Subject = 'Hey Dad';
$mail->Body = 'I sent this code using php and other stupid code';

$mail->send();

echo'
<script>
    alert("Sent Succesfully")
    document.location.href = "index.php"
</script>
';