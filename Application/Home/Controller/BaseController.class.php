<?php
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {

    private $_user_role;
    private $_org_ta;
    protected $redis;

    public function show($uri, $redirect) {
        $html = $this->fetch($uri);
        $html = preg_replace('/\sneed=\"author\"/', $this->checkrole(), $html);
        if(isset($html)) {
            header('Content-Type:' . C('DEFAULT_CHARSET') . '; charset=' . C('TMPL_CONTENT_TYPE'));
            header('Cache-control: ' . C('HTTP_CACHE_CONTROL'));  // 页面缓存控制
            header('X-Powered-By:ThinkPHP');
            echo $html;
        } else {
            $this->redirect($redirect);
        }
    }

    public function _before_index(){
        if(!session('?name')) {
            C('TOKEN_ON', true);
            $this->display('Login/index');
        } else {
            $this->_user_role = D('userrole');
            $this->_org_ta = D('organization');

//            $this->redis = new \Redis();;
//            $this->redis->connect('127.0.0.1');

            $this->assign(array(
                'checkLogin' => '退出登录',
                'checkState' => U(CONTROLLER_NAME . '/destroySession'),
                'checkOrga'=> U(CONTROLLER_NAME . '/checkOrg'),
                'name' => session('name'),
            ));

            $cond = array('user_id' => session('user_id'));
            $stu_org = $this->_user_role->findUsers_org();
            $org_num = $this->_org_ta->where($cond)->count();
            for($i = 0 ;$i < $org_num; $i++) {
                $stu_org[$i]['org_name'] = $this->_org_ta->checkOrg($stu_org[$i]['organization_id']);
            }
            $this->assign('org',$stu_org);

            if(!session('?now_org')){
                session('now_org',$stu_org[0]['organization_id']);
                $cond['organization_id'] = session('now_org');
                $user_role = $this->_user_role->findUser_role($cond);
                session('user_role',$user_role);
                // $condition3['users_id'] = session('user_id');
                // $now_dep = D('member')->mfind($condition3);
                // $condition4['id'] = $now_dep['department_id'];
                // $nowdepa = D('department')->searchDep('')
                $now_org = session('now_org');
                $dep = D('department');
                $user_id = session('user_id');
                $now_dep = $dep->join('member ON department.id = member.department_id')->where("member.users_id = '$user_id' AND department.organization_id = '$now_org'")->find();
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
        $now_dep = $dep->join('member ON department.id = member.department_id')->where("member.users_id = '$user_id' AND department.organization_id = '$now_org'")->find();

        session('now_dep',$now_dep['department_id']);
        $this->redirect('Index/index');
    }

    public function destroySession(){
        session(null);
        $this->redirect(CONTROLLER_NAME . '/index');
    }
}