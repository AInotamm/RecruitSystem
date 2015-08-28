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

    private $_cname;
    private $_cinfo;
    private $_cfav;
    private $_cip;
    private $_cqq;
    private $_ctel;

    private $_fav = array(
        '动漫' => 1, '极客' => 2, '摄影' => 3,
        '吃货' => 4, 'lol' => 5, '篮球' => 6,
        '旅游' => 7, '电影' => 8, '学霸' => 9,
        '健身' => 10, '音乐' => 11, '综艺' => 12,
    );
    private $_favid = array();
    private $_favinsid = array();

    /**
     * 前置操作,判断用户登录情况
     */
    public function _before_index(){
        if(!session('?stu_id')) {
            $this->assign(array(
                'login1' => 'login1',
                'login2' => 'login2',
                'checkLogin' => '新生登录',
                'checkState' => '#'
            ));
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

    /**
     * 登陆的入口方法
     */
    public function login() {
        $this->_onLogging();
        $this->_getStuInfo();
    }

    /**
     * 登陆框登陆，并根据POST参数，返回status参数
     * 私有方法
     * $username:用户名字，$password：用户密码
     * $password 注释方法 md5(hash('sha256', ($password >> ($password%3)).substr($password, 1, 3)));
     * 共有方法，所有方法的入口
     */
    private function _onLogging() {
        static::$username = I(trim('post.name'), 'htmlspecialchars');
        static::$password = I(trim('post.pwd'));
        if (!IS_POST && empty(static::$username) && empty(static::$password)) {
            $this->ajaxReturn(array(
                'status' => 302,
                'info' => '请求数据有问题,请重试'
            ));
        }
    }

    /**
     * 用户的具体登陆判断既登陆
     * 做了ip判断，简单防刷
     * IP判断为每次存储错误会注册session+1，到10次会禁止该次登陆，默认session存储时间24分钟
     * 每10次会注册数据库blackip,到5次永久封
     * 内部调用方法_saveSESSION存储session
     * 登陆正确返回成功状态码200
     */
    private function _getStuInfo(){  //查询学生
        // md5(hash('sha256', ($password >> ($password%3)).substr($password, 1, 3)));
        $condition = array(
            'stu_name' => static::$username,
            'stu_passwd' => $static::$password
            // md5(hash('sha256', (static::$password >> (static::$password % 3)) . substr(static::$password, 1, 3)))
        );
        $this->_cinfo = M('stuinfo');
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
        //兴趣爱好提交，如果填了一次第二次没有，就这么输入
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

    /**
     * qq爱好窗口的判断方法，为填满及未填过返回201,202，同时弹窗
     */
    private function _checkExtraInfo(){
        $id = session('stu_id');
        if($id) {
            cookie('IP_state', 0);
            $extra = $this->_cinfo->where(array('stu_id' => $id))->getField('stu_id, stu_tel, stu_qq, stu_fav');
            $extra = each($extra);
            $extra = array_values($extra[1]);
            list(, $tel, $qq, $fav) = $extra;
            $fav_arr = explode(',', $fav);
            if (!empty($tel) && !empty($qq) && count($fav_arr) == 3) {
                $this->status_code = 201;
                $this->status_msg .= ",信息完整";
            } else {
                $this->status_code = 202;
                $this->status_msg = "信息未补充,请填写";
            }
        }
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

    /**
     * 获取客户端IP地址
     * @return mixed
     */
    private function _getIp(){
        return get_client_ip();
//         $Ip = new Org</br>et\IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
//         $area = $Ip->getlocation($stuIp); // 获取某个IP地址所在的位置
//         echo $area;
    }

    /**
     * 注销退出用户方法
     */
    public function destroySession(){
        session(null);
        $this->redirect(CONTROLLER_NAME . '/index');
    }
}