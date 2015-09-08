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
	private $_users_organisation;
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
		$this->_users_organisation = D('users_organisation');
	}

	private function _departmentVal() {
//		$condition['organization_id'] = session('now_org');
//		$condition['show'] = session('now_org');
        $cond = array(
            'organization_id' => session('now_org'),
            'show' => session('now_org')
        );
		$depa = $this->_department->searchDep($cond);
		for($i = 0; $i < count($depa); $i++) {
			$depa[$i]['tab_id'] = $i;  // 导航栏顺序
		}
		$firstdepa = array_shift($depa);
		$firstdepa_id = $firstdepa['id'];
		// $key = 'users_id';
		// $firstdepa_mem = $this->_users_organisation->checkUO($condition,$key);
		// $condition2['id'] = $firstdepa_mem[0]['users_id'];
		$first_meminfo = $this->_users->join('users_organisation ON users.id = users_organisation.users_id')->where("users_organisation.department_id = '$firstdepa_id'")->select();
        for($i = 0; $i < count($first_meminfo) ;$i++) {
			$academy = $this->_academy->findAcademy(array('id' => $first_meminfo[$i]['academy_id']));
			$first_meminfo[$i]['academy'] = $academy;
			$first_meminfo[$i]['gender'] = $this->_users->checkgender($first_meminfo[$i]['gender']);
		}
		$this->assign('first_meminfo',$first_meminfo);
		$this->assign('firstdepa',$firstdepa);
		$this->assign('org_name',$depa);
	}

	
	public function _empty() {
        $this->display('Errors/index');
    }
}