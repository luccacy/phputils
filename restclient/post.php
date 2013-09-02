<?php
include('../lib/httpful-0.2.0.phar');
$json = '{"services" : {"node-ip":"127.0.0.1","name":"httpd"}}';
$url = 'http://10.12.13.11:8989/services';
$response = \Httpful\Request::post($url)
->sendsJson()
->body($json)
->send();

$result = json_decode($response);

var_dump($result);