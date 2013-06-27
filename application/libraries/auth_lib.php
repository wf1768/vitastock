<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * stock System
 *
 * 基于ci的商品管理系统
 *
 * @package		stock
 * @author		blues <blues0118@gmail.com>
 * @copyright	Copyright (c) 2013 - 2015, ussoft.net.
 * @license
 * @link
 * @version		0.1.0
 */

// ------------------------------------------------------------------------

/**
 * stock Auth Library Class
 *
 * 权限控制,控制帐户登陆和登出
 *
 * @package		stock
 * @subpackage	Libraries
 * @category	Libraries
 * @author		blues <blues0118@gmail.com>
 * @link
 */
class auth_lib {

    /**
     * 帐户
     * @var array
     */
    private $_account = array();

    /**
     * 帐户登陆日志
     * @var array
     */
    private $_loginlog = array();

    /**
     * CI句柄
     *
     * @access private
     * @var object
     */
    private $_CI;

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set('PRC');
        /** 获取CI句柄 */
        $this->_CI = & get_instance();
//        $this->_CI->load->model('account_model');
        $this->_CI->load->model('loginlog_model');
        $this->_CI->load->model('function_model');
        $this->_CI->load->model('org_model');
        $this->_CI->load->model('role_model');

//        $this->_CI->load->library('Uuid_lib');
        $this->_account = unserialize($this->_CI->session->userdata('account'));
        log_message('debug', "stock: auth library Class Initialized");
    }

    /**
     * 判断帐户是否已经登录
     *
     * @access public
     * @return void
     */
    public function hasLogin() {
        $isLogin = false;
        /** 检查session */
        if (!empty($this->_account)) {
            $isLogin = true;
        }

        return $isLogin;
    }

    /**
     * 处理帐户登录.写登陆日志
     *
     * @access public
     * @param  array $account 帐户信息
     * @return boolean
     */
    public function login($account)
    {
        /** 获取帐户信息 */
        $this->_account = $account;

        /** 每次登陆时需要更新登陆日志 */
        $this->_loginlog["id"] = md5(uniqid(rand(), true));  //uuid有问题。先采用这种试试
        $this->_loginlog["accountid"] = $account['id'];
        $this->_loginlog["accountcode"] = $account['accountcode'];
        $this->_loginlog["accountname"] = $account['accountname'];
        $this->_loginlog["loginip"] =$this->get_client_ip();
        $this->_loginlog["logintime"] = date('Y-m-d H:i:s',now());
        if ($this->_CI->loginlog_model->add_loginlog($this->_loginlog)) {
            $this->_set_session();
            return true;
        }

        return false;
    }
// 获取客户端IP地址
function get_client_ip() {
    static $ip = NULL;
    if ($ip !== NULL) return $ip;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos =  array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip   =  trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
    return $ip;
}
    /**
     * 设置session
     *
     * @access private
     * @return void
     */
    private function _set_session()
    {
        $roleid = $this->_account['roleid'];//$this->_account['roleid'];
        if ($roleid == '0') {
            if ($this->_account['orgid'] != '0') {
                $org = $this->_CI->org_model->getOne($this->_account['orgid']);
                $roleid = $org[0]->roleid;
            }
        }
        $this->_account['roleid'] = $roleid;

        $store = $this->_account['store'];
        if ($store == '["0"]') {
            if ($this->_account['orgid'] != '0') {
                $org = $this->_CI->org_model->getOne($this->_account['orgid']);
                $store = $org[0]->store;
            }
        }
        $this->_account['store'] = $store;
        $session_data = array('account' => serialize($this->_account));



        $this->_CI->session->set_userdata($session_data);
    }

    /**
     * 处理帐户登出
     *
     * @access public
     * @return void
     */
    public function logout()
    {
        $this->_CI->session->sess_destroy();

        redirect('login');
    }

    //===========权限====


    /**
     * 根据当前帐户角色获取功能列表
     * @return bool
     */
    private function get_funlist() {
        //获取session里的帐户
        $account = unserialize($this->_CI->session->userdata('account'));
        //如果session里不存在帐户
        if (empty($account)) {
            return false;
        }

        $roleid = '';
        if ($account['roleid'] == '') {
            //TODO 如果帐户的角色为空，这里要加上读取帐户所属组的角色功能
        }
        else {
            $roleid = $account['roleid'];
        }
        //读取角色与功能的对应关系
        $this->_CI->load->model('role_Fun_model','rolefunModel');
        $functions = $this->_CI->rolefunModel->get_function_by_roleid($roleid);

        return $functions;
    }

    /**
     * 获取当前角色的模块内操作权限
     * @param $funid        功能id
     */
    public function role_fun_operate($funid) {

        $roleid = $this->_account['roleid'];
        if (!$roleid) {
            //TODO 如果帐户的角色为空。获取组的角色
        }

        if (!$roleid) {
            return false;
        }
        //读取角色与功能的对应关系
        $this->_CI->load->model('role_Fun_model');
        $function = $this->_CI->role_Fun_model->getOneByWhere(array('roleid'=>$roleid,'funid'=>$funid));

        if ($function) {
            if ($function->sfunid == '0' || $function->sfunid == '') {
                return false;
            }
            else {
                return true;
            }
        }
        else {
            return false;
        }

    }

    /**
     * 根据父节点id，获取当前帐户能管理的功能。
     * @param string $parentid
     * @return bool
     */
    public function get_fun_auth($parentid='') {

        $functions = $this->get_funlist();
        $ids = Common::array_flatten($functions, 'funid');
        $query = false;
        if (count($ids) > 0) {
            $query = $this->_CI->function_model->get_functions_by_parentid_ids($parentid,$ids);
        }

        $result = false;

        if ($query) {
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
            }
        }

        return $result;
    }

    /**
     * 获取系统常用功能（首页的二级菜单是当前帐户能管理的所有常用功能，系统管理员可以管理这个常用功能）
     * @return array
     */
    public function get_fun_auth_used() {
        $functions = $this->get_funlist();
        $ids = Common::array_flatten($functions, 'funid');

        $query = false;
        if (count($ids) > 0) {
            $query = $this->_CI->function_model->get_functions_by_used($ids);
        }

        $result = false;

        if ($query) {
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
            }
        }

        return $result;

    }

    /**
     * 获取当前帐户或组能访问的库房数据范围
     */
    public function get_house_auth() {

        $store = $this->_account['store'];

        require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
        $json = new Services_JSON();
        $ids = $json->decode($store);

        //判断帐户是否有库房权限
        $count = count($ids);

        if ($count == 1) {
            if ($ids[0] == '0') {
                $orgid = $this->_account['orgid'];
                $this->_CI->load->model('org_model');
                $org = $this->_CI->org_model->getOne($orgid);
                if (!$org) {
                    return false;
                }
                $store = $org[0]->store;
                $ids = $json->decode($store);
                $count = count($ids);
                if ($count == 0) {
                    return false;
                }
                if ($count == 1 && $ids[0] == '0') {
                    return false;
                }
            }
        }

        $this->_CI->db->where_in('id',$ids);

        //获取库房
        $this->_CI->load->model('storehouse_model');
        $houses = $this->_CI->storehouse_model->getAllByWhere();

        return $houses;
    }

}
