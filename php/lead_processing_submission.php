<?php
include 'functions.php';
session_start();

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize the input data
    $lead_id = isset($_POST['lead_id']) ? $_POST['lead_id'] : '';
    $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
    $recontact_date = isset($_POST['recontact_date']) ? $_POST['recontact_date'] : '';

    // Fetch lead's details
    $stmt = $pdo->prepare("SELECT first_name, last_name, email, phone FROM Leads WHERE id = ?");
    $stmt->execute([$lead_id]);
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($lead) {
        // Construct the event title and description
        $event_title = $lead['first_name'] . ' ' . $lead['last_name'];
        $event_description = "Email: " . $lead['email'] . "\nPhone: " . $lead['phone'] . "\n" . $notes;

        // Define the chosen color
        $chosen_color = '#5373ae';

        // Calculate dateend as the end of the day of datestart
        $dateend = date('Y-m-d 23:59:59', strtotime($recontact_date));

        // Insert the data into the 'events' table
        $stmt = $pdo->prepare("INSERT INTO events (title, description, datestart, dateend, color, uid, lead_id) VALUES (?, ?, ?, ?, ?, 0, ?)");
        $stmt->execute([$event_title, $event_description, $recontact_date, $dateend, $chosen_color, $lead_id]);

        // Update 'Leads' table (set serviced to 1, update recontact_date and notes)
        $stmt = $pdo->prepare("UPDATE Leads SET serviced = 1, recontact_date = ?, notes = ? WHERE id = ?");
        $stmt->execute([$recontact_date, $notes, $lead_id]);
    }

    // Redirect or indicate success
    header('Location: ../view/CRM/lead_process.php?status=success');
    exit;
} else {
    // Redirect or handle the error if the form is not submitted
    header('Location: ../view/CRM/lead_process.php?status=error');
    exit;
}
?>
