<?php
include '../php/functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Error message
$error_msg = '';
// Success message
$success_msg = '';
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
    if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['phone']) || empty($data['title']) || empty($data['created'])) {
        $error_msg = 'Please fill out all required fields!';
    } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $error_msg = 'Please provide a valid email address!';
    } else if (!preg_match('/^[0-9]+$/', $data['phone'])) {
        $error_msg = 'Please provide a valid phone number!';
    }
    // If no validation errors, proceed to insert the record(s) in the database
    if (!$error_msg) {
        // Insert the records
        $stmt = $pdo->prepare('INSERT INTO Customers (first_name, last_name, email, phone, title, created) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([ $data['first_name'], $data['last_name'], $data['email'], $data['phone'], $data['title'], $data['created'] ]);
        // Output message
        $success_msg = 'Created Successfully!';
    }
}
?>
<?=CRM_header('Create')?>
<div class=container-fluid>
    <div class="content update">

        <form action="" method="post" class="crud-form">

            <div class="cols">

                <div class="style-form-control">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" placeholder="John" required>
                </div>

                <div class="style-form-control">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" placeholder="Doe" required>
                </div>

                <div class="style-form-control">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email Address" required>
                </div>

                <div class="style-form-control">
                    <label for="phone">Phone</label>
                    <input type="tel" name="phone" id="phone" placeholder="Phone Number" required>
                </div>

                <div class="style-form-control">
                    <label for="title">Title</label>
                    <select name="title" id="title" required>
                        <option value="default">Default</option>
                        <option value="employee">Employee</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="style-form-control">
                    <label for="created">Created</label>
                    <input type="datetime-local" name="created" id="created" placeholder="Created" value="<?=date('Y-m-d\TH:i')?>" required>
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



