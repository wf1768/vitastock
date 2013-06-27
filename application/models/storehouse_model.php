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
 * stock storehouse model Class
 *
 * 库房管理操作Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class storehouse_model extends MY_Model {

//    const TABLE_NAME = "s_storehouse";
    public $tableName= "s_storehouse";

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();

        log_message('debug', "storehouse Model Class Initialized");
    }


//
//
//    /**
//     * 获取单个库房信息
//     *
//     * @access public
//     * @param string $id 库房id
//     * @return array - 库房信息
//     */
//    public function get_storehouse_by_id($id) {
//        $data = array();
//
//        $this->db->select('*')->from(self::TABLE_NAME)->where('id', $id)->limit(1);
//        $query = $this->db->get();
//        if($query->num_rows() == 1) {
//            $data = $query->row_array();
//        }
//        //释放结果集
//        $query->free_result();
//
//        return $data;
//    }
//
//    /**
//     * 获取所有库房信息
//     *
//     * @access public
//     * @return array - 库房信息
//     */
//    public function get_storehouses() {
//        return $this->db->get(self::TABLE_NAME);
//    }
//
//    /**
//     * 获取库房
//     * @param string $ids       库房id范围
//     * @return mixed
//     */
//    public function get_storehouses_by_ids($ids='') {
//        if (!empty($ids)) {
//            $this->db->where_in('id',$ids);
//        }
//        $query = $this->db->get(self::TABLE_NAME);
//
//        if($query->num_rows() > 0) {
//            $data = $query->result_array();
//        }
//        //释放结果集
//        $query->free_result();
//
//        return $data;
//    }
//
//    //TODO 应该增加分页的查询
//
//    /**
//     * 删除一个库房
//     *
//     * @access public
//     * @param string- $id 库房id
//     * @return boolean - success/failure
//     */
//    public function remove_storehouse($id) {
//
//        //TODO 先移除库房与功能对应关系。
//
//        $this->db->delete(self::TABLE_NAME, array('id' => $id));
//
//        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
//    }
//
//    /**
//     * 添加一个库房
//     *
//     * @access public
//     * @param string- $data 库房信息
//     * @return boolean - success/failure
//     */
//    public function add_storehouse($data) {
//
//        $this->db->insert(self::TABLE_NAME, $data);
//
//        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
//    }
//
//    /**
//     * 修改库房信息
//     *
//     * @access public
//     * @param string- $id 库房ID
//     * @param array- $data 库房信息
//     * @return boolean - success/failure
//     */
//    public function update_storehouse($id, $data) {
//
//        $this->db->where('id', $id);
//        $this->db->update(self::TABLE_NAME, $data);
//
//        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
//    }
//     /*
//	 * 数据验证
//	 */
//	public  function _validate(){
////		return array(
////		   array('storehousecode','required','厂家名称必须'),
////		);
//	}

}
