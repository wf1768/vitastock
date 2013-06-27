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
 * stock stock pic model Class
 *
 * 商品图片操作Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */


class stock_pic_model extends MY_Model {

    const TABLE_NAME = "s_pic";

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->tableName = 's_pic';

        log_message('debug', "pic Model Class Initialized");
    }

    /**
     * 删除一个商品图片
     *
     * @access public
     * @param varchar - $id 商品图片id
     * @return boolean - success/failure
     */
    public function remove_pic($id) {
        $this->db->delete($this->tableName, array('id' => $id));

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }









    //TODO 以下因继承父类。在不需要时删除

    /**
     * 获取单个商品图片信息
     *
     * @access public
     * @param varchar $id 商品图片id
     * @return array - 商品图片信息
     */
    public function get_pic_by_id($id) {
        $data = array();

        $this->db->select('*')->from(self::TABLE_NAME)->where('id', $id)->limit(1);
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            $data = $query->row_array();
        }
        //释放结果集
        $query->free_result();

        return $data;
    }

    /**
     * 获取所有商品图片信息
     *
     * @access public
     * @return array - 商品图片信息
     */
    public function get_pics($stockid='') {
        if (!empty($stockid)) {
            $this->db->where('stockid',$stockid);
        }
        return $this->db->get(self::TABLE_NAME);
    }



    /**
     * 添加一个商品图片
     *
     * @access public
     * @param  array $data 商品图片信息
     * @return boolean - success/failure
     */
    public function add_pic($data) {
        $this->db->insert(self::TABLE_NAME, $data);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * 修改商品图片信息
     *
     * @access public
     * @param varchar - $id 商品图片ID
     * @param varchar - $data 商品图片信息
     * @return boolean - success/failure
     */
    public function update_pic($id, $data) {

        $this->db->where('id', $id);
        $this->db->update(self::TABLE_NAME, $data);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
}
