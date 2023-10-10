<!-- http://localhost:8080/index.php -->
<!-- General Functions to avoid constantly making new function files -->

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
    $dbconfig = parse_ini_file("../.env");

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
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Star-Point-CRM</title>

        <link href="../css/CRM.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    </head>
    <body>
    <nav class="navtop">
        <div>
            <h1>StarPoint-CRM</h1>
            <a href="CRM.php"><i class="fas fa-home"></i>Home</a>
            <a href="read.php"><i class="fas fa-address-book"></i>Contacts</a>
        </div>
    </nav>
EOT;
}

function CRM_footer() {
echo <<<EOT
    </body>
</html>
EOT;
}
?>