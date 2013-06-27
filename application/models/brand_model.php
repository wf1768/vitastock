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
 * stock brand model Class
 *
 * 品牌代码操作Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */


class brand_model extends MY_Model {

    public $tableName= "sys_brand";

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();

        log_message('debug', "brand Model Class Initialized");
    }

    /*
	 * 数据验证
	 */
    public  function _validate(){
        return array(
            array('brandname','required','品牌名称必须'),
            array('brandcode','required','品牌代码必须'),
            array('brandname','is_unique[sys_brand.brandcode]','品牌代码已经存在，换一个吧'),
        );
    }

}
