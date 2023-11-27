<?php
include("../../php/functions.php");
session_start();

$pdo = pdo_connect_mysql();
check_loggedin($pdo, '../index.php');

// Retrieve user ID from session
$userId = $_SESSION['id'];

// Error message
$error_msg = '';
// Success message
$success_msg = '';
// Ensure contact "ID" param exists
if (!isset($userId)) {
    exit('No ID specified!');
}
// Get the contact
$stmt = $pdo->prepare('SELECT * FROM ' . $Employee . ' WHERE id = ?');
$stmt->execute([ $userId ]);
$contact = $stmt->fetch(PDO::FETCH_ASSOC);
// Contact doesn't exist with the specified ID, so output error message and stop the script
if (!$contact) {
    exit('Contact doesn\'t exist with that ID!');
}
// Check if POST data exists (user submitted the form)
if (isset($_POST['submit'])) {
    // Iterate through the fields and extract the data from the form
    $data = [];
    foreach ($Employee_Profile_Columns as $column => $array) {
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
    // If no validation errors, proceed to update the record(s) in the database
    if (!$error_msg) {
        // Update the record
        $stmt = $pdo->prepare('UPDATE ' . $Employee . ' SET ' . implode(', ', array_map(function ($column) {
            return $column . ' = :' . $column;
        }, array_keys($data))) . ' WHERE id = :id');
        // bind over the data to the placeholders in the prepared statement
        foreach ($data as $column => $value) {
            $stmt->bindValue(':' . $column, $value);
        }
        // Bind ID
        $stmt->bindValue(':id', $userId);
        // Execute the SQL statement
        $stmt->execute();
        // Get the updated contact from the contacts table
        $stmt = $pdo->prepare('SELECT * FROM  ' . $Employee . ' WHERE id = ?');
        $stmt->execute([ $userId ]);
        $contact = $stmt->fetch(PDO::FETCH_ASSOC);
        // Output message
        $success_msg = 'Updated Successfully!';
    }
}
?>


<?CRM_header("Profile")?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
.card {
    box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: .25rem;
}

.card-body {
    flex: 1 1 auto;
    min-height: 1px;
    padding: 1rem;
}

.gutters-sm {
    margin-right: -8px;
    margin-left: -8px;
}

.gutters-sm>.col, .gutters-sm>[class*=col-] {
    padding-right: 8px;
    padding-left: 8px;
}
.mb-3, .my-3 {
    margin-bottom: 1rem!important;
}

.bg-gray-300 {
    background-color: #e2e8f0;
}
.h-100 {
    height: 100%!important;
}
.shadow-none {
    box-shadow: none!important;
}

    </style>
</head>
<body>
    <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
    <?=CRM_topbar($_SESSION)?>

    <div class="container">
    <div class="main-body">

<?php if (!isset($_GET['action'])): ?>
    <div class="row gutters-sm">
    <div class="col-md-4 mb-3">
    <div class="card">
    <div class="card-body">
    <div class="d-flex flex-column align-items-center text-center">
    <img src="../../img/undraw_profile.svg" alt="Photo" class="rounded-circle" width="150">
    <div class="mt-3">
    <h4><?=$contact['first_name']?> <?=$contact['last_name']?></h4>
    <!-- <p class="text-secondary mb-1">Full Stack Developer</p>
    <p class="text-muted font-size-sm">Bay Area, San Francisco, CA</p> -->
    <!-- <button class="btn btn-primary">Follow</button>
    <button class="btn btn-outline-primary">Message</button> -->
    </div>
    </div>
    </div>
    </div>
    </div>
    <div class="col-md-8">
    <div class="card mb-3">
    <div class="card-body">
    <div class="row">
    <div class="col-sm-3">
    <h6 class="mb-0">Full Name</h6>
    </div>
    <div class="col-sm-9 text-secondary">
    <?=$contact['first_name']?> <?=$contact['last_name']?>
    </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-3">
    <h6 class="mb-0">Email</h6>
    </div>
    <div class="col-sm-9 text-secondary">
    <?=$contact['email']?>
    </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-3">
    <h6 class="mb-0">Phone</h6>
    </div>
    <div class="col-sm-9 text-secondary">
    <?php
    if (empty($contact['phone'])) {
        echo "Unknown";
    } else {
        echo $contact['phone'];
    }
    ?>

    </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-3">
    <h6 class="mb-0">role</h6>
    </div>
    <div class="col-sm-9 text-secondary">
    <?=$contact['role']?>
    </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-3">
    <h6 class="mb-0">Joined</h6>
    </div>
    <div class="col-sm-9 text-secondary">
    <?=$contact['registered']?>
    </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-12">
    <a class="btn btn-info " target="__blank" href="profile.php?action=edit">Edit</a>
    <a class="btn btn-info " target="__blank" href="profile.php?action=change_password">Change Password</a>
    <?php
    if ($contact['role'] == 'admin') {
        echo '<a class="btn btn-info" target="_blank" href="admin.php">Admin Panel</a>';
    }
    ?>

    </div>
    </div>
    </div>
    </div>


    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

    <?php elseif ($_GET['action'] == 'edit'): ?>
        <div class=container-fluid>

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <div class="wrap">
                    <h1 class="h3 mb-0 text-gray-800">Update <?=$contact['username']?></h1>
                </div>
            </div>

            <form action="?id=<?=$contact['id']?>" method="post" class="crud-form">

                <div class="cols">
                    <?php foreach ($Employee_Profile_Columns as $column => $array): ?>
                    <?php if (isset($array['input'])): ?>
                    <?php $input = $array['input']; ?>
                    <div class="style-form-control">
                        <label for="<?=$column?>"><?=$array['label']?></label>
                        <?php if ($input['type'] == 'text' || $input['type'] == 'hidden' || $input['type'] == 'email' || $input['type'] == 'number' || $input['type'] == 'tel'): ?>
                        <input id="<?=$column?>" type="<?=$input['type']?>" name="<?=$column?>" placeholder="<?=$input['placeholder']?>" value="<?=htmlspecialchars((string)$contact[$column], ENT_QUOTES)?>" <?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
                        <?php elseif ($input['type'] == 'date'): ?>
                        <input id="<?=$column?>" type="<?=$input['type']?>" name="<?=$column?>"<?=$input['required'] ? ' required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?> value="<?=date('Y-m-d')?>">
                        <?php elseif ($input['type'] == 'datetime-local'): ?>
                        <input id="<?=$column?>" type="<?=$input['type']?>" name="<?=$column?>" value="<?=date('Y-m-d\TH:i', strtotime($contact[$column]))?>" <?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
                        <?php elseif ($input['type'] == 'textarea'): ?>
                        <textarea id="<?=$column?>" name="<?=$column?>" placeholder="<?=$input['placeholder']?>" <?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>><?=htmlspecialchars($contact[$column], ENT_QUOTES)?></textarea>
                        <?php elseif ($input['type'] == 'select'): ?>
                        <select id="<?=$column?>" name="<?=$column?>" <?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
                            <?php foreach ($input['options'] as $option): ?>
                            <option value="<?=$option?>" <?=$contact[$column] == $option ? 'selected' : ''?>><?=$option?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php elseif ($input['type'] == 'checkbox'): ?>
                        <input id="<?=$column?>" type="hidden" name="<?=$column?>" value="0" <?=isset($input['custom']) ? $input['custom'] : ''?>>
                        <input type="<?=$input['type']?>" name="<?=$column?>" value="1" <?=$contact[$column] == 1 ? 'checked' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
                        <?php elseif ($input['type'] == 'radio'): ?>
                        <?php foreach ($input['options'] as $option): ?>
                        <div>
                            <input id="<?=$option?>" type="<?=$input['type']?>" name="<?=$column?>" value="<?=$option?>" <?=$contact[$column] == $option ? 'checked' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
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
                <?php endif; ?>

                <button type="submit" name="submit" class="btn">Save Record</button>

            </form>
        </div>
    <?php elseif ($_GET['action'] == 'change_password'): ?>
        

<?php endif; ?>

<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
	
</script>

<?=CRM_footer()?>
<?=js_torun()?>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

</body>
</html>