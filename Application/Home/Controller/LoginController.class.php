<?php
namespace Home\Controller;
use Think\Controller;

class LoginController extends Controller {
	private $studentNum = '';
	private $password = '';
    public function index(){
    	$this->studentNum = I('post.studentNum');
        $this->password = I('post.password');
        $this->_getInfo();
    }

    private function _getInfo(){
    	$cinfo = D('users');
    	$nowtime = time();
    	session('testnum','0');
    	$condition['studentnum'] = $this->studentNum;
    	$condition['password'] = $this->password;
    	$stu = $cinfo->findUsers($condition);
    	if($stu){
            if($stu['state'] == '1'){
        		$this->initSession($stu);
        		$content['updated_at'] = date("Y-m-d H:i:s", time());
        		$cinfo->where($condition)->save($content);
                $this->assign(array(
                    'checkLogin' => '退出登录',
                    'checkState' => U(CONTROLLER_NAME . '/destroySession')
                ));
                $this->assign('name' ,session('name'));
                $this->redirect('Index/index');
            }else{
                $this->error('账号已跪');
            }
    	}elseif(session('testnum') == 5){
    		if(!session('?lasttime')){
    			session('lasttime',$nowtime);
    		}elseif($nowtime - session('lasttime') < 600){
    			echo 0;
    		}else{
    			session('testnum',0);
    		}
    	}else{
    		session('testnum',session('testnum') + 1);
    	}
    }
    
    private function initSession($stu){
    	session('name',$stu['name']);
    	session('studentnum',$stu['studentnum']);
    	session('gender',$stu['gender']);

    }

    public function _empty() {
        $this->display('Errors/index');
    }
}