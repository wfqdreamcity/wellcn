<?php
$code =$_GET['code'];

$url ="http://ad.qctt.cn/ChouJiang/index.php?code=".$code;

header("Location:$url");

?>