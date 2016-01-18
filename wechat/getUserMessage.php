<?php
header("Content-type:text/html;charset=utf-8");
require_once('getTokenMessage.php');
$appid = "wxd77c30b31041c36c";
$secret ="e25a371a5de71eb02a47bf1bd8f3e04a";
	
$code = $_GET['code'];
$state = $_GET['state'];

//获取openid
$result = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code");

$result = json_decode($result);
$openid = $result->{'openid'};

//获取Token
//$access_token = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret");
//$access_token ="I0GQMvnAdNF0orNfy0fMYb4iG3oRhaZmzXyxS-l63JbERbQNkXr0IcnIdyFOGuAjpVm7K2jPVpuiLXG-bvdoVTR05H3YWZ33h3ripKRITiE";
$access_token = getToken();

//获取用户的基本信息
$result = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN");




echo $result;
