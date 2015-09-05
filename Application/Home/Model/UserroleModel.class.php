<?php
namespace Home\Model;
use Think\Model;
class UserroleModel extends Model {
	private $_user_role;
	public function setUser_role(){
		$this->_user_role = M('userrole');
	}

	public function findUser_role($condition){
		$this->setUser_role();
		$stu = $this->_user_role->where($condition)->find();
		return $stu['role_id'];
	}

	public function findUsers_org($condition){
		$this->setUser_role();
		$stu = $this->_user_role->where($condition)->select();
		return $stu;
	}
	public function addOne($user_id,$position){
		$this->setUser_role();
		$content['user_id'] = $user_id;
		$content['role_id'] = $position;
		$content['organisation_id'] = session('now_org');
		$condition['user_id'] = $user_id;
		$condition['organisation_id'] =  session('now_org');
		$stu = $this->_user_role->where($condition)->find();
			if($stu){
				$this->_user_role->where($condition)->save($content);
			}else{
				$this->_user_role->add($content);
			}
	}

	public function changeRole($user_id,$role_id){
		$this->setUser_role();
		$content['role_id'] = $role_id;
		$condition['id'] = $user_id;
		$this->_user_role->where($condition)->save($content);
	}
}