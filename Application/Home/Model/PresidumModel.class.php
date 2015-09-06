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
		return $stu;
	}

	public function addPresidum($stu,$academy,$gender,$position){
		$this->setPresidum();
		$condition['user_id'] = $stu['id'];
		$condition['organisation'] = session('now_org');
		$pre = $this->findPresidum($condition);

		if($pre){
			echo "该主席已存在";exit;
		}else{
			$content['name'] = $stu['name'];
			$content['gender'] = $gender;
			$content['user_id'] = $stu['id'];
			$content['phone']  = $stu['phone'];
			$content['stu_num'] = $stu['studentnum'];
			$content['academy'] = $academy;
			$content['organisation'] = session('now_org');
			$content['position'] = $position;
			$this->_presidum->add($content);
		}

	}

	public function changePre($preid,$content){
		$this->setPresidum();
		$condition['id'] = $preid;
		$this->_presidum->where($condition)->save($content);
	}

	public function getPre($preid){
		$this->setPresidum();
		$condition['id'] = $preid;
		$pre = $this->_presidum->where($condition)->find();		
		return $pre;
	}
}