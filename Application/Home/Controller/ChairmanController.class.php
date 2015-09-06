<?php
namespace Home\Controller;
use Think\Controller;
//分站长，主席好累。。果断统一主席了
class ChairmanController extends BaseController{
	private $_presidum;
	private $_users;
	private $_academy;
	private $_user_role;
	private $_organization;
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
		$this->_organization = D('organization');
	}

	private function _presidumVal(){
		$orgid = session('now_org');
		$presidum = $this->_presidum->where("state = 1 AND organisation ='$orgid'")->select();		
		$org_name = $this->_organization->checkOrg($orgid);
		$orgname = $org_name['organization'];
		$this->assign('orgname',$orgname);
		$this->assign('presidum',$presidum);
		if($user_role == 7){
			$this->assign('pre_but',$but);
		}
	}

	public function deletePre(){
		$this->_presidumInit();
		$content['state'] = '0';
		$condition['id'] = I('post.presidum_id');
		$condition['organisation'] =session('now_org');
		$pre_info = $this->_presidum->findPresidum($condition);
		$condition3['user_id'] = $pre_info['user_id'];
		$condition3['organization'] = session('now_org');
		$pre_role = $this->_user_role->findUser_role($condition3);
		if($pre_role['role_id'] == '7'){
			echo "不能删除主席";
			exit;
		}
		if( session('user_role') < 6){
			echo "权限不够";
			exit;
		}
		$a = $this->_presidum->changePre(I('post.presidum_id'),$content);
		$pre = $this->_presidum->getPre(I('post.presidum_id'));
		$role = '3';
		$b =$this->_user_role->changeRole($pre['user_id'],$role);
		echo '1';
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
	public function checkrole(){
		if( session('user_role') < 6){
			$this->ajaxReturn('1');
		}else{
			$this->ajaxReturn('1');
		}
	}
	public function addpre(){
		if( session('user_role') < 6){
			$this->ajaxReturn('0');
			exit;
		}
		$this->_presidumInit();
		$condition['studentnum'] = I('post.user_id');
		$stu =$this->_users->findUsers($condition);
		$condition2['id'] = $stu['academy_id'];
		if($stu){
			$academy = $this->_academy->findAcademy($condition2);
			$gender = $this->_users->checkgender($stu['gender']);
			$this->_user_role->addOne($stu['id'],I('post.position'));
			$position = '副主席';
			$this->_presidum->addPresidum($stu,$academy,$gender,$position);
			echo '添加成功';
		}else{
			echo 0;
		}


	}
	
	public function _empty() {
        $this->display('Errors/index');
    }
}