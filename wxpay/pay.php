<?php
$order_sn="sn".time();
$goods_name="首次支付";
$cost_price="0.01";
$act = $_GET['act'];
if(!$act){
	$url ="http://wellcn.duapp.com/wxpay/newCarPay.php?order_sn=".$order_sn."&goods_name=".$goods_name."&order_amount=".$cost_price;
	header("Location: $url");
}else{
	echo "结果为:".$act;
}

?>