<?php	
function p($arr){
	echo '<pre>' . print_r($arr, true) . '</pre>';
}
 //����ʱ,ʣ�������
 //day1�ǽ�������ڣ�day2�ǽ�ֹ����
function get_the_remaining_days($day2){
	 $day1=date("Y-m-d H:i:s",time());
     $day=ceil((strtotime($day2)-strtotime($day1))/86400); //60s*60min*24h
	 if($day<=0)
	 {
		return "�ѳɹ�";
	 }else{
	    return $day."��";
	 }
}

// ʱ���ɶ��ٷ���ǰ
function get_long_time($date){  
    $curr = time();
    $date = strtotime($date);
    $tmp = $curr - $date;
    if($tmp < 60){
        $re = $tmp.'��ǰ';
    }else if($tmp < 3600){
        $re = floor($tmp/60).'����ǰ';
    }else if($tmp < 86400){
        $re = floor($tmp/3600).'Сʱǰ';
    }else if($tmp < 259200){//3����
        $re = floor($tmp/86400).'��ǰ';
    }else{
        $re = date('Y��m��d��',$date);
    }
    echo $re;
}  

/*��ȡ�ַ�������ĸ*/
function getfirstchar($s0){   
    $fchar = ord($s0{0});
    if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
    $s1 = iconv("UTF-8","gb2312", $s0);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $s0){$s = $s1;}else{$s = $s0;}
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if($asc >= -20319 and $asc <= -20284) return "A";
    if($asc >= -20283 and $asc <= -19776) return "B";
    if($asc >= -19775 and $asc <= -19219) return "C";
    if($asc >= -19218 and $asc <= -18711) return "D";
    if($asc >= -18710 and $asc <= -18527) return "E";
    if($asc >= -18526 and $asc <= -18240) return "F";
    if($asc >= -18239 and $asc <= -17923) return "G";
    if($asc >= -17922 and $asc <= -17418) return "I";
    if($asc >= -17417 and $asc <= -16475) return "J";
    if($asc >= -16474 and $asc <= -16213) return "K";
    if($asc >= -16212 and $asc <= -15641) return "L";
    if($asc >= -15640 and $asc <= -15166) return "M";
    if($asc >= -15165 and $asc <= -14923) return "N";
    if($asc >= -14922 and $asc <= -14915) return "O";
    if($asc >= -14914 and $asc <= -14631) return "P";
    if($asc >= -14630 and $asc <= -14150) return "Q";
    if($asc >= -14149 and $asc <= -14091) return "R";
    if($asc >= -14090 and $asc <= -13319) return "S";
    if($asc >= -13318 and $asc <= -12839) return "T";
    if($asc >= -12838 and $asc <= -12557) return "W";
    if($asc >= -12556 and $asc <= -11848) return "X";
    if($asc >= -11847 and $asc <= -11056) return "Y";
    if($asc >= -11055 and $asc <= -10247) return "Z";
    return null;
}
 
/*����תƴ��*/
function pinyin($zh){
    $ret = "";
    $s1 = iconv("UTF-8","gb2312", $zh);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $zh){$zh = $s1;}
    for($i = 0; $i < strlen($zh); $i++){
        $s1 = substr($zh,$i,1);
        $p = ord($s1);
        if($p > 160){
            $s2 = substr($zh,$i++,2);
            $ret .= getfirstchar($s2);
        }else{
            $ret .= $s1;
        }
    }
    return $ret;
}

//----------------------------------------------------------------------------------- 
// ��������CheckImageSize($ImageFileName,$LimitSize) 
// �� �ã������ϴ�ͼƬ�Ĵ�С 
// �� ����$ImageFileName �ϴ���ͼƬ�� 
// $LimitSize Ҫ��ĳߴ� 
// ����ֵ������ֵ 
// �� ע����
// ʵ����echo CheckImageSize('https://www.baidu.com/img/bd_logo1.png',array('539','257')) ;
// ����1 ˵��ͼƬ���ʣ�����0˵��������
//----------------------------------------------------------------------------------- 
function CheckImageSize($ImageFileName,$LimitSize) 
{ 
	$size=GetImageSize($ImageFileName); 
	if($size[0]>$LimitSize[0] || $size[1]>$LimitSize[1]) 
	{ 
		//echo 'ͼƬ�ߴ����'; 
		echo "0";
		return false; 
	} 
	return true; 
} 


//�ж��Ƿ����ֻ�
function is_mobile(){ 
    $user_agent = $_SERVER['HTTP_USER_AGENT']; 
    $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong",
"airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525",
"applewebkit/532","asus","audio","aumic","avantogo","becker","benq","bilbo",
"bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger",
"dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web",
"goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei",
"hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc",
"lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-",
"lge9","longcos","maemo","mercator","meridian","micromax","midp","mini",
"mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen",
"nexian","nfbrowser","nintendo","nitro","nokia","nook","novarra","obigo",
"palm","panasonic","pantech","philips","phone","pg-","playstation","pocket",
"pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-",
"scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony",
"spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca",
"telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar",
"verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser",
"wii","windows ce","wireless","xda","xde","zte"); 
    $is_mobile = false; 
    foreach ($mobile_agents as $device) { 
        if (stristr($user_agent, $device)) { 
            $is_mobile = true; 
            break; 
        } 
    } 
    return $is_mobile; 
} 
//���߽��׶���֧��������
//�������ܣ�����֧���ӿڴ��ص������жϸö����Ƿ��Ѿ�֧���ɹ���
//����ֵ����������Ѿ��ɹ�֧��������true�����򷵻�false��
function checkorderstatus($ordid){
    $Ord=M('Orderlist');
    $ordstatus=$Ord->where('ordid='.$ordid)->getField('ordstatus');
    if($ordstatus==1){
        return true;
    }else{
        return false;    
    }
}
//����������
//���¶���״̬��д�붩��֧���󷵻ص�����
function orderhandle($parameter){
    $ordid=$parameter['out_trade_no'];
    $data['payment_trade_no']      =$parameter['trade_no'];
    $data['payment_trade_status']  =$parameter['trade_status'];
    $data['payment_notify_id']     =$parameter['notify_id'];
    $data['payment_notify_time']   =$parameter['notify_time'];
    $data['payment_buyer_email']   =$parameter['buyer_email'];
    $data['payment_status']             =2;
    $data['order_status']             =2;
    $Ord=M('orders');
    $Ord->where('order_sn='.$ordid)->save($data);
	$info=M("orders")->where('order_sn='.$ordid)->find();
	$name=$info['name'];
	$mobile=$info['mobile'];
    $sms=new SmsAction();
	$sms->OrdersSuccess($name,$mobil);

} 
/*-----------------------------------
2013.8.13����
���������������ʵ����Ҫ����ҿ��԰���ɾ����
���忴��������������䲿�ֵ�˵��
------------------------------------*/
//��ȡһ�������Ψһ�Ķ����ţ�
function getordcode(){
    $Ord=M('Orderlist');
    $numbers = range (10,99);
    shuffle ($numbers); 
    $code=array_slice($numbers,0,4); 
    $ordcode=$code[0].$code[1].$code[2].$code[3];
    $oldcode=$Ord->where("ordcode='".$ordcode."'")->getField('ordcode');
    if($oldcode){
        getordcode();
    }else{
        return $ordcode;
    }
}
?>