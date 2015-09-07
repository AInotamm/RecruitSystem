<?php
namespace Home\Model;
use Think\Model;
class UsersModel extends Model {
	private $_users;
	public function setUsers() {
		$this->_users = M('users');
	}

	public function findUsers($condition) {
		$this->setUsers();
		$stu = $this->_users->where($condition)->find();
		return $stu;
	}
	public function selectUsers($condition){
		$this->setUsers();
		$stu = $this->_users->where($condition)->select();
		return $stu;
	}

	public function checkgender($condition) {
		$user_gender ='未设置';
        if (is_numeric($condition)) {
            if($condition == 1) {
                $user_gender = '男';
            } else if($condition == 0) {
                $user_gender = '女';
            }
        }
		return $user_gender;
	}
}