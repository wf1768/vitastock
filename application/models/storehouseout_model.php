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

class storehouseout_model extends MY_Model {

    const TABLE_NAME = "s_storehouse_out";
    public $tableName= "s_storehouse_out";

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
        log_message('debug', "storehouseout Model Class Initialized");
    }
     /*
	 * 数据验证
	 */
	public  function _validate(){
	}

}
