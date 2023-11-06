<?php
include '../../php/functions.php'; // Ensure the path to your functions is correct
session_start();

$pdo = pdo_connect_mysql();

// Check if we are logged in
check_loggedin($pdo, '../index.php');

$error_msg = '';
$success_msg = '';

// Check if POST data exists (user submitted the form)
if (isset($_POST['submit'])) {
    // Validate the CSV file
    if (empty($_FILES['csv']['tmp_name'])) {
        $error_msg = 'Please select a CSV file to import!';
    } else if (strtolower(pathinfo($_FILES['csv']['name'], PATHINFO_EXTENSION)) != 'csv') {
        $error_msg = 'File must be a CSV file type!';
    } else {
        // Parse the CSV file
        $csv = array_map('str_getcsv', file($_FILES['csv']['tmp_name']));
        // Validate the header
        $header = array_shift($csv);
        $Ambetter_Columns = array_keys($Ambetter_Columns);
        if ($header != $Ambetter_Columns) {
            $error_msg = 'The CSV header must match the Ambetter columns!';
        } else {
            // Import the CSV data
            foreach ($csv as $row) {
                $stmt = $pdo->prepare('INSERT INTO ' . $Ambetter . ' (' . implode(', ', $Ambetter_Columns) . ') VALUES (' . implode(', ', array_map(function ($column) {
                    return ':' . $column;
                }, $Ambetter_Columns)) . ')');
                foreach ($row as $key => $value) {
                    // check if datetime
                    if (in_array($Ambetter_Columns[$key], ['created', 'updated'])) {
                        $value = date('Y-m-d H:i:s', strtotime($value));
                    }
                    $stmt->bindValue(':' . $Ambetter_Columns[$key], $value);
                }
                $stmt->execute();
            }
            // Output message with number of records imported
            $success_msg = 'Imported ' . count($csv) . ' record(s) from CSV file!';
        }
    }
}

?>

<?=CRM_header('Ambetter - Import')?>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top"></nav>

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <div class="wrap">
                    <h2>Ambetter Import</h2>
                </div>
            </div>

            <form action="" method="post" enctype="multipart/form-data" class="crud-form">

                <div class="cols">
                    <div class="style-form-control">
                        <label for="csv">CSV File</label>
                        <input type="file" name="csv" id="csv" accept=".csv" required>
                    </div>
                </div>

                <?php if ($error_msg): ?>
                <p class="msg-error"><?=$error_msg?></p>
                <?php endif; ?>

                <?php if ($success_msg): ?>
                <p class="msg-success"><?=$success_msg?></p>
                <?php endif; ?>

                <button type="submit" name="submit" class="btn">Import</button>

            </form>
        </div>
    </div>
    <?=CRM_footer()?>
    <?=js_torun()?>
</div>

