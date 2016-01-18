<?php
header("Content-type:text/html;charset=utf-8");
$appid = "wxd77c30b31041c36c";
$secret ="e25a371a5de71eb02a47bf1bd8f3e04a";
//$redirect_url ="http://test.qctt.cn/1/getUserMessage.php";
//$redirect_url ="http://www.chenvxu.com/mobile/index.php";
//$redirect_url ="http://www.chenvxu.com/mobile/yc.php?act=select_make";
//$redirect_url ="http://www.chenvxu.com/mobile/my.php?act=coupon";
$redirect_url ="http://www.chenvxu.com/mobile/my.php";
$redirect_url = urlencode($redirect_url);
//echo $redirect_url;exit;

//echo "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_url&response_type=code&scope=snsapi_base&state=123#wechat_redirect";exit;

header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_url&response_type=code&scope=snsapi_base&state=123#wechat_redirect");


?>