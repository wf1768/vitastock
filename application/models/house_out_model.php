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


class house_out_model extends MY_Model {

//    const TABLE_NAME = "sys_color";
    public $tableName= "s_storehouse_out";
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();

        $this->tableName = 's_storehouse_out';
        log_message('debug', "house_out_model Model Class Initialized");
    }
}
