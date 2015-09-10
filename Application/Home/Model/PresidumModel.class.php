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
		$pre = $this->findPresidum(array(
            'user_id' => $stu['id'],
            'organisation' => session('now_org')
        ));

		if(empty($pre)) {
			return false;
		} else {
            $content = array(
                'name' => $stu['name'],
                'gender' => $gender,
                'user_id' => $stu['id'],
                'phone' => $stu['phone'],
                'stu_num' => $stu['studentnum'],
                'academy' => $academy,
                'organisation' => session('now_org'),
                'position' => $position,
                'update' => date('Y-m-d H:i:s', time()),
            );
			$this->_presidum->add($content);
            return $this->_presidum->getLastInsID();
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