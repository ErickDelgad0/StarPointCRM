<?php

// We need to use sessions, so you should always start sessions using the below code.
session_start();

function pdo_connect_mysql() {
    // Read from .env file
    $envPath = __DIR__ . '/../../.env';
    if (!file_exists($envPath)) {
        $envPath = __DIR__ . '/../.env';
    }
    $dbconfig = parse_ini_file($envPath);


    $DATABASE_HOST = isset($dbconfig["MYSQL_HOST"]) ? $dbconfig["MYSQL_HOST"] : 'db';
    $DATABASE_USER = $dbconfig["MYSQL_USER"];
    $DATABASE_PASS = $dbconfig["MYSQL_PASSWORD"];
    $DATABASE_NAME = $dbconfig["MYSQL_DATABASE"];

    // Define PHPMailer constants -> do not need for now

    // define('MAIL_HOST', $dbconfig["MAIL_HOST"]);
    // define('MAIL_USERNAME', $dbconfig["MAIL_USERNAME"]);
    // define('MAIL_PASSWORD', $dbconfig["MAIL_PASSWORD"]);

    // Global variables
    define('WEBSITE_NAME', 'StarPoint');

    try {
        $pdo = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // It's good to set error mode to exception for better error handling
        return $pdo;
    } catch (PDOException $exception) {
        // Display the actual error from PDO for better troubleshooting
        exit('Database connection error: ' . $exception->getMessage());
    }
}

function check_loggedin($pdo, $redirect_file = 'index.php') {
	// If you want to update the "last seen" column on every page load, you can uncomment the below code
	/*
	if (isset($_SESSION['loggedin'])) {
		$date = date('Y-m-d\TH:i:s');
		$stmt = $pdo->prepare('UPDATE Employee SET last_seen = ? WHERE id = ?');
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
			$_SESSION['role'] = $account['role'];
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


function CRM_header($title) {
echo <<<EOT

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>$title</title>
    <meta name="author" content="Erick Delgado">

    <!-- Custom fonts for this template -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../css/CRM/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- DB Table Styling -->
    <link href="../../css/CRM/style.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Star Point</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Heading -->
            <div class="sidebar-heading">
                Data Tables
            </div>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Ambetter -->
            <li class="nav-item">
                <a class="nav-link" href="ambetter.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Ambetter</span></a>
            </li>

            <!-- Nav Item - Agentcrm -->
            <li class="nav-item">
                <a class="nav-link" href="agentcrm.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Agent CRM</span></a>
            </li>

            <!-- Nav Item - Leads -->
            <li class="nav-item">
                <a class="nav-link" href="leads.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Leads</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->
EOT;
}

function CRM_footer() {
echo <<<EOT

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Star-Point 2023</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->
EOT;
}

function js_torun(){
echo <<<EOT
    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../../js/demo/datatables-demo.js"></script>

    <!-- Tables Script -->
    <script src="../../js/script.js"></script>
EOT;
}
?>