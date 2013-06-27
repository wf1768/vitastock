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
 * stock Account info Library Class
 *
 * 获取当前登陆帐户信息，用于每个页面显示
 *
 * @package		stock
 * @subpackage	Libraries
 * @category	Libraries
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class account_info_lib {

    /**
     * 帐户对象
     * @var array
     */
    private $_account = array();

    /**
     * 帐户id
     * @var string
     */
    public $id = '';

    /**
     * 帐户名
     * @var string
     */
    public $accountcode = '';

    /**
     * 帐户真实姓名
     * @var string
     */
    public $accountname = '';

    /**
     * 帐户密码
     * @var string
     */
    public $password = '';

    /**
     * email
     * @var string
     */
    public $email = '';

    /**
     * 电话
     * @var string
     */
    public $phone = '';

    /**
     * 移动电话
     * @var string
     */
    public $mobilephone = '';

    /**
     * 所属组id
     * @var string
     */
    public $orgid = '';

    /**
     * 所属角色id
     * @var string
     */
    public $roleid = '';

    /**
     * 角色名称
     * @var string
     */
    public $rolecode = '';

    public $power = '';

    /**
     * 帐户照片
     * @var string
     */
    public $accountimage = '';

    /**
     * 帐户能管理的库房
     * @var string
     */
    public $store = '';
    /**
     * CI 句柄
     * @var
     */
    private $_CI;

    /**
     * 构造函数
     */
    public function __construct() {
        /**
         * 获取ci句柄
         */
        $this->_CI = & get_instance();
        $this->_CI->load->model('role_model');
        $this->_CI->load->model('org_model');

        /**
         * 获取session里的帐户，并恢复为account对象
         */
        $this->_account = unserialize($this->_CI->session->userdata('account'));
        /**
         * 如果帐户不为空，赋值给各个变量。
         */
        if (!empty($this->_account)) {
            $this->id = $this->_account['id'];
            $this->accountcode = $this->_account['accountcode'];
            $this->accountname = $this->_account['accountname'];
            $this->password = $this->_account['password'];
            $this->email = $this->_account['email'];
            $this->phone = $this->_account['phone'];
            $this->mobilephone = $this->_account['mobilephone'];
            $this->orgid = $this->_account['orgid'];
            $this->accountimage = $this->_account['accountimage'];
            $this->roleid = $this->_account['roleid'];
            $this->store = $this->_account['store'];
            //要判断帐户是否有角色和库房管理权限。没有就读取所属组的
//            $roleid = $this->_account['roleid'];
//            if ($roleid == '0') {
//                if ($this->orgid != '0') {
//                    $org = $this->_CI->org_model->getOne($this->orgid);
//                    $roleid = $org[0]->roleid;
//                }
//            }
//            $this->roleid = $roleid;
//
//            $store = $this->_account['store'];
//            if ($store == '["0"]') {
//                if ($this->orgid != '0') {
//                    $org = $this->_CI->org_model->getOne($this->orgid);
//                    $store = $org[0]->store;
//                }
//            }
//            $this->store = $store;



            //获取帐户角色
            if (!empty($this->roleid)) {
                $role = $this->_CI->role_model->get_role_by_id($this->roleid);
                $this->rolecode = $role['rolecode'];
                $this->power = $role['power'];
            }
        }

    }

}
