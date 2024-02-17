<?php
include 'php/functions.php';
// Output message
$msg = '';

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// First we check if the email and code exists...
if (isset($_GET['email'], $_GET['code']) && !empty($_GET['code'])) {
	$stmt = $pdo->prepare('SELECT * FROM Members WHERE email = ? AND activation_code = ?');
	$stmt->execute([ $_GET['email'], $_GET['code'] ]);
	// Store the result so we can check if the account exists in the database.
	$account = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($account) {
		// Account exists with the requested email and code.
		$stmt = $pdo->prepare('UPDATE Members SET activation_code = ? WHERE email = ? AND activation_code = ?');
		// Set the new activation code to 'activated', this is how we can check if the user has activated their account.
		$activated = 'activated';
		$stmt->execute([ $activated, $_GET['email'], $_GET['code'] ]);
		$msg = 'Your account is now activated! You can now <a href="index.php">Login</a>.';
	} else {
		$msg = 'The account is already activated or doesn\'t exist!';
	}
} else {
	$msg = 'No code and/or email was specified!';
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>Activate Account</title>
		<link href="style.css" rel="stylesheet" type="text/css">
	</head>
	<body class="loggedin">
		<div class="content">
			<p><?=$msg?></p>
		</div>
	</body>
</html>