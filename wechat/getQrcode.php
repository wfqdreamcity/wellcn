<?php
//header("Content-type:text/html;charset=utf-8");
require_once('getTokenMessage.php');


$scene_id = $_GET['scene_id'];
if($scene_id){
	$access_token = getToken();

	$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;

	$qrcode='{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
	$result = https_request($url, $qrcode);

	$result = json_decode($result);
	//var_dump($result);
	$ticket = UrlEncode($result->{'ticket'});
	//获取二维码
	$url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;

	$result = getqrcode($url,$scene_id);
}else{
	echo 'type the url as:http://www.chenvxu.com/mobile/wechat/getQrcode.php?scene_id=123(1--100000)';
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

function getqrcode($url,$scene_id){
	$imageinfo = downloadImageFromWechat($url);
	
	$filename = time()."qrcode_".$scene_id.".jpg";
	$local_file = fopen('qrcode_image/'.$filename,'w');
	if(false !== $local_file){
		if(false !== fwrite($local_file,$imageinfo['body'])){
			fclose($local_file);
		}
	}
	//echo '<img src="qrcode_image/"'.$filename.'/>';
	downloads($filename);exit;
}
//下载文件到当前服务器
function downloadImageFromWechat($url){
	$ch = curl_init($url);
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_NOBODY,0);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	$package = curl_exec($ch);
	$httpinfo = curl_getinfo($ch);
	curl_close($ch);
	return array_merge(array('body'=> $package),array('header'=>$httpinfo));
}

//下载文件到本地
function downloads($name){
        $file_dir = dirname(__FILE__)."/qrcode_image/";
        if (!file_exists($file_dir.$name)){
            header("Content-type: text/html; charset=utf-8");
            echo "File not found!";
            exit; 
        } else {
            $file = fopen($file_dir.$name,"r"); 
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: ".filesize($file_dir . $name));
            Header("Content-Disposition: attachment; filename=".$name);
            echo fread($file, filesize($file_dir.$name));
            fclose($file);
        }
}


