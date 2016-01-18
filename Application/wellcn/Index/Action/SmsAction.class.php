<?php
/**
 * 在PHP 5.5.17 中测试通过。
 * 默认用通用接口(send)发送，若需使用模板接口(tpl_send),请自行将代码注释去掉。
 */
header("Content-type:text/html; charset=utf-8");
Class SmsAction extends CommonAction{
//通用接口发送样例
	Public function _initialize(){
		$this->apikey = "76e046852f581e0bdf86b6814155f570"; //请用自己的apikey代替
	}



//模板接口样例（不推荐。需要测试请将注释去掉。)
/* 以下代码块已被注释
  $apikey = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"; //请用自己的apikey代替
  $mobile = "xxxxxxxxxxx"; //请用自己的手机号代替
  $tpl_id = 1; //对应默认模板 【#company#】您的验证码是#code#
  $tpl_value = "#company#=云片网&#code#=1234";
  echo tpl_send_sms($apikey,$tpl_id, $tpl_value, $mobile);
*/
	//登陆验证
	function validate($mobile,$code){
		$where['Phone']=$mobile;
		$where['RandomCode']=$code;
		$time=time()-60*30;
		$where['UpdateTime']=array("egt",$time);
		$info=M("phonevalidation")->where($where)->find();
		if($info){
			$zhuce=new OrderAction();
			$zhuce->AutoRegister($mobile);
			return "1";
		}else{
			return "0";
		}
	}
	//登陆短信
	function model_sms($mobile){
		$code=rand(111111,999999);
		$tpl_id = 733521; //对应默认模板 【#company#】您的验证码是#code#
		$tpl_value = "#code#=".$code;
		//$info=$this->tpl_send_sms($this->apikey,$tpl_id, $tpl_value, $mobile);
		$ch = curl_init();
		$url = 'http://apis.baidu.com/xingqitel/app_sms/app_sms?c=%E6%82%A8%E7%9A%84%E6%B3%A8%E5%86%8C%E9%AA%8C%E8%AF%81%E7%A0%81%E4%B8%BA%EF%BC%9A123456%E3%80%90%E5%85%B4%E4%BC%81%E9%80%9A%E4%BF%A1%E3%80%91&m='.$mobile;
		$header = array(
			'apikey: c527da3c4465548a5cd5399993b9d2a6',
		);
		// 添加apikey到header
		curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 执行HTTP请求
		curl_setopt($ch , CURLOPT_URL , $url);
		$res = curl_exec($ch);


		var_dump(json_decode($res));exit;
		$strinfo=json_decode($info,true);
		if($strinfo['msg']=='OK'){
			$status=M("phonevalidation")->where('Phone='.$mobile)->find();
			if($status){
				$data['RandomCode']=$code;
				$data['UpdateTime']=time();
				M("phonevalidation")->where('Phone='.$mobile)->save($data);
			}else{
				$data['RandomCode']=$code;
				$data['Phone']=$mobile;
				$data['CreateTime']=time();
				$data['UpdateTime']=time();
				M("phonevalidation")->add($data);
			}
		}
		return $info;
	}

/**
* 通用接口发短信
* apikey 为云片分配的apikey
* text 为短信内容
* mobile 为接受短信的手机号
*/
function send_sms($apikey, $text, $mobile){
	$url="http://yunpian.com/v1/sms/send.json";
	$encoded_text = urlencode("$text");
	$post_string="apikey=$apikey&text=$encoded_text&mobile=$mobile";
	return $this->sock_post($url, $post_string);
}

/**
* 模板接口发短信
* apikey 为云片分配的apikey
* tpl_id 为模板id
* tpl_value 为模板值
* mobile 为接受短信的手机号
*/
function tpl_send_sms($apikey, $tpl_id, $tpl_value, $mobile){
	$url="http://yunpian.com/v1/sms/tpl_send.json";
	$encoded_tpl_value = urlencode("$tpl_value");  //tpl_value需整体转义
	$post_string="apikey=$apikey&tpl_id=$tpl_id&tpl_value=$encoded_tpl_value&mobile=$mobile";
	return $this->sock_post($url, $post_string);
}

/**
* url 为服务的url地址
* query 为请求串
*/
function sock_post($url,$query){
	$data = "";
	$info=parse_url($url);
	$fp=fsockopen($info["host"],80,$errno,$errstr,30);
	if(!$fp){
		return $data;
	}
	$head="POST ".$info['path']." HTTP/1.0\r\n";
	$head.="Host: ".$info['host']."\r\n";
	$head.="Referer: http://".$info['host'].$info['path']."\r\n";
	$head.="Content-type: application/x-www-form-urlencoded\r\n";
	$head.="Content-Length: ".strlen(trim($query))."\r\n";
	$head.="\r\n";
	$head.=trim($query);
	$write=fputs($fp,$head);
	$header = "";
	while ($str = trim(fgets($fp,4096))) {
		$header.=$str;
	}
	while (!feof($fp)) {
		$data .= fgets($fp,4096);
	}
	return $data;
}


/*
*微信发送模板推送接口
*/
public function sendmMessageForWechat($order_id){
	$url ="http://www.chenvxu.com/wechat/sendTemMessage.php?order_id=$order_id";
	$result=file_get_contents($url);
}

}
?>