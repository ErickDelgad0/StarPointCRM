<?php
include '../../php/functions.php';
session_start();

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// check if we are logged in
check_loggedin($pdo, '../index.php');
?>

<?=CRM_header("Calendar")?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <?= CRM_topbar($_SESSION) ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Re-contact Leads</h1>
            </div>
        </div>
        
        <iframe src="../calendar/index.php" style="width:100%; height:100%;"></iframe>
    </div>

</div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<?=logout_modal()?>
<?=js_torun()?>
