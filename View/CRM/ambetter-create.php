<?php
include '../../php/functions.php';
session_start();

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Check if we are logged in
check_loggedin($pdo, '../index.php');

// Error message
$error_msg = '';
// Success message
$success_msg = '';
// Check if POST data exists (user submitted the form)
if (isset($_POST['submit'])) {
    // Iterate through the fields and extract the data from the form
    $data = [];
    foreach ($Ambetter_Columns as $column => $array) {
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
    // If no validation errors, proceed to insert the record(s) in the database
    if (!$error_msg) {
        // Insert the records
        $stmt = $pdo->prepare('INSERT INTO ' . $Ambetter . ' (' . implode(', ', array_keys($data)) . ') VALUES (' . implode(', ', array_map(function ($column) {
            return ':' . $column;
        }, array_keys($data))) . ')');
        // bind over the data to the placeholders in the prepared statement
        foreach ($data as $column => $value) {
            $stmt->bindValue(':' . $column, $value);
        }
        // Execute the SQL statement
        $stmt->execute();
        // Output message
        $success_msg = 'Created Successfully!';
    }
}
?>
<?=CRM_header('Ambetter- Create')?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?= CRM_topbar($_SESSION) ?>

            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div class="wrap">
                        <h1 class="h3 mb-0 text-gray-800">Create Ambetter Contact</h1>
                    </div>
                </div>

                <form action="" method="post" class="crud-form">

                    <div class="cols">
                        <?php foreach ($Ambetter_Columns as $column => $array): ?>
                        <?php if (isset($array['input'])): ?>
                        <?php $input = $array['input']; ?>
                        <div class="style-form-control">
                            <label for="<?=$column?>"><?=$array['label']?></label>
                            <?php if ($input['type'] == 'text' || $input['type'] == 'hidden' || $input['type'] == 'email' || $input['type'] == 'number' || $input['type'] == 'tel'): ?>
                            <input id="<?=$column?>" type="<?=$input['type']?>" name="<?=$column?>" placeholder="<?=$input['placeholder']?>" <?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
                            <?php elseif ($input['type'] == 'datetime-local'): ?>
                            <input id="<?=$column?>" type="<?=$input['type']?>" name="<?=$column?>"<?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?> value="<?=date('Y-m-d\TH:i')?>">
                            <?php elseif ($input['type'] == 'date'): ?>
                            <input id="<?=$column?>" type="<?=$input['type']?>" name="<?=$column?>"<?=$input['required'] ? ' required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?> value="<?=date('Y-m-d')?>">
                            <?php elseif ($input['type'] == 'textarea'): ?>
                            <textarea id="<?=$column?>" name="<?=$column?>" placeholder="<?=$input['placeholder']?>" <?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>></textarea>
                            <?php elseif ($input['type'] == 'select'): ?>
                            <select id="<?=$column?>" name="<?=$column?>" <?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
                                <?php foreach ($input['options'] as $option): ?>
                                <option value="<?=$option?>"><?=$option?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php elseif ($input['type'] == 'checkbox'): ?>
                            <input id="<?=$column?>" type="hidden" name="<?=$column?>" value="0" <?=isset($input['custom']) ? $input['custom'] : ''?>>
                            <input type="<?=$input['type']?>" name="<?=$column?>" value="1" <?=isset($input['custom']) ? $input['custom'] : ''?>>
                            <?php elseif ($input['type'] == 'radio'): ?>
                            <?php foreach ($input['options'] as $option): ?>
                            <div class="">
                                <input id="<?=$option?>" type="<?=$input['type']?>" name="<?=$column?>" value="<?=$option?>" <?=isset($input['custom']) ? $input['custom'] : ''?>>
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





