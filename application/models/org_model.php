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
 * 颜色代码操作Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */


class org_model extends MY_Model {

//    const TABLE_NAME = "sys_org";
    public $tableName= "sys_org";
    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->tableName = 'sys_org';
        log_message('debug', "Color Model Class Initialized");
    }

     /*
	 * 数据验证
	 */
	public  function _validate(){
		return array(
		   array('orgcode','required','分组名称必须'),
		);
	}
}
