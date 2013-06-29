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
 * apply Login log model Class
 *
 * 期货订单表 Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class apply_Model extends MY_Model {


    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();

        $this->tableName = 'b_order_apply';

        log_message('debug', "order_apply Model Class Initialized");
    }

    /**
     * 重写父类表单验证
     */
    public function _validate() {
//        $this->form_validation->set_rules('applynumber', '订单编号', 'required|trim|htmlspecialchars');
        $this->form_validation->set_rules('clientname', '客户名称', 'required|trim|htmlspecialchars');
//        $this->form_validation->set_rules('applyby', '申请人', 'required|trim|htmlspecialchars');


    }

}
