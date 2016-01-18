<?php
  header("Content-type:text/html; charset=utf-8");
  Class UserwxAction extends CommonAction{
		//分页大小
		public $pageSize;
		//设置订单状态
		public $status;
		
		
		public function _initialize(){
			
			 //微信授权(如果是微信浏览器)
			 $user_agent = $_SERVER['HTTP_USER_AGENT'];
			 if (strpos($user_agent, 'MicroMessenger') != false) {
				 $str =__ACTION__;
				 $str =explode("/",$str);
				 $url ='/userwx/'.$str['2'];
				 $_SESSION['wx_url']=$url;
				 $Wechat = new WechatAction();
				 $Wechat->wechat();
				 if(!isset($_SESSION['openid'])){
					 exit;
				 }
				//p($_SESSION);
			 }		
			
			if(!isset($_SESSION['user'])){
				$url ='index.php/User/indexlogin';
				$this->redirect($url);
				exit;
			}
			//设置页大小
			$this->pageSize=15;
			//设置订单状态
			$this->status = array(
				'1'=>'下单成功',
				'2'=>'已接单',
				'3'=>'已完成',
				'4'=>'待支付',
			);
			
		}
		
		public function index(){
			$user_info =$_SESSION['user'];
			$this->user =$user_info;
			$this->display();
		}
		
		//微信客户端使用的
		public function myorders(){
				
			$pagesize =$this->pageSize;
			$user_id=$_SESSION['user']['id'];
			$sql ="SELECT o.*,u.mobile as postman_name_mobile FROM orders o LEFT JOIN user u ON o.postman_id=u.id where o.user_id =$user_id ORDER BY o.createtime DESC LIMIT 0,$pagesize";
			$list =M()->query($sql);
			
			$this->status1 =$this->status;
			$this->myorders =$list;
			
			$this->display();
		 }
		//获取我的订单(异步加载)
		public function getMyObtainList(){
			$page =I("post.page");
			
			$pagesize =$this->pageSize;
			$start =$pagesize*$page;
			$user_id=$_SESSION['user']['id'];
			$sql ="SELECT o.*,u.mobile as postman_name_mobile FROM orders o LEFT JOIN user u ON o.postman_id=u.id where o.user_id =$user_id ORDER BY o.createtime DESC LIMIT $start,$pagesize";
			$list =M()->query($sql);
			$str="";
			
			foreach($list as $key=>$value){
				$str .='<li>
						<a class="LiBgImg-8 boderUp">
						<div style=" width:84%; padding-left:16%">
							   <span class="u_taocan_item_tit" style=" width:60%">'.date("Y-m-d H:i:s",$value["createtime"]).'</span>
							   <span class="u_taocan_item_tit" style=" width:60%">'.$value["user_name"].'
							   <!--增加的标识2个-->
							   &nbsp;&nbsp;<em style=" background-color:#448aca; padding:0 3%; font-size:12px;color:#fff;">状态:</em>               
							   </span>     
							   <span class="u_taocan_item_price2" style=" width:30%;text-align:right;">
										  <i>'.$this->status[$value["status"]].'</i>
							   </span>
							   <span class="u_taocan_item_cont" style=" width:100%">商家:'.$value["restaurant_name"].'</span>
							   <span class="u_taocan_item_cont" style=" width:100%">详情地址:'.$value["address"].'</span>';
				if($value["status"] !='1' && $value["status"] !='4'){
					$str .='<span class="u_taocan_item_tit" style=" width:60%">派送员:'.$value["postman_name"].'
							   </span>     
							   <span class="u_taocan_item_price2" style=" width:20%;text-align:right;">
										  <i>'.$value["postman_name_mobile"].'</i>
							   </span>';
					}
					if($value['status']=='2'){
					 $str.='<span class="u_taocan_item_tit orders_comfirm" style="width:60%;color: #DF2B2B;" data-id="'.$value["id"].'">确认收货</span>'; 
					}
				 $str.='<div style="clear:both"></div>
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
		
		//确认订单处理
		public function comfirm(){
			$order_id =I("post.order_id");
			$data['status']=3;
			$condition['id']=$order_id;
			$result =M("orders")->where($condition)->save($data);
			if($result){
				echo "ok";
			}
		}
		
		
		 //我的抢单
		 public function myduty(){
			$pagesize =$this->pageSize;
			$postman_id=$_SESSION['user']['id'];
			$sql ="SELECT * FROM orders where postman_id =$postman_id ORDER BY createtime DESC LIMIT 0,$pagesize";
			$list =M()->query($sql);
			
			$this->myorders =$list;
			
			$this->display();
		 }
		 
		 //获取我的抢单(异步加载)
		public function getMyDutyList(){
			$page =I("post.page");
			
			$pagesize =$this->pageSize;
			$start =$pagesize*$page;
			$postman_id=$_SESSION['user']['id'];
			$sql ="SELECT * FROM orders where postman_id =$postman_id ORDER BY createtime DESC LIMIT $start,$pagesize";
			$list =M()->query($sql);
			$str="";
			
			foreach($list as $key=>$value){
				$str .='<li><a class="LiBgImg-8 boderUp">
						<div style=" width:84%; padding-left:16%">
							   <span class="u_taocan_item_tit" style=" width:95%">'.date("Y-m-d H:i:s",$value["createtime"]).'</span>
							   <span class="u_taocan_item_tit" style=" width:55%">'.$value["user_name"].'
							   &nbsp;&nbsp;<em style=" background-color:#448aca; padding:0 3%; font-size:12px;color:#fff;">电话:</em>               
							   </span>     
							   <span class="u_taocan_item_price2" style=" width:20%;text-align:right;">
										  <i>'.$value["user_mobile"].'</i>
							   </span>
							   <span class="u_taocan_item_cont" style=" width:100%">商家:'.$value["restaurant_name"].'</span>
							   <span class="u_taocan_item_cont" style=" width:100%">详情地址:'.$value["address"].'</span>
							   <span class="u_taocan_item_cont" style=" width:100%">备注:'.$value["comment"].'</span>
							   <div style="clear:both"></div>
						 </div></a></li>';
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
		
		//我的地址
		public function myAddress(){
			$address =M('user_address')->where('user_id='.$_SESSION['user']['id'])->select();
			$num =count($address);
			if($num<5){
				$is_add_address =1;
			}else{
				$is_add_address =0;
			}
			
			$this->is_add_address =$is_add_address;
			$this->address =$address;
			$this->display('myaddress');
		}
		
		//获取微信用户基本
	 public function wechat(){
		if(!isset($_SESSION['openid'])){
			//获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
			 $url = 'http://wellcn.duapp.com/index.php/userwx/wechat';
			 $Wechat = new WechatAction();
			 //=========步骤1：网页授权获取用户openid============
			 //通过code获得openid
			 if (!isset($_GET['code'])){
				//触发微信返回code码
				$url = $Wechat->createOauthUrlForCode($url);
				Header("Location: $url"); 
			 }else{
				//获取code码，以获取openid
				$code = $_GET['code'];
				$Wechat->setCode($code);
				$openid = $Wechat->getOpenId();
				//赋值openid
				$_SESSION['openid']=$openid;
				//通过openid 登入
				$this->LoginByOpenid($openid);
				//回调原来的页面
				//Header("Location: selectcombo");
				$redirect_url =web_root."/".$redirect_url;
				//$url =web_root.'/index/index';
				$this->redirect($redirect_url);				
			 }
			 
			 
			 
		}
	 }
	 
	 //openid 登入
	 public function LoginByOpenid($openid){
		 $sql="SELECT * FROM user WHERE openid='".$openid."' LIMIT 1";
		 $result =M()->query($sql);
		 $result =$result['0'];
		 $_SESSION['user'] =$result;
	 }
		
  }  
?>
