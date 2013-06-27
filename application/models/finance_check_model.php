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
 * finance_check model Class
 *
 * 财务审核意见表 model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */
class finance_check_model extends MY_Model {

	public $tableName= "b_finance_check";
	/**
	 * 构造函数
	 */
	public function __construct() {
		parent::__construct();
		log_message('debug', "finance_check Model Class Initialized");
	}
	/*
	 * 数据验证
	 */
	public function _validate(){

	}
}
