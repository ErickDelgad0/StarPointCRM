<?php
include("../../php/functions.php");
session_start();

$pdo = pdo_connect_mysql();
check_loggedin($pdo, '../index.php');


// output message (errors, etc)
$msg = '';
// Retrieve additional account info from the database because we don't have them stored in sessions
$stmt = $pdo->prepare('SELECT * FROM Employee WHERE id = ?');
// In this case, we can use the account ID to retrieve the account info.
$stmt->execute([ $_SESSION['id'] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);



// Handle edit profile post data
if (isset($_POST['username'], $_POST['password'], $_POST['cpassword'], $_POST['email'])) {
	// Make sure the submitted registration values are not empty.
	if (empty($_POST['username']) || empty($_POST['email'])) {
		$msg = 'The input fields must not be empty!';
	} else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$msg = 'Please provide a valid email address!';
	} else if (!preg_match('/^[a-zA-Z0-9]+$/', $_POST['username'])) {
	    $msg = 'Username must contain only letters and numbers!';
	} else if (!empty($_POST['password']) && (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5)) {
		$msg = 'Password must be between 5 and 20 characters long!';
	} else if ($_POST['cpassword'] != $_POST['password']) {
		$msg = 'Passwords do not match!';
	}
	// No validation errors... Process update
	if (empty($msg)) {
		// Check if new username or email already exists in database
		$stmt = $pdo->prepare('SELECT COUNT(*) FROM Employee WHERE (username = ? OR email = ?) AND username != ? AND email != ?');
		$stmt->execute([ $_POST['username'], $_POST['email'], $_SESSION['name'], $account['email'] ]);
		// Account exists? Output error...
		if ($result = $stmt->fetchColumn()) {
			$msg = 'Account already exists with that username and/or email!';
		} else {
			// No errors occured, update the account...
			// If email has changed, generate a new activation code
			$stmt = $pdo->prepare('UPDATE Employee SET username = ?, password = ?, email = ?, WHERE id = ?');
			
            // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
			$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $account['password'];
			$stmt->execute([ $_POST['username'], $password, $_POST['email'], $_SESSION['id'] ]);
			// Update the session variables
			$_SESSION['name'] = $_POST['username'];
            header('Location: profile.php');
            exit;
		}
	}
}
?>


<?CRM_header("Profile")?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
    	body{
    margin-top:20px;
    color: #1a202c;
    text-align: left;
    background-color: #e2e8f0;    
}
.main-body {
    padding: 15px;
}
.card {
    box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: .25rem;
}

.card-body {
    flex: 1 1 auto;
    min-height: 1px;
    padding: 1rem;
}

.gutters-sm {
    margin-right: -8px;
    margin-left: -8px;
}

.gutters-sm>.col, .gutters-sm>[class*=col-] {
    padding-right: 8px;
    padding-left: 8px;
}
.mb-3, .my-3 {
    margin-bottom: 1rem!important;
}

.bg-gray-300 {
    background-color: #e2e8f0;
}
.h-100 {
    height: 100%!important;
}
.shadow-none {
    box-shadow: none!important;
}

    </style>
</head>
<body>
    <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
    <?=CRM_topbar($_SESSION)?>

    <div class="container">
    <div class="main-body">

<?php if (!isset($_GET['action'])): ?>
    <div class="row gutters-sm">
    <div class="col-md-4 mb-3">
    <div class="card">
    <div class="card-body">
    <div class="d-flex flex-column align-items-center text-center">
    <img src="../../img/undraw_profile.svg" alt="Photo" class="rounded-circle" width="150">
    <div class="mt-3">
    <h4><?=$account['first_name']?> <?=$account['last_name']?></h4>
    <!-- <p class="text-secondary mb-1">Full Stack Developer</p>
    <p class="text-muted font-size-sm">Bay Area, San Francisco, CA</p> -->
    <!-- <button class="btn btn-primary">Follow</button>
    <button class="btn btn-outline-primary">Message</button> -->
    </div>
    </div>
    </div>
    </div>
    </div>
    <div class="col-md-8">
    <div class="card mb-3">
    <div class="card-body">
    <div class="row">
    <div class="col-sm-3">
    <h6 class="mb-0">Full Name</h6>
    </div>
    <div class="col-sm-9 text-secondary">
    <?=$account['first_name']?> <?=$account['last_name']?>
    </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-3">
    <h6 class="mb-0">Email</h6>
    </div>
    <div class="col-sm-9 text-secondary">
    <?=$account['email']?>
    </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-3">
    <h6 class="mb-0">Phone</h6>
    </div>
    <div class="col-sm-9 text-secondary">
    <?php
    if (empty($account['phone'])) {
        echo "Unknown";
    } else {
        echo $account['phone'];
    }
    ?>

    </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-3">
    <h6 class="mb-0">role</h6>
    </div>
    <div class="col-sm-9 text-secondary">
    <?=$account['role']?>
    </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-3">
    <h6 class="mb-0">Joined</h6>
    </div>
    <div class="col-sm-9 text-secondary">
    <?=$account['registered']?>
    </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-sm-12">
    <a class="btn btn-info " target="__blank" href="profile.php?action=edit">Edit</a>
    <?php
    if ($account['role'] == 'admin') {
        echo '<a class="btn btn-info" target="_blank" href="admin.php">Admin Panel</a>';
    }
    ?>

    </div>
    </div>
    </div>
    </div>


    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

    <?php elseif ($_GET['action'] == 'edit'): ?>
    <div class="content profile">

        <h2>Edit Profile</h2>

        <div class="block">

            <form action="profile.php?action=edit" method="post">

                <label for="username">Username</label>
                <input type="text" value="<?=$_SESSION['name']?>" name="username" id="username" placeholder="Username">

                <label for="password">New Password</label>
                <input type="password" name="password" id="password" placeholder="New Password">

                <label for="cpassword">Confirm Password</label>
                <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password">

                <label for="email">Email</label>
                <input type="email" value="<?=$account['email']?>" name="email" id="email" placeholder="Email">

                <div>
                    <input class="profile-btn" type="submit" value="Save">
                </div>

                <p><?=$msg?></p>

            </form>

        </div>

    </div>
<?php endif; ?>

<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
	
</script>

<?=CRM_footer()?>
<?=js_torun()?>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

</body>
</html>