<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends BaseController {
	private $studentNum = '';
	private $password = '';
    public function index(){
    	$this->studentNum = I('post.studentNum');
        $this->password = I('post.password');
        $this->_getInfo();
    }

    private function(){
    	$cinfo = M('users');
    	$nowtime = time();
    	session('testnum') = '0';
    	$condition(['studentnum'] = $this->studentNum;
    	$condition['password'] = $this->password;
    	$stu = $cinfo->where($condition)->find();
    	if($cinfo){
    		$this->initSession($stu);
    		$content['updated_at'] = date("Y-m-d H:i:s", time());
    		$cinfo->where($condition)->save($content);
    	}elseif(session('testnum') == 5){
    		if(!session('?lasttime')){
    			session('lasttime') = $nowtime;
    		}elseif{$nowtime - session('lasttime') < 600}{
    			echo 0;
    		}else{
    			session('testnum') = 0;
    		}
    	}else{
    		session('testnum') = session('testnum') + 1;
    	}
    }
    
    private function initSession($stu){
    	session('name') = $stu['name'];
    	session('studentnum') = $stu['studentnum'];
    	session('gender') = $stu['gender'];

    }

    public function _empty() {
        $this->display('404/index');
    }
}