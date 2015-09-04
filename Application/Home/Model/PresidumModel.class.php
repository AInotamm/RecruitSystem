<?php
namespace Home\Model;
use Think\Model;
class PresidumModel extends Model {
	private $_presidum;
	public function setPresidum(){
		$this->_presidum = M('presidum');
	}

	public function findPresidum($condition){
		$this->setPresidum();
		$stu = $this->_presidum->where($condition)->find();
	}

	public function addPresidum($stu,$academy,$gender,$organisation,$position){
		$this->setPresidum();
		if($organisation == '1'){
			$content['name'] = $stu['name'];
			$content['gender'] = $gender;
			$content['user_id'] = $stu['id'];
			$content['phone']  = $stu['phone'];
			$content['stu_num'] = $stu['studentnum'];
			$content['academy'] = $academy;
			$content['organisation'] = '1';
			$content['position'] = $position;
			$this->_presidum->add($content);
 		}

	}

	public function getPre($preid){
		$this->setPresidum();
		$condition['id'] = $preid;
		$pre = $this->_presidum->where($condition)->find();
		
		return $pre;
	}
}