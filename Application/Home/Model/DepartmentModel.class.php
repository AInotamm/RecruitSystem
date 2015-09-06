<?php
namespace Home\Model;
use Think\Model;
class DepartmentModel extends Model {
	private $_department;
	public function setDep(){
		$this->_department = M('department');
	}

	public function searchDep($condition){
		$this->setDep();
		$org = $this->_department->where($condition)->select();
		return $org;
	}

}