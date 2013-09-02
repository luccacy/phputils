<?php

$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, "http://192.168.213.60:8888/v1/AUTH_d2ff9b5c1af5455a9b8be3d366ecf779/test/testhtml");
curl_setopt($ch, CURLOPT_HEADER, true);
//set to true to return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Auth-Token: d0e75051a1d54fd082972a3b6eeb60c0'));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//curl_setopt($ch, CURLOPT_NOBODY, true);
// $output contains the output string
$output = curl_exec($ch);
$response_header = preg_split("/\n/", $output);
$file_name = preg_split("/[ ,\n]/", $response_header[4]);
echo preg_replace("/[\n,\t,\r]/", '', $file_name[1]);
echo strlen($file_name[1]);
//curl_exec($ch);
//print_r(curl_getinfo($ch));

// close curl resource to free up system resources
curl_close($ch);

//echo $output;