<?php

include("functions.php"); // Assuming this file contains the pdo_connect_mysql function.

$pdo = pdo_connect_mysql(); // Establish a database connection.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Check if a file has been uploaded.
    if (isset($_FILES['file'])) {
        
        // Retrieve column mappings from the form data.
        $column_mappings = json_decode($_POST['column_mappings'], true);
        
        // Open the uploaded file for reading.
        $file = fopen($_FILES['file']['tmp_name'], 'r');
        
        // Initialize an array to hold error messages.
        $errorMessages = [];

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
                $query = "INSERT INTO Ambetter ($columns) VALUES ($placeholders)
                          ON DUPLICATE KEY UPDATE $update_clause";

                // Prepare the query using PDO.
                $statement = $pdo->prepare($query);

                // Bind the values dynamically to the placeholders.
                foreach ($filtered_data as $key => &$value) {
                    // Handle any necessary transformations such as date format conversions here.
                    // Ensure dates are in the correct format for MySQL.
                    // If the field is a date field, convert it to MySQL date format:
                    if (in_array($key, ['broker_effective_date', 'broker_term_date', 'policy_effective_date', 'policy_term_date', 'paid_through_date', 'member_DOB', 'created'])) {
                        $originalValue = $value; // Store the original value for debugging

                        // Determine if the year is in a two-digit or four-digit format
                        $dateParts = explode('/', $value);
                        if (count($dateParts) === 3) {
                            $year = $dateParts[2];

                            // Special handling for 'broker_term_date' with two-digit year
                            if ($key === 'broker_term_date' && strlen($year) === 2 && $year == '99') {
                                $year = '9999';
                                $dateParts[2] = $year;
                                $value = implode('/', $dateParts);
                                $date = DateTime::createFromFormat('m/d/Y', $value);
                            } 
                            // Standard processing for other fields and formats
                            else {
                                if (strlen($year) === 4) {
                                    $date = DateTime::createFromFormat('m/d/Y', $value);
                                } else {
                                    $date = DateTime::createFromFormat('m/d/y', $value);
                                }
                            }
                        } else {
                            // Try the 'M jS, Y' format
                            $date = DateTime::createFromFormat('M jS, Y', $value);
                        }

                        if (!$date) {
                            // Log the original value for debugging purposes
                            error_log("Failed to parse date: " . $originalValue);
                            $value = null; // Or handle this case as appropriate
                        } else {
                            $value = $date->format('Y-m-d'); // Format the date for MySQL.
                        }
                    }

                    // Bind the value to the statement.
                    $statement->bindValue(":$key", $value);
                }


                try {    
                    // Attempt to execute the statement.
                    if (!$statement->execute()) {
                        // Handle errors, e.g., add error information to the array.
                        $errorMessages[] = ['Row' => $processedCount, 'Error' => json_encode($statement->errorInfo())];
                    } else {
                        $processedCount += 1;
                    }
                } catch (PDOException $e) {
                    // Log the error with the row number or content to the array
                    $errorMessages[] = ['Row' => $processedCount, 'Error' => $e->getMessage()];
                }
            }
        }
        // Close the file after reading.
        fclose($file);

        // Final count output.
        echo "Import complete: " . $processedCount . " records processed.\n";

        if (isset($_SESSION['file_data'])) {
            unset($_SESSION['file_data']);
        }

        // If there are errors, print them.
        if (!empty($errorMessages)) {
            echo "Some records could not be processed:\n";
            // Loop through and print out each error.
            foreach ($errorMessages as $error) {
                echo "Row {$error['Row']}: {$error['Error']}\n";
            }
            // Error Option, save errors to a session variable, redirect, and display on another page idk
            // $_SESSION['error_messages'] = $errorMessages;
            // header('Location: show_errors.php');
            // exit;
        }

        // Redirect to another page after completion if no errors or after displaying them.
        // header('Location: success_page.php');
        // exit;

    } else {
        // Output an error message if no file was uploaded.
        echo "No file uploaded.";
    }
} else {
    // Output an error message if the server request method isn't POST.
    echo "Invalid request.";
}