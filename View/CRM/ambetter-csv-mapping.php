<?php
include("../../php/functions.php");
session_start();

$pdo = pdo_connect_mysql();

// Check if we are logged in
check_loggedin($pdo, '../index.php');

// Redirect back if there is no CSV header in the session
if (!isset($_SESSION['csv_header'])) {
    header('Location: ambetter-import.php');
    exit;
}

// Retrieve the CSV header from the session
$header = $_SESSION['csv_header'];

// Database columns, excluding 'id' and 'created'
$dbColumns = [
    'broker_name', 'broker_npn', 'policy_number', 'first_name',
    'last_name', 'broker_effective_date', 'broker_term_date',
    'policy_effective_date', 'policy_term_date', 'paid_through_date',
    'county', 'state', 'on/off_exchange', 'exchange_subscriber_id',
    'member_phone_number', 'member_email', 'member_responsibility',
    'member_DOB', 'autopay', 'eligible_for_commission',
    'number_of_members', 'payable_agent', 'ar_policy_type'
];
?>

<?=CRM_header('Ambetter - Mapping')?>

<div class="content update">
    <?= CRM_topbar($_SESSION) ?>
    <h2>Map the following fields to the Ambetter Database</h2>
    <form action="ambetter-import-handler.php" method="post">
        <table>
            <thead>
                <tr><th>CSV Column Headers</th><th>Database Fields</th></tr>
            </thead>
            <tbody>
                <?php // Create table rows with dropdowns for each CSV header column
                foreach ($header as $index => $columnName) {
                    echo "<tr>\n";
                    echo "<td>" . htmlspecialchars($columnName) . "</td>\n";
                    echo "<td>\n";
                    echo "<select name=\"mapping[$index]\">\n";
                    echo "<option value=\"\">Select Field</option>\n";

                    // Add options for each database column
                    foreach ($dbColumns as $dbColumn) {
                        $selected = (strtolower(str_replace(' ', '_', $columnName)) == $dbColumn) ? 'selected' : '';
                        echo "<option value=\"" . htmlspecialchars($dbColumn) . "\" $selected>" . htmlspecialchars($dbColumn) . "</option>\n";
                    }

                    echo "</select>\n";
                    echo "</td>\n";
                    echo "</tr>\n";
                }
                ?>
            </tbody>
        </table>
        <input type="submit" value="Import CSV" class="btn btn-primary">
    </form>
</div>


<?=CRM_footer()?>
