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
 * apply_content_status Login log model Class
 *
 * 期货订单商品状态 表 Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class apply_content_status_Model extends MY_Model {


    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();

        $this->tableName = 'sys_apply_content_status';

        log_message('debug', "apply_status Model Class Initialized");
    }

}
