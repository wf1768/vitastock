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
 * stock sys_config model Class
 *
 * 系统配置Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */
class config_Model extends MY_Model {
//    const TABLE_NAME = "sys_config";
    public $tableName= "sys_config";
    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();

        $this->tableName = 'sys_config';

        log_message('debug', "config Model Class Initialized");
    }



    //TODO 因继承父类，以下可以删除

//    /**
//     * 获取单个配置信息
//     *
//     * @access public
//     * @param string $id id
//     * @return array - 配置信息
//     */
//    public function get_config_by_id($id) {
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
//     * 根据key的值，获取配置信息
//     * @param $key          key值
//     * @return array|bool   配置信息 or false
//     */
//    public function get_config_by_key($key) {
//        if (!empty($key)) {
//            $data = array();
//
//            $this->db->select('*')->from(self::TABLE_NAME)->where('KEY',$key)->limit(1);
//
//            $query = $this->db->get();
//            if ($query->num_rows() == 1) {
//                $data = $query->row_array();
//            }
//
//            $query->free_result();
//            return $data;
//        }
//
//        return false;
//
//    }
//
//    /**
//     * 获取所有配置信息
//     *
//     * @access public
//     * @return array - 配置信息
//     */
//    public function get_configs()
//    {
//        return $this->db->get(self::TABLE_NAME);
//    }
//
//    /**
//     * 删除一个配置信息
//     *
//     * @access public
//     * @param string - $id 配置信息id
//     * @return boolean - success/failure
//     */
//    public function remove_config($id)
//    {
//        $this->db->delete(self::TABLE_NAME, array('id' => $id));
//
//        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
//    }
//
//    /**
//     * 添加一个配置
//     *
//     * @access public
//     * @param array - $data 配置信息
//     * @return boolean - success/failure
//     */
//    public function add_config($data)
//    {
//        $this->db->insert(self::TABLE_NAME, $data);
//
//        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
//    }
//
//    /**
//     * 修改配置信息
//     *
//     * @access public
//     * @param string - $id 配置ID
//     * @param array - $data 配置信息
//     * @return boolean - success/failure
//     */
//    public function update_account($id, $data)
//    {
//        $this->db->where('id', $id);
//        $this->db->update(self::TABLE_NAME, $data);
//
//        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
//    }

}
