<?php
include '../php/functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Error message
$error_msg = '';
// Success message
$success_msg = '';
// Ensure contact "ID" param exists
if (!isset($_GET['id'])) {
    exit('No ID specified!');
}
// Get the contact from the Customers table
$stmt = $pdo->prepare('SELECT * FROM Customers WHERE id = ?');
$stmt->execute([ $_GET['id'] ]);
$contact = $stmt->fetch(PDO::FETCH_ASSOC);
// Contact doesn't exist with the specified ID, so output error message and stop the script
if (!$contact) {
    exit('Contact doesn\'t exist with that ID!');
}
// Check if POST data exists (user submitted the form)
if (isset($_POST['submit'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'], $_POST['title'], $_POST['created'])) {
    // Validate form data
    $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'title' => $_POST['title'],
        'created' => date('Y-m-d H:i:s', strtotime($_POST['created']))
    ];
    // Validate the form data
    if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['phone']) || empty($data['title']) || empty($data['created'])) {
        $error_msg = 'Please fill out all required fields!';
    } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $error_msg = 'Please provide a valid email address!';
    } else if (!preg_match('/^[0-9]+$/', $data['phone'])) {
        $error_msg = 'Please provide a valid phone number!';
    }
    // If no validation errors, proceed to update the record(s) in the database
    if (!$error_msg) {
        // Update the contact in the database
        $stmt = $pdo->prepare('UPDATE Customers SET first_name = ?, last_name = ?, email = ?, phone = ?, title = ?, created = ? WHERE id = ?');
        $stmt->execute([ $data['first_name'], $data['last_name'], $data['email'], $data['phone'], $data['title'], $data['created'], $_GET['id'] ]);
        // Get the updated contact from the Customers table
        $stmt = $pdo->prepare('SELECT * FROM Customers WHERE id = ?');
        $stmt->execute([ $_GET['id'] ]);
        $contact = $stmt->fetch(PDO::FETCH_ASSOC);
        // Output message
        $success_msg = 'Updated Successfully!';
    }
}
?>
<?=CRM_header('Update')?>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top">

        </nav>

        <div class=container-fluid>
            <div class="content update">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div class="wrap">
                        <h1 class="h3 mb-0 text-gray-800">Update Ambetter Contact #<?=$contact['id']?></h1>
                    </div>
                </div>

                <form action="?id=<?=$contact['id']?>" method="post" class="crud-form">

                    <div class="cols">

                        <div class="style-form-control">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="<?=$contact['first_name']?>" placeholder="John" required>
                        </div>

                        <div class="style-form-control">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="<?=$contact['last_name']?>" placeholder="Doe" required>
                        </div>

                        <div class="style-form-control">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" value="<?=$contact['email']?>" placeholder="Email Address" required>
                        </div>

                        <div class="style-form-control">
                            <label for="phone">Phone</label>
                            <input type="tel" name="phone" id="phone" value="<?=$contact['phone']?>" placeholder="Phone Number" required>
                        </div>

                        <div class="style-form-control">
                            <label for="title">Title</label>
                            <select name="title" id="title" required>
                                <option value="default"<?=$contact['title']=='Employee'?' selected':''?>>Default</option>
                                <option value="employee"<?=$contact['title']=='Assistant'?' selected':''?>>Employee</option>
                                <option value="admin"<?=$contact['title']=='Manager'?' selected':''?>>Admin</option>
                            </select>
                        </div>

                        <div class="style-form-control">
                            <label for="created">Created</label>
                            <input type="datetime-local" name="created" id="created" value="<?=date('Y-m-d\TH:i', strtotime($contact['created']))?>" placeholder="Created" required>
                        </div>

                    </div>

                    <?php if ($error_msg): ?>
                    <p class="msg-error"><?=$error_msg?></p>
                    <?php endif; ?>

                    <?php if ($success_msg): ?>
                    <p class="msg-success"><?=$success_msg?></p>
                    <?php endif; ?>

                    <button type="submit" name="submit" class="btn">Save Record</button>

                </form>
            </div>
            <?=CRM_footer()?>
        </div>
    </div>
</div>





