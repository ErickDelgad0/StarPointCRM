<!-- Display Records from out db -->

<?php
include '../php/functions.php';


// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 5;

// Prepare the SQL statement and get records from our Customer table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM Customers ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();

// Fetch the records so we can display them in our template.
$Customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of Customers, this is so we can determine whether there should be a next and previous button
$num_Customers = $pdo->query('SELECT COUNT(*) FROM Customers')->fetchColumn();
?>

<?=CRM_header('Read')?>
<div class="content read">
<h2>Read Customer</h2>
    <a href="create.php" class="create-contact">Create Contact</a>
    <table>
        <thead>
            <tr>
                <td>#</td>
                <td>Name</td>
                <td>Email</td>
                <td>Phone</td>
                <td>Title</td>
                <td>Created</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($Customers as $Customer): ?>
            <tr>
                <td><?=$Customer['id']?></td>
                <td><?=$Customer['name']?></td>
                <td><?=$Customer['email']?></td>
                <td><?=$Customer['phone']?></td>
                <td><?=$Customer['title']?></td>
                <td><?=$Customer['created']?></td>
                <td class="actions">
                    <a href="update.php?id=<?=$Customer['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="delete.php?id=<?=$Customer['id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php if ($page > 1): ?>
        <a href="read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
        <?php endif; ?>
        <?php if ($page*$records_per_page < $num_Customers): ?>
        <a href="read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
        <?php endif; ?>
    </div>
</div>

<?=CRM_footer()?>