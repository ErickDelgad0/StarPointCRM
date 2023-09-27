<?php
    session_start();

    // Read from .env file
    $dbconfig = parse_ini_file("../.env");

    // MySQL constants
    define('SERVER', 'db'); // This is based on your provided db service name.
    define('USERNAME', $dbconfig["MYSQL_USER"]);
    define('PASSWORD', $dbconfig["MYSQL_PASSWORD"]);
    define('DATABASE', $dbconfig["MYSQL_DATABASE"]);

    // Define PHPMailer constants -> do not need for now

    // define('MAIL_HOST', $dbconfig["MAIL_HOST"]);
    // define('MAIL_USERNAME', $dbconfig["MAIL_USERNAME"]);
    // define('MAIL_PASSWORD', $dbconfig["MAIL_PASSWORD"]);

    // Global variables
    define('WEBSITE_NAME', 'StarPoint');

    // Database connection
    $db_connect = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
    
    if ($db_connect === False){
        die("Error: Connection Failure." . mysqli_connect_error());
    }
?>
