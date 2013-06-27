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
 * stock stock model Class
 *
 * 库存操作Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class stock_model extends MY_Model {

    const TABLE_NAME = "s_stock";
//     public $tableName= "s_stock";

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->tableName = 's_stock';


        log_message('debug', "stock Model Class Initialized");
    }

     /**
     * 删除一个库存
     *
     * @access public
     * @param string- $id 库存id
     * @return boolean - success/failure
     */
    public function remove_stock($id) {

        //TODO 先移除库存与功能对应关系。

        $this->db->delete($this->tableName, array('id' => $id));

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * 重写父类表单验证
     */
    public function _validate() {
//        $this->form_validation->set_rules('storehouse', '库房', 'trim');
        $this->form_validation->set_rules('title', '商品名称', 'required|trim|htmlspecialchars');
        $this->form_validation->set_rules('code', '代码', 'required|trim');
//        $this->form_validation->set_rules('memo', '商品描述', 'trim');
//        $this->form_validation->set_rules('cost', '单价', 'required|trim|is_natural');
//        $this->form_validation->set_rules('salesprice', '售价', 'required|trim|is_natural');
//        $this->form_validation->set_rules('remark', '备注', 'trim');
//        $this->form_validation->set_rules('brand', '品牌', 'trim');
//        $this->form_validation->set_rules('factory', '厂家', 'trim');
//        $this->form_validation->set_rules('commoditytype', '商品类别', 'trim');
        //条形码自定义验证，是否唯一
//        $this->form_validation->set_rules('barcode', '条形码', 'required|trim|callback_barcode_check');
//        $this->form_validation->set_rules('number', '数量', 'required|trim');
//        $this->form_validation->set_rules('color', '颜色', 'trim');
//        $this->form_validation->set_rules('format', '规格', 'trim');
//        $this->form_validation->set_rules('statusvalue', '商品状态', 'trim');
    }

    /**
     * 检查是否存在相同{ 条形码等}
     *
     * @access public
     * @param varchar - $key {barcode}
     * @param varchar - $value {帐户名/邮箱}的值
     * @param varchar - $exclude_id 需要排除的id
     * @return boolean - success/failure
     */
    public function check_exist($key = 'barcode',$value = '', $exclude_id = '') {
        //如果值不为空
        if(!empty($value)) {
            $this->db->select('id')->from(self::TABLE_NAME)->where($key, $value);

            //如果要排除的id不为空
            if(!empty($exclude_id)) {
                $this->db->where('id <>', $exclude_id);
            }

            $query = $this->db->get();
            $num = $query->num_rows();

            $query->free_result();

            return ($num > 0) ? TRUE : FALSE;
        }

        return FALSE;
    }
}
