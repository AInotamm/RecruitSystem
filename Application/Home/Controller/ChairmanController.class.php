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
        $this->show('Chairman/presidium', 'Errors/503');
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
		$presidum = $this->_presidum->where("state = 1 AND organisation = '$orgid'")->select();
		$org_name = $this->_organization->checkOrg($orgid);
		$orgname = $org_name['organization'];
		$this->assign('orgname',$orgname);
		$this->assign('presidum',$presidum);
		if($this->_user_role == 7){
//			$this->assign('pre_but',$but);
		}
	}

	public function deletePre(){
        $this->_check_role();
		$this->_presidumInit();
        $pre_id = I('post.presidum_id');
        $content = array('state' => '0');
        $cond = array(
            'id' => $pre_id,
            'organisation' => session('now_org'),
        );
		$pre_info = $this->_presidum->findPresidum($cond);
        unset($cond['id']);
		$cond['user_id'] = $pre_info['user_id'];
		$pre_role = $this->_user_role->findUser_role($cond);
		if($pre_role['role_id'] == '7'){
			$this->ajaxReturn(array('info' => '不能删除该角色.'));
		}
		$this->_presidum->changePre($pre_id, $content);
		$pre = $this->_presidum->getPre($pre_id);
		$this->_user_role->changeRole($pre['user_id'], '3');  // 直接转变为干事
        $this->ajaxReturn(array('code' => 'success'));
	}

	public function searchStudent(){
		$this->_presidumInit();
		$condition['studentnum'] = I('post.studentID');
		$stu = $this->_users->findUsers($condition);
		if(session('user_role') == 7) {
            if(!empty($stu)) {
                $academy = $this->_academy->findAcademy(array('id' => $stu['academy_id']));
                $stu['academy'] = $academy;
                $stu['gender'] = $this->_users->checkgender($stu['gender']);
            } else {
                $stu = array(
                    'name' => '佚名',
                    'gender' => '未知',
                    'phone' => '未知',
                    'academy' => '未知',
                );
            }
            $this->ajaxReturn($stu);
		}
	}

	public function addpre() {
        $this->_check_role();
		$this->_presidumInit();
		$stu = $this->_users->findUsers(array('studentnum' => I('post.user_id')));
		if(isset($stu)){
			$academy = $this->_academy->findAcademy(array('id' => $stu['academy_id']));
			$gender = $this->_users->checkgender($stu['gender']);
			$this->_user_role->addOne($stu['id'],I('post.position'));
			$last = $this->_presidum->addPresidum($stu, $academy, $gender, '副主席');
            if($last) $this->ajaxReturn(array('status' => 200, 'info' => '职位添加成功.'));
		} else {
            $this->ajaxReturn(array('status' => 404, 'info' => '职位添加失败, 请确认此人学号是否正确'));
        }
	}
	
	public function _empty() {
        $this->display('Errors/index');
    }

    private function _check_role() {
        if(session('user_role') < 6){
            $this->ajaxReturn(array(
                'info' => '权限不足, 请重试.'
            ));
        }
    }
}