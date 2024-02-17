<?php
include 'php/functions.php';
include 'php/config.php';

session_start();

$activation_link = "http://localhost:8080/activate.php";
$account_activation = true;

// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['email'], $_POST['phone'], $_POST['password'], $_POST['cpassword'], )) {
	// Could not get the data that should have been sent.
	exit('Please complete the registration form!');
}
// Check to see if the email is valid.
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit('Please provide a valid email address!');
}
// Password must be between 5 and 20 characters long.
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
	exit('Password must be between 5 and 20 characters long!');
}
// Check if both the password and confirm password fields match
if ($_POST['cpassword'] != $_POST['password']) {
	exit('Passwords do not match!');
}

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Check if the account with that username already exists
$stmt = $pdo->prepare('SELECT * FROM Members WHERE email = ?');
$stmt->execute([$_POST['email'] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);



// Store the result, so we can check if the account exists in the database.
if ($account) {
	// Email already exists
	$_SESSION['error'] = 'Email already exists!';
	header('Location: register-member.php');
	exit();
} else {
	// Email doesn't exist, insert new account
	// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	// Generate unique activation code
	$uniqid = $account_activation ? uniqid() : 'activated';
	// Current date
	$date = date('Y-m-d\TH:i:s');

	// Prepare query; prevents SQL injection
	$stmt = $pdo->prepare('INSERT INTO Members (email, phone, password, activation_code, last_seen) VALUES (?, ?, ?, ?, ?)');
	$stmt->execute([$_POST['email'],$_POST['phone'], $password, $uniqid, $date]);
	// If account activation is required, send activation email
	if ($account_activation) {
		// Account activation required, send the user the activation email with the "send_activation_email" function from the "main.php" file
		send_activation_email($_POST['email'], $uniqid);
		$_SESSION['success'] = 'Please check your email to activate your account!';
		header('Location: register-member.php');
		exit();
	}
}