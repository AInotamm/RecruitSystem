<?php
namespace Home\Controller;
use Think\Controller;

class ChairmanController extends BaseController{
	private $_presidum;
	private $_users;
	private $_academy;
	public function index(){
		$this->_presidumInit();
		$this->_presidumVal();
		$this->display('Chairman/presidium');
	}

	private function _presidumInit(){
		$this->_presidum = M('presidum');
		$this->_users = M('users');
		$this->_academy = M('academy');
	}

	private function _presidumVal(){

		$presidum = $this->_presidum->where('id > 1 AND state = 1')->select();
		$this->assign('presidum',$presidum);
	}

	public function deletePre(){
		$condition['id'] = I('post.presidum_id');
		$content['state'] = '0';
		var_dump($content);exit;
		$a = $this->_presidum->where($condition)->save($content);
		if($a){
			echo 1;
		}else{
			echo '删除失败';
		}
	}

	public function searchStudent(){
		$this->_users = M('users');
		$this->_academy = M('academy');
		$condition['studentnum'] = I('post.studentID');
		$stu = $this->_users->where($condition)->find();
		$condition2['id'] = $stu['academy_id'];
		$academy = $this->_academy->where($condition2)->find();
		$stu['academy'] = $academy['academy'];
		if($stu['gender'] == 1){
			$stu['gender'] = '男';
		}elseif($stu['gender'] == 0){
			$stu['gender'] = '女';
		}else{
			$stu['gender'] = '人妖';
		}
		$this->ajaxReturn($stu);

	}

	public function addpre(){
		
	}
	
	public function _empty() {
        $this->display('404/index');
    }
}