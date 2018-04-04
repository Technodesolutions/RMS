<?php 

//function for sending post http request using curl
function sendPost($url,$post,&$errorMessage,$returnResult=false){
	$res = curl_init($url);
	curl_setopt($res, CURLOPT_POST,true);
	curl_setopt($res, CURLOPT_POSTFIELDS, $post);
	$certPath =str_replace( "application\helpers\MY_url_helper.php",'cacert.pem', __FILE__);
	curl_setopt($res, CURLOPT_CAINFO, $certPath);
	if ($returnResult) {
		curl_setopt($res, CURLOPT_RETURNTRANSFER, true);
	}
	$referer = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	curl_setopt($res, CURLOPT_REFERER, $referer);
	$result = curl_exec($res);
	$errorMessage = curl_error($res);
	curl_close($res);
	return $result;
}

 ?>