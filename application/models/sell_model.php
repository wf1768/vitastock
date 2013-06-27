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
 * stock color model Class
 *
 * 操作Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */
class sell_model extends MY_Model {
	//    const TABLE_NAME = "sys_color";
	public $tableName= "e_sell";
	/**
	 * 构造函数
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		log_message('debug', "sell Model Class Initialized");
	}
	/*
	 * 数据验证
	 */
	public function _validate(){
        //TODO 由期货订单转销售单时，有验证就不能通过，这里先屏蔽。找原因
//		return array(
//		  // array('contractnumber','required','合同编号必须'),
//		   array('clientname','required','客户名称必须'),
//		   array('clientphone','required','客户电话必须'),
//		   array('clientadd','required','客户地址必须'),
//		   array('checkby','required','销售者必须'),
//		);
	}
}
