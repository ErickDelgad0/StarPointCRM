<?php
include '../../php/functions.php';

session_start();
$pdo = pdo_connect_mysql();
check_loggedin($pdo, '../index.php');

// Combined SQL Query to count current events for today and pending leads
$query = "SELECT 
(SELECT COUNT(*) FROM events WHERE datestart <= CURDATE() AND dateend >= CURDATE()) AS event_count,
(SELECT COUNT(*) FROM Leads WHERE serviced = 0) AS pending_count";

// Prepare and execute the query
$stmt = $pdo->prepare($query);
$stmt->execute();

// Fetching the result
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$currentEventCount = $result['event_count'];
$pendingLeadsCount = $result['pending_count'];
?>

<?=CRM_header('CRM Dashboard')?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?= CRM_topbar($_SESSION) ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Leads to Recontact Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <a href="calendar.php" style="text-decoration: none; color: inherit;">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Leads to Recontact
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <?php echo $currentEventCount; ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>


                        <!-- Pending Leads Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <a href="lead_process.php" style="text-decoration: none; color: inherit;">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Pending Leads
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <?php echo $pendingLeadsCount; ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->
            <?=CRM_footer()?>
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    
    <?=logout_modal()?>
    <?=js_torun()?>

</body>

</html>