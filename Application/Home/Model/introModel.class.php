<?php
/**
 * Created by PhpStorm.
 * User: Haku
 * Date: 15/9/9
 * Time: 15:19
 */

namespace Home\Model;
use Think\Model;

class introModel extends Model {

    private $in;

    private function _init() {
        $this->in = M('intro');
    }

    public function querySigned() {
        $this->_init();
        return $this->in->field(array(
            'department_id', 'updatetime', 'update_id'
        ), true)->where(array(
            'department_id' => session('now_dep'),
        ))->find();
    }
}