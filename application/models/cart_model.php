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

class cart_model extends MY_Model {
    public $tableName= "s_cart";
    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
        log_message('debug', "cart Model Class Initialized");
    }
    /*
     * 获得购物车的产品
     */
    public function getProduct($uid){
       $sql="SELECT t.* FROM `s_cart` s ,s_stock t where  s.goodsid=t.id and s.uid='$uid'";
       $query=$this->db->query($sql);
       return $query->result();
    }
     /*
	 * 数据验证
	 */
	public  function _validate(){}
}
