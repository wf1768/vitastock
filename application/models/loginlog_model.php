<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * stock Controller Class
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

//=================================================

/**
 * stock account Login log model Class
 *
 * 帐户登陆日志表操作Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class loginlog_Model extends CI_Model
{

    const TABLE_NAME = "sys_login_log";
    public $tableName= "sys_login_log";

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
        log_message('debug', "loginlog Model Class Initialized");
    }

    /**
     * 添加一个帐户登录日志
     *
     * @access public
     * @param array - $data 日志信息
     * @return boolean - success/failure
     */
    public function add_loginlog($data) {
        $this->db->insert(self::TABLE_NAME, $data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

}
