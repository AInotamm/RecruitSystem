<?php
namespace Home\Controller;
use Think\Controller;

class ChairmanController extends Controller{
	public function index(){
		$this->display('Chairman/index');
	}
}