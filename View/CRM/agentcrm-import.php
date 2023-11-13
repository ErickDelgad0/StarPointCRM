<?php
include '../../php/functions.php'; // Ensure the path to your functions is correct
session_start();

$pdo = pdo_connect_mysql();

// Check if we are logged in
check_loggedin($pdo, '../index.php');

CRM_header('AgentCRM - Import');
?>

<style>
    .table tbody tr th, .table tbody tr td {
        min-width: 200px;
    }
</style>

<script src="http://code.jquery.com/jquery.js"></script>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <?= CRM_topbar($_SESSION) ?>

        <div class="container">
            <div id="message"></div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Select CSV File</h3>
                </div>
                <div class="panel-body">
                    <div class="row" id="upload_area">
                    <form method="post" id="upload_form" enctype="multipart/form-data">
                        <div class="col-md-6" align="left"></div>
                        <div class="col-md-6">
                        <input type="file" name="file" id="csv_file" />
                        </div>
                        <br /><br /><br />
                        <div class="col-md-12" align="left">
                        <input type="submit" name="upload_file" id="upload_file" class="btn btn-primary" value="Upload" />
                        </div>
                    </form>
                    
                    </div>
                    <div class="table-responsive" id="process_area">
                    <!-- Content dynamically added here, possibly through AJAX or PHP -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <?=CRM_footer()?>
    <?=logout_modal()?>
</div>

<script>
$(document).ready(function() {
  // Variable to store column mappings
  var columnMappings = {};

  // Handler for the form submission
  $('#upload_form').on('submit', function(event) {
    event.preventDefault();
    $.ajax({
      url: "../../php/agentCRM-upload.php",
      method: "POST",
      data: new FormData(this),
      dataType: 'json',
      contentType: false,
      cache: false,
      processData: false,
      success: function(data) {
        if (data.error != '') {
          $('#message').html('<div class="alert alert-danger">' + data.error + '</div>');
        } else {
          $('#process_area').html(data.output);
          $('#upload_area').css('display', 'none');
        }
      }
    });
  });

  // Handler for when the mapping select elements change value
  $(document).on('change', '.set_column_data', function() {
    var columnNumber = $(this).data('column_number');
    var columnName = $(this).val();
    columnMappings[columnNumber] = columnName;
  });

  // Event delegation for the import button click event
  // This is necessary if the button is dynamically loaded after initial page load
  $(document).on('click', '#import', function(event) {
    event.preventDefault();

    // Iterate over the mapping select elements and build the columnMappings object
    $('.set_column_data').each(function() {
      var columnNumber = $(this).data('column_number');
      var columnName = $(this).val();
      columnMappings[columnNumber] = columnName;
    });

    // Prepare FormData with the column mappings and any additional data
    var formData = new FormData($('#upload_form')[0]);
    formData.append('column_mappings', JSON.stringify(columnMappings));

    // AJAX request to import.php
    $.ajax({
      url: "../../php/agentCRM-import.php",
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      cache: false,
      beforeSend: function() {
        $('#import').text('Importing...');
      },
      success: function(data) {
        $('#import').text('Import');
        $('#process_area').hide();
        $('#upload_area').show();
        $('#upload_form')[0].reset();
        $('#message').html("<div class='alert alert-success'>" + data + "</div>");
      },
      error: function(xhr, status, error) {
        console.error(error);
        $('#message').html("<div class='alert alert-danger'>Error: " + error + "</div>");
      }
    });
  });
});

</script>