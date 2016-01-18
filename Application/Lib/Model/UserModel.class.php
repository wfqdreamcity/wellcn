<?php
/**
*用户基本类
*包括用户基本信息,登入,注册信息等
*@ 吴方琦
*@ 2015-07-30 创建时间
*@ version 0.1
**/
class UserModel extends Model{
	
	protected $tableName = 'user';
	
	
	//获取用户信息
	public function getUserInfo($user_id){
		
		$condition['id']=$user_id;
		$result =M('user')->where($condition)->find();
		
		return $result;
		
	}
	
	//更新用户状态
	public function updateUserStatus($user_id,$status){
		
		$condition['id']=$user_id;
		$data['enabled']=$status;
		
		$resutl =M('user')->where($condition)->save($data);
		
	}
	
	//更新用户信息
	public function updateUserInfo($user_id,$data){
		
		$condition['id']=$user_id;
		
		$resutl =M('user')->where($condition)->save($data);
		
	}
	
	//用户登入
	public function login($account,$passwd){
		$condition['account']=$account;
		$condition['passwd']=md5($passwd);
		
		$user =M('user')->where($condition)->find();
		if($user){
			unset($user['passwd']);
			$_SESSION['user']=$user;
			return true;
		}else{
			return false;
		}
		
	}
	
	//用户注册
	public function register($mobile,$passwd){
		
		//验证改账号是否已经存在
		$result =$this->checkAccount($mobile);
		if($result){
			return "该用户已经存在!";
			exit;
		}
		
		$data['account']=$mobile;
		$data['passwd']=md5($passwd);
		$data['username']=$mobile;
		$data['createtime']=time();
		$data['mobile']=$mobile;
		$data['enabled']=1;
		
		$result =M('user')->add($data);
		
		return 'ok';
		
	}
	
	//微信用户登入
	
	
	//验证用户是否已经存在
	public function checkAccount($mobile){
		
		$condition['account']=$mobile;
		$result =M('user')->where($condition)->find();
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	//获取校区列表
	public function getSchoolList($region_id='52'){
		
		$condition['region_id']=$region_id;
		$condition['enabled']=1;
		
		$list =M('school')->where($condition)->select();
		
		return $list;
	}
	
	
	
	
	
	
	
	
	
}

?>