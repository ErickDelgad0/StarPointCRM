<?php
include 'config.php';

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
            
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Contacts
            </div>

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

            <!-- Heading -->
            <div class="sidebar-heading">
                Data Analytics
            </div>

            <!-- Nav Item - Ambetter Report -->
            <li class="nav-item">
                <a class="nav-link" href="ambetter-report.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Ambetter Reports</span></a>
            </li>
            

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->
EOT;
}

function CRM_topbar($session_data) {
    $name = htmlspecialchars((string)$session_data["name"]);
    echo <<<EOT

        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

        <!-- Topbar Search 
        <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
                <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" data-ddg-inputtype="unknown">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </form>
        -->

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                        $name
                    </span>
                    <img class="img-profile rounded-circle" src="../../img/undraw_profile.svg">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="profile.php">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                        Settings
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                        Activity Log
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>

        </ul>

        </nav>
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

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!--Reports Scripts -->
    <script src="../../js/demo/completed-lead.js"></script>
    <script src="../../js/demo/completed-team-lead-pie.js"></script>

EOT;
}

function logout_modal(){
    echo <<<EOT
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../../php/logout.php">Logout</a>
                </div>

            </div>
        </div>
    </div>
    EOT;
}

function admin_header($title, $selected = 'dashboard', $selected_child = '') {
    // Admin HTML links
    $admin_links = '
        <a href="index.php"' . ($selected == 'dashboard' ? ' class="selected"' : '') . '><i class="fas fa-tachometer-alt"></i>Dashboard</a>
        <a href="accounts.php"' . ($selected == 'accounts' ? ' class="selected"' : '') . '><i class="fas fa-users"></i>Accounts</a>
        <div class="sub">
            <a href="accounts.php"' . ($selected == 'accounts' && $selected_child == 'view' ? ' class="selected"' : '') . '><span>&#9724;</span>View Accounts</a>
            <a href="account.php"' . ($selected == 'accounts' && $selected_child == 'manage' ? ' class="selected"' : '') . '><span>&#9724;</span>Create Account</a>
        </div>
        <a href="roles.php"' . ($selected == 'roles' ? ' class="selected"' : '') . '><i class="fas fa-list"></i>Roles</a>
        <a href="emailtemplate.php"' . ($selected == 'emailtemplate' ? ' class="selected"' : '') . '><i class="fas fa-envelope"></i>Email Templates</a>
        <a href="settings.php"' . ($selected == 'settings' ? ' class="selected"' : '') . '><i class="fas fa-tools"></i>Settings</a>
    ';
    // Indenting the below code may cause an error
echo <<<EOT
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1">
        <title>$title</title>
        <link href="../../css/admin.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    </head>
    <body class="admin">
        <aside class="responsive-width-100 responsive-hidden">
            <h1>Admin</h1>
            $admin_links
            <div class="footer">
                <a href="https://codeshack.io/package/php/advanced-secure-login-registration-system/" target="_blank">Advanced Login & Registration</a>
                Version 2.0.1
            </div>
        </aside>
        <main class="responsive-width-100">
            <header>
                <a class="responsive-toggle" href="#">
                    <i class="fas fa-bars"></i>
                </a>
                <div class="space-between"></div>
                <div class="dropdown right">
                    <i class="fas fa-user-circle"></i>
                    <div class="list">
                        <a href="account.php?id={$_SESSION['id']}">Edit Profile</a>
                        <a href="../logout.php">Logout</a>
                    </div>
                </div>
            </header>
EOT;
}
?>
