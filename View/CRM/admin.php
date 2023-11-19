<?php
include '../../php/functions.php';

$pdo = pdo_connect_mysql();


// Retrieve the GET request parameters (if specified)
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';
// Filters parameters
$status = isset($_GET['status']) ? $_GET['status'] : '';
$activation = isset($_GET['activation']) ? $_GET['activation'] : '';
$role = isset($_GET['role']) ? $_GET['role'] : '';
// Order by column
$order = isset($_GET['order']) && $_GET['order'] == 'DESC' ? 'DESC' : 'ASC';
// Add/remove columns to the whitelist array
$order_by_whitelist = ['id','username','email','activation_code','role','registered'];
$order_by = isset($_GET['order_by']) && in_array($_GET['order_by'], $order_by_whitelist) ? $_GET['order_by'] : 'id';
// Number of results per pagination page
$results_per_page = 20;
// Declare query param variables
$param1 = ($page - 1) * $results_per_page;
$param2 = $results_per_page;
$param3 = '%' . $search . '%';
// SQL where clause
$where = '';
$where .= $search ? 'WHERE (username LIKE :search OR email LIKE :search) ' : '';
// Add filters
if ($status == 'active') {
    $where .= $where ? 'AND last_seen > date_sub(now(), interval 1 month) ' : 'WHERE last_seen > date_sub(now(), interval 1 month) ';
}
if ($status == 'inactive') {
    $where .= $where ? 'AND last_seen < date_sub(now(), interval 1 month) ' : 'WHERE last_seen < date_sub(now(), interval 1 month) ';
}
if ($activation == 'pending') {
    $where .= $where ? 'AND activation_code != "activated" ' : 'WHERE activation_code != "activated" ';
}
if ($role) {
    $where .= $where ? 'AND role = :role ' : 'WHERE role = :role ';
}
// Retrieve the total number of Employee
$stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM Employee ' . $where);
if ($search) $stmt->bindParam('search', $param3, PDO::PARAM_STR);
if ($role) $stmt->bindParam('role', $role, PDO::PARAM_STR);
$stmt->execute();
$Employee_total = $stmt->fetchColumn();
// Prepare Employee query
$stmt = $pdo->prepare('SELECT * FROM Employee ' . $where . ' ORDER BY ' . $order_by . ' ' . $order . ' LIMIT :start_results,:num_results');
$stmt->bindParam('start_results', $param1, PDO::PARAM_INT);
$stmt->bindParam('num_results', $param2, PDO::PARAM_INT);
if ($search) $stmt->bindParam('search', $param3, PDO::PARAM_STR);
if ($role) $stmt->bindParam('role', $role, PDO::PARAM_STR);
$stmt->execute();
// Retrieve query results
$Employee = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Delete account
if (isset($_GET['delete'])) {
    // Delete the account
    $stmt = $pdo->prepare('DELETE FROM Employee WHERE id = ?');
    $stmt->execute([ $_GET['delete'] ]);
    header('Location: admin.php?success_msg=3');
    exit;
}
// Handle success messages
if (isset($_GET['success_msg'])) {
    if ($_GET['success_msg'] == 1) {
        $success_msg = 'Account created successfully!';
    }
    if ($_GET['success_msg'] == 2) {
        $success_msg = 'Account updated successfully!';
    }
    if ($_GET['success_msg'] == 3) {
        $success_msg = 'Account deleted successfully!';
    }
}
// Create URL
$url = 'admin.php?search=' . $search . '&status=' . $status . '&activation=' . $activation . '&role=' . $role;
?>
<?=admin_header('Employee', 'accounts', 'view')?>

<div class="content-title">
    <div class="title">
        <i class="fa-solid fa-users"></i>
        <div class="txt">
            <h2>Employee</h2>
            <p>View, edit, and create accounts.</p>
        </div>
    </div>
</div>

<?php if (isset($success_msg)): ?>
<div class="msg success">
    <i class="fas fa-check-circle"></i>
    <p><?=$success_msg?></p>
    <i class="fas fa-times"></i>
</div>
<?php endif; ?>

<div class="content-header responsive-flex-column pad-top-5">
    <a href="account.php" class="btn">Create Account</a>
    <form action="" method="get">
        <div class="filters">
            <a href="#"><i class="fas fa-filter"></i> Filters</a>
            <div class="list">
                <label><input type="checkbox" name="status" value="active"<?=$status=='active'?' checked':''?>>Active</label>
                <label><input type="checkbox" name="status" value="inactive"<?=$status=='inactive'?' checked':''?>>Inactive</label>
                <label><input type="checkbox" name="activation" value="pending"<?=$activation=='pending'?' checked':''?>>Pending Activation</label>
                <?php if ($role): ?>
                <label><input type="checkbox" name="role" value="<?=$role?>" checked><?=$role?></label>
                <?php endif; ?>
                <button type="submit">Apply</button>
            </div>
        </div>
        <div class="search">
            <label for="search">
                <input id="search" type="text" name="search" placeholder="Search username or email..." value="<?=htmlspecialchars($search, ENT_QUOTES)?>" class="responsive-width-100">
                <i class="fas fa-search"></i>
            </label>
        </div>
    </form>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td><a href="<?=$url . '&order=' . ($order=='ASC'?'DESC':'ASC') . '&order_by=id'?>">#<?php if ($order_by=='id'): ?><i class="fas fa-level-<?=str_replace(['ASC', 'DESC'], ['up','down'], $order)?>-alt fa-xs"></i><?php endif; ?></a></td>
                    <td><a href="<?=$url . '&order=' . ($order=='ASC'?'DESC':'ASC') . '&order_by=username'?>">Username<?php if ($order_by=='username'): ?><i class="fas fa-level-<?=str_replace(['ASC', 'DESC'], ['up','down'], $order)?>-alt fa-xs"></i><?php endif; ?></a></td>
                    <td class="responsive-hidden"><a href="<?=$url . '&order=' . ($order=='ASC'?'DESC':'ASC') . '&order_by=email'?>">Email<?php if ($order_by=='email'): ?><i class="fas fa-level-<?=str_replace(['ASC', 'DESC'], ['up','down'], $order)?>-alt fa-xs"></i><?php endif; ?></a></td>
                    <td class="responsive-hidden"><a href="<?=$url . '&order=' . ($order=='ASC'?'DESC':'ASC') . '&order_by=activation_code'?>">Activation Code<?php if ($order_by=='activation_code'): ?><i class="fas fa-level-<?=str_replace(['ASC', 'DESC'], ['up','down'], $order)?>-alt fa-xs"></i><?php endif; ?></a></td>
                    <td class="responsive-hidden"><a href="<?=$url . '&order=' . ($order=='ASC'?'DESC':'ASC') . '&order_by=role'?>">Role<?php if ($order_by=='role'): ?><i class="fas fa-level-<?=str_replace(['ASC', 'DESC'], ['up','down'], $order)?>-alt fa-xs"></i><?php endif; ?></a></td>
                    <td class="responsive-hidden"><a href="<?=$url . '&order=' . ($order=='ASC'?'DESC':'ASC') . '&order_by=registered'?>">Registered Date<?php if ($order_by=='registered'): ?><i class="fas fa-level-<?=str_replace(['ASC', 'DESC'], ['up','down'], $order)?>-alt fa-xs"></i><?php endif; ?></a></td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php if (!$Employee): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no employees</td>
                </tr>
                <?php endif; ?>
                <?php foreach ($Employee as $account): ?>
                <tr>
                    <td><?=$account['id']?></td>
                    <td><?=$account['username']?></td>
                    <td class="responsive-hidden"><?=$account['email']?></td>
                    <td class="responsive-hidden"><?=$account['activation_code'] ? $account['activation_code'] : '--'?></td>
                    <td class="responsive-hidden"><?=$account['role']?></td>
                    <td class="responsive-hidden"><?=date('Y-m-d H:ia', strtotime($account['registered']))?></td>
                    <td>
                        <a href="account.php?id=<?=$account['id']?>" class="link1">Edit</a>
                        <a href="accounts.php?delete=<?=$account['id']?>" class="link1" onclick="return confirm('Are you sure you want to delete this account?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">
    <?php if ($page > 1): ?>
    <a href="<?=$url?>&page=<?=$page-1?>&order=<?=$order?>&order_by=<?=$order_by?>">Prev</a>
    <?php endif; ?>
    <span>Page <?=$page?> of <?=ceil($Employee_total / $results_per_page) == 0 ? 1 : ceil($Employee_total / $results_per_page)?></span>
    <?php if ($page * $results_per_page < $Employee_total): ?>
    <a href="<?=$url?>&page=<?=$page+1?>&order=<?=$order?>&order_by=<?=$order_by?>">Next</a>
    <?php endif; ?>
</div>

<?=CRM_footer()?>