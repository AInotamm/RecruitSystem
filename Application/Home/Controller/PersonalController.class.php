<?php
namespace Home\Controller;
use Think\Controller;

class PersonalController extends Controller{
	private $_cinfo;
	public function index(){
		$this->_showInfo();
		$this->display('Personal/index');
	}
	public function _showInfo(){
		session('studentnum','2013211854');
		$condition['studentnum'] = $_SESSION['studentnum'];
		$this->_cinfo = M('users');
		$stu = $this->_cinfo->where($condition)->find();
		$this->assign('address','111');
		$this->assign('stu_info',$stu);
	}

	public function reaPassword(){//修改密码
		$stu_tel = I('post.stu_tel');
		$new_password = I('post.new_password');
		$confirm_password = I('post.confirm_password');
		// if($new_password != $confirm_password && strlen($new_password) > 10 && strlen($confirm_password) < 6 && strlen($stu_tel) != 11){
		// 	echo 0;
		// }else{
			$condition['studentnum'] = $_SESSION['studentnum'];
			$condition['studentnum'];
			$content['phone'] = $stu_tel;
			$content['password'] = $new_password;
			$this->_cinfo = M('users');
			$this->_cinfo->where($condition)->save($content);
			echo 1;
		// }
		
	}
}