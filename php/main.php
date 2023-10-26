<?php
// The main file contains the database connection, session initializing, and functions, other PHP files will depend on this file.
// Include the configuration file
include_once "functions.php";
// We need to use sessions, so you should always start sessions using the below function
session_start();

$pdo = pdo_connect_mysql();

// The below function will check if the user is logged-in and also check the remember me cookie
function check_loggedin($pdo, $redirect_file = 'login.php') {
	// If you want to update the "last seen" column on every page load, you can uncomment the below code
	/*
	if (isset($_SESSION['loggedin'])) {
		$date = date('Y-m-d\TH:i:s');
		$stmt = $pdo->prepare('UPDATE accounts SET last_seen = ? WHERE id = ?');
		$stmt->execute([ $date, $_SESSION['id'] ]);
	}
	*/
	// Check for remember me cookie variable and loggedin session variable
    if (isset($_COOKIE['rememberme']) && !empty($_COOKIE['rememberme']) && !isset($_SESSION['loggedin'])) {
    	// If the remember me cookie matches one in the database then we can update the session variables.
    	$stmt = $pdo->prepare('SELECT * FROM Employee WHERE rememberme = ?');
    	$stmt->execute([ $_COOKIE['rememberme'] ]);
    	$account = $stmt->fetch(PDO::FETCH_ASSOC);
		// If account exists...
    	if ($account) {
    		// Found a match, update the session variables and keep the user logged-in
    		session_regenerate_id();
    		$_SESSION['loggedin'] = TRUE;
    		$_SESSION['name'] = $account['username'];
    		$_SESSION['id'] = $account['id'];
			$_SESSION['type'] = $account['type'];
			// Update last seen date
			$date = date('Y-m-d\TH:i:s');
			$stmt = $pdo->prepare('UPDATE Employee SET last_seen = ? WHERE id = ?');
			$stmt->execute([ $date, $account['id'] ]);
    	} else {
    		// If the user is not remembered redirect to the login page.
    		header('Location: ' . $redirect_file);
    		exit;
    	}
    } else if (!isset($_SESSION['loggedin'])) {
    	// If the user is not logged in redirect to the login page.
    	header('Location: ' . $redirect_file);
    	exit;
    }
}
// Send activation email function
function send_activation_email($email, $code) {
	// Email Subject
	$subject = 'Account Activation Required';
	// Email Headers
	$headers = 'From: ' . mail_from . "\r\n" . 'Reply-To: ' . mail_from . "\r\n" . 'Return-Path: ' . mail_from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
	// Activation link
	$activate_link = activation_link . '?email=' . $email . '&code=' . $code;
	// Read the template contents and replace the "%link" placeholder with the above variable
	$email_template = str_replace('%link%', $activate_link, file_get_contents('activation-email.html'));
	// Send email to user
	mail($email, $subject, $email_template, $headers);
}
?>