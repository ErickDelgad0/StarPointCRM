<?php
function DisplayCustomerTable() {
    $pdo = pdo_connect_mysql();
    $Customer_Table = "Customer";
    $Query_Customer_Table = "SELECT * FROM $Customer_Table";

    try {
        $stmt = $pdo->query($Query_Customer_Table);

        $tableHTML = "<table class='customer-table'>";
        $tableHTML .= "<thead>";
        $tableHTML .= "<tr>";
        $tableHTML .= "<th>ID</th>";
        $tableHTML .= "<th>First Name</th>";
        $tableHTML .= "<th>Last Name</th>";
        $tableHTML .= "<th>DOB</th>";
        $tableHTML .= "</tr>";
        $tableHTML .= "</thead>";
        
        $tableHTML .= "<tbody>";
        while ($i = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableHTML .= "<tr>";
            $tableHTML .= "<td>".$i['id']."</td>";
            $tableHTML .= "<td>".$i['firstname']."</td>";
            $tableHTML .= "<td>".$i['lastname']."</td>";
            $tableHTML .= "<td>".$i['DOB']."</td>";
            $tableHTML .= "</tr>";
        }
        $tableHTML .= "</tbody>";

        $tableHTML .= "</table>";

    } catch (PDOException $e) {
        exit('Failed to fetch data from database: ' . $e->getMessage());
    }

    // With PDO, you don't typically need to "close" the connection, as it's done automatically.
    // However, if you want to explicitly close it, you can set $pdo to null.
    // $pdo = null;

    return $tableHTML;
}

function pdo_connect_mysql() {
    // Read from .env file
    $dbconfig = parse_ini_file("../../.env");

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