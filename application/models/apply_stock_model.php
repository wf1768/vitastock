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
 * apply_stock model Class
 *
 * 期货订单表与商品表关联 Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class apply_stock_Model extends MY_Model {


    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();

        $this->tableName = 'b_apply_stock';

        log_message('debug', "apply_stock Model Class Initialized");
    }

    /**
     * 重写父类表单验证
     */
    public function _validate() {

    }

    /**
     * 获得符合条件的所有数据
     * @access public
     * @param array $where  查询条件
     * @param array $field  查询字段
     * @param array $order  排序字段
     * @return mixed
     */
    public function getAllByWhere($where = array (), $field = array (), $order = array ()) {
        $this->db->from($this->tableName);
        !empty($where) && $this->db->where($where);
        !empty($field) && $this->db->select($field);
        if(!empty($order)) foreach ($order as $key=>$val) $this->db->order_by($key,$val);
        $query = $this->db->get();
        return $query->result_array();
    }

}
