<?php
namespace Home\Controller;
use Think\Controller;

class ChairmanController extends BaseController{
	private $_presidum;
	private $_users;
	private $_academy;
	private $_user_role;

	public function index(){
		$this->_presidumInit();
		$this->_presidumVal();
		$this->display('Chairman/presidium');
	}

	private function _presidumInit(){
		$this->_presidum = D('presidum');
		$this->_users = D('users');
		$this->_academy = D('academy');
		$this->_user_role = D('userrole');
	}

	private function _presidumVal(){

		$presidum = $this->_presidum->where('id > 1 AND state = 1')->select();
		$this->assign('presidum',$presidum);
	}

	public function deletePre(){
		$this->_presidumInit();
		$condition['id'] = I('post.presidum_id');
		$content['state'] = '0';
		$a = $this->_presidum->where($condition)->save($content);
		$pre = $this->_presidum->getPre( I('post.presidum_id'));
		$b =$this->_users->change_role($pre['user_id'],'3');
		if($a){
			echo 1;
		}else{
			echo '删除失败';
		}
	}

	public function searchStudent(){
		$this->_presidumInit();
		$condition['studentnum'] = I('post.studentID');
		$stu = $this->_users->findUsers($condition);
		$condition2['id'] = $stu['academy_id'];
		$academy = $this->_academy->findAcademy($condition2);
		$stu['academy'] = $academy;
		$stu['gender'] = $this->_users->checkgender($stu['gender']);
		$this->ajaxReturn($stu);

	}

	public function addpre(){
		$this->_presidumInit();
		$condition['studentnum'] = I('post.user_id');
		$stu =$this->_users->findUsers($condition);
		if($stu){
			$academy = $this->_academy->findAcademy($condition2);
			$gender = $this->_users->checkgender($stu['gender']);
			$this->_user_role->addOne(I('post.organisation'),$stu['id'],I('post.position'));
			$position = '副站';
			$this->_presidum->addPresidum($stu,$academy,$gender,I('post.organisation'),$position);
			echo '添加成功';
		}else{
			echo 0;
		}


	}
	
	public function _empty() {
        $this->display('Errors/index');
    }
}