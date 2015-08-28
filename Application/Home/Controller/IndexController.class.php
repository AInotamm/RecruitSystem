<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends BaseController {
    public function index(){
        $this->display('Index/index');
    }
    
    public function _empty() {
        $this->display('404/index');
    }
}