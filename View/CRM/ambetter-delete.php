<?php
include '../../php/functions.php';
session_start();

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Check if we are logged in
check_loggedin($pdo, '../index.php');

// Output message
$msg = '';
// Check that the contact policy_number exists
if (isset($_GET['policy_number'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM Ambetter WHERE policy_number = ?');
    $stmt->execute([ $_GET['policy_number'] ]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Contact doesn\'t exist with that policy_number!');
    }
    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM Ambetter WHERE policy_number = ?');
            $stmt->execute([ $_GET['policy_number'] ]);
            $msg = 'You have deleted the contact!';
        } else {
            // User clicked the "No" button, redirect them back to the ambetter page
            header('Location: ambetter.php');
            exit;
        }
    }
} else {
    exit('No policy_number specified!');
}
?>
<?=CRM_header('Ambetter - Delete')?>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?= CRM_topbar($_SESSION) ?>

        <div class= container-fluid>
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <div class="wrap">
                    <h1 class="h3 mb-0 text-gray-800">Delete Ambetter Contact #<?=$contact['policy_number']?></h1>
                </div>
            </div>

            <form action="" method="get" class="crud-form">

                <input type="hidden" name="policy_number" value="<?=$contact['policy_number']?>">

                <?php if ($msg): ?>
                <p class="msg-success"><?=$msg?></p>
                <?php else: ?>
                <p>Are you sure you want to delete contact #<?=$contact['policy_number']?>?</p>
                <div class="btns">
                    <button type="submit" name="confirm" value="yes" class="btn red">Yes</button>
                    <button type="submit" name="confirm" value="no" class="btn">No</button>
                </div>
                <?php endif; ?>

            </form>
        </div>
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    </div>
    <?=CRM_footer()?>
    <?=js_torun()?>
    <?=logout_modal()?>
</div>

