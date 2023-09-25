<!-- http://localhost:8080/index.php -->

<?php

$db_connect = mysqli_connect(
    'db', # db service name
    'cheesestick', # username
    'cheesestickpassword', # password
    'StarPointCustomers' # database name
);

$Customer_Table = "Customer";

$Query_Customer_Table = "SELECT * FROM $Customer_Table";

$Response_Customer_Table = mysqli_query($db_connect, $Query_Customer_Table);

echo "<strong>$Customer_Table: </strong>";
while($i = mysqli_fetch_assoc($Response_Customer_Table)){
    echo "<p>".$i['id']."</p>";
    echo "<p>".$i['firstname']."</p>";
    echo "<p>".$i['lastname']."</p>";
    echo "<p>".$i['DOB']."</p>";
}