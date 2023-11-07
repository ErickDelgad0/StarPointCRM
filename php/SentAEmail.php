<?php

$envPath = __DIR__ . '/../../.env';
    if (!file_exists($envPath)) {
        $envPath = __DIR__ . '/../.env';
    }
    $config = parse_ini_file($envPath);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

if (isset($_POST['send'])){
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $config['GMAIL_USER'];
    $mail->Password = $config['GMAIL_PASSWORD'];
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('StarPoint.Insurance@gmail.com');

    $mail->addAddress('delrick2323@outlook.com');

    $mail->isHTML(true);

    $mail->Subject = 'Another Test';
    $mail->Body = 'Here lies some text';

    $mail->send();

    echo'
    <script>
        alert("Sent Succesfully")
        document.location.href = "index.php"
    </script>
    ';
}