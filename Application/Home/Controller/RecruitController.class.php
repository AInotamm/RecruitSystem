<?php
/**
 * Created by PhpStorm.
 * User: Haku
 * Date: 15/9/7
 * Time: 23:29
 */

namespace Home\Controller;
use Think\Controller;

class RecruitController extends BaseController {

    private $flow;
    private $dept;

    public function index() { parent::_before_index(); }

    public function add() {
        $this->display('Recruit/add');
    }

    public function audit() {
        $this->display('Recruit/audit');
    }

    public function info() {
        $this->index();
        $this->display('Recruit/info');
    }

    private function _init_flow() {
        if(!isset($this->flow)) $this->flow = D('flow');
    }

    private function _init_intro() {
        if(!isset($this->dept)) $this->dept = D('intro');
    }

    public function set() {
        $this->index();
        $this->show('Recruit/set', 'Error/503');
    }

    public function setFlow() {
        $this->_init_flow();
        $info = $this->flow->fadd();
        if(!empty($info))
            $this->error($info);
    }

    public function deleteFlow() {
        if(IS_AJAX && IS_POST) {
            $this->_init_flow();
            $error = $this->flow->fdel();
            if(!is_bool($error)) {
                $this->ajaxReturn(array('error' => $error));exit;
            }
        }
    }

    public function modifyFlow() {
        if(IS_AJAX && IS_POST) {
            $data = I('post.');
            $id = $data['id'];
            unset($data['id'], $data['type']);
            $cond = array('id' => $id);
            if($data['type'] == '报名' || $data['type'] == '录取') {
                $this->_init_intro();
                $res = $this->dept->where($cond)->data($data)->save();
            } else {
                $this->_init_flow();
                $res = $this->flow->where($cond)->data($data)->save();
            }
            if($res > 0) {} else {
                $error =  isset($this->flow) ? $this->flow->getError() : $this->dept->getError();
                $this->ajaxReturn(array('error' => $error));
            }
        }
    }

    public function showFlow() {
        if(IS_AJAX && IS_GET && empty(I('get.'))) {
            $this->_init_flow();
            $this->_init_intro();
            $flows = $this->flow->fshow();
            $content = $this->dept->querySigned();

            if(!empty($flows)) {
                $id = $content['id'];
                $list[0] = array(
                    0, $content['deadline'], $content['hand_num'] . '人',
                    $content['content'], $content['remark'], intval($id),
                );
                foreach($flows as $key => &$val) {
                    $temp = intval($val['id']);
                    $val['type'] = intval($val['type']);
                    array_shift($val);
                    $val['id'] = $temp;
                    $list[] = array_values($val);
                }
                $list[] = array(
                    3, $content['pretotal'], $content['admit_remark'], intval($id)
                );
            }
            $this->ajaxReturn(array('data' => $list));
        }
    }

}