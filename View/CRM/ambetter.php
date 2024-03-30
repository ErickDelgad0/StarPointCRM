<?php
include '../../php/functions.php';
session_start();

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Check if we are logged in
check_loggedin($pdo, '../index.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['month'], $_POST['day'], $_POST['year'])) {
    // Construct and process the date input
    $commissionDateInput = $_POST['month'] . '/' . $_POST['day'] . '/' . $_POST['year'];
    $commissionDate = DateTime::createFromFormat('m/d/y', $commissionDateInput);

    // Check if the date is valid
    if ($commissionDate !== false) {
        $formattedDate = $commissionDate->format('Y-m-d');
    
        // Prepare SQL Query
        $sql = "SELECT * FROM Ambetter WHERE 
                broker_effective_date <= :commissionDate AND 
                broker_term_date > :commissionDate AND 
                policy_effective_date <= :commissionDate AND 
                policy_term_date >= :commissionDate AND 
                paid_through_date >= :commissionDate";
    
        // Execute the query
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['commissionDate' => $formattedDate]);
    
        // Fetch results
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // If rows are fetched, write them to the CSV
        if (!empty($rows)) {
            // Set headers for the CSV download
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="commission_data_' . $formattedDate . '.csv"');
    
            // Open output stream
            $output = fopen('php://output', 'w');
    
            // Write the headers of the CSV file
            fputcsv($output, array_keys($rows[0]));
    
            // Write data to the CSV file
            foreach ($rows as $row) {
                fputcsv($output, $row);
            }
    
            fclose($output);
            exit;
        } else {
            // Use a flag to indicate no data for CSV
            $noCsvData = true;
        }
    } else {
        $error = "Invalid date format. Please use MM/DD/YY.";
        exit;
    }
}

// Check if 'report' parameter is set to 'commission'
$shouldOpenModal = isset($_GET['report']) && $_GET['report'] == 'commission';

// Modal HTML and JavaScript for modal interaction
if ($shouldOpenModal) {
    echo "<script> window.onload = function() { openModal(); }; </script>";
}


// Bulk action handler
if (isset($_GET['bulk_action'])) {
	// Get the policy_numbers of the selected records
    $policy_numbers = isset($_GET['record']) && is_array($_GET['record']) ? $_GET['record'] : null;

	// Make sure we have policy_numbers
	if ($policy_numbers) {
		// Delete records
		if ($_GET['bulk_action'] == 'delete') {
			// Delete from the database
			$stmt = $pdo->prepare('DELETE FROM ' . $Ambetter . ' WHERE policy_number IN (' . implode(',', array_fill(0, count($policy_numbers), '?')) . ')');
			$stmt->execute($policy_numbers);
		}
		// Export records to CSV file
		if ($_GET['bulk_action'] == 'export') {
			// Prepare the SQL statement, we basically want to select all the records where the ID is in the POST values
			$stmt = $pdo->prepare('SELECT * FROM ' . $Ambetter . ' WHERE policy_number IN (' . implode(',', array_fill(0, count($policy_numbers), '?')) . ')');
			$stmt->execute($policy_numbers);
			$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
			// Download the CSV file - we force this by sending a HTTP header with header()
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=Ambetter.csv');
			$output = fopen('php://output', 'w');
			fputcsv($output, array_keys($Ambetter_Columns));
			foreach ($records as $record) {
				fputcsv($output, $record);
			}
			fclose($output);
			exit;
		}
		// Edit records
		if ($_GET['bulk_action'] == 'edit') {
			// Redirect to the bulk-update.php page, with all the policy_numbers specified in the URL parameters
			header('Location: ambetter-bulk-update.php?policy_numbers=' . implode(',', $policy_numbers));
			exit;
		}
	}
}

// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = isset($_GET['records_per_page']) && (is_numeric($_GET['records_per_page']) || $_GET['records_per_page'] == 'all') ? $_GET['records_per_page'] : $default_records_per_page;
// Order by which column if specified (default to id)
$order_by = isset($_GET['order_by']) && in_array($_GET['order_by'], array_keys($Ambetter_Columns)) ? $_GET['order_by'] : $default_ambetter_column;
// Sort by ascending or descending if specified (default to ASC)
$order_sort = isset($_GET['order_sort']) && $_GET['order_sort'] == 'DESC' ? 'DESC' : 'ASC';
// Filter params
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';
// Where SQL condition
$where_sql = '';
// Add search to SQL query (if search term exists)
if (isset($_GET['search']) && !empty($_GET['search'])) {
	$where_sql .= ($where_sql ? ' AND ' : ' WHERE ') .  implode(' LIKE :search_query OR ', array_keys($Ambetter_Columns)) . ' LIKE :search_query ';
}
// Add from and/or to date filter to SQL query (if set); It will only work if the "created" column exists in your database! Rename the column below if you want to use a different DATETIME column
if (!empty($from_date) && !empty($to_date)) {
	$where_sql .= ($where_sql ? ' AND ' : ' WHERE ') .  ' created BETWEEN :from_date AND :to_date ';
} else if (!empty($from_date)) {
	$where_sql .= ($where_sql ? ' AND ' : ' WHERE ') .  ' created >= :from_date ';
} else if (!empty($to_date)) {
	$where_sql .= ($where_sql ? ' AND ' : ' WHERE ') .  ' created <= :to_date ';
}
// Limit SQL
$limit_sql = '';
if ($records_per_page != 'all') {
	$limit_sql = ' LIMIT :current_page, :record_per_page ';
}
// SQL statement to get all contacts with search query
$stmt = $pdo->prepare('SELECT * FROM ' . $Ambetter . $where_sql . ' ORDER BY ' . $order_by . ' ' . $order_sort . $limit_sql);
// Bind the search query param to the SQL query
if (isset($_GET['search']) && !empty($_GET['search'])) {	
	$stmt->bindValue(':search_query', '%' . $_GET['search'] . '%');
}
// Bind the page and records per page params to the SQL query
if ($records_per_page != 'all') {
	$stmt->bindValue(':current_page', ($page-1)*(int)$records_per_page, PDO::PARAM_INT);
	$stmt->bindValue(':record_per_page', (int)$records_per_page, PDO::PARAM_INT);
}
// Bind the from and to date params to the SQL query
if (!empty($from_date)) {
	$stmt->bindValue(':from_date', date('Y-m-d H:i:s', strtotime($from_date)));
}
if (!empty($to_date)) {
	$stmt->bindValue(':to_date', date('Y-m-d H:i:s', strtotime($to_date)));
}
// Execute the prepared statement and fetch the results
$stmt->execute();
// Fetch the records so we can populate them in our template below.
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of contacts, so we can determine whether there should be a next and previous button
$stmt = $pdo->prepare('SELECT COUNT(*) FROM ' . $Ambetter . $where_sql);
// Bind the search query param to the SQL query
if (isset($_GET['search']) && !empty($_GET['search'])) {	
	$stmt->bindValue(':search_query', '%' . $_GET['search'] . '%');
}
$stmt->execute();
// Total number of results
$num_results = $stmt->fetchColumn();
?>


<?=CRM_header("Insurance Carrier Contacts")?>
    <style>
        /* Modal Styling */
        .modal {
            display: none; 
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?= CRM_topbar($_SESSION) ?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Insurance Carrier Contacts</h1>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary shadow-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-download fa-sm text-white-50"></i> Import & Reports
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuButton">
                                    <div class="dropdown-header">Select Report:</div>
                                    <a class="dropdown-item" href="ambetter.php?report=commission" id="commission">Expected Commission</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="ambetter-import.php"> New Import</a>

                                </div>
                            </div>        
                    </div>
                    <div class="style-content read">
                    <?php 
                        // In case there was no data for CSV
                        if (isset($noCsvData) && $noCsvData) {
                            echo 'No data to write to CSV.';
                        }
                        if (isset($error)) {
                            echo $error;
                        }
                    ?>
                    <form action="" method="get" class="crud-form">

                        <div class="top">
                            <div class="btns">
                                <a href="ambetter-create.php" class="btn">Create Contact</a>
                            </div>
                            <div class="wrap">
                                <div class="filters">
                                    <a href="#" class="toggle-filters-btn"><i class="fa-solid fa-sliders"></i></a>
                                    <div class="dropdown">
                                        <label for="from_date">From</label>
                                        <input type="datetime-local" name="from_date" id="from_date" value="<?=isset($_GET['from_date']) ? htmlentities($_GET['from_date'], ENT_QUOTES) : ''?>">
                                        <label for="to_date">To</label>
                                        <input type="datetime-local" name="to_date" id="to_date" value="<?=isset($_GET['to_date']) ? htmlentities($_GET['to_date'], ENT_QUOTES) : ''?>">
                                        <button type="submit" class="btn">Filter</button>
                                    </div>
                                </div>
                                <div class="search">
                                    <input type="text" name="search" placeholder="Search..." value="<?=isset($_GET['search']) ? htmlentities($_GET['search'], ENT_QUOTES) : ''?>">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                            </div>
                        </div>

                        <div class="table">
                            <table>
                                <thead>
                                    <tr>
                                        <td class="checkbox">
                                            <input type="checkbox" class="select-all">
                                        </td>
                                        <?php foreach ($Ambetter_Columns as $column_key => $column): ?>
                                        <td<?=$order_by==$column_key?' class="active"':''?>>
                                            <?php if ($column['sortable']): ?>
                                            <a href="ambetter.php?page=1&records_per_page=<?=$records_per_page?>&order_by=<?=$column_key?>&order_sort=<?=$order_sort == 'ASC' ? 'DESC' : 'ASC'?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">
                                            <?php endif; ?>
                                                <?=$column['label']?>
                                                <?php if ($order_by == $column_key): ?>
                                                <i class="fa-solid fa-arrow-<?=str_replace(array('ASC', 'DESC'), array('up', 'down'), $order_sort)?>-long fa-sm"></i>
                                                <?php endif; ?>
                                            <?php if ($column['sortable']): ?>
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                        <?php endforeach; ?>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($results)): ?>
                                    <tr>
                                        <td colspan="10" style="text-align:center;">There are no records.</td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php foreach ($results as $result): ?>
                                    <tr>
                                        <td class="checkbox"><input type="checkbox" value="<?=$result['policy_number']?>" name="record[]"></td>
                                        <?php foreach ($Ambetter_Columns as $column_key => $column): ?>
                                        <?php if ($column['type'] == 'datetime'): ?>
                                        <td class="<?=$column_key?>"><?=date('m-d-Y H:i', strtotime($result[$column_key]))?></td>
                                        <?php elseif ($column['type'] == 'date'): ?>
                                        <td class="<?=$column_key?>"><?=date('m-d-Y', strtotime($result[$column_key]))?></td>
                                        <?php elseif ($column['type'] == 'time'): ?>
                                        <td class="<?= $column_key ?>"><?= date('H:i', strtotime($result[$column_key])) ?></td>
                                        <?php elseif ($column['type'] == 'integer'): ?>
                                        <td class="<?=$column_key?>"><?=number_format($result[$column_key])?></td>
                                        <?php elseif ($column['type'] == 'tel'): ?>
                                        <td class="<?= $column_key ?>"><?= formatPhoneNumber($result[$column_key]) ?></td>
                                        <?php else: ?>
                                        <td class="<?=$column_key?>"><?=htmlspecialchars((string) $result[$column_key], ENT_QUOTES)?></td>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                        <td class="actions">
                                            <a href="ambetter-update.php?policy_number=<?=$result['policy_number']?>" class="edit"><i class="fa-solid fa-pen fa-xs"></i></a>
                                            <a href="ambetter-delete.php?policy_number=<?=$result['policy_number']?>" class="trash"><i class="fa-solid fa-xmark fa-xs"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="bot">
                            <div class="records-per-page">
                                <select name="bulk_action" class="bulk-action">
                                    <option value="" disabled selected>Bulk Actions</option>
                                    <option value="delete">Delete</option>
                                    <option value="edit">Edit</option>
                                    <option value="export">Export</option>
                                </select>
                                <a href="ambetter.php?page=1&records_per_page=5&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">5</a>
                                <a href="ambetter.php?page=1&records_per_page=10&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">10</a>
                                <a href="ambetter.php?page=1&records_per_page=20&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">20</a>
                                <a href="ambetter.php?page=1&records_per_page=50&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">50</a>
                                <a href="ambetter.php?page=1&records_per_page=100&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">100</a>
                                <!-- <a href="ambetter.php?page=1&records_per_page=all&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">all</a> -->
                            </div>
                            <div class="pagination">
                                <?php if ($records_per_page != 'all'): ?>
                                <?php if ($page > 1): ?>
                                <a href="ambetter.php?page=<?=$page-1?>&records_per_page=<?=$records_per_page?>&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>" class="prev">
                                    <i class="fa-solid fa-angle-left"></i> Prev
                                </a>
                                <?php endif; ?>
                                <?php if ($page > 1): ?>
                                <a href="ambetter.php?page=1&records_per_page=<?=$records_per_page?>&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">1</a>
                                <?php endif; ?>
                                <?php if ($page > 2): ?>
                                <div class="dots">...</div>
                                <?php if ($page == ceil($num_results/$records_per_page) && ceil($num_results/$records_per_page) > 3): ?>
                                <a href="ambetter.php?page=<?=$page-2?>&records_per_page=<?=$records_per_page?>&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>"><?=$page-2?></a>
                                <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($page-1 > 1): ?>
                                <a href="ambetter.php?page=<?=$page-1?>&records_per_page=<?=$records_per_page?>&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>"><?=$page-1?></a>
                                <?php endif; ?>
                                <a href="ambetter.php?page=<?=$page?>&records_per_page=<?=$records_per_page?>&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>" class="selected"><?=$page?></a>
                                <?php if ($page+1 < ceil($num_results/$records_per_page)): ?>
                                <a href="ambetter.php?page=<?=$page+1?>&records_per_page=<?=$records_per_page?>&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>"><?=$page+1?></a>
                                <?php if ($page == 1 && $page+2 < ceil($num_results/$records_per_page)): ?>
                                <a href="ambetter.php?page=<?=$page+2?>&records_per_page=<?=$records_per_page?>&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>"><?=$page+2?></a>
                                <?php endif; ?>
                                <div class="dots">...</div>
                                <?php endif; ?>
                                <?php if ($page < ceil($num_results/$records_per_page)): ?>
                                <a href="ambetter.php?page=<?=ceil($num_results/$records_per_page)?>&records_per_page=<?=$records_per_page?>&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>"><?=ceil($num_results/$records_per_page)?></a>
                                <?php endif; ?>
                                <?php if ($records_per_page != 'all' && $page < ceil($num_results/$records_per_page)): ?>
                                <a href="ambetter.php?page=<?=$page+1?>&records_per_page=<?=$records_per_page?>&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>" class="next">
                                    Next <i class="fa-solid fa-angle-right"></i>
                                </a>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        </form>
                    </div>
            </div>
            <!-- End of Main Content -->

            <?=CRM_footer()?>

            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <form method="post">
                        Commission Statement Date:
                        <input type="text" name="month" placeholder="MM" size="2" maxlength="2"> /
                        <input type="text" name="day" placeholder="DD" size="2" maxlength="2"> /
                        <input type="text" name="year" placeholder="YY" size="2" maxlength="2">
                        <input type="submit" value="Submit">
                    </form>
                </div>
            </div>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script>
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];

        function openModal() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];
        var btn = document.getElementById("yourButtonId"); // Make sure this matches the ID of your button
        btn.onclick = function() {
            modal.style.display = "block";
        }
        span.onclick = function() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <?=logout_modal()?>
    <?=js_torun()?>
</body>
</html>