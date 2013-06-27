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
 * b_buy_content model Class
 *
 * 采购商品 Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class buy_product_model extends MY_Model{

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();

        $this->tableName = 'b_buy_content';

        log_message('debug', "b_buy_content Model Class Initialized");
    }

    /**
     * 重写父类表单验证
     */
    public function _validate() {
        $this->form_validation->set_rules('title', '商品名称', 'required|trim|htmlspecialchars');
        $this->form_validation->set_rules('code', '代码', 'required|trim');

//        $validate = array(
//            array(
//                'title',
//                'required|trim|htmlspecialchars',
//                '标题'
//            ),
//            array(
//                'code',
//                'required|trim',
//                '代码'
//            )
//
//        );
//        return $validate;
    }

    /**
     * 插入导入的excel数据。因为是ajax提交多条数组形式的数据，没办法通过验证。单独写sql插入吧
     *
     * @param $data
     * @return bool
     */
    public function insert($data) {

        $this->db->insert($this->tableName, $data);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    public function update($id,$data) {
        $this->db->where('id', $id);
        $this->db->update($this->tableName, $data);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

}
