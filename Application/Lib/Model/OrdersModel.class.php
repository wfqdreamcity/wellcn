<?php
/**
*订单基本类
*包括订单的基本操作
*@ 吴方琦
*@ 2015-08-02 创建时间
*@ version 0.1
**/
class OrdersModel extends Model{
	
	protected $tableName = 'orders';
	
	//订单查询(获取订单列表)
	public function getOrderList($condition){
		 $list =M('orders')->where($condition)->select(); 
		 return $list;
	}
	
	//获取订单的基本信息
	
	//增加订单信息
	public function addOrder($data){
		$result =M('orders')->add($data); 
		if($result !== false){
			return $result;
		}else{
			return false;
		}
	}
	
	//修改订订单信息
	public function updateOrder($condition,$data){
		$result =M('orders')->where($condition)->save($data); 
		if($result !== false){
			return true;
		}else{
			return false;
		}
	}
	
	//修改订单状态
	
	//删除订单
	public function delOrders($condition){
		$result =M('orders')->where($condition)->delete();
		if($result !== false){
			return true;
		}else{
			return false;
		}		
	}
	
}

?>