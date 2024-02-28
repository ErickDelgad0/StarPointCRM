<?php
session_start();
include 'functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$envPath = __DIR__ . '/../.env';
$config = parse_ini_file($envPath);
$errors = [];

// Validate required POST data exists
if (empty(trim($_POST['first_name'])) || empty(trim($_POST['last_name'])) 
|| empty(trim($_POST['email'])) || empty(trim($_POST['phone'])) || empty(trim($_POST['state'])) || empty(trim($_POST['DOB']))) {
    $errors[] = 'Please complete all fields in the form!';
}

// Validate email format
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please provide a valid email address!';
}

// Validate first and last names
if (!preg_match('/^[a-zA-Z\s\-\']+$/', $_POST['first_name']) || !preg_match('/^[a-zA-Z\s\-\']+$/', $_POST['last_name'])) {
    $errors[] = 'Name can only contain letters, spaces, hyphens, and apostrophes.';
}

$phone_number = preg_replace('/[^0-9]/', '', $_POST['phone']); // Remove non-numeric characters
if (strlen($phone_number) != 10) {
    $errors[] = 'Phone number must contain 10 digits.';
}

// Validate state (assuming US states)
$valid_states = ['AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'];
if (!in_array($_POST['state'], $valid_states)) {
    $errors[] = 'Invalid state selected.';
}

// Validate date of birth format and range
$dob = DateTime::createFromFormat('Y-m-d', $_POST['DOB']);
$now = new DateTime();
if (!$dob || $dob > $now) {
    $errors[] = 'Invalid date of birth provided.';
} else {
    $date_of_birth = $dob->format('Y-m-d');
}

// Check if there are any errors
if (count($errors) == 0) {
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare('INSERT INTO Leads (first_name, last_name, email, phone, state, DOB) VALUES (?, ?, ?, ?, ?, ?)');
    
    if ($stmt->execute([ $_POST['first_name'], $_POST['last_name'], $_POST['email'], $phone_number, $_POST['state'], $date_of_birth ])) {
        $_SESSION['message'] = 'Thank you, StarPoint will contact you shortly';
        
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $config['GMAIL_USER'];
        $mail->Password = $config['GMAIL_PASSWORD'];
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('StarPoint.Insurance@gmail.com');
        $mail->addAddress($_POST['email']);
        $mail->isHTML(true);
        $mail->Subject = 'Acknowledgement of Inquiry - StarPoint Insurance';
        $mail->Body = 'Hello ' . $_POST['first_name'] . ',<br><br>Thank you for reaching out to StarPoint Insurance. 
        Your inquiry is important to us. We are currently reviewing your request and will ensure a response is provided 
        within 2-3 business days. Should you have any immediate concerns or additional queries, please feel free to contact 
        our support team at StarPoint.Insurance@gmail.com.<br><br>Best Regards,<br>The StarPoint Insurance Team';
        $mail->send();

    } else {
        $_SESSION['message'] = 'There was an error with your information';
    }
} else {
    $_SESSION['errors'] = $errors;
}
header('Location: ../view/index.php#section-contact');
exit();