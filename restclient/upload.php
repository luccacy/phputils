<?php
//include the httpful package.
include('../lib/httpful-0.2.0.phar');
if(!isset($_FILES) || !isset($_POST['token'])) {
	die('No file specified.');
}
else {
	echo md5_file($_FILES['upfile']['tmp_name']);
	$uploadfile = fopen($_FILES['upfile']['tmp_name'],"r");//打开文件
	$filesize = $_FILES['upfile']['size'];//文件大小
	$buffer = fread($uploadfile, $filesize);//读文件内容
	$ori_file_name = $_FILES['upfile']['name'];//原始文件名
	
	//upload file with httpful lib
	//$response = \Httpful\Request::put($_POST['swift_url'].'/'.$_POST['container'].'/'.$_POST['obj_name'])
	//	->addHeader("X-Auth-Token",$_POST['token'])
	//	->addHeader("X-Object-Meta-Orig-Filename",preg_replace("/[ ,\n,\t,\r]/", '', $ori_file_name))
	//	->body($buffer)
	//	->parseResponsesWith(function ($result) {
	//		//echo $raw_headers;
	//		return explode("\r\n\r\n", $result);
	//	})
	//	->send();
	//print_r($response);
	
	
	//upload file with curl lib
	$ch = curl_init();
	// set url
	curl_setopt($ch, CURLOPT_URL, $_POST['swift_url'].'/'.$_POST['container'].'/'.$_POST['obj_name']);
	curl_setopt($ch, CURLOPT_HEADER, true);
	//set to true to return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Auth-Token: '.$_POST['token'], "X-Object-Meta-Orig-Filename: ".preg_replace("/[ ,\n,\t,\r]/", '', $ori_file_name)));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $buffer);
	//curl_setopt($ch, CURLOPT_PUT, 1);
	//curl_setopt($ch, CURLOPT_INFILE, $uploadfile);
	//curl_setopt($ch, CURLOPT_INFILESIZE, $filesize);
	//curl_setopt($ch, CURLOPT_NOBODY, true);
	// $output contains the output string
	$output = curl_exec($ch);
	echo $output;
}


//move_uploaded_file($_FILES['upfile']['tmp_name'], $uploadfile);
//print_r($_FILES);
//print_r($_POST);
//$tmp_file = 
//while (!feof($tmp_file)){
//	$buffer = fread($tmp_file, 2048);
//	echo $buffer;
//}
 	  // code to execute endwhile;$tmp_file;
?>