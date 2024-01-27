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

// Check if the 'report' query parameter is set and equals 'allLeads'
if(isset($_GET['report']) && $_GET['report'] == 'allLeads') {

    // Prepare the SQL statement
    $sql = "SELECT closure, team_name, COUNT(*) as total_leads, closure_date, closure_time FROM AgentCRM GROUP BY closure, team_name, closure_date, closure_time";
    $stmt = $pdo->prepare($sql);

    // Execute the statement and fetch the results
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Set headers to force download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="AgentsCompletedLeads.csv"');

    // Open the output stream
    $output = fopen('php://output', 'w');

    // Add column headers to the CSV
    fputcsv($output, ['Closure', 'Team Name', 'Total Leads', 'Closure Date', 'Closure Time']);

    // Add rows to the CSV
    foreach($results as $row) {
        // Ensure that the 'team_name' key exists in the $row array
        $teamName = array_key_exists('team_name', $row) ? $row['team_name'] : 'No Team';

        // Output the data to the CSV
        fputcsv($output, [
            $row['closure'],
            $teamName,
            $row['total_leads'],
            $row['closure_date'],
            $row['closure_time']
        ]);
    }

    // Close the output stream
    fclose($output);
    exit();
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
                    <h1 class="h3 mb-0 text-gray-800">Reports</h1>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary shadow-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-download fa-sm text-white-50"></i> Reports
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuButton">
                            <div class="dropdown-header">Select Report:</div>
                            <a class="dropdown-item" href="ambetter-report.php?report=allLeads" id="allLeads">Agent Leads Worked Report</a>

                        </div>
                    </div>
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
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">
                                <?php
                                    $dateText = '';
                                    if (!empty($from_date) && !empty($to_date)) {
                                        $dateText = " from " . date('m-d-Y', strtotime($from_date)) . " to " . date('m-d-Y', strtotime($to_date));
                                    }
                                    echo "<span id='chartTitle'>All Team Leads Completed</span><span id='dateRange'>$dateText</span>";
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
                                        <a class="dropdown-item" href="#" id="top5">Top 5 Agents</a>
                                        <a class="dropdown-item" href="#" id="bottom5">Bottom 5 Agents</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#" id="allLeads">All Agents</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="leadsChart" width="600" height="1000"></canvas>
                                    <canvas id="topLeadsChart" width="600" height="1000"></canvas>
                                    <canvas id="bottomLeadsChart" width="600" height="1000"></canvas>
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
                                        echo "Team Leads Completed from " . date('m-d-Y', strtotime($from_date)) . " to " . date('m-d-Y', strtotime($to_date));
                                    } else {
                                        echo "All Team Leads Completed";
                                    }
                                    ?>
                                </h6>
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
    function showChart(chartId) {
        // Hide all charts
        document.getElementById('leadsChart').style.display = 'none';
        document.getElementById('topLeadsChart').style.display = 'none';
        document.getElementById('bottomLeadsChart').style.display = 'none';

        // Show the selected chart
        document.getElementById(chartId).style.display = 'block';

        // Update the title based on the selected chart
        updateTitle(chartId);
    }

    function updateTitle(chartId) {
        var title = '';
        switch (chartId) {
            case 'topLeadsChart':
                title = 'Top 5 Team Leads Completed';
                break;
            case 'bottomLeadsChart':
                title = 'Bottom 5 Team Leads Completed';
                break;
            case 'leadsChart':
            default:
                title = 'All Team Leads Completed';
                break;
        }

        document.getElementById('chartTitle').innerText = title;
    }

    // Event listeners for dropdown items
    document.getElementById('top5').addEventListener('click', function() {
        showChart('topLeadsChart');
        enableOptions();
    });

    document.getElementById('bottom5').addEventListener('click', function() {
        showChart('bottomLeadsChart');
        enableOptions();
    });

    document.getElementById('allLeads').addEventListener('click', function() {
        showChart('leadsChart');
        disableOptions();
    });

    // Disable "Top 5" and "Bottom 5" initially
    function disableOptions() {
        document.getElementById('top5').setAttribute('disabled', 'disabled');
        document.getElementById('bottom5').setAttribute('disabled', 'disabled');
    }

    // Enable "Top 5" and "Bottom 5"
    function enableOptions() {
        document.getElementById('top5').removeAttribute('disabled');
        document.getElementById('bottom5').removeAttribute('disabled');
    }

    // Initialize with "All Leads" chart
    document.addEventListener("DOMContentLoaded", function() {
        showChart('leadsChart');
        disableOptions();
    });
        <?php 
        // Bar Chart Dynamic Label Updating
        if (!empty($from_date) && !empty($to_date)) {
            echo "setChartLabel('Leads Completed between " . date('m-d-Y', strtotime($from_date)) . " and " . date('m-d-Y', strtotime($to_date)) . "');";
        }

        $labels = array_map(function($result) { return $result['closure']; }, $results);
        $data = array_map(function($result) { return $result['total_leads']; }, $results);

        echo "setChartLabels(['" . implode("', '", $labels) . "']);";
        echo "setChartData([" . implode(", ", $data) . "]);";

        echo "setBottomLeadsChartLabels(['" . implode("', '", $labels) . "']);";
        echo "setBottomLeadsChartData([" . implode(", ", $data) . "]);";

        echo "setTopLeadsChartLabels(['" . implode("', '", $labels) . "']);";
        echo "setTopLeadsChartData([" . implode(", ", $data) . "]);";

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