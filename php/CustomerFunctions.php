<!-- http://localhost:8080/index.php -->

<?php

function DisplayCustomerTable() {
    $dbconfig = parse_ini_file("../.env");

    $db_connect = mysqli_connect(
        'db', # db service name
        $dbconfig["MYSQL_USER"], # username
        $dbconfig["MYSQL_PASSWORD"], # password
        $dbconfig["MYSQL_DATABASE"] # database name
    );

    $Customer_Table = "Customer";

    $Query_Customer_Table = "SELECT * FROM $Customer_Table";

    $Response_Customer_Table = mysqli_query($db_connect, $Query_Customer_Table);

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
    while($i = mysqli_fetch_assoc($Response_Customer_Table)) {
        $tableHTML .= "<tr>";
        $tableHTML .= "<td>".$i['id']."</td>";
        $tableHTML .= "<td>".$i['firstname']."</td>";
        $tableHTML .= "<td>".$i['lastname']."</td>";
        $tableHTML .= "<td>".$i['DOB']."</td>";
        $tableHTML .= "</tr>";
    }
    $tableHTML .= "</tbody>";

    $tableHTML .= "</table>";

    // Close DB connection
    mysqli_close($db_connect);

    return $tableHTML;
}

?>