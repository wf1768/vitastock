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
 * storehouse_move model Class
 *
 * 调拨表 Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class storehouse_move_model extends MY_Model {


    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();

        $this->tableName = 's_storehouse_move';
        log_message('debug', "s_storehouse_move Model Class Initialized");
    }

}
