<?php
  header("Content-type:text/html; charset=utf-8");
  Class IndexAction extends CommonAction{
		//分页大小
		public $pageSize;

		public function _initialize(){
			//设置页大小
			$this->pageSize=15;
		}
		
		public function index(){
			//筛选订单(学校id)
			if($_GET['school_id']){
				$where['school_id']=$_GET['school_id'];
			}
			
			//判断是否登录
			if($_SESSION['user']['is_post']=='1'){
				$is_available=1;
			}else{
				$is_available=0;
			}
			//筛选订单(学校id)和单签用户性别
			$where['school_id']=$_SESSION['user']['school'];
			$where['type']=$_SESSION['user']['sex'];
			
			$where['status']=1;
			//保存搜索条件
			$_SESSION['where']=$where;
			
			$pagesize =$this->pageSize;
			//$sql ="SELECT * FROM orders where status=1 ORDER BY createtime DESC LIMIT 0,$pagesize";
			$sql ="SELECT * FROM orders where 1=1";
			foreach($where as $key =>$value){
				$sql .=" and ".$key."=".$value;
			}
			$sql .=" ORDER BY createtime DESC LIMIT 0,$pagesize";
			$list =M()->query($sql);
			
			//获取地区
			$condition1['enabled']=1;
			$school =M('school')->where($condition1)->select();
			
			$this->is_available=$is_available;
			$this->school=$school;
			$this->orderlist =$list;

			$this->display();
			//p($listboy);
		}
		
		//首页获取订单(异步加载)
		public function getOrderList(){
			$page =I("post.page");
			
			//筛选条件
			$where =$_SESSION['where'];
			
			$pagesize =$this->pageSize;
			$start =$pagesize*$page;
			//$sql ="SELECT * FROM orders where status =1 ORDER BY createtime DESC LIMIT $start,$pagesize";
			$sql ="SELECT * FROM orders where 1=1";
			foreach($where as $key =>$value){
				$sql .=" and ".$key."=".$value;
			}
			$sql .=" ORDER BY createtime DESC LIMIT $start,$pagesize";
			$list =M()->query($sql);
			$str ="";
			foreach($list as $key=>$value){
				$str .='<li style="margin-bottom:0%;width:100%;" class="orderbody">
					<a class="LiBgImg-8 boderUp" style="padding: 0.6%;">
					<div style=" width:84%; padding-left:16%">
						   <span class="u_taocan_item_tit" style=" width:45%;overflow:hidden;">'.$value["user_name"].'
						   </span>';
				if($_SESSION['user']){
					$str .='<span class="u_taocan_item_price2" style=" width:30%;text-align:right;">
						   <em style=" background-color:#448aca; padding:0 5%; font-size:12px;color:#fff;">备:</em>       
									  <i data-id='.$value["id"].' class="obtain">抢单</i>
						   </span>';
			    }
				$str .='<span class="u_taocan_item_cont" style="width:100%;overflow:hidden;">商家:'.$value["restaurant_name"].'</span>
						   <span class="u_taocan_item_cont" style="width:100%;overflow:hidden;">地址:'.$value["address"].'</span>
						   <span class="u_taocan_item_cont" style=" width:100%;overflow:hidden;">备注:'.$value["comment"].'</span>
						   <div style="clear:both"></div>
					 </div>      
					</a>
				</li>';
			}
			if($list){
				$data['result']=1;
			}else{
				$data['result']=0;
			}
			$data['info']=$str;
			$data['page']=$page+1;
			$this->ajaxReturn($data);
		}
		
		//抢订单
		public function getOrder(){
			$condition['id'] =I("post.order_id");
			$condition['status'] ='1';
			
			//判断该订单是否已经被抢
			$result =M('orders')->where($condition)->find();
			if(!$result){
				echo "fail";
				exit;
			}
			$data['postman_id']=$_SESSION['user']['id'];
			$data['postman_name']=$_SESSION['user']['username'];
			$data['status']=2;
			//p($data);
			$order =D("Orders");
			if($order->updateOrder($condition,$data)){
				echo "ok";
			}else{
				echo "fail";
			}
		}
		//更新用户信息
		public function updataUserInfo(){
			$data =array(
				"username" => "FangqiWu",
				"createtime" =>"1438250691", 
				"mobile" => "15117950233",
				"address"=>"版本 朝阳区",
			);
			$user =D('User');
			
			$userInfo =$user->updateUserInfo(1,$data);
		
		}
	
  }  
?>
