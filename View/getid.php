<?php

// Read .env file
$config = parse_ini_file('../.env');

$token = $config['ACRM_TOKEN'];

$curl = curl_init();

$headers = array();
$headers[] = "Authorization: Bearer " . $token;

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://dev.branninggroup.com/webhooks/agentcrm/custom-fields/tsaceDaIqeXPpCYNbqBX/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => $headers,  // Add the headers here
));

$response = curl_exec($curl);

if (curl_errno($curl)) {
    echo 'Error:' . curl_error($curl);
}

curl_close($curl);
echo $response;

?>
