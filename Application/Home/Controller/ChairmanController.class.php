<?php
namespace Home\Controller;
use Think\Controller;

class ChairmanController extends BaseController{
	private $_presidum;
	public function index(){
		$this->_presidumInit();
		$this->_presidumVal();
		$this->display('Chairman/presidium');
	}

	private function _presidumInit(){
		$this->_presidum = M('presidum');
	}

	private function _presidumVal(){

		$presidum = $this->_presidum->where('id > 1')->select();
		$this->assign('presidum',$presidum);
	}
}