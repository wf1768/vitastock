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
 * stock storehouse_in model Class
 *
 * 入库单Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class s_storehouse_in_model extends MY_Model{


    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
        $this->tableName = 's_storehouse_in';

        log_message('debug', "storehouse_in Model Class Initialized");
    }

    /**
     * 重写父类表单验证
     */
    public function _validate() {
        $this->form_validation->set_rules('innumber', '入库单编号', 'required|trim|htmlspecialchars');
    }

}
