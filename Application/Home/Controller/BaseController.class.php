<?php
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {

    private $status_code;
    private $status_msg;
    private $_user_role;

    public function _before_index(){
        if(!session('?name')) {
            $this->assign(array(

            ));
            $this->display('Login/index');
            exit;
        } else {
            $this->_user_role = D('userrole');
            $this->assign(array(
                'checkLogin' => '退出登录',
                'checkState' => U(CONTROLLER_NAME . '/destroySession'),
                'checkOrga'=> U(CONTROLLER_NAME . '/checkOrg')
            ));
            $this->assign('name' ,session('name'));
            $stu_role = D('userrole');
            $org_ta = D('organization');
            $condition['user_id'] = session('user_id');

            $stu_org = $stu_role->findUsers_org($condition);
            $org_num = $stu_role->where($condition)->count();        
            for($i = 0 ;$i < $org_num;$i++){
                $stu_org[$i]['org_name'] = $org_ta->checkOrg($stu_org[$i]['organization_id']);
            }
            $this->assign('org',$stu_org); 
            if(!session('?now_org')){
                session('now_org',$stu_org[0]['organization_id']);
                $condition['oragnization_id'] = session('now_org');
                $condition['user_id'] = session('user_id');
                $user_role = $this->_user_role->findUser_role($condition);
                session('user_role',$user_role);
                // $condition3['users_id'] = session('user_id');
                // $now_dep = D('usersorganisation')->findUO($condition3);
                // $condition4['id'] = $now_dep['department_id'];
                // $nowdepa = D('department')->searchDep('')
                $now_org = session('now_org');
                $dep = D('department');
                $user_id = session('user_id');
                $now_dep = $dep->join('usersorganisation ON department.id = usersorganisation.department_id')->where("usersorganisation.users_id = '$user_id' AND department.organization_id = '$now_org'")->find();
                session('now_dep',$now_dep['department_id']);
            }
        }
    }

    protected function checkrole() {
        if(session('user_role') == 7){
            return '';
        } else {
            return 'disabled="disabled"';
        }
    }

    public function checkOrg(){
        $org_id = I('get.orgid');
        session('now_org',$org_id);
        $condition['oragnization_id'] = session('now_org');
        $condition['user_id'] = session('user_id');
        $this->_user_role = D('userrole');
        $user_role = $this->_user_role->findUser_role($condition);
        session('user_role',$user_role);

        $now_org = session('now_org');
        $dep = D('department');
        $user_id = session('user_id');
        $now_dep = $dep->join('usersorganisation ON department.id = usersorganisation.department_id')->where("usersorganisation.users_id = '$user_id' AND department.organization_id = '$now_org'")->find();
        // var_dump($now_dep);exit;

        session('now_dep',$now_dep['department_id']);
        $this->redirect('Index/index');
    }

    public function destroySession(){
        session(null);
        $this->redirect(CONTROLLER_NAME . '/index');
    }
}