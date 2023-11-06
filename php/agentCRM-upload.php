<?php
include("config.php");
//upload.php
session_start();

$error = '';
$html = '';

if ($_FILES['file']['name'] != '') {
    $file_array = explode(".", $_FILES['file']['name']);
    $extension = end($file_array);

    if ($extension == 'csv') {
        $file_data = fopen($_FILES['file']['tmp_name'], 'r');
        $file_header = fgetcsv($file_data);
        $html .= '<table class="table table-bordered"><tr>';

        foreach ($file_header as $count => $header) {
            $html .= '<th>' . htmlspecialchars($header) . '<br>'; // Show the CSV header
            $html .= '<select name="set_column_data[]" class="form-control set_column_data" data-column_number="'.$count.'">
                <option value="">Do Not Import</option>'; // Include this option to ignore the column
        
            // Loop through each column configuration to create the options
            foreach ($AgentCRM_Columns as $key => $value) {
                $selected = (strtolower($header) === strtolower($value['label'])) ? ' selected="selected"' : '';
                $html .= '<option value="'.$key.'"'.$selected.'>'.$value['label'].'</option>';
            }
        
            $html .= '</select></th>';
        }

        
        $html .= '</tr>';
        $limit = 0;

        while (($row = fgetcsv($file_data)) !== FALSE) {
            $limit++;
            if ($limit < 4) {
                $html .= '<tr>';
                for ($count = 0; $count < count($row); $count++) {
                    $html .= '<td>'.$row[$count].'</td>';
                }
                $html .= '</tr>';
            }
            $temp_data[] = $row;
        }
        $_SESSION['file_data'] = $temp_data;

        $html .= '

        </table>
        <br />
        <div align="left">
        <button type="button" name="import" id="import" class="btn">Import</button>
        </div>

        <br />
        ';
    } else {
        $error = 'Only <b>.csv</b> file allowed';
    }
} else {
    $error = 'Please Select CSV File';
}

$output = array(
    'error'  => $error,
    'output' => $html
);

echo json_encode($output);

?>
