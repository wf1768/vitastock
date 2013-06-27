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
 * stock role_function model Class
 *
 * 角色与功能关联表操作Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */
class role_Fun_Model extends MY_Model {

    const TABLE_NAME = "sys_role_fun";
    public $tableName= "sys_role_fun";

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();

        log_message('debug', "role_fun Model Class Initialized");
    }

    /**
     * 获取特定角色id所能管理的功能id
     * @param $roleid
     */
    public function get_function_by_roleid($roleid) {

        $this->db->where('roleid',$roleid);
        $this->db->select('*')->from(self::TABLE_NAME);
        $query = $this->db->get();
        $functions = array();

        if ($query->num_rows() > 0) {
            $functions = $query->result_array();
        }
        $query->free_result();
        return $functions;
    }

}
