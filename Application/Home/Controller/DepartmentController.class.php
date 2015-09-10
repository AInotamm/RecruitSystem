<?php
namespace Home\Controller;
use Think\Controller;
class DepartmentController extends BaseController {
	private $_presidum;
	private $_users;
	private $_academy;
	private $_user_role;
	private $_organization;
	private $_department;
	private $_member;

	public function index() {
		$this->_departmentInit();
		$this->_departmentVal();
		$this->display('Department/index');
	}

	private function _departmentInit() {
		$this->_presidum = D('presidum');
		$this->_users = D('users');
		$this->_academy = D('academy');
		$this->_user_role = D('userrole');
		$this->_organization = D('organization');
		$this->_department = D('department');
		$this->_member = D('member');
	}

	private function _departmentVal() {
//		$condition['organization_id'] = session('now_org');
//		$condition['show'] = session('now_org');
        $cond = array(
            'organization_id' => session('now_org'),
            'show' => session('now_org')
        );
		$depa = $this->_department->dfind($cond);
		for($i = 0; $i < count($depa); $i++) {
			$depa[$i]['tab_id'] = $i;  // 导航栏顺序
		}
		$firstdepa = array_shift($depa);
		$firstdepa_id = $firstdepa['id'];
		// $key = 'users_id';
		// $firstdepa_mem = $this->_users_organisation->mcheck($condition,$key);
		// $condition2['id'] = $firstdepa_mem[0]['users_id'];
		$first_meminfo = $this->_users->join('member ON users.id = member.users_id')->where("member.department_id = '$firstdepa_id'")->select();
        for($i = 0; $i < count($first_meminfo) ;$i++) {
			$academy = $this->_academy->findAcademy(array('id' => $first_meminfo[$i]['academy_id']));
			$first_meminfo[$i]['academy'] = $academy;
			$first_meminfo[$i]['gender'] = $this->_users->checkgender($first_meminfo[$i]['gender']);
		}
		$this->assign('first_meminfo',$first_meminfo);
		$this->assign('add_depa',$depa);
		array_shift($depa);
//		$member = array();
		for($i = 0; $i < count($first_meminfo) - 1; $i++) {
			$depa_id = $depa[$i]['id'];
			$member[$i] = $this->_users->join('member ON users.id = member.users_id')->where("member.department_id = '$depa_id'")->select();
		}
		$this->assign('firstdepa',$firstdepa);
		$this->assign('org_name',$depa);
	}
	public function addMem(){
		// if( session('user_role') < 6){
		// 	$this->ajaxReturn('0');
		// 	exit;
		// }
		$this->_departmentInit();
		$condition['studentnum'] = I('post.user_id');
		$stu =$this->_users->findUsers($condition);

		//使用者
		$condition3['user_id'] = $stu['id'];
		$condition3['organization_id'] = session('now_org');
		$stu_role =$this->_user_role->findUser_role($condition3);
		$condtion4['users_id']  = $stu['id'];
		$condtion4['department_id'] = session('now_dep');
		$user_or = $this->_member->where($condtion4)->find();

		$searchruser=$this->_users->findUsers($condition);
		$searchruser_id = $searchruser['id'];
		$content['organization_id'] = session('now_org');
		$content['role_id'] = I('post.position');
		$content['user_id'] = $searchruser_id;
		$condition5['user_role'] = $searchruser_id;
		$content2['users_id'] = $searchruser_id;
		$content2['department_id'] = I('post.department');
		$condition6['users_role'] = $searchruser_id;
		if($stu_role > 5 || session('user_role') < $stu_role){
			echo "你搜索用户权限过高，不能添加";
		}elseif(session('user_role') == "7" || session('user_role') == "6"){
			if(!$user_or){
				$this->_member->create($content2);
			}else{
				$this->_member->where($condition6)->save($content2);
			}
			if(!$stu_role){
				$this->_user_role->create($content);
			}else{
				$this->_user_role->where($condition5)->save($content);
			}
			echo "添加成功";
		}else{
			if(session('user_role') <= I('post.position')){
				echo "你不能添加等级比你高的用户";
			}else{
				if(!$user_or){
					$this->_member->add($content2);
				}else{
					$this->_member->where($condition6)->save($content2);
				}
				if(!$stu_role){
					$this->_user_role->add($content);
				}else{
					$this->_user_role->where($condition5)->save($content);
				}
				echo "添加成功";
			}
		}
	}

	public function deletePre(){
		$this->_departmentInit();
		$condition['id'] = I('post.user_id');
		$condition1['department_id'] =I('post.department_id');//隐藏表单传部门
		$user_dep=$this->_department->where($condition1)->find();
		$condition['organization_id'] = $user_dep['organization_id'];
		$theuserrole = $this->_user_role->findUser_role($condition);
		if(session('user_role') > 5 ){
			$condition2['id'] = I('post.user_id');
			$content['role_id'] = '0';
			$this->_user_role->where($condition)->save($content);
		}elseif(session('now_dep')!=I('post.department_id')){
			echo "你无删除权限";
		}elseif(session('role_id') <= $theuserrole){
			echo "权限低";
		}else{
			$condition2['id'] = I('post.user_id');
			$content['role_id'] = '0';
			$this->_user_role->where($condition)->save($content);
		}
	}

	public function addDep(){
		$this->_departmentInit();
		$name = I('post.new_name');
		$content = I('new_intro');
		$pre_num = I('post.pre_num');
		if(!is_numeric($pre_num)){
			$this->error('请按要求输入');
		}elseif(session('user_role')<6){
			$this->error('无权限添加人员');//前端还是加个不能点击吧
		}else{
			$content['department'] = $name;
			$content['organization_id'] = session('now_org');
			$content['id'] = $this->_department->max('id') +1;
			$content['show'] = session('now_org');
			$this->_department->create($content);
			$de_intro = M('department_intro');
			$content2['department_id'] = $this->_department->max('id') +1;
			$content2['content'] = $content;
			$content2['pre_num'] = $pre_num;
			$content2['updatetime'] =  date("Y-m-d H:i:s", time());
			$de_intro->create($content2);
			$this->redirect('Index/index');
		}

	}
	public function addContent(){
		$department_into = M('intro');
		$content['content'] = I('post.intro_content');
		$condition['department_id'] = session('now_dep');
		$content['update_id'] = session('user_id');
		$content['updatetime'] =  date("Y-m-d H:i:s", time());
		$department_into->where($condition)->save($content);
		$this->redirect('Index/index');
	}

	public function searchMem(){
		$this->_departmentInit();
		$condition['studentnum'] = I('post.studentID');
		$stu = $this->_users->findUsers($condition);
		if(isset($stu)) {
			$condition2['id'] = $stu['academy_id'];
			$academy = $this->_academy->findAcademy($condition2);
			$stu['academy'] = $academy;
			$stu['gender'] = $this->_users->checkgender($stu['gender']);
		}
		$this->ajaxReturn($stu);
	}
	public function _empty() {
        $this->display('Errors/index');
    }
}