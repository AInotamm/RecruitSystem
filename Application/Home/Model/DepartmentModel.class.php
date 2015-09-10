<?php
namespace Home\Model;
use Think\Model;
class DepartmentModel extends Model {

	public function dfind($condition){
		return $this->field('id', true)->where($condition)->select();
	}

}