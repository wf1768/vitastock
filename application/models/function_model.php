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
 * stock function model Class
 *
 * 系统功能操作Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class function_Model extends MY_Model {

    const TABLE_NAME = "sys_function";
     public $tableName= "sys_function";

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();

        log_message('debug', "Function Model Class Initialized");
    }

    /**
     * 获取功能。根据传入的字段名和查询值
     *
     * @access public
     * @param string - $key    字段名
     * @param string - $value  值
     * @return array
     */
    public function get_functions_by_where($key = 'funparentid',$value = '') {
        $query = array();
        //如果值不为空
        if(!empty($value)) {
            $this->db->select('*')->from(self::TABLE_NAME)->where($key, $value);

            $query = $this->db->get();
        }

        return $query;
    }

    /**
     * 获取功能。根据传入的父节点id，和id范围
     * @param $parentid
     * @param $ids
     * @return mixed
     */
    public function get_functions_by_parentid_ids ($parentid,$ids) {
        $this->db->where('funparentid',$parentid);
        $this->db->where_in('id',$ids);

        $this->db->select('*')->from(self::TABLE_NAME);

        $query = $this->db->get();

        return $query;
    }

    /**
     * 获取功能列表。根据传入的id范围。
     * @param $ids
     */
    public function get_functions_by_used($ids) {
        $this->db->where('used',1);
        $this->db->where_in('id',$ids);

        $this->db->select('*')->from(self::TABLE_NAME);

        $query = $this->db->get();

        return $query;
    }

}
