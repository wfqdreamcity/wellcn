<?php
header("Content-type:text/html;charset=utf-8");
require_once('getTokenMessage.php');
//$access_token ="1dn7E1W4OCI5Jdbfop5Qu51GkUVJrDMTYPPM1b7PPth-wVfuMsNpdHJN7DEW1xVjTcSdkHZmJcYvgMtSTDnrQ-ifmyRAnpw9L6Xkjhx89cY";
$access_token =getToken();
$jsonmenu='{
    "button": [
	    {
            "type": "view", 
            "name": "优惠活动", 
            "url": "http://wellcn.duapp.com/index.php/user/addOrderIndex"
        },
        {
             "type": "view", 
            "name": "棒棒达", 
            "url": "http://wellcn.duapp.com/index.php/user/addOrderIndex"
        }, 
        {
            "name": "个人中心", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "我的订单", 
                    "url": "http://wellcn.duapp.com/index.php/userwx/myorders"
                }, 
                {
                    "type": "view", 
                    "name": "我的任务", 
                    "url": "http://wellcn.duapp.com/index.php/userwx/myduty"
                }, 
				{
                    "type": "view", 
                    "name": "地址管理", 
                    "url": "http://wellcn.duapp.com/index.php/userwx/myAddress"
                }, 
				{
                    "type": "view", 
                    "name": "账号信息", 
                    "url": "http://wellcn.duapp.com/index.php/userwx/index"
                }, 
            ]
        }
    ]
}';

$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$result = https_request($url, $jsonmenu);
var_dump($result);

//开启自动回复功能
$result = file_get_contents("https://api.weixin.qq.com/cgi-bin/get_current_autoreply_info?access_token=$access_token");
var_dump($result);

function https_request($url,$data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
