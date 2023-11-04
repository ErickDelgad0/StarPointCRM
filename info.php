<?php
// Show all information, defaults to INFO_ALL
// phpinfo();

echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . "\n";
echo 'post_max_size: ' . ini_get('post_max_size') . "\n";
echo 'max_execution_time: ' . ini_get('max_execution_time') . "\n";
?>
