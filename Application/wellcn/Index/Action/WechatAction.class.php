<?php
header("Content-type:text/html; charset=utf-8");
Class WechatAction extends Action{
	
	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
	var $appid = 'wxa2986e7d8863b2ab';

	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	var $appsecret = '08d000097799e646d11b57e0acd71a9f';

	//=======【curl超时设置】===================================
	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	var $curl_timeout = 30;
	
	var $code;//code码，用以获取openid
	var $openid;//用户的openid
	
	/**
	 * 	作用：生成可以获得code的url
	 */
	function createOauthUrlForCode($redirectUrl)
	{
		$urlObj["appid"] =$this->appid;
		$urlObj["redirect_uri"] = "$redirectUrl";
		$urlObj["response_type"] = "code";
		$urlObj["scope"] = "snsapi_base";
		$urlObj["state"] = "STATE"."#wechat_redirect";
		$bizString = $this->formatBizQueryParaMap($urlObj, false);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
	}
	
	/**
	 * 	作用：生成可以获得openid的url
	 */
	function createOauthUrlForOpenid()
	{
		$urlObj["appid"] = $this->appid;
		$urlObj["secret"] = $this->appsecret;
		$urlObj["code"] = $this->code;
		$urlObj["grant_type"] = "authorization_code";
		$bizString = $this->formatBizQueryParaMap($urlObj, false);
		return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
	}
	
	/**
	 * 	作用：通过curl向微信提交code，以获取openid
	 */
	function getOpenid()
	{
		$url = $this->createOauthUrlForOpenid();
        //初始化curl
       	$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOP_TIMEOUT, $this->curl_timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//运行curl，结果以jason形式返回
        $res = curl_exec($ch);
		curl_close($ch);
		//取出openid
		$data = json_decode($res,true);
		$this->openid = $data['openid'];
		return $this->openid;
	}
	
	/**
	 * 	作用：格式化参数，签名过程需要使用
	 */
	function formatBizQueryParaMap($paraMap, $urlencode)
	{
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
		    if($urlencode)
		    {
			   $v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0) 
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	
	/**
	 * 	作用：设置code
	 */
	function setCode($code_)
	{
		$this->code = $code_;
	}
	
	//获取微信用户基本
    function wechat(){
		if(!isset($_SESSION['openid'])){
			//获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
			 $url = 'http://wellcn.duapp.com/index.php/wechat/wechat';
			 //=========步骤1：网页授权获取用户openid============
			 //通过code获得openid
			 if (!isset($_GET['code'])){
				//触发微信返回code码
				$url = $this->createOauthUrlForCode($url);
				Header("Location: $url"); 
			 }else{
				//获取code码，以获取openid
				$code = $_GET['code'];
				$this->setCode($code);
				$openid = $this->getOpenId();
				//赋值openid
				$_SESSION['openid']=$openid;
				//通过openid 登入
				$this->LoginByOpenid($openid);
				//回调原来的页面
				//Header("Location: selectcombo");
				$url =web_root.$_SESSION['wx_url'];
				$this->redirect($url);				
			 }
			 
			 
			 
		}
	 }
	 
	 //openid 登入
	 function LoginByOpenid($openid){
		 $sql="SELECT * FROM user WHERE openid='".$openid."' LIMIT 1";
		 $result =M()->query($sql);
		 $result =$result['0'];
		 $_SESSION['user'] =$result;
	 }










}
?>