<?php
  header("Content-type:text/html; charset=utf-8");
  Class UserAction extends CommonAction{
		
		public function _initialize(){
			
		}
		public function index(){
		
			if($_SESSION['user']){
				
				$user_info =$_SESSION['user'];
				
				//我的订单
				$myorders =$this->getMyList();
				
				//订单状态
				$status = array(
					'1'=>'下单成功',
					'2'=>'已接单',
					'3'=>'已完成',
					'4'=>'待支付',
				);
				
				$this->myorders =$myorders;
				$this->status =$status;
				$this->user =$user_info;
				$this->display();
				//p($address);
				//p($user_info);
			}else{
				$this->display('login');
			}
		}
		//获取我的订单
		public function getMyList(){
			
			$condition['user_id']=$_SESSION['user']['id'];
			$list =M("orders")->where($condition)->order('createtime desc')->select();
			return $list;
		}
		
		//获取我的抢单
		public function getMyObtainList(){
			
			$user_id =$_SESSION['user']['id'];
			$condition['postman_id']=$user_id;
			$list =M("orders")->where($condition)->order('createtime desc')->select();
			$str="";
			
			foreach($list as $key=>$value){
				$str .='<li><a class="LiBgImg-8 boderUp">
						<div style=" width:98%; padding-left:16%">
							   <span class="u_taocan_item_tit" style=" width:65%">'.$value["user_name"].'
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
			
	        echo $str;
		}
		
		//更新用户信息index
		public function updateUserInfoIndex(){
			$condition['id']=$_SESSION['user']['id'];
			$result =M("user")->where($condition)->find();
			
			//获取学校信息
			$obj =D('User');
			$school =$obj->getSchoolList();
			
			$this->user_info =$result;
			$this->school =$school;
			
			$this->display(personal);
			
		}
		
		//更新用户信息
		public function updataUserInfo(){
			
			$user_id=$_SESSION['user']['id'];
			
			$data['username']=I("post.username");
			$data['mobile']=I("post.tel");
			$data['sex']=I("post.sex");
			$data['address']=I("post.address");
			$data['school']=I("post.school_id");
			$data['school_name']=I("post.schoolname");
			
			$user =D('User');
			
			$userInfo =$user->updateUserInfo($user_id,$data);	

			//更新session['user']信息
			 $sql="SELECT * FROM user WHERE id='".$user_id."' LIMIT 1";
			 $result =M()->query($sql);
			 $result =$result['0'];
			 $_SESSION['user'] =$result;
			
			echo "1";

		}
		
		//下单赋值我模板页
		public function addOrderIndex(){
			//微信授权(如果是微信浏览器)
			 $user_agent = $_SERVER['HTTP_USER_AGENT'];
			 if (strpos($user_agent, 'MicroMessenger') != false) {
				 $url ='/user/addOrderIndex';
				 $_SESSION['wx_url']=$url;
				 $Wechat = new WechatAction();
				 $Wechat->wechat();
				 if(!isset($_SESSION['openid'])){
					 exit;
				 }
				//p($_SESSION);
			 }		
			
			if($_SESSION['user']){
				//获取地址
				$address =$this->getAddress();
				//如果还为设置地址,先添加地址
				if(!$address){
					$this->addAddressIndex();
					exit;
				}
				
				//获取餐馆列表
				$restaurant =$this->getRestaurant();
				
				//获取配置项
				$condition['type']='service';
				$option =M('options')->where($condition)->select();
				
				$this->address =$address;
				$this->restaurant =$restaurant;
				$this->option =$option;
				
				$this->display(order);
			}else{
				$this->display('login');
			}
			//p($address);
			//p($option);
		}
		
		//保存订单
		public function addOrder(){
			
			$user_info =$_SESSION['user'];
			$data['order_sn']="sn".time();
			$data['user_id']=$user_info['id'];
			$data['user_name']=I("post.name");
			$data['user_mobile']=I("post.mobile");
			$data['type']=I("post.type");
		
			$data['school_id']=I("post.shool_id");
			$data['address']=I("post.address");
			
			$data['restaurant_id'] =I("post.restaurant_id");
			$data['restaurant_name'] =I("post.restaurant_name");
			$data['category'] =I("post.service_id");
			$data['status'] =4;
			$data['createtime'] =time();
			$data['comment'] =I("post.comment");
			
			M('orders')->add($data);

			$order_sn =$data['order_sn'];
			$goods_name =$data['restaurant_name']."服务";
			$goods_price =0.01;
			$this->WxPay($order_sn,$goods_name,$goods_price);
			
		}
		
		//获取用户地址
		public function getAddress(){
			$type=I("post.type");
			$address =M('user_address')->where('user_id='.$_SESSION['user']['id'])->select();
			if($type){
				$str ="";
				foreach($address as $key=>$value){
					$str.=' <li class="address"><a href="#" class="LiBgImg-7 boderUp">
							<div style=" width:98%; padding-left:16%">
								   <span class="u_taocan_item_tit" style=" width:80%">'.$value["schoolname"].'</span>     
								   <span class="u_taocan_item_price2" style=" width:20%;text-align:right;"><i></i></span>
								   <span class="u_taocan_item_cont" style=" width:100%">联系人:'.$value["name"].'&nbsp;&nbsp;'.$value["mobile"].'</span>
								   <span class="u_taocan_item_cont" style=" width:88%">详情地址:'.$value["address"].'</span>
								   <span class="u_taocan_item_price" style=" width:12%;text-align:right;">
										  <i data-id='.$value["id"].' class="editaddress">删除</i>
							  
								   </span>
								   <div style="clear:both"></div>
							 </div></a></li>';
				}
			
				echo $str;
				exit;
			}else{
				return $address;
			}
			
			
		}
		
		//删除地址
		public function deladdress(){
			$id=$_POST['id'];
			$condition['id']=$id;
			
			M('user_address')->where($condition)->delete();
			
			echo "ok";
			
		}
		
		//获取餐馆列表
		public function getRestaurant(){
			
			$condition['enabled']=1;
			$list =M("restaurant")->where($condition)->select();
			return $list;
		}
		
		//获取指定地址
		public function getSpecAddress($id){
			$address =M('user_address')->where('id=$id')->find();
			return $address;
		}
		
		//增加地址赋值模板页
		public function addAddressIndex(){
			$obj =D('User');
			$school =$obj->getSchoolList();
			
			$this->school =$school;
			$this->display(address);
			
		}
		
		//增加用户地址
		public function addAddress(){
			
			$condition['user_id'] =$_SESSION['user']['id'];
			
			//$result =M('user_address')->where($condition)->find();
			
			$data['user_id'] =$_SESSION['user']['id'];
			$data['school'] =I("post.school_id");
			$data['schoolname'] =I("post.schoolname");
			$data['name'] =I("post.name");
			$data['type'] =I("post.type");
			$data['typedes'] =I("post.typesex");
			$data['mobile'] =I("post.mobile");
			$data['address'] =I("post.address");
			
			if($result){//更新数据
				M('user_address')->where($condition)->save($data);
			}else{//添加数据
				M('user_address')->add($data);
			}
			
			echo '1';
			//$url =web_root.'/user/index';
			//header("location:$url");
			
		}
		
		//用户注册
		public function register(){
			
			$account ='18065896336';
			$passwd ='123456';
			
			/* $user =D('User');
			$result =$user->register($account,$passwd); */
			$result =$this->AutoRegister($account);
			
			echo $result;
			
			
		}
		//显示登入页面
		public function indexlogin(){
			$this->display('login');
		}
		
		//用户登入
		public function login(){
			$tel =I("post.tel");
			$passwd =I("post.passwd");
	
			//$result =D('User')->login($tel,$passwd);
			$result =$this->AutoRegister($tel);
			if($result){
				echo "1";
			}else{
				echo "0";
			}
			
		}
		
		//微信支付函数
	  public function WxPay($order_sn=0,$goods_name=0,$cost_price=0){
			$wechat =I("get.act");//微信支付返回结果
			 if($wechat==""){
				$url ="http://wellcn.duapp.com/wxpay/newCarPay.php?order_sn=".$order_sn."&goods_name=".$goods_name."&order_amount=".$cost_price;
				header("Location: $url");
			 }else{
				 //支付成功
				 if($wechat=="wxpay_success"){
					 $condition['order_sn'] =I("get.order_sn");
					 $data['status']=1;
					 //更新订单状态
					 M('orders')->where($condition)->save($data);
					 
				 }
				 //取消支付 或者支付失败
				 if($wechat =="wxpay_fail"){
					 //提示错误信息
					 //$this->redirect("Mobile/user/myorders");
					 //$this->display(succeed2);
				 }
				 $url =web_root.'/userwx/myorders';
				 header("location:$url");
				 exit;
			 }
		  
	  }
		
		//退出登入
		public function logout(){
			unset($_SESSION['user']);
			unset($_SESSION['openid']);
			$url =web_root.'/index/index';
			header("location:$url");
		}
		
		//普通用户注册
	 public function RegisterAtt($username,$passwd){
		 
		 $exist =$this->checkUsername($username);
		 if(!$exist){
			 $passwd =MD5($passwd);
			 $sql ="INSERT INTO user (account,username,passwd,mobile,createtime) VALUES('".$username."','".$username."','".$passwd."','".$username."','".time()."')";
			 $result =M()->execute($sql);
			 if($result){
				 return true;
			 }
		 }
	     return false;
		 
	 }
	 //验证用户是否已经存在
	 public function checkUsername($username){
		 
		 $sql ="SELECT id FROM user WHERE account='".$username."' LIMIT 1";
		 $result =M()->query($sql);
		 if($result){
			 return true;
		 }else{
			 return false;
		 }
	 }
	 
	 //用户下单时自动注册
	 public function AutoRegister($username){
		 
		 $result=$this->checkUsername($username);
		 //存在获取信息
		 if($result){
			 $sql="SELECT * FROM user WHERE account='".$username."' LIMIT 1";
			 $result =M()->query($sql);
			 $result =$result['0'];
			 $_SESSION['user'] =$result;
		//不存在注册新用户
		 }else{
			 $passwd='111111';//默认密码
			 $this->RegisterAtt($username,$passwd);
			 $sql="SELECT * FROM user WHERE account='".$username."' LIMIT 1";
			 $result =M()->query($sql);
			 $result =$result['0'];
			 $_SESSION['user'] =$result;
		 }
		 
		 //更新openid
		 if(isset($_SESSION['openid'])){
			 $user_id =$_SESSION['user']['id'];
			 $openid =$_SESSION['openid'];
			 
			 //查看是否含有openid
			 $sql ="SELECT openid FROM user WHERE id=$user_id LIMIT 1";
			 $user =M()->query($sql);
			 if($user['0']['openid']==''){
				 $sql ="UPDATE user SET openid='".$openid."' WHERE id=$user_id LIMIT 1";
				 M()->execute($sql);
			 }
		 }
		 
		 return "ok";
		 
	 }
	 
	 //手机发送验证码
	public function sendCode(){
		$mobile =I("post.mobile");
		
		//实例化短信类
		$sms =new SmsAction();
		
		$sms->model_sms($mobile);
	}
	//手机验证码验证
	public function validate(){
		$mobile =I("post.mobile");
		$code =I("post.code");
		
		//实例化短信类
		$sms =new SmsAction();
		$result =$sms->validate($mobile,$code);
		if($result){
			echo '1';
		}else{
			echo '0';
		}
	}
	 //注销SESSION
	 public function unsession(){
		  session_destroy();
		 $this->redirect('/user/index');
	 }
		
  }  
?>
