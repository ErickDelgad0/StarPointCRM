<?php
include '../../php/functions.php';
session_start();

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Check if we are logged in
check_loggedin($pdo, '../index.php');

// Fetching unserviced leads
$query = "SELECT id, first_name, last_name, email, phone, state, DOB FROM Leads WHERE serviced = false ORDER BY created ASC";

// Prepare and execute the query using PDO
$stmt = $pdo->prepare($query);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Display lead information
        echo "<div class='lead-card'>";
        echo "<p>Name: " . htmlspecialchars($row["first_name"]) . " " . htmlspecialchars($row["last_name"]) . "</p>";
        echo "<p>Email: " . htmlspecialchars($row["email"]) . "</p>";
        // Additional fields here

        // Edit button
        echo "<button onclick='toggleForm(" . $row["id"] . ")' class='edit-btn'>Edit</button>";

        // Form for notes and recontact date, initially hidden
        echo "<form id='form-" . $row["id"] . "' style='display:none;' action='../../php/lead_processing_submission.php' method='post'>";
        echo "<input type='hidden' name='lead_id' value='" . htmlspecialchars($row["id"]) . "'>";
        echo "<textarea name='notes' placeholder='Enter notes here'></textarea>";
        echo "<input type='datetime-local' name='recontact_date'>";
        echo "<button type='submit'>Save</button>";
        echo "</form>";

        echo "</div>";
    }
} else {
    echo "No unserviced leads found.";
}
?>

<script>
function toggleForm(leadId) {
    var form = document.getElementById('form-' + leadId);
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}
</script>
