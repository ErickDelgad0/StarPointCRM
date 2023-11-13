<?php
include '../../php/functions.php';
session_start();

// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Check if we are logged in
check_loggedin($pdo, '../index.php');

// Error message
$error_msg = [];
// Success message
$success_msg = '';
// Ensure contact "ID" param exists
if (!isset($_GET['ids'])) {
    exit('No IDs specified!');
}
// Get the contacts from teh prepared statement
$ids = explode(',', $_GET['ids']);
// Make sure there are IDs
if (!$ids) {
    exit('No IDs specified!');
}
// Create a placeholder string for the IDs
$ids_placeholders = implode(',', array_fill(0, count($ids), '?'));
// Get the contacts from the contacts table
$stmt = $pdo->prepare('SELECT * FROM  ' . $Ambetter . ' WHERE id IN (' . $ids_placeholders . ')');
$stmt->execute($ids);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Contacts don't exist with the specified IDs, so output error message and stop the script
if (!$contacts) {
    exit('Records don\'t exist with those IDs!');
}
// Check if POST data exists (user submitted the form)
if (isset($_POST['submit'])) {
    // Iterate the IDs
    foreach ($ids as $id) {
        // Iterate through the fields and extract the data from the form
        $data = [];
        foreach ($Ambetter_Columns as $column => $array) {
            if (isset($_POST[$column . '_' . $id])) {
                $data[$column] = $_POST[$column . '_' . $id];
                // Validate
                if ((isset($array['input']['required']) && !$array['input']['required'] && empty($_POST[$column . '_' . $id]))) {
                    continue;
                }
                if (isset($array['input']['validate_regex']) && $array['input']['validate_regex']) {
                    if (!preg_match($array['input']['validate_regex'], $_POST[$column . '_' . $id])) {
                        $error_msg[] = '#' . $id . ': ' . (isset($array['input']['validate_msg']) ? $array['input']['validate_msg'] : 'Please enter a valid ' . $column . '.');
                    }
                }
            }
        }
        // If no validation errors, proceed to update the record(s) in the database
        if (!$error_msg) {
            // Update the record
            $stmt = $pdo->prepare('UPDATE ' . $Ambetter . ' SET ' . implode(', ', array_map(function ($column) {
                return $column . ' = :' . $column;
            }, array_keys($data))) . ' WHERE id = :id');
            // bind over the data to the placeholders in the prepared statement
            foreach ($data as $column => $value) {
                $stmt->bindValue(':' . $column, $value);
            }
            // Bind ID
            $stmt->bindValue(':id', $id);
            // Execute the SQL statement
            $stmt->execute();
        }
    }
    // Success
    if (!$error_msg) {
        // Retrieve the updated contacts
        $stmt = $pdo->prepare('SELECT * FROM  ' . $Ambetter . ' WHERE id IN (' . $ids_placeholders . ')');
        $stmt->execute($ids);
        $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Output success message
        $success_msg = 'Updated Successfully!';
    }
}
?>

<?=CRM_header('Ambetter - Bulk Update')?>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?= CRM_topbar($_SESSION) ?>

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <div class="wrap">
                    <h2>Bulk Ambetter Update</h2>
                </div>
            </div>


            <form action="?ids=<?=$_GET['ids']?>" method="post" class="crud-form">

            <?php if ($error_msg): ?>
            <p class="msg-error"><?=implode('<br>', $error_msg)?></p>
            <?php elseif ($success_msg): ?>
            <p class="msg-success"><?=$success_msg?></p>
            <?php endif; ?>
            
                <?php foreach ($contacts as $contact): ?>
                <div class="cols">
                    <h2>Contact <?=$contact['id']?></h2>
                    <?php foreach ($Ambetter_Columns as $column => $array): ?>
                    <?php if (isset($array['input'])): ?>
                    <?php $input = $array['input']; ?>
                    <div class="style-form-control">
                        <label for="<?=$column?>"><?=$array['label']?></label>
                        <?php if ($input['type'] == 'text' || $input['type'] == 'hidden' || $input['type'] == 'email' || $input['type'] == 'number' || $input['type'] == 'tel'): ?>
                        <input id="<?=$column?>" type="<?=$input['type']?>" name="<?=$column?>_<?=$contact['id']?>" placeholder="<?=$input['placeholder']?>" value="<?=htmlspecialchars((string)$contact[$column], ENT_QUOTES)?>" <?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
                        <?php elseif ($input['type'] == 'datetime-local'): ?>
                        <input id="<?=$column?>" type="<?=$input['type']?>" name="<?=$column?>_<?=$contact['id']?>"" value="<?=date('Y-m-d\TH:i', strtotime($contact[$column]))?>" <?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
                        <?php elseif ($input['type'] == 'date'): ?>
                        <input id="<?=$column?>" type="<?=$input['type']?>" name="<?=$column?>"<?=$input['required'] ? ' required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?> value="<?=date('Y-m-d')?>">
                        <?php elseif ($input['type'] == 'textarea'): ?>
                        <textarea id="<?=$column?>" name="<?=$column?>_<?=$contact['id']?>"" placeholder="<?=$input['placeholder']?>" <?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>><?=htmlspecialchars($contact[$column], ENT_QUOTES)?></textarea>
                        <?php elseif ($input['type'] == 'select'): ?>
                        <select id="<?=$column?>" name="<?=$column?>_<?=$contact['id']?>"" <?=$input['required'] ? 'required' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
                            <?php foreach ($input['options'] as $option): ?>
                            <option value="<?=$option?>" <?=$contact[$column] == $option ? 'selected' : ''?>><?=$option?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php elseif ($input['type'] == 'checkbox'): ?>
                        <input id="<?=$column?>" type="hidden" name="<?=$column?>_<?=$contact['id']?>"" value="0" <?=isset($input['custom']) ? $input['custom'] : ''?>>
                        <input type="<?=$input['type']?>" name="<?=$column?>_<?=$contact['id']?>"" value="1" <?=$contact[$column] == 1 ? 'checked' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
                        <?php elseif ($input['type'] == 'radio'): ?>
                        <?php foreach ($input['options'] as $option): ?>
                        <div>
                            <input id="<?=$option?>" type="<?=$input['type']?>" name="<?=$column?>_<?=$contact['id']?>"" value="<?=$option?>" <?=$contact[$column] == $option ? 'checked' : ''?> <?=isset($input['custom']) ? $input['custom'] : ''?>>
                            <label for="<?=$option?>"><?=$option?></label>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>  
                <?php endforeach; ?>

                <button type="submit" name="submit" class="btn">Save Records</button>

            </form>
        </div>
        <?=CRM_footer()?>
    </div>
</div>