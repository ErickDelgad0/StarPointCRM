<?php
include("../../php/functions.php");

session_start();
$pdo = pdo_connect_mysql();
check_loggedin($pdo, '../index.php');

$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

try {
    $sql = "SELECT closure, COUNT(*) as total_leads FROM AgentCRM";

    if (!empty($from_date) || !empty($to_date)) {
        $sql .= " WHERE ";
        if (!empty($from_date)) {
            $sql .= "closure_date >= :from_date";
        }
        if (!empty($from_date) && !empty($to_date)) {
            $sql .= " AND ";
        }
        if (!empty($to_date)) {
            $sql .= "closure_date <= :to_date";
        }
    }

    $sql .= " GROUP BY closure";
    $stmt = $pdo->prepare($sql);

    // Bind the from and to date params to the SQL query
    if (!empty($from_date)) {
        $stmt->bindValue(':from_date', date('Y-m-d', strtotime($from_date)));
    }
    if (!empty($to_date)) {
        $stmt->bindValue(':to_date', date('Y-m-d', strtotime($to_date)));
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    usort($results, function($a, $b) {
        return $a['total_leads'] - $b['total_leads'];
    });

} catch (PDOException $e) {
    // Handle the error
    echo "Error: " . $e->getMessage();
}

try {
    $sqlPieChart = "SELECT team_name, COUNT(*) as total_leads FROM AgentCRM";
    $conditions = [];

    if (!empty($from_date)) {
        $conditions[] = "closure_date >= :from_date_pie";
    }
    if (!empty($to_date)) {
        $conditions[] = "closure_date <= :to_date_pie";
    }
    if (!empty($conditions)) {
        $sqlPieChart .= " WHERE " . implode(' AND ', $conditions);
    }

    $sqlPieChart .= " GROUP BY team_name";
    $stmtPieChart = $pdo->prepare($sqlPieChart);

    // Bind parameters
    if (!empty($from_date)) {
        $stmtPieChart->bindValue(':from_date_pie', date('Y-m-d', strtotime($from_date)));
    }
    if (!empty($to_date)) {
        $stmtPieChart->bindValue(':to_date_pie', date('Y-m-d', strtotime($to_date)));
    }

    $stmtPieChart->execute();
    $resultsPieChart = $stmtPieChart->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>




<?=CRM_header("Ambetter - Reports")?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
       <!-- Main Content -->
       <div id="content">
            <?= CRM_topbar($_SESSION) ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Ambetter Report</h1>
                </div>

                <div class="style-content read">
                    <form action="" method="get" class="crud-form">
                        <div class="top">
                            <div class="wrap">
                                <div class="filters">
                                    <a href="#" class="toggle-filters-btn"><i class="fa-solid fa-sliders"></i></a>
                                    <div class="dropdown">
                                        <label for="from_date">From</label>
                                        <input type="date" name="from_date" id="from_date" value="<?=isset($_GET['from_date']) ? htmlentities($_GET['from_date'], ENT_QUOTES) : ''?>">
                                        <label for="to_date">To</label>
                                        <input type="date" name="to_date" id="to_date" value="<?=isset($_GET['to_date']) ? htmlentities($_GET['to_date'], ENT_QUOTES) : ''?>">
                                        <button type="submit" class="btn">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="row">
                    <!-- Area Chart -->
                    <div class="col-xl-5 col-lg-7">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div
                                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <?php
                                    if (!empty($from_date) && !empty($to_date)) {
                                        // If both from and to dates are set, show them in the title
                                        echo "Leads Completed from " . date('Y-m-d', strtotime($from_date)) . " to " . date('Y-m-d', strtotime($to_date));
                                    } else {
                                        // If no dates are set, show the default title
                                        echo "All Leads Completed";
                                    }
                                    ?>
                                </h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Dropdown Header:</div>
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="leadsChart" width="600" height="1000"></canvas>
                                </div>
                            </div>
                        </div>
                    </div> 

                    <div class="col-xl-7 col-lg-5">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                                <h6 class="m-0 font-weight-bold text-primary">
                                    <?php
                                    if (!empty($from_date) && !empty($to_date)) {
                                        // If both from and to dates are set, show them in the title
                                        echo "Team Leads Completed from " . date('Y-m-d', strtotime($from_date)) . " to " . date('Y-m-d', strtotime($to_date));
                                    } else {
                                        // If no dates are set, show the default title
                                        echo "All Team Leads Completed";
                                    }
                                    ?>
                                </h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Dropdown Header:</div>
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="chart-pie pt-4 pb-2">
                                    <canvas id="leadsPieChart"></canvas>
                                </div>
                            </div>
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

    <?=js_torun()?>
    <?=logout_modal()?>

    <script>
        <?php 
        // Bar Chart Dynamic Label Updating
        if (!empty($from_date) && !empty($to_date)) {
            echo "setChartLabel('Leads Completed between " . date('Y-m-d', strtotime($from_date)) . " and " . date('Y-m-d', strtotime($to_date)) . "');";
        }

        $labels = array_map(function($result) { return $result['closure']; }, $results);
        $data = array_map(function($result) { return $result['total_leads']; }, $results);

        echo "setChartLabels(['" . implode("', '", $labels) . "']);";
        echo "setChartData([" . implode(", ", $data) . "]);";

        // Pie Chart Dynamic Label Updating
        $labelsPie = array_map(function($result) {
            return !empty($result['team_name']) ? $result['team_name'] : 'N/A';
        }, $resultsPieChart);
        
        $dataPie = array_map(function($result) {
            return $result['total_leads'];
        }, $resultsPieChart);
    
        // Combine labels with their corresponding data
        $combinedLabels = array_map(function($label, $data) {
            return $label . ': ' . $data;
        }, $labelsPie, $dataPie);
    
        echo "setPieChartLabels(['" . implode("', '", $combinedLabels) . "']);";
        echo "setPieChartData([" . implode(", ", $dataPie) . "]);";
        ?>
    </script>
</body>
</html>