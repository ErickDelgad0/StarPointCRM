<?php
include "../../php/functions.php";

session_start();
$pdo = pdo_connect_mysql();

check_loggedin($pdo, "../view/index.php");
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>Event Calendar</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<!-- calendar stylesheet -->
		<link href="calendar.css" rel="stylesheet" type="text/css">
	</head>
	<body>
	    <!-- <nav class="navtop">
	    	<div>
	    		<h1>Re-Contact Calendar</h1>
	    	</div>
	    </nav> -->
		<div class="content">
			<div class="calendar-container"></div>
		</div>
		<!-- calendar script -->
		<script src="Calendar.js"></script>
		<script>
		new Calendar({
			// Unique ID - each page should have a unique ID
			uid: 1,
			// Size of the calendar - normal | mini | auto
			size: 'auto',
			// Expanded list - true | false
			expanded_list: true
		});
		</script>
	</body>
</html>