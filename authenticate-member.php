<?php
include 'php/functions.php';

session_start();

$activation_link = "http://localhost:8080/activate.php";
$account_activation = true;

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (!isset($_POST['email'], $_POST['password'])) {
	// Could not retrieve the captured data, output error
	exit('Please fill both the email and password fields!');
}

$pdo = pdo_connect_mysql();

// Prepare our SQL query and find the account associated with the login details
// Preparing the SQL statement will prevent SQL injection
$stmt = $pdo->prepare('SELECT * FROM Members WHERE email = ?');
$stmt->execute([ $_POST['email'] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);
// Check if the account exists
if ($account) {
	// Account exists... Verify the password
	if (password_verify($_POST['password'], $account['password'])) {
		// Check if the account is activated
		if ($account_activation && $account['activation_code'] != 'activated') {
			// User has not activated their account, output the message
			echo 'Please activate your account to login! Click <a href="resend-activation.php">here</a> to resend the activation email.';
		} else {
			// Verification success! User has loggedin!
			// Declare the session variables, which will basically act like cookies, but will store the data on the server as opposed to the client
			session_regenerate_id();
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['email'] = $account['email'];
			$_SESSION['id'] = $account['id'];
			// IF the user checked the remember me checkbox...
			if (isset($_POST['rememberme'])) {
				// Generate a hash that will be stored as a cookie and in the database. It will be used to identify the user.
				$cookiehash = !empty($account['rememberme']) ? $account['rememberme'] : password_hash($account['id'] . $account['email'] . 'yoursecretkey', PASSWORD_DEFAULT);
				// The number of days a user will be remembered
				$days = 30;
				// Create the cookie
				setcookie('rememberme', $cookiehash, (int)(time()+60*60*24*$days));
				// Update the "rememberme" field in the accounts table with the new hash
				$stmt = $pdo->prepare('UPDATE Members SET rememberme = ? WHERE id = ?');
				$stmt->execute([ $cookiehash, $account['id'] ]);
			}
			// Update last seen date
			$date = date('Y-m-d\TH:i:s');
			$stmt = $pdo->prepare('UPDATE Members SET last_seen = ? WHERE id = ?');
			$stmt->execute([ $date, $account['id'] ]);
			$_SESSION['success'] = 'You have successfully logged in!';
			header('Location: index.php');
			exit();	
		}
	} else {
		// Incorrect password
		$_SESSION['error'] = 'Incorrect email and/or password!';
		header('Location: index.php');
		exit();
	}
} else {
	// Incorrect username
	$_SESSION['error'] = 'Incorrect username and/or password!';
	header('Location: index.php');
	exit();
}