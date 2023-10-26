<?php
include '../../php/functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Output message
$msg = '';
// Check that the contact ID exists
if (isset($_GET['id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Contact doesn\'t exist with that ID!');
    }
    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM contacts WHERE id = ?');
            $stmt->execute([ $_GET['id'] ]);
            $msg = 'You have deleted the contact!';
        } else {
            // User clicked the "No" button, redirect them back to the ambetter page
            header('Location: ambetter.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>
<?=CRM_header('Delete')?>

<div class="content delete">

    <div class="page-title">
		<i class="fa-solid fa-user fa-lg"></i>
		<div class="wrap">
			<h2>Delete Contact #<?=$contact['id']?></h2>
			<p>The contact will be permanently deleted from the database.</p>
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

<?=CRM_footer()?>