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


class message_model extends MY_Model {

//    const TABLE_NAME = "sys_color";
    public $tableName= "i_message";
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
        $this->tableName = 'i_message';
        log_message('debug', "message Model Class Initialized");
    }
     /*
	 * 数据验证
	 */
		public  function _validate(){
		return array(
		   array('title','required','消息标题必须'),
		   array('content','required','消息内容必须'),
		);
	}
}
