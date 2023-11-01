<?php
include '../../php/functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Error message
$error_msg = '';
// Success message
$success_msg = '';
// Check if POST data exists (user submitted the form)
if (isset($_POST['submit'], $_POST['policy_number'], $_POST['broker_name'], $_POST['broker_npn'], $_POST['first_name'], $_POST['last_name'], $_POST['state'], $_POST['member_dob'], $_POST['title'], $_POST['created'])) {
    // Validate form data
    $data = [
        'policy_number' => $_POST['policy_number'],
        'broker_name' => $_POST['broker_name'],
        'broker_npn' => $_POST['broker_npn'],
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'state' => $_POST['state'],
        'member_dob' => $_POST['member_dob'],
        'created' => date('Y-m-d H:i:s', strtotime($_POST['created']))
    ];
    // Validate fields
    if (empty($data['policy_number']) || empty($data['broker_name']) || empty($data['broker_npn']) || empty($data['first_name']) || empty($data['last_name']) || empty($data['state']) || empty($data['member_dob']) || empty($data['created'])) {
        $error_msg = 'Please fill out all required fields!';
    } else if (!preg_match('/^[0-9]+$/', $data['broker_npn'])) {
        $error_msg = 'Please provide a valid Broker NPN!';
    }

    // If no validation errors, proceed to insert the record(s) in the database
    if (!$error_msg) {
        // Insert the records, assuming you're using PDO for database connection
        $stmt = $pdo->prepare('INSERT INTO Ambetter (policy_number, broker_name, broker_npn, first_name, last_name, state, member_dob, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([ $data['policy_number'], $data['broker_name'], $data['broker_npn'], $data['first_name'], $data['last_name'], $data['state'], $data['member_dob'], $data['created'] ]);

        // Output message
        $success_msg = 'Created Successfully!';
    }
}
?>
<?=CRM_header('Ambetter- Create')?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top">

            </nav>

            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div class="wrap">
                        <h1 class="h3 mb-0 text-gray-800">Create Ambetter Contact</h1>
                    </div>
                </div>

                <form action="" method="post" class="crud-form">

                    <div class="cols">

                        <div class="style-form-control">
                            <label for="policy_number">Policy Number</label>
                            <input type="text" name="policy_number" id="policy_number" placeholder="123456" required>
                        </div>

                        <div class="style-form-control">
                            <label for="broker_name">Broker Name</label>
                            <input type="text" name="broker_name" id="first_name" placeholder="Erick Delgado" required>
                        </div>

                        <div class="style-form-control">
                            <label for="broker_npn">Broker NPN</label>
                            <input type="text" name="broker_npn" id="broker_npn" placeholder="NPN #" required>
                        </div>

                        <div class="style-form-control">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" placeholder="Hello" required>
                        </div>

                        <div class="style-form-control">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" placeholder="World" required>
                        </div>

                        <div class="style-form-control">
                            <label for="State">State</label>
                            <input type="text" name="state" id="state" placeholder="FL, GA, HI" required>
                        </div>

                        <div class="style-form-control">
                            <label for="member_dob">Member Date of Birth</label>
                            <input type="text" name="member_dob" id="member_dob" placeholder="2023-04-12" required>
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
    </div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    
    <?=js_torun()?>
</body>
</html>





