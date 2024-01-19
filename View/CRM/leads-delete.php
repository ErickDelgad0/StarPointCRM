<?php
include '../../php/functions.php';
session_start();

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Check if we are logged in
check_loggedin($pdo, '../index.php');

// Output message
$msg = '';
// Check that the contact id exists
if (isset($_GET['id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM Leads WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Contact doesn\'t exist with that id!');
    }
    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            $pdo->beginTransaction();

            try {
                // Delete linked records in the 'events' table
                // When a lead is destroyed so will its calendar counterpart
                $stmt = $pdo->prepare('DELETE FROM events WHERE lead_id = ?');
                $stmt->execute([ $_GET['id'] ]);

                // After deleting linked record from 'events' we will delete the record in the 'Leads' table
                $stmt = $pdo->prepare('DELETE FROM Leads WHERE id = ?');
                $stmt->execute([ $_GET['id'] ]);

                // Commit the transaction
                $pdo->commit();
                $msg = 'You have deleted the contact and related calendar events!';
            } catch (PDOException $e) {
                // An error occurred, roll back the transaction
                $pdo->rollBack();
                exit('Error deleting contact: ' . $e->getMessage());
            }
        } else {
            // User clicked the "No" button, redirect them back to the Leads page
            header('Location: leads.php');
            exit;
        }
    }

} else {
    exit('No id specified!');
}
?>
<?=CRM_header('Leads - Delete')?>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?= CRM_topbar($_SESSION) ?>

        <div class= container-fluid>
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <div class="wrap">
                    <h1 class="h3 mb-0 text-gray-800">Delete Lead Contact #<?=$contact['id']?></h1>
                </div>
            </div>

            <form action="" method="get" class="crud-form">

                <input type="hidden" name="id" value="<?=$contact['id']?>">

                <?php if ($msg): ?>
                <p class="msg-success"><?=$msg?></p>
                <?php else: ?>
                <p>Are you sure you want to delete contact #<?=$contact['id']?>?</p>
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

