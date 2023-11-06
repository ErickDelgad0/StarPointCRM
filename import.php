<?php

include("php/functions.php"); // Assuming this file contains the pdo_connect_mysql function.

$pdo = pdo_connect_mysql(); // Establish a database connection.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Check if a file has been uploaded.
    if (isset($_FILES['file'])) {
        
        // Retrieve column mappings from the form data.
        $column_mappings = json_decode($_POST['column_mappings'], true);
        
        // Open the uploaded file for reading.
        $file = fopen($_FILES['file']['tmp_name'], 'r');

        // Initialize a counter for the total number of processed records.
        $processedCount = 0;

        // Determine the total number of records in the file for progress indication (excluding the header).
        $totalRecords = count(file($_FILES['file']['tmp_name'])) - 1;
        echo $totalRecords . " records detected.\n";

        // Initialize a counter to skip the header row if needed.
        $headerSkipped = false;

        // Loop through each line of the file.
        while (($row = fgetcsv($file)) !== FALSE) {
            // Skip the first row if it contains headers.
            if (!$headerSkipped) {
                $headerSkipped = true;
                continue;
            }

            // Create an associative array to match CSV columns to database fields.
            $mapped_data = [];
            foreach ($column_mappings as $csv_column => $db_column) {
                // Skip columns that should not be imported
                if ($db_column === 'DO NOT IMPORT' || $db_column === '') {
                    continue;
                }

                $index = array_search($csv_column, array_keys($column_mappings));
                $mapped_data[$db_column] = $index !== false ? $row[$index] : null;
            }
            
            // Filter out null values and empty keys to prevent binding data that is not needed
            $filtered_data = array_filter($mapped_data, function($value, $key) {
                return $value !== null && $key !== '';
            }, ARRAY_FILTER_USE_BOTH);

            if (count($filtered_data) > 0) {

                // Construct the update clause for duplicate keys
                $update_clause = implode(", ", array_map(function ($column) {
                    return "$column = VALUES($column)";
                }, array_keys($filtered_data)));

                // Construct a query to insert the data into the database or update if a duplicate key is found.
                $columns = implode(", ", array_keys($filtered_data));
                $placeholders = ":" . implode(", :", array_keys($filtered_data));
                $query = "INSERT INTO AgentCRM ($columns) VALUES ($placeholders)
                          ON DUPLICATE KEY UPDATE $update_clause";

                // Prepare the query using PDO.
                $statement = $pdo->prepare($query);

                // Bind the values dynamically to the placeholders.
                foreach ($filtered_data as $key => &$value) {
                    // Handle any necessary transformations such as date format conversions here.
                    // Ensure dates are in the correct format for MySQL.
                    // If the field is a date field, convert it to MySQL date format:
                    if ($key === 'closure_date' || $key === 'DOB') { // replace with your actual date columns
                        $date = DateTime::createFromFormat('M jS, Y', $value);
                        if (!$date) {
                            $date = DateTime::createFromFormat('m/d/y', $value);
                        }
                        if (!$date) {
                            // If the date format is not correct, set the value to NULL or use a default date.
                            $value = null; // Or a default date like '1970-01-01'.
                        } else {
                            $value = $date->format('Y-m-d'); // Format the date for MySQL.
                        }
                    }

                    // Bind the value to the statement.
                    $statement->bindValue(":$key", $value);
                }

                // Attempt to execute the statement.
                if (!$statement->execute()) {
                    echo("An error occured at");
                    echo($processedCount);
                    // Handle errors, e.g., output error information.
                    echo "\nPDOStatement::errorInfo():\n";
                    print_r($statement->errorInfo());
                    fclose($file);
                }
                else{
                    $processedCount += 1;
                }
            }
        }
        // Close the file after reading.
        echo "\n";
        fclose($file);

        // Final count output.
        echo "Import complete: " . $processedCount . " records processed.\n";
    } else {
        // Output an error message if no file was uploaded.
        echo "No file uploaded.";
    }
} else {
    // Output an error message if the server request method isn't POST.
    echo "Invalid request.";
}
?>
