<?php
namespace Home\Model;
use Think\Model;
class AcademyModel extends Model {
	private $_academy;
	public function setUsers(){
		$this->_academy = M('academy');
	}

	public function findAcademy($condition){
		$this->setUsers();
		$stu = $this->_academy->where($condition)->find();
		return $stu['academy'];
	}
}