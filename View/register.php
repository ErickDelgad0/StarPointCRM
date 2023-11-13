<?php
include '../php/functions.php';
session_start();

// No need for the user to see the login form if they're logged-in, so redirect them to the home page
if (isset($_SESSION['loggedin'])) {
	// If the user is not logged in, redirect to the home page.
    header('Location: CRM/index.php');
    exit;
}

// // Also check if they are "remembered"
if (isset($_COOKIE['rememberme']) && !empty($_COOKIE['rememberme'])) {
	// If the remember me cookie matches one in the database then we can update the session variables and the user will be logged-in.
	$stmt = $pdo->prepare('SELECT * FROM Employee WHERE rememberme = ?');
	$stmt->execute([ $_COOKIE['rememberme'] ]);
	$account = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($account) {
		// Authenticate the user
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $account['username'];
		$_SESSION['id'] = $account['id'];
        $_SESSION['role'] = $account['role'];
        $_SESSION['fullname'] = $account['first_name'] . ' ' . $account['last_name'];
		// Update last seen date
		$date = date('Y-m-d\TH:i:s');
		$stmt = $pdo->prepare('UPDATE Employee SET last_seen = ? WHERE id = ?');
		$stmt->execute([ $date, $account['id'] ]);
		// Redirect to home page
        header('Location: CRM/index.php');
		exit;
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Myaca - Register</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/CRM/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                <div class="col-lg-5 d-none d-lg-block" style="background-image: url('/img/register.png'); background-position: center center; background-size: cover; height: 105vh; width: 100%; overflow: hidden;"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" action="../php/register-process.php" method="POST" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" name="firstname" id="firstName"
                                            placeholder="First Name" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" name="lastname" id="lastName"
                                            placeholder="Last Name" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="username" class="form-control form-control-user" name="username" id="username"
                                        placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" name="email" id="email"
                                        placeholder="Email Address" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" name="password" id="exampleInputPassword"
                                            placeholder="Password" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" name="cpassword"
                                            id="cpassword" placeholder="Confirm Password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">Register Account</button>
                            </form>
                            <hr>
                            <!-- <a href="index.html" class="btn btn-google btn-user btn-block">
                                <i class="fab fa-google fa-fw"></i> Register with Google
                            </a> -->
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.html">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.html">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?=js_torun()?>

    <script>
        document.querySelector('.user').addEventListener('submit', function(event) {
            const username = document.getElementById('username').value;
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('exampleInputPassword').value;
            const confirmPassword = document.getElementById('cpassword').value;
            
            // Check if passwords match
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                event.preventDefault();
                return;
            }

            // Check input lengths
            if (username.length > 50) {
                alert('Username exceeds the maximum allowed length of 50 characters.');
                event.preventDefault();
                return;
            }

            if (firstName.length > 50) {
                alert('First Name exceeds the maximum allowed length of 50 characters.');
                event.preventDefault();
                return;
            }

            if (lastName.length > 50) {
                alert('Last Name exceeds the maximum allowed length of 50 characters.');
                event.preventDefault();
                return;
            }

            if (email.length > 255) {
                alert('Email exceeds the maximum allowed length of 255 characters.');
                event.preventDefault();
                return;
            }

            if (password.length > 255) {
                alert('Password exceeds the maximum allowed length of 255 characters.');
                event.preventDefault();
                return;
            }

            if (password.length < 5) {
                alert('Password should be at least 5 characters long.');
                event.preventDefault();
                return;
            }
        });

		// AJAX code
		let registrationForm = document.querySelector('.register form');
		registrationForm.onsubmit = event => {
			event.preventDefault();
			fetch(registrationForm.action, { method: 'POST', body: new FormData(registrationForm) }).then(response => response.text()).then(result => {
				if (result.toLowerCase().includes('autologin')) {
					window.location.href = 'CRM/index.php';
				} else {
					document.querySelector('.msg').innerHTML = result;
				}
			});
		};
	</script>	

</body>

</html>