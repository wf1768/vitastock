<?php

//=================================================

/**
 * stock color model Class
 *
 * 颜色代码操作Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */
class operatelog_model extends MY_Model {
	const TABLE_NAME = "sys_operate_log";
	public $tableName= "sys_operate_log";
	/**
	 * 构造函数
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		log_message('debug', "Color Model Class Initialized");
	}
}
