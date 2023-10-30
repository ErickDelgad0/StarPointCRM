<?php

include '../../php/functions.php';

// Connect to MySQL database
$pdo = pdo_connect_mysql();

$default_records_per_page = 20;

// Bulk action handler
if (isset($_GET['bulk_action'])) {
	// Get the IDs of the selected records
	$ids = isset($_GET['record']) && is_array($_GET['record']) ? array_filter($_GET['record'], 'is_numeric') : null;
	// Make sure we have IDs
	if ($ids) {
		// Delete records
		if ($_GET['bulk_action'] == 'delete') {
			// Delete from the database
			$stmt = $pdo->prepare('DELETE FROM Ambetter WHERE id IN (' . implode(',', array_fill(0, count($ids), '?')) . ')');
			$stmt->execute($ids);
		}
		// Export records to CSV file
		if ($_GET['bulk_action'] == 'export') {
			// Prepare the SQL statement, we basically want to select all the records where the ID is in the POST values
			$stmt = $pdo->prepare('SELECT * FROM Ambetter WHERE id IN (' . implode(',', array_fill(0, count($ids), '?')) . ')');
			$stmt->execute($ids);
			$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
			// Download the CSV file - we force this by sending a HTTP header with header()
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=Ambetter.csv');
			$output = fopen('php://output', 'w');
            fputcsv($output, ['id', 'broker_name', 'broker_npn', 'policy_number', 'insured_first_name', 'insured_last_name', 'broker_effective_date', 'broker_term_date', 'policy_effective_date', 'policy_term_date', 'paid_through_date', 'county', 'state', 'on/off exchange', 'exchange_subscriber_id', 'member_phone_number', 'member_email', 'member_responsibilty', 'member_Date_Of_Birth', 'autopay', 'eligible_for_commission', 'number_of_members', 'payable_agent', 'ar_policy_type', 'created']);
			
            foreach ($records as $record) {
				fputcsv($output, $record);
			}
			fclose($output);
			exit;
		}
        
		// Edit records
		if ($_GET['bulk_action'] == 'edit') {
			// Redirect to the bulk-update.php page, with all the IDs specified in the URL parameters
			header('Location: bulk-update.php?ids=' . implode(',', $ids));
			exit;
		}
	}
}
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Number of records to show on each page
$records_per_page = isset($_GET['records_per_page']) && (is_numeric($_GET['records_per_page']) || $_GET['records_per_page'] == 'all') ? $_GET['records_per_page'] : $default_records_per_page;

// Column list
$columns = ['policy_number', 'broker_name', 'broker_npn', 'first_name','last_name', 'state',`member_DOB`, 'member_phone_number', 'created'];

// Order by which column if specified (default to policy_number)
$order_by = isset($_GET['order_by']) && in_array($_GET['order_by'], $columns) ? $_GET['order_by'] : 'policy_number';

// Sort by ascending or descending if specified (default to ASC)
$order_sort = isset($_GET['order_sort']) && $_GET['order_sort'] == 'DESC' ? 'DESC' : 'ASC';

// Filter params
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

// Where SQL condition
$where_sql = '';
// Add search to SQL query (if search term exists)
if (isset($_GET['search']) && !empty($_GET['search'])) {
	$where_sql .= ($where_sql ? ' AND ' : ' WHERE ') .  ' policy_number LIKE :search_query OR broker_name LIKE :search_query OR broker_npn LIKE :search_query OR first_name LIKE :search_query OR last_name LIKE :search_query OR state LIKE :search_query OR member_DOB LIKE :search_query';
}


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

// SQL statement to get all Ambetter with search query
$stmt = $pdo->prepare('SELECT * FROM Ambetter ' . $where_sql . ' ORDER BY ' . $order_by . ' ' . $order_sort . $limit_sql);
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

// Get the total number of Ambetter, so we can determine whether there should be a next and previous button
$stmt = $pdo->prepare('SELECT COUNT(*) FROM Ambetter ' . $where_sql);

// Bind the search query param to the SQL query
if (isset($_GET['search']) && !empty($_GET['search'])) {	
	$stmt->bindValue(':search_query', '%' . $_GET['search'] . '%');
}

// Bind the from and to date params to the SQL query
if (!empty($from_date)) {
	$stmt->bindValue(':from_date', date('Y-m-d H:i:s', strtotime($from_date)));
}
if (!empty($to_date)) {
	$stmt->bindValue(':to_date', date('Y-m-d H:i:s', strtotime($to_date)));
}

$stmt->execute();
// Total number of results
$num_results = $stmt->fetchColumn();

?>


<?=CRM_header("Ambetter Contacts")?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top">

                </nav>
                <!-- End of Topbar -->


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Ambetter Contacts</h1>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                    class="fas fa-download fa-sm text-white-50"></i> New Import</a>
                    </div>

                    <div class="style-content read">
                        <form action="" method="get" class="crud-form">

                            <div class="top">
                                <div class="btns">
                                    <a href="create.php" class="btn">Create Contact</a>
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
                                            <td<?=$order_by=='policy_number'?' class="active"':''?>>
                                                <a href="ambetter.php?page=1&records_per_page=<?=$records_per_page?>&order_by=policy_number&order_sort=<?=$order_sort == 'ASC' ? 'DESC' : 'ASC'?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">
                                                    Policy #
                                                    <?php if ($order_by == 'policy_number'): ?>
                                                    <i class="fa-solid fa-arrow-<?=str_replace(array('ASC', 'DESC'), array('up', 'down'), $order_sort)?>-long fa-sm"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                            <td<?=$order_by=='broker_name'?' class="active"':''?>>
                                                <a href="ambetter.php?page=1&records_per_page=<?=$records_per_page?>&order_by=broker_name&order_sort=<?=$order_sort == 'ASC' ? 'DESC' : 'ASC'?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">
                                                    Broker Name
                                                    <?php if ($order_by == 'broker_name'): ?>
                                                    <i class="fa-solid fa-arrow-<?=str_replace(array('ASC', 'DESC'), array('up', 'down'), $order_sort)?>-long fa-sm"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                            <td<?=$order_by=='broker_npn'?' class="active"':''?>>
                                                <a href="ambetter.php?page=1&records_per_page=<?=$records_per_page?>&order_by=broker_npn&order_sort=<?=$order_sort == 'ASC' ? 'DESC' : 'ASC'?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">
                                                    Broker NPN
                                                    <?php if ($order_by == 'broker_npn'): ?>
                                                    <i class="fa-solid fa-arrow-<?=str_replace(array('ASC', 'DESC'), array('up', 'down'), $order_sort)?>-long fa-sm"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                            <td<?=$order_by=='first_name'?' class="active"':''?>>
                                                <a href="ambetter.php?page=1&records_per_page=<?=$records_per_page?>&order_by=first_name&order_sort=<?=$order_sort == 'ASC' ? 'DESC' : 'ASC'?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">
                                                    Insured First Name
                                                    <?php if ($order_by == 'first_name'): ?>
                                                    <i class="fa-solid fa-arrow-<?=str_replace(array('ASC', 'DESC'), array('up', 'down'), $order_sort)?>-long fa-sm"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </td>			
                                            <td<?=$order_by=='last_name'?' class="active"':''?>>
                                                <a href="ambetter.php?page=1&records_per_page=<?=$records_per_page?>&order_by=last_name&order_sort=<?=$order_sort == 'ASC' ? 'DESC' : 'ASC'?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">
                                                    Insured Last Name
                                                    <?php if ($order_by == 'last_name'): ?>
                                                    <i class="fa-solid fa-arrow-<?=str_replace(array('ASC', 'DESC'), array('up', 'down'), $order_sort)?>-long fa-sm"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                            <td<?=$order_by=='state'?' class="active"':''?>>
                                                <a href="ambetter.php?page=1&records_per_page=<?=$records_per_page?>&order_by=state&order_sort=<?=$order_sort == 'ASC' ? 'DESC' : 'ASC'?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">
                                                    State
                                                    <?php if ($order_by == 'state'): ?>
                                                    <i class="fa-solid fa-arrow-<?=str_replace(array('ASC', 'DESC'), array('up', 'down'), $order_sort)?>-long fa-sm"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                            <td<?=$order_by=='member_DOB'?' class="active"':''?>>
                                                <a href="ambetter.php?page=1&records_per_page=<?=$records_per_page?>&order_by=member_DOB&order_sort=<?=$order_sort == 'ASC' ? 'DESC' : 'ASC'?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">
                                                    Member DOB
                                                    <?php if ($order_by == 'member_DOB'): ?>
                                                    <i class="fa-solid fa-arrow-<?=str_replace(array('ASC', 'DESC'), array('up', 'down'), $order_sort)?>-long fa-sm"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                            <td<?=$order_by=='created'?' class="active"':''?>>
                                                <a href="read.php?page=1&records_per_page=<?=$records_per_page?>&order_by=created&order_sort=<?=$order_sort == 'ASC' ? 'DESC' : 'ASC'?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">
                                                    Created
                                                    <?php if ($order_by == 'created'): ?>
                                                    <i class="fa-solid fa-arrow-<?=str_replace(array('ASC', 'DESC'), array('up', 'down'), $order_sort)?>-long fa-sm"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </td>	
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
                                            <td class="checkbox"><input type="checkbox" value="<?=$result['id']?>" name="record[]"></td>
                                            <td class="policy_number"><?=$result['policy_number']?></td>
                                            <td class="broker_name"><?=htmlspecialchars($result['broker_name'], ENT_QUOTES)?></td>
                                            <td class="broker_npn"><?=htmlspecialchars($result['broker_npn'], ENT_QUOTES)?></td>
                                            <td class="first_name"><?=htmlspecialchars($result['first_name'], ENT_QUOTES)?></td>
                                            <td class="last_name"><?=htmlspecialchars($result['last_name'], ENT_QUOTES)?></td>
                                            <td class="state"><?=htmlspecialchars($result['state'], ENT_QUOTES)?></td>
                                            <td class="member_DOB"><?=htmlspecialchars($result['member_DOB'], ENT_QUOTES)?></td>
                                            <td class="created"><?=date('Y-m-d H:i', strtotime($result['created']))?></td>
                                            <td class="actions">
                                                <a href="update.php?id=<?=$result['policy_number']?>" class="edit"><i class="fa-solid fa-pen fa-xs"></i></a>
                                                <a href="delete.php?id=<?=$result['policy_number']?>" class="trash"><i class="fa-solid fa-xmark fa-xs"></i></a>
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
                                    <a href="ambetter.php?page=1&records_per_page=all&order_by=<?=$order_by?>&order_sort=<?=$order_sort?>&from_date=<?=$from_date?>&to_date=<?=$to_date?><?=isset($_GET['search']) ? '&search=' . htmlentities($_GET['search'], ENT_QUOTES) : ''?>">all</a>
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

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?=js_torun()?>
</body>
</html>