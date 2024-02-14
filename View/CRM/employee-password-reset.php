<?php
include '../../php/functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

$envPath = __DIR__ . '/../../.env';
$config = parse_ini_file($envPath);

session_start();

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Check if we are logged in
check_loggedin($pdo, '../index.php');
check_role(['admin'], 'index.php');

// Output message
$msg = '';
// Check that the contact id exists
if (isset($_GET['id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM Employee WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Contact doesn\'t exist with that id!');
    }
    // Make sure the user confirms before password reset
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            $pdo->beginTransaction();

            try {
                // Generate a unique token
                $token = bin2hex(random_bytes(16));

                // Get the current timestamp
                $currentTimestamp = date("Y-m-d H:i:s");

                // Store the token and the current timestamp in the database
                $stmt = $pdo->prepare('UPDATE Employee SET forgot_password_token = ?, last_seen = ? WHERE id = ?');
                $stmt->execute([$token, $currentTimestamp, $_GET['id']]);

                // Commit the transaction
                $pdo->commit();

                // Send email with new password
                // (Ensure you have PHPMailer set up correctly)
                $mail = new PHPMailer(true);
                try {
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
                    $mail->addAddress($contact['email'], ($contact['email']));

                    // Content
                    $mail->isHTML(true); // Set email format to HTML
                    $mail->Subject = 'Password Reset';
                    $mail->Body = 'Please click the link to reset your password: http://localhost:8080/view/process-forgot-password.php?token=' . $token;

                    $mail->send();
                    $msg =  'Message has been sent';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

            } catch (PDOException $e) {
                // An error occurred, roll back the transaction
                $pdo->rollBack();
                exit('Error resetting password: ' . $e->getMessage());
            }
        } else {
            // User clicked the "No" button, redirect them back to the Employee page
            header('Location: admin.php');
            exit;
        }
    }

} else {
    exit('No id specified!');
}
?>
<?=CRM_header('Employee Password Reset')?>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?= CRM_topbar($_SESSION) ?>

        <div class= container-fluid>
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <div class="wrap">
                    <h1 class="h3 mb-0 text-gray-800">Employee #<?=$contact['id']?></h1>
                </div>
            </div>

            <form action="" method="get" class="crud-form">

                <input type="hidden" name="id" value="<?=$contact['id']?>">

                <?php if ($msg): ?>
                <p class="msg-success"><?=$msg?></p>
                <?php else: ?>
                <p>Are you sure you want to reset the employee password #<?=$contact['id']?>?</p>
                <div class="btns">
                    <button type="submit" name="confirm" value="yes" class="btn red">Yes</button>
                    <button type="submit" name="confirm" value="no" class="btn">No</button>
                </div>
                <?php endif; ?>

            </form>
        </div>
    </div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
    </a>

    <?=CRM_footer()?>
    <?=js_torun()?>
    <?=logout_modal()?>
</div>

