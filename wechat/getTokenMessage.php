<?php
getToken();
function getToken(){
	require_once('comm.php');
	$currnet = time();
	$Token_result = new connect();
	$sql = "SELECT * FROM options where type='token'";
	$result = $Token_result->query($sql);
	$arr=mysql_fetch_array($result);
	$time_token = $arr['value'];
	if($currnet >= $time_token){
		$appid = "wxa2986e7d8863b2ab";
		$secret ="08d000097799e646d11b57e0acd71a9f";
		$access_token = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret");
		$access_token = json_decode($access_token);
		$access_token = $access_token->{'access_token'}; 
		
		//修改新的token信息
		$time = $currnet+7200;
		$sql = "UPDATE options SET title='".$access_token."',value='".$time."' where type='token'";
		$Token_result->query($sql);
	}else{
		$access_token = $arr['title'];
		//$access_token ="48QToD2xXqQSwSNF8uqn6iF3o3P4yDEqvrrxaDDO0nxqcZFgg95IwA_xAafzwhsVxcM4Hh0tQTtwIcTqFxEa_B1IUpXE67cVUmpBnq_09rE";
	}
	
	return $access_token;
}
