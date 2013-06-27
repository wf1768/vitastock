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
 * stock commodity_type model Class
 *
 * 商品类型代码操作Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class commodityType_model extends MY_Model {


    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
        $this->tableName = 'sys_commodity_type';

        log_message('debug', "commodity_type Model Class Initialized");
    }

    /**
     * 数据验证
     * @return array|void
     */
    public  function _validate(){
        return array(
            array('typename','required','类别名称必须'),
            array('typecode','required','类别代码必须'),
            array('typecode','is_unique[sys_commodity_type.typecode]','类别代码已经存在，换一个吧'),
        );
    }

}
