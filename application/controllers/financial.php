<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * stock Login Controller Class
 *
 * 基于ci的商品管理系统1
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
 * apply Controller Class
 *
 * 财务管理 控制器。
 *
 * @package		financial
 * @subpackage	Controller
 * @category	Controller
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class financial extends Stock__Controller {

    /**
     * 传递到页面的参数载体
     * @var
     */
    private $_data;

    /**
     * 构造函数
     */
    function __construct() {

        parent::__construct();

        /** 在继承的自定义父类，获取系统配置。 */
        $this->_data = $this->get_stock_config('0','5');
        $this->_data['page_title'] =  '采购、期货管理';
        $this->_data['fun_path'] = "financial";

    }

    public function index() {
        redirect('apply?stype=financial');
//        $this->load->view('financial/index',$this->_data);
    }

}