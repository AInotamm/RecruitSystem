<?php
namespace Home\Controller;
use Think\Controller;

class PersonalController extends BaseController{
	private $_cinfo;
	private $_userRole;
	private $_role;
	private $_academy;
	public function index(){
		$this->_obInit();
		$this->_showInfo();
		$this->display('Personal/index');
	}

	private function _obInit(){
		$this->_cinfo = M('users');
		$this->_userRole = M('userrole');
		$this->_role = M('role');
		$this->academy = M('academy');		
	}

	public function _showInfo(){
		$condition['studentnum'] = $_SESSION['studentnum'];
		$stu = $this->_cinfo->where($condition)->find();

		$condition2['user_id'] = $stu['id'];
		$stu_id = $this->_userRole->where($condition2)->find();

		$condition3['id'] = $stu_id['role_id'];
		$stu_position = $this->_role->where($condition3)->find();

		$stu['position'] = $stu_position['role'];
		$stu['grade'] = substr($stu['studentnum'],0,4);

		$condtion4['id'] = $stu['academy_id'];
		$condtion4['school_id'] = $stu['school_id'];
		$stu_academy = $this->academy->where($condtion4)->field('academy')->find();
		$stu['academy'] = $stu_academy['academy'];
		if($stu['gender'] == 1){
			$stu['gender'] = '男';
		}elseif($stu['gender'] == 0){
			$stu['gender'] = '女';
		}else{
			$stu['gender'] = '人妖';
		}
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
	public function _empty() {
        $this->display('Errors/index');
    }
}