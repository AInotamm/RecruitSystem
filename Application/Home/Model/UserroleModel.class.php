<?php
namespace Home\Model;
use Think\Model;
class UserroleModel extends Model {
	private $_user_role;
	public function setUser_role(){
		$this->_user_role = M('userrole');
	}

	public function findUser_role($condition){
		$this->setUsers();
		$stu = $this->_user_role->where($condition)->find();
		return $stu['user_role'];
	}

	public function addOne($organisation_id,$user_id,$position){
		$this->setUser_role();
		$content['user_id'] = $user_id;
		$content['role_id'] = $position;
		$content['department_id'] = $organisation_id;
		$condition['user_id'] = $user_id;
		$condition['department_id'] = '1';
		$stu = $this->_user_role->where($condition)->find();
		if($organisation_id = 1){
			if($stu){
					$this->_user_role->where($condition)->save($content);
			}else{
				$this->_user_role->add($content);
			}
		}else{

		}
	}

	public function changeRole($user_id,$role_id){
		$this->setUser_role();
		$content['role_id'] = $role_id;
		$condition['id'] = $user_id;
		$this->_user_role->where($condition)->save($content);
	}
}