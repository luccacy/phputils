<?php
//include the httpful package.
include('../lib/httpful-0.2.0.phar');
if(!isset($_GET['file']) || !isset($_GET['token'])) {
	die('No file specified.');
}
else {
	//注意，下面两行代码的作用是清除下载文件的头部中多出来的三个字节
	//这三个字节是UTF8的BOM，它会把文件内容挤掉三个字节
	ob_end_clean();
    ob_start();
	//Retrieve object metadata and other standard HTTP headers. 
	$ch = curl_init();
	// set url
	curl_setopt($ch, CURLOPT_URL, $_GET['file']);
	curl_setopt($ch, CURLOPT_HEADER, true);
	//set to true to return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Auth-Token: '.$_GET['token']));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');
	curl_setopt($ch, CURLOPT_NOBODY, true);
	// $output contains the output string
	$output = curl_exec($ch);
	//echo '<p>'.$output.'</p>';
	$response_header = preg_split("/\n/", $output);
 	$file_name = preg_split("/[ ,\n]/", $response_header[4]);
 	//print_r(curl_getinfo($ch));
 	//download the file.
	header('Content-type: '.$_GET['type']);
 	header("Content-Disposition: filename=\"".preg_replace("/[\n,\t,\r]/", '',$file_name[1])."\"");
 	//header("Content-Disposition: filename=\"".$temp."\"");
 	header('Content-length: '.$_GET['size']);
 	
 	//header();
	$response = \Httpful\Request::get($_GET['file'])
		->addHeader("X-Auth-Token",$_GET['token'])
		->send();
	
	echo $response;
	
	curl_close($ch);
}
?>