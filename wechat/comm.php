<?php

class connect{
	private $query;
	public function __construct(){
		$this->mysqlconnect();
	}
	public function mysqlconnect(){
		$link = mysql_connect('sqld.duapp.com:4050','P6ikt8MVSnRdEX4WZsq3FcFa','jIEHtY0AsBHHakNzn7ZbGjP7u1lZAS7i');
		//$link = mysql_connect('127.0.0.1','root','qctt123');
		if(!$link){
			 die('Could not connect server err: ' . mysql_error());
		}
		$result = mysql_select_db('tuvPAeZWsKuOIRTcNzbT',$link);
		//$result = mysql_select_db('wellcn',$link);
		 if(!$result){
			 die('Could not connect db err: ' . mysql_error());
		 }
		//return $this->connect;
	}
	public function  query($sql){
		$result= mysql_query($sql);
		return $result;
	}
	
}