<?php
include '../php/functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$envPath = __DIR__ . '/../.env';
$config = parse_ini_file($envPath);

session_start();

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

// Connect to MySQL database
$pdo = pdo_connect_mysql();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the email field is set and not empty
    if (isset($_POST['InputEmail']) && !empty($_POST['InputEmail'])) {
        $email = $_POST['InputEmail'];

        $pdo->beginTransaction();
        try {
            // Fetch user data from the database using email
            $stmt = $pdo->prepare('SELECT id, email FROM Employee WHERE email = ?');
            $stmt->execute([$email]);
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($employee) {
                // Generate a unique token
                $token = bin2hex(random_bytes(16));

                // Get the current timestamp
                $currentTimestamp = date("Y-m-d H:i:s");

                // Store the token and the current timestamp in the database
                $stmt = $pdo->prepare('UPDATE Employee SET forgot_password_token = ?, last_seen = ? WHERE id = ?');
                $stmt->execute([$token, $currentTimestamp, $employee['id']]);

                // Commit the transaction
                $pdo->commit();
                
                // Send email with new password
                $mail = new PHPMailer(true);
                // server config
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $config['GMAIL_USER'];
                $mail->Password = $config['GMAIL_PASSWORD'];
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                // Recipients
                $mail->setFrom('StarPoint.Insurance@gmail.com', 'StarPoint');
                $mail->addAddress($employee['email'], ($employee['email']));

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Password Reset';
                
                $mail->Body = 'Please click the link below to reset your password http://localhost:8080/view/process-forgot-password.php?token=' . $token;

                $mail->send();
                $_SESSION['success'] = 'Password Reset Link Sent!';
            } else {
                $_SESSION['error'] = 'Error Resetting Password';
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            // Handle errors
            $_SESSION['error'] =  'Error resetting password: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = 'Enter an email to reset password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>StarPoint - Forgot Password</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/CRM/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                        <div class="col-lg-6 d-none d-lg-block" style="background-image: url('/img/forgot-password.png'); background-position: center center; background-size: cover; height: 80vh; width: 100%; overflow: hidden;"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                                        <p class="mb-4">We get it, stuff happens. Just enter your email address below
                                            and we'll send you a link to reset your password!</p>
                                    </div>
                                    <form action="" method="POST" class="user">
                                        <div class="form-group">
                                            <input type="email" name="InputEmail" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Reset Password</button>
                                    </form>
                                    <hr>
                                    <!-- <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div> -->
                                    <div class="text-center">
                                        <a class="small" href="login.php">Already have an account? Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <?=js_torun() ?>

</body>

</html>