<?php
// Include the calendar class
include 'Calendar.class.php';

// The directory where the uploaded photos will be saved.
define('upload_directory','uploads/');
// If enabled, the ability to manage events is disabled on the calendar. You can still manage events in the admin panel.
define('disable_event_management',false);
session_start();

// Get the current date (if specified); default is null
$current_date = isset($_GET['current_date']) && strtotime($_GET['current_date']) ? $_GET['current_date'] : null;
$uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;

// Get expanded list parameter (if specified); default is false
$expanded_list = isset($_GET['expanded_list']) ? $_GET['expanded_list'] : false;
// Create a new calendar instance
$calendar = new Calendar($current_date, $uid, $expanded_list);
// Connect to the calendar database using the constants declared in the config.php file
$calendar->connect_to_database();
// Check if the event management is disabled
if (!disable_event_management) {
    // Check if the add/update event form was submitted 
    if (isset($_POST['title'], $_POST['description'], $_POST['startdate'], $_POST['enddate'], $_POST['color'], $_POST['recurring'])) {
        // Title cannot be empty
        if (empty($_POST['title'])) {
            exit('Please enter the event title!');
        }
        // Validate the color
        $color = ctype_xdigit(ltrim($_POST['color'], '#')) && strlen(ltrim($_POST['color'], '#')) == 6 ? $_POST['color'] : '#5373ae';
        // If the event ID exists, update the corresponding event otherwise add a new event 
        if (empty($_POST['eventid'])) {
            // Add new event
            $calendar->add_event($_POST['title'], $_POST['description'], $_POST['startdate'], $_POST['enddate'], $color, $_POST['recurring'], isset($_FILES['photo']) ? $_FILES['photo'] : null);
        } else {
            // Update existing event
            $calendar->update_event($_POST['eventid'], $_POST['title'], $_POST['description'], $_POST['startdate'], $_POST['enddate'], $color, $_POST['recurring'], isset($_FILES['photo']) ? $_FILES['photo'] : null);
            $calendar->update_lead_on_event_change($_POST['eventid']);
        }
        exit('success');
    }
    // Delete event
    if (isset($_GET['delete_event'])) {
        $calendar->delete_event($_GET['delete_event']);
        exit;
    }
}
// Retrieve events list in HTML format
if (isset($_GET['events_list'])) {
    $events_list = $calendar->list_events_by_date_html($_GET['events_list']);
    if ($events_list) {
        echo $events_list;
    } else {
        echo '<div class="events">There are no events.</div>';
    }
    exit;
}
// Display the calendar
echo $calendar;
?>