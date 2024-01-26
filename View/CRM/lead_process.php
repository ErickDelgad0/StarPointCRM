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
?>

<?=CRM_header("Lead-Processing")?>

<style>
    .lead-card {
    border: 1px solid #ccc; /* Add a border */
    border-radius: 8px; /* Round the corners */
    padding: 16px; /* Add some padding */
    margin: 16px 0; /* Add some margin to separate the cards */
    background-color: #f9f9f9; /* Light background color */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Add a subtle shadow */
    transition: box-shadow 0.3s ease-in-out; /* Smooth transition for hover effect */
}

.lead-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15); /* Slightly larger shadow on hover */
}

/* Style for the edit button */
.edit-btn {
    background-color: #007bff; /* Bootstrap primary color */
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
}

.edit-btn:hover {
    background-color: #0056b3; /* Darker shade for hover effect */
}

/* Styles for form elements */
.lead-card form {
    margin-top: 12px;
}

.lead-card textarea, .lead-card input[type='datetime-local'] {
    width: 100%; /* Full width */
    padding: 8px;
    margin-bottom: 8px;
    border-radius: 4px;
    border: 1px solid #ccc;
}

.lead-card button[type='submit'] {
    background-color: #28a745; /* Bootstrap success color */
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
}

.lead-card button[type='submit']:hover {
    background-color: #218838; /* Darker shade for hover effect */
}

</style>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <?= CRM_topbar($_SESSION) ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Leads to Process</h1>
            </div>
        </div>
        <?php
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Start of the card
                echo "<div class='lead-card'>";

                // Lead information
                echo "<p>Name: " . htmlspecialchars($row["first_name"]) . " " . htmlspecialchars($row["last_name"]) . "</p>";
                echo "<p>Email: " . htmlspecialchars($row["email"]) . "</p>";
                echo "<p>Phone: " . htmlspecialchars(formatPhoneNumber($row["phone"])) . "</p>";
                echo "<p>State: " . htmlspecialchars($row["state"]) . "</p>";
                echo "<p>DOB: " . date('m-d-Y', strtotime(htmlspecialchars($row["DOB"]))) . "</p>";

                // Edit button
                echo "<button onclick='toggleForm(" . $row["id"] . ")' class='edit-btn'>Edit</button>";

                // Hidden form for notes and recontact date
                echo "<form id='form-" . $row["id"] . "' style='display:none;' action='../../php/lead_processing_submission.php' method='post'>";
                echo "<input type='hidden' name='lead_id' value='" . htmlspecialchars($row["id"]) . "'>";
                echo "<textarea name='notes' placeholder='Enter notes here'></textarea>";
                echo "<input type='datetime-local' name='recontact_date'>";
                echo "<button type='submit'>Save</button>";
                echo "</form>";

                // End of the card
                echo "</div>";
            }
        } else {
            echo "No unserviced leads found.";
        }
        ?>

    </div>

</div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<?=logout_modal()?>
<?=js_torun()?>

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
