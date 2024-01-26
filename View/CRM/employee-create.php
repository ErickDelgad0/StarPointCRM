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

// Error message
$error_msg = '';
// Success message
$success_msg = '';
// Check if POST data exists (user submitted the form)
if (isset($_POST['submit'])) {
    // Iterate through the fields and extract the data from the form
    $data = [];
    foreach ($Employee_Admin_Columns as $column => $array) {
        if (isset($_POST[$column])) {
            $data[$column] = $_POST[$column];
            // Validate
            if ((isset($array['input']['required']) && !$array['input']['required'] && empty($_POST[$column]))) {
                continue;
            }
            if (isset($array['input']['validate_regex']) && $array['input']['validate_regex']) {
                if (!preg_match($array['input']['validate_regex'], $_POST[$column])) {
                    $error_msg = isset($array['input']['validate_msg']) ? $array['input']['validate_msg'] : 'Please enter a valid ' . $column . '.';
                }
            }
        }
    }
    // If no validation errors, proceed to insert the record(s) in the database
    if (!$error_msg) {
        // Generate a new random password
        $newPassword = generateRandomPassword(); // You can specify the length as a parameter

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Add the hashed password to the data array
        $data['password'] = $hashedPassword;

        // Regsitered field
        $data['registered'] = date('Y-m-d H:i:s'); 

        // Insert the records
        $stmt = $pdo->prepare('INSERT INTO ' . $Employee . ' (' . implode(', ', array_keys($data)) . ') VALUES (' . implode(', ', array_map(function ($column) {
            return ':' . $column;
        }, array_keys($data))) . ')');

        // Bind over the data to the placeholders in the prepared statement
        foreach ($data as $column => $value) {
            $stmt->bindValue(':' . $column, $value);
        }

        // Execute the SQL statement
        $stmt->execute();

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
            $mail->addAddress($data['email'], ($data['email']));

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Starpoint Credentials';
            $mail->Body = '
                <html>
                <head>
                <title>Welcome to StarPoint Insurance</title>
                </head>
                <body>
                <h1>Welcome to StarPoint Insurance!</h1>
                <p>Hello ' . htmlspecialchars($data['first_name']) . ',</p>
                <p>We are excited to have you on board. Your account has been created successfully. Here are your initial login details:</p>
                <p><strong>Email:</strong> ' . htmlspecialchars($data['email']) . '<br>
                    <strong>Password:</strong> ' . htmlspecialchars($newPassword) . '</p>
                <p>For your security, please ensure to change this password as soon as you log in. Remember, your password is confidential and should not be shared with anyone.</p>
                <p>If you have any questions or encounter any issues, please do not hesitate to contact our support team.</p>
                <p>Best regards,<br>
                The StarPoint Insurance Team</p>
                </body>
                </html>
            ';

            $mail->send();
            
        } catch (Exception $e) {
            $error_msg =  "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        // Output message
        $success_msg = 'Message has been sent and Employee Created!';
    }

}
?>
<?=CRM_header('Employee- Create')?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?= CRM_topbar($_SESSION) ?>

            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div class="wrap">
                        <h1 class="h3 mb-0 text-gray-800">Create Employee Contact</h1>
                    </div>
                </div>

                <form action="" method="post" class="crud-form">

                    <div class="cols">
                        <?php foreach ($Employee_Admin_Columns as $column => $array): ?>
                        <?php if (isset($array['input'])): ?>
                        <?php $input = $array['input']; ?>
                        <div class="style-form-control">
                            <label for="<?=$column?>"><?=$array['label']?></label>
                            <?php if ($input['type'] == 'text' || $input['type'] == 'hidden' || $input['type'] == 'email' || $input['type'] == 'number' || $input['type'] == 'tel'): ?>
                            <input id="<?=$column?>" type="<?=$input['type']?>" name="<?=$column?>" placeholder="<?=$input['placeholder']?>" <?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
                            <?php elseif ($input['type'] == 'datetime-local'): ?>
                            <input id="<?=$column?>" type="<?=$input['type']?>" name="<?=$column?>"<?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?> value="<?=date('Y-m-d\TH:i')?>">
                            <?php elseif ($input['type'] == 'date'): ?>
                            <input id="<?=$column?>" type="<?=$input['type']?>" name="<?=$column?>"<?=$input['required'] ? ' required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?> value="<?=date('Y-m-d')?>">
                            <?php elseif ($input['type'] == 'textarea'): ?>
                            <textarea id="<?=$column?>" name="<?=$column?>" <?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
                                <?=htmlspecialchars(" ")?>
                            </textarea>
                            <?php elseif ($input['type'] == 'time'): ?>
                            <input type="time" id="<?= $column ?>" name="<?= $column ?>" placeholder="<?= $input['placeholder'] ?>" <?= $input['required'] ? 'required' : '' ?> <?= isset($input['custom']) ? $input['custom'] : '' ?>>
                            <?php elseif ($input['type'] == 'select'): ?>
                            <select id="<?=$column?>" name="<?=$column?>" <?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
                                <?php foreach ($input['options'] as $option): ?>
                                <option value="<?=$option?>"><?=$option?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php elseif ($input['type'] == 'checkbox'): ?>
                            <input id="<?=$column?>" type="hidden" name="<?=$column?>" value="0" <?=isset($input['custom']) ? $input['custom'] : ''?>>
                            <input type="<?=$input['type']?>" name="<?=$column?>" value="1" <?=isset($input['custom']) ? $input['custom'] : ''?>>
                            <?php elseif ($input['type'] == 'radio'): ?>
                            <?php foreach ($input['options'] as $option): ?>
                            <div class="">
                                <input id="<?=$option?>" type="<?=$input['type']?>" name="<?=$column?>" value="<?=$option?>" <?=isset($input['custom']) ? $input['custom'] : ''?>>
                                <label for="<?=$option?>"><?=$option?></label>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($error_msg): ?>
                    <p class="msg-error"><?=$error_msg?></p>
                    <?php endif; ?>

                    <?php if ($success_msg): ?>
                    <p class="msg-success"><?=$success_msg?></p>
                    <a href="admin.php">Return to Admin Panel</a>
                    <?php endif; ?>

                    <button type="submit" name="submit" class="btn">Save Record</button>

                </form>
            </div>
            <?=CRM_footer()?>
        </div>
    </div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    
    <?=js_torun()?>
    <?=logout_modal()?>
</body>
</html>





