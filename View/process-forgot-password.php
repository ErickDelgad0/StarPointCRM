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

// Check if the token is present in the URL
if (!isset($_GET['token'])) {
    die('No token provided');
}

$token = $_GET['token'];

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Fetch user data from the database using the token
$stmt = $pdo->prepare('SELECT id, forgot_password_token, last_seen FROM Employee WHERE forgot_password_token = ?');
$stmt->execute([$token]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    die('Invalid token');
}

// Check if the token is still valid (within the last  15 minutes)
$fifteenMinutesAgo = date("Y-m-d H:i:s", strtotime("-15 minutes"));
if ($employee['last_seen'] < $fifteenMinutesAgo) {
    die('Token expired');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the new password and confirm password fields are set and not empty
    if (isset($_POST['NewPassword']) && !empty($_POST['NewPassword']) &&
        isset($_POST['ConfirmPassword']) && !empty($_POST['ConfirmPassword'])) {
        
        $newPassword = $_POST['NewPassword'];
        $confirmPassword = $_POST['ConfirmPassword'];

        if ($newPassword !== $confirmPassword) {
            die('Passwords do not match');
        }

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the employee's password in the database
        $stmt = $pdo->prepare('UPDATE Employee SET password = ?, forgot_password_token = NULL, last_seen = NULL WHERE id = ?');
        $stmt->execute([$hashedPassword, $employee['id']]);

        // Redirect to the login page with a success message
        header('Location: login.php?message=Password+reset+successful');
        exit;
    } else {
        die('Both password fields must be filled out');
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
                                        <h1 class="h4 text-gray-900 mb-2">Create Your New Password</h1>
                                    </div>
                                    <form action="" method="POST" class="user">
                                        <div class="form-group">
                                            <input type="password" name="NewPassword" class="form-control form-control-user" id="exampleInputPassword" placeholder="New Password">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="ConfirmPassword" class="form-control form-control-user" id="exampleRepeatPassword" placeholder="Confirm New Password">
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