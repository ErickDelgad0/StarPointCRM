<!-- localhost:8080/html/dashboard.php -->

<?php include '../php/functions.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StarPoint</title>
</head>
<body>
    
    <h1> Customer Information </h1>

    <div>
        <?php
        echo DisplayCustomerTable();
        ?>
    </div>

</body>
</html>
