<?php
namespace Home\Model;
use Think\Model;

class MemberModel extends Model {

	public function mcheck($condition,$key){
		return $this->field($key)->where($condition)->select();
	}

	public function mfind($condition){
		return $this->field('id', true)->where($condition)->find();
	}
}