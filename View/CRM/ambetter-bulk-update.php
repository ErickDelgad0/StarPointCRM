<?php
include '../../php/functions.php';

$valid_states = "AL|AK|AZ|AR|CA|CO|CT|DE|FL|GA|HI|ID|IL|IN|IA|KS|KY|LA|ME|MD|MA|MI|MN|MS|MO|MT|NE|NV|NH|NJ|NM|NY|NC|ND|OH|OK|OR|PA|RI|SC|SD|TN|TX|UT|VT|VA|WA|WV|WI|WY";

// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Error message
$error_msg = [];
// Success message
$success_msg = '';
// Ensure contact "ID" param exists
if (!isset($_GET['ids'])) {
    exit('No IDs specified!');
}
// Get the Customers from teh prepared statement
$ids = explode(',', $_GET['ids']);
// Make sure there are IDs
if (!$ids) {
    exit('No IDs specified!');
}
// Create a placeholder string for the IDs
$ids_placeholders = implode(',', array_fill(0, count($ids), '?'));
// Get the Customers from the Customers table
$stmt = $pdo->prepare('SELECT * FROM Ambetter WHERE id IN (' . $ids_placeholders . ')');
$stmt->execute($ids);
$Customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Customers don't exist with the specified IDs, so output error message and stop the script
if (!$Customers) {
    exit('Records don\'t exist with those id\'s!');
}
// Check if POST data exists (user submitted the form)
if (isset($_POST['submit'])) {
    // Iterate the IDs
    foreach ($ids as $id) {
        // Validate form data
        $data = [
            'broker_name' => $_POST['broker_name_' . $id],
            'broker_npn' => $_POST['broker_npn_' . $id],
            'policy_number' => $_POST['policy_number_' . $id],
            'first_name' => $_POST['first_name_' . $id],
            'last_name' => $_POST['last_name_' . $id],
            'state' => $_POST['state_' . $id],
            'member_DOB' => $_POST['member_DOB_' . $id],
        ];
        
        if (empty($data['broker_name']) || empty($data['broker_npn']) || empty($data['first_name']) || empty($data['last_name']) || empty($data['state']) || empty($data['member_DOB']) || empty($data['member_phone_number'])) {
            $error_msg[] = 'Please fill out all required fields!';
        } else if (!preg_match('/^[0-9]+$/', $data['phone'])) {
            $error_msg[] = 'Please provide a valid phone number!';
        } else if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $data['member_DOB'])) {
            $error_msg[] = 'Please provide a valid date in the format YYYY-MM-DD!';
        } else if (!preg_match('/^(' . $valid_states . ')$/', $data['state'])) {
            $error_msg[] = 'Please provide a valid state abbreviation in uppercase!';
        }
        
        
        // If no validation errors, proceed to update the record(s) in the database
        if (!$error_msg) {
            // Update the record
            $stmt = $pdo->prepare('UPDATE Ambetter SET broker_name = ?, broker_npn = ?, policy_number = ?, first_name = ?, last_name = ?, state = ?, member_DOB = ?, member_phone_number = ? WHERE policy_number = ?');
            $stmt->execute([ $data['broker_name'], $data['broker_npn'], $data['policy_number'], $data['first_name'], $data['last_name'], $data['state'], $data['member_DOB'], $data['member_phone_number'], $id ]);
        }
    }
    // Success
    if (!$error_msg) {
        // Retrieve the updated Ambetter
        $stmt = $pdo->prepare('SELECT * FROM Ambetter WHERE policy_number IN (' . $ids_placeholders . ')');
        $stmt->execute($ids);
        $Customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Output success message
        $success_msg = 'Updated Successfully!';
    }
}
?>
<?=CRM_header('Ambetter - Bulk Update')?>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top"></nav>

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <div class="wrap">
                    <h2>Bulk Ambetter Update</h2>
                </div>
            </div>

            <form action="?ids=<?=$_GET['ids']?>" method="post" class="crud-form">

                <?php foreach ($Customers as $contact): ?> 
                <div class="cols">

                    <h2>Contact <?=$contact['id']?></h2>

                    <div class="style-form-control">
                        <label for="policy_number">Policy Number</label>
                        <input type="text" name="policy_number_<?=$contact['id']?>" id="policy_number" value="<?=$contact['policy_number']?>" placeholder="N123456" required>
                    </div>

                    <div class="style-form-control">
                        <label for="broker_name">Broker Name</label>
                        <input type="text" name="broker_name_<?=$contact['id']?>" id="broker_name" value="<?=$contact['broker_name']?>" placeholder="Erick" required>
                    </div>

                    <div class="style-form-control">
                        <label for="broker_name">Broker NPN</label>
                        <input type="text" name="broker_npn_<?=$contact['id']?>" id="broker_npn" value="<?=$contact['broker_npn']?>" placeholder="Delgado" required>
                    </div>

                    <div class="style-form-control">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name_<?=$contact['id']?>" id="first_name" value="<?=$contact['first_name']?>" placeholder="Michael" required>
                    </div>

                    <div class="style-form-control">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name_<?=$contact['id']?>" id="last_name" value="<?=$contact['last_name']?>" placeholder="Jackson" required>
                    </div>

                    <div class="style-form-control">
                        <label for="state">State</label>
                        <input type="text" name="state_<?=$contact['id']?>" id="state" value="<?=$contact['state']?>" placeholder="FL, GA, HI..." required>
                    </div>

                    <div class="style-form-control">
                        <label for="member_dob">Member DOB</label>
                        <input type="text" name="member_DOB_<?=$contact['id']?>" id="member_DOB" value="<?=$contact['member_DOB']?>" placeholder="FL, GA, HI..." required>
                    </div>

                    <div class="style-form-control">
                        <label for="created">Created</label>
                        <input type="datetime-local" name="created_<?=$contact['id']?>" id="created" value="<?=date('Y-m-d\TH:i', strtotime($contact['created']))?>" placeholder="Created" required>
                    </div>

                </div>
                <?php endforeach; ?>

                <?php if ($error_msg): ?>
                <p class="msg-error"><?=implode('<br>', $error_msg)?></p>
                <?php elseif ($success_msg): ?>
                <p class="msg-success"><?=$success_msg?></p>
                <?php endif; ?>

                <button type="submit" name="submit" class="btn">Save Records</button>

                </form>
        </div>
        <?=CRM_footer()?>
    </div>
</div>