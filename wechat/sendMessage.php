<?php
header("Content-type:text/html;charset=utf-8");
require_once('comm.php');

$page =0;
$pagesize =20;
if($_POST['page']){
	$page = $_POST['page'];
}
$start =$pagesize*$page;
echo "<form action='sendMessage.php' method='post'>
			<table>	
				<input type='hidden' name='page' id='page' value=".($page+1)." />
				<input type='submit' value='NextPage' />
			</table>
	 </form>";
$connect =  new connect();
$list = $connect->query("SELECT * FROM ecs_chat_log ORDER BY time DESC LIMIT $start,$pagesize");
while($arr = mysql_fetch_array($list)){
	echo $arr['time']."=>".$arr['nickname'].":".$arr['message']."<br />";
	echo "<form action='sendMessage.php' method='post'>
			<table>
				<input type='text' name='message' id='message' />
				<input type='hidden' name='openid' id='openid' value=".$arr['openid']." />
				<input type='hidden' name='page' id='page' value=".$page."/>
				<input type='submit' value='send' />
			</table>
	 </form>";
}
//$result = $connect->query("INSERT INTO ecs_chat_log (openid,message,nickname,time) VALUES('".$openid."','".$Content."','".$nickname."','".$CreateTime."')");
if($_POST['message'] && $_POST['openid']){
	require_once('getTokenMessage.php');
	$message = $_POST['message'];
	$openid = $_POST['openid'];
	unset($_POST['message']);
	$access_token =getToken();
	$content='{
		"touser":"'.$openid.'",
		"msgtype":"text",
		"text":
		{
			 "content":"'.$message.'"
		}
	}';
	$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$access_token";
	$result = https_request($url, $content);
	echo $result;
	header("Location:sendMessage.php");
}else{
	echo 'please text the message!!!';
}

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
