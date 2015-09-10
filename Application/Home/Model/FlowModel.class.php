<?php
/**
 * Created by PhpStorm.
 * User: Haku
 * Date: 15/9/8
 * Time: 03:25
 */

namespace Home\Model;
use Think\Model;

class FlowModel extends Model {

    private $rule = array();
    private $unix;

    public function fadd() {
        $para = I('post.');
        if(!empty($para)) {
            $para['type'] = $this->_type($para['type']);
            $para['dept_id'] = session('now_dep');
            $this->rule = array(
                array('dept_id', 'require', '信息失效,请重新登录'),
                array('type', 'require', '必须选择其中一种类型'),
                array('time', 'require', '必须填写正确的日期时间'),
                array('location', 'require', '必须填写所在地点'),
            );
            $para = $this->validate($this->rule)->create($para);
            if(!$para) {
                return $this->getError();
            } else {
                $para['status'] = 1;
                $this->add($para);
            }
        }
    }

    public function fdel() {
        $_type = ['报名', '笔试', '面试', '录取'];
        if(in_array(I('post.type'), $_type)) {
            $org = I('post.type');
            $id = I('post.id');
            $cond = array('id' => $id);
            if($org == '报名') {
                $dept = M('intro');
                $data = array(
                    'deadline' => '',
                    'hand_num' => 0,
                    'content' => '',
                    'remark' => ''
                );
                $res = $dept->where($cond)->data($data)->save();
            } else if ($org == '录取') {
                $dept = M('intro');
                $data = array(
                    'pretotal' => 0,
                    'admit_remark' => ''
                );
                $res = $dept->where($cond)->data($data)->save();
            } else {
                $res = $this->where($cond)->delete();
            }
            if($res > 0) {
                return true;
            } else {
                return !empty($dept) ? $dept->getError() : $this->getError();
            }
        }
    }

    private function _type($type) {
        if($type == '笔试') {
            return 1;
        } else if ($type == '面试') {
            return 2;
        }
    }

    public function fshow() {
        return $this->field(array('status', 'dept_id'), true)->where(array('dept_id' => session('now_dep')))->select();
    }
}