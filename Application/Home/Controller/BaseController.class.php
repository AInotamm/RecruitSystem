<?php
/**
 * Created by PhpStorm.
 * Date: 15/8/11
 * Time: 03:08
 * @name:BaseController
 * @access:abstract
 * @version:thinkphp3.23, php5.6
 */
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {

    private $status_code;
    private $status_msg;

    protected static $username;
    protected static $password;

    public function _before_index(){
        if(!session('?stu_id')) {
            $this->assign(array(

            ));
            $this->display('Login/index');
            exit;
        } else {
            $this->assign(array(
                'login1' => 'loginnot1',
                'login2' => 'loginnot2',
                'checkLogin' => '退出登录',
                'checkState' => U(CONTROLLER_NAME . '/destroySession')
            ));
        }
        if(!cookie('IP_state')) {
            cookie('IP_state', 0, array('expire' => 3600, 'httponly' => 1));
        }
    }

    
    // private function _onLogging() {
    //     static::$studentnum = I(trim('post.studentnum'), 'htmlspecialchars');
    //     static::$password = I(trim('post.password'));
    //     if (!IS_POST && empty(static::$username) && empty(static::$password)) {
    //         $this->ajaxReturn(array(
    //             'status' => 302,
    //             'info' => '请求数据有问题,请重试'
    //         ));
    //     }
    // }

    private function _getStuInfo(){  //查询学生
        $condition = array(
            'studentnum' => static::$username,
            'password' => static::$password
            // md5(hash('sha256', (static::$password >> (static::$password % 3)) . substr(static::$password, 1, 3)))
        );
        $this->_cinfo = M('users');
        $stu = $this->_cinfo->where($condition)->find();

        //关于IP判断，防刷
        $this->_cip = M('blackip');
        $ban_ip = array('black_ip' => $this->_getIp());
        $blacktime = $this->_cip->where($ban_ip)->find();
        cookie('IP_state', cookie('IP_state') == 0 ? 1 : cookie('IP_state') + 1);
        if ($blacktime['black_time'] > 5) {  //5次后永久gg这里添加个View层或者别的直接指向gg页面
            $this->ajaxReturn(array(
                'status' => 110,
                'info' => '你的 IP 已被封禁,请联系网站管理员'
            ));
        } else if(cookie('IP_state') >= 10) {  //该次登陆如果连续10次，session默认失效时间是24分钟
            if(!$blacktime) {
                $goal['black_time'] = 1;
                $goal['black_ip'] = $this->_getIp();
                $this->_cip->add($goal);
            } else {
                $this->_cip->where($ban_ip)->data(array(
                    'black_time' => $blacktime['black_time'] + 1
                ))->save();
            }
        } else if($stu && $stu['stu_name'] == static::$username) {
            $this->_saveSession($stu);
            if (!$stu['stu_status']) {
                $this->_cinfo->where($condition)->save(array('stu_status' => 1));
            }

            $this->status_code = 200;
            $this->status_msg = "登录成功";
            $this->_checkExtraInfo();
            $this->ajaxReturn(array(
                'status' => $this->status_code,
                'info' => $this->status_msg,
                'data' => array(
                    'qq' => $stu['stu_qq'],
                    'tel' => $stu['stu_tel'],
                    'behavior' => explode(',', $stu['stu_fav'])
                )
            ));
        } else {
            if (empty($_SESSION['IP'])) {
                $_SESSION['IP_state'] = 0;
            } else {
                $_SESSION['IP_state'] = $_SESSION['IP_state'] + 1;
            }
            $this->ajaxReturn(array(
                'status' => 400,
                'info' => '登录失败,姓名或身份证后六位错误',
            ));
        }
    }

    /**
     * 爱好及qq，电话添加
     * 如果数据非空，第二次登陆，会不显示界面
     */
    public function getExtraInfo() {
        $name = I(trim('post.name'), '');
        $pass = I(trim('post.pwd'), '');
        $stu_tel = I(trim('post.stu_tel'), '');
        $stu_qq = I(trim('post.stu_qq'), '');
        $beh_arr = array(
            I(trim('post.beh_arr0'), ''),
            I(trim('post.beh_arr1'), ''),
            I(trim('post.beh_arr2'), '')
        );
        if (!IS_POST) {
            $this->ajaxReturn(array(
                'status' => 403,
                'info' => '请求发生转移,请重试'
            ));
        }
        $mob = '/^134[0-8]\d{7}$|^(?:13[5-9]|147|15[0-27-9]|178|18[2-478])\d{8}$/';
        $union  = '/^(?:13[0-2]|145|15[56]|176|18[56])\d{8}$/';
        $telcom = '/^(?:133|153|177|18[019])\d{8}$/';
        $other = '/^170([059])\d{7}$/';
        if(empty($stu_tel) || preg_match($mob, $stu_tel) || preg_match($union, $stu_tel) || preg_match($telcom, $stu_tel) || preg_match($other, $stu_tel)) {
            if(empty($stu_qq) || preg_match('/[1-9][0-9]{4,10}/', $stu_qq)) {
                $this->_cqq = $stu_qq;
                $this->_ctel = $stu_tel;
            } else {
                $this->ajaxReturn(array(
                    'status' => 402,
                    'info' => 'QQ 格式错误,请重试'
                ));
            }
        } else {
            $this->ajaxReturn(array(
                'status' => 402,
                'info' => '电话格式错误,请重试'
            ));
        }
        if ($name && $pass) {
            $stu = M('stuinfo');
            $this->_cname = function() use ($stu, $name, $pass) {
                $data = $stu->where(array(
                    'stu_name' => $name,
                    'stu_passwd' => md5(hash('sha256', ($pass >> ($pass % 3)) . substr($pass, 1, 3)))
                ))->find();
                if($data) {
                    return $data['stu_id'];
                }
            };
            if(!$this->_cname) {
                $this->ajaxReturn(array(
                    'status' => 404,
                    'info' => '信息查询失败'
                ));
            }
        }
        $this->_cfav = M('fav');
        $extraInfo['stu_id'] = session('stu_id') ? session('stu_id') : $this->_cname;
        $extra_exist = $this->_cfav->where($extraInfo)->find();
        foreach($beh_arr as $val) {
            if (array_key_exists($val, $this->_fav)) {
                $this->_favid[] = $this->_fav[$val];
            }
        }
        if (isset($extra_exist)) {
            for($i = 1; $i < 13; $i++) {
                if($extra_exist['fav_info' . $i] == 1) {
                    $this->_favinsid[] = $i;
                }
            }
            $this->_cfav->where($extraInfo)->filter('strip_tags')->data(array(
                'fav_info' . $this->_favinsid[0] => 0,
                'fav_info' . $this->_favinsid[1] => 0,
                'fav_info' . $this->_favinsid[2] => 0,
                'fav_info' . $this->_favid[0] => 1,
                'fav_info' . $this->_favid[1] => 1,
                'fav_info' . $this->_favid[2] => 1
            ))->save();
        }
        $str = implode(',', $beh_arr);
        $this->_cinfo = M('stuinfo');
        $goal['stu_tel'] = $this->_ctel;
        $goal['stu_qq'] = $this->_cqq;
        $goal['stu_fav'] = $str;
        // 保存爱好以及额外信息
        if (!$name && !$pass) {
            session('stu_tel', $this->_ctel);
            session('stu_qq', $this->_cqq);
            session('stu_fav', $str);
        }
        $this->_cinfo->where($extraInfo)->save($goal);

        $this->ajaxReturn(array(
            'status' => 203,
            'info' => '信息更新成功'
        ));
    }

    private function _saveSession($data) {
        $_SESSION['stu_id'] = $data['stu_id'];
        $_SESSION['stu_name'] = $data['stu_name'];
        $_SESSION['stu_unicode'] = $data['stu_unicode'];
        $_SESSION['stu_sexy'] = $data['stu_sexy'];
        $_SESSION['stu_date'] = $data['stu_date'];
        $_SESSION['stu_dept'] = $data['stu_dept'];
        $_SESSION['stu_dorm'] = $data['stu_dorm'];
        $_SESSION['stu_qq'] = $data['stu_qq'];
        $_SESSION['stu_tel'] = $data['stu_tel'];
        $_SESSION['stu_prov'] = $data['stu_prov'];
        $_SESSION['stu_class']= $data['stu_class'];
        $_SESSION['stu_building'] = $data['stu_building'];
    }

    public function destroySession(){
        session(null);
        $this->redirect(CONTROLLER_NAME . '/index');
    }
}