<?php
//include the httpful package.
include('../lib/httpful-0.2.0.phar');
$pattern = '/[ ,\n]/';
//////获取token
$url_base = 'http://192.168.213.60:5000/v2.0/';
$postfix = 'tokens';
$auth_user = 'admin';
$auth_pwd = '123456';
$tenant_name = 'openstackDemo';
//set the final auth url.
$auth_url = $url_base.$postfix;
$json = '{"auth":{"passwordCredentials":{"username": "'.$auth_user.'", "password":"'.$auth_pwd.'"}, "tenantName":"'.$tenant_name.'"}}';
$response = \Httpful\Request::post($auth_url)
	->sendsJson()
	->body($json)
	->send();
//echo $response."<br>";//显示返回的json
//从json中取得token和swift url
$result = json_decode($response);
//get the token
$token = $result->access->token->id;
echo "<p>token=".$token."</p>";
//get a service list
$service_catalog = $result->access->serviceCatalog;
echo "<p></p>";
//print_r($service_catalog);//print the service list
$swift_url = $service_catalog[4]->endpoints[0]->publicURL;
echo "swift publicURL = ".$swift_url;
echo "<br>";
//开始swift的操作
//Retrieve a list of existing storage containers ordered by names.
//$json = '{}';
$response = \Httpful\Request::get($swift_url)
	->addHeader("X-Auth-Token",$token)
	->send();
echo $response."<br>";//显示返回的结果
$containers = preg_split($pattern, $response);
//print_r($containers);//显示返回的结果
echo "<br>";

//Retrieve a list of objects stored in the container. 
//$container = "";
foreach ($containers as $container) {
 	 // loop through values 
 	 if ($container == '') continue;
 	 //echo 'objects in '.$container."<br>";
 	 $response = \Httpful\Request::get($swift_url.'/'.$container.'?format=json')
 	 	->addHeader("X-Auth-Token",$token)
 	 	->send();
 	 //echo $response."<br><br>";
 	 //print_r(preg_split($pattern, $response));
 	 echo "<br>";
 	 //下面是显示列出来的文件/文件夹                            
	$result = json_decode($response);
	//print_r($result);
	echo '<table width="200" border="1"><caption>Objects in '
		.$container
		.'</caption>
		<tr>
		<th scope="col">Object Name</th>
		<th scope="col">Object Type</th>
		<th scope="col">Object Size(bytes)</th>
		<th scope="col">Last Modified</th>
		<th scope="col">Hash(MD5)</th>
		</tr> ';
	foreach ($result as &$obj) {
	 	 // loop through values
	 	 echo '<tr><td><a href="./download.php?file='.$swift_url.'/'.$container.'/'.$obj->name
	 	 .'&token='.$token
	 	 .'&size='.$obj->bytes
	 	 .'&type='.$obj->content_type
	 	 .'" target="new">'
	 	 .$obj->name.'</a></td>';
	 	 echo '<td>'.$obj->content_type.'</td>';
	 	 echo '<td>'.$obj->bytes.'</td>';
	 	 echo '<td>'.$obj->last_modified.'</td>';
	 	 echo '<td>'.$obj->hash.'</td></tr>'; 
	} 
	echo '</table>';
	echo '<form enctype="multipart/form-data" action="upload.php" method="post">
		<input name="upfile" type="file"/><br>
		object name:<input name="obj_name" type="input" value=""/><br>
		<input name="swift_url" type="hidden" value="'.$swift_url.'"/>
		<input name="container" type="hidden" value="'.$container.'"/>
		<input name="token" type="hidden" value="'.$token.'"/>
    	<input value="Submit" type="submit"/>
		</form>';
} 
//Retrieve object metadata and other standard HTTP headers. 
//$response = \Httpful\Request::get($swift_url.'/test/testPDF')
//	->addHeader("X-Auth-Token",$token)
//	->send();
//echo $response;