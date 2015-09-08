<?php
namespace Home\Model;
use Think\Model;
class UsersorganisationModel extends Model {
	private $_users_organisation;
	public function setUO(){
		$this->_users_organisation = M('usersorganisation');
	}

	public function checkUO($condition,$key){
		$this->setUO();
		$UO = $this->_users_organisation->where($condition)->field($key)->select();
		return $UO;
	}

	public function findUO($condition){
		$this->setUO();
		$UO = $this->_users_organisation->where($condition)->find();
		return $UO;
	}
}