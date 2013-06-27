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
 * stock factory model Class
 *
 * 厂家代码操作Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */


class factory_model extends MY_Model {
    public $tableName= "sys_factory";

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
        $this->tableName = 'sys_factory';

        log_message('debug', "factory Model Class Initialized");
    }

    /**
     * 数据验证
     * @return array|void
     */
    public  function _validate(){
        return array(
            array('factoryname','required','厂家名称必须'),
            array('factorycode','required','厂家代码必须'),
            array('factorycode','is_unique[sys_factory.factorycode]','厂家代码已经存在，换一个吧'),
        );
    }


}
