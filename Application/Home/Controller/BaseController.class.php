<?php
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {

    private $status_code;
    private $status_msg;

    public function _before_index(){
        if(!session('?name')) {
            $this->assign(array(

            ));
            $this->display('Login/index');
            exit;
        } else {
            $this->assign(array(
                'checkLogin' => '退出登录',
                'checkState' => U(CONTROLLER_NAME . '/destroySession')
            ));
            $this->assign('name' ,session('name'));
        }
    }

    public function destroySession(){
        session(null);
        $this->redirect(CONTROLLER_NAME . '/index');
    }
}