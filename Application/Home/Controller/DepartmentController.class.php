<?php
namespace Home\Controller;
use Think\Controller;
class DepartmentController extends BaseController{
	private $_presidum;
	private $_users;
	private $_academy;
	private $_user_role;
	private $_organization;
	public function index(){
		$this->_departmentInit();
		$this->_departmentVal();
		$this->display('Department/index');
	}

	private function _departmentInit(){
		$this->_presidum = D('presidum');
		$this->_users = D('users');
		$this->_academy = D('academy');
		$this->_user_role = D('userrole');
		$this->_organization = D('organization');
	}

	private function _departmentVal(){
		$orgid = session('now_org');
		$presidum = $this->_presidum->where("state = 1 AND organisation ='$orgid'")->select();		
		$org_name = $this->_organization->checkOrg($orgid);
		$orgname = $org_name['organization'];
		$this->assign('orgname',$orgname);
		$this->assign('presidum',$presidum);
	}

	
	public function _empty() {
        $this->display('Errors/index');
    }
}