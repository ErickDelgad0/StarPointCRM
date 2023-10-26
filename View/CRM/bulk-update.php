<?php
include '../../php/functions.php';
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
$stmt = $pdo->prepare('SELECT * FROM Customers WHERE id IN (' . $ids_placeholders . ')');
$stmt->execute($ids);
$Customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Customers don't exist with the specified IDs, so output error message and stop the script
if (!$Customers) {
    exit('Records don\'t exist with those IDs!');
}
// Check if POST data exists (user submitted the form)
if (isset($_POST['submit'])) {
    // Iterate the IDs
    foreach ($ids as $id) {
        // Validate form data
        $data = [
            'first_name' => $_POST['first_name_' . $id],
            'last_name' => $_POST['last_name_' . $id],
            'email' => $_POST['email_' . $id],
            'phone' => $_POST['phone_' . $id],
            'title' => $_POST['title_' . $id],
            'created' => date('Y-m-d H:i:s', strtotime($_POST['created_' . $id]))
        ];
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['phone']) || empty($data['title']) || empty($data['created'])) {
            $error_msg[] = 'Please fill out all required fields!';
        } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $error_msg[] = 'Please provide a valid email address!';
        } else if (!preg_match('/^[0-9]+$/', $data['phone'])) {
            $error_msg[] = 'Please provide a valid phone number!';
        }
        // If no validation errors, proceed to update the record(s) in the database
        if (!$error_msg) {
            // Update the record
            $stmt = $pdo->prepare('UPDATE Customers SET first_name = ?, last_name = ?, email = ?, phone = ?, title = ?, created = ? WHERE id = ?');
            $stmt->execute([ $data['first_name'], $data['last_name'], $data['email'], $data['phone'], $data['title'], $data['created'], $id ]);
        }
    }
    // Success
    if (!$error_msg) {
        // Retrieve the updated Customers
        $stmt = $pdo->prepare('SELECT * FROM Customers WHERE id IN (' . $ids_placeholders . ')');
        $stmt->execute($ids);
        $Customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Output success message
        $success_msg = 'Updated Successfully!';
    }
}
?>
<?=CRM_header('Bulk Update')?>

<div class="content update">

    <div class="page-title">
		<i class="fa-solid fa-user fa-lg"></i>
		<div class="wrap">
			<h2>Bulk Update</h2>
			<p>Update multiple Customers at once.</p>
		</div>
	</div>

    <form action="?ids=<?=$_GET['ids']?>" method="post" class="crud-form">

        <?php foreach ($Customers as $contact): ?> 
        <div class="cols">

            <h2>Contact <?=$contact['id']?></h2>

            <div class="form-control">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name_<?=$contact['id']?>" id="first_name" value="<?=$contact['first_name']?>" placeholder="John" required>
            </div>

            <div class="form-control">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name_<?=$contact['id']?>" id="last_name" value="<?=$contact['last_name']?>" placeholder="Doe" required>
            </div>

            <div class="form-control">
                <label for="email">Email</label>
                <input type="email" name="email_<?=$contact['id']?>" id="email" value="<?=$contact['email']?>" placeholder="Email Address" required>
            </div>

            <div class="form-control">
                <label for="phone">Phone</label>
                <input type="tel" name="phone_<?=$contact['id']?>" id="phone" value="<?=$contact['phone']?>" placeholder="Phone Number" required>
            </div>

            <div class="form-control">
                <label for="title">Title</label>
                <select name="title_<?=$contact['id']?>" id="title" required>
                    <option value="Employee"<?=$contact['title']=='Employee'?' selected':''?>>Employee</option>
                    <option value="Assistant"<?=$contact['title']=='Assistant'?' selected':''?>>Assistant</option>
                    <option value="Manager"<?=$contact['title']=='Manager'?' selected':''?>>Manager</option>
                </select>
            </div>

            <div class="form-control">
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

<?=template_footer()?>