<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * stock System
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

// ------------------------------------------------------------------------

/**
 * stock Library Class
 *
 * 库存的某些业务操作都可以放入这里
 *
 * @package		stock
 * @subpackage	Libraries
 * @category	Libraries
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class stock_lib {

    /**
     * CI句柄
     *
     * @access private
     * @var object
     */
    private $_CI;

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        /** 获取CI句柄 */
        $this->_CI = & get_instance();
        $this->_CI->load->model('stock_model');
        $this->_CI->load->model('stock_pic_model');

//        $this->_account = unserialize($this->_CI->session->userdata('account'));
        log_message('debug', "stock: stock library Class Initialized");
    }

    /**
     * 删除库存
     * @param $id       库存id
     */
    public function remove_stock($id) {
        $result = false;
        if (!$id) {
            return $result;
        }
        //移除库存商品关联的信息（库存商品信息关联的业务比较多，不应该删除，应该是库存的状态改变，这里提供删除功能，生产环境下，应避免删除）
        //1.移除商品图片
        //获取商品全部图片
        $query_pics = $this->_CI->stock_pic_model->getAllByWhere(array('stockid'=>$id));

        if ($query_pics) {
            foreach ($query_pics as $pic) {
                $this->remove_pic($pic);
            }
        }

        //2.TODO 删除商品关联的其他业务。例如：调拨、入库、出库等关联id


        //移除库存
        $result = $this->_CI->stock_model->remove_stock($id);

        return $result;

    }

    /**
     * 移除商品图片。包括物理文件和数据库记录
     * @param $picid        id
     */
    public function remove_pic($pic) {
        $result = false;
        if (!$pic) {
            return $result;
        }
        //检查文件是否存在
        if (!file_exists($pic->picpath)) {
            return $result;
        }

        //删除物理文件
        $b = unlink($pic->picpath);
        if (!$b) {
            return $result;
        }
        //删除数据库记录
        $result = $this->_CI->stock_pic_model->remove_pic($pic->id);

        return $result;
    }

    /**
     * 移除商品图片。包括物理文件和数据库记录
     * @param $picid        id
     */
    public function remove_pic2($picid) {
        $result = false;
        if (!$picid) {
            return $result;
        }
        //删除物理文件
        $picObject = $this->_CI->stock_pic_model->get_pic_by_id($picid);

        if (!$picObject || $picObject['picpath'] == '') {
            return $result;
        }
        //检查文件是否存在
        if (!file_exists($picObject['picpath'])) {
            return $result;
        }

        //删除物理文件
        $b = unlink($picObject['picpath']);
        if (!$b) {
            return $result;
        }
        //删除数据库记录
        $result = $this->_CI->stock_pic_model->remove_pic($picid);

        return $result;
    }

    /**
     * 设置商品图片为主要图片
     * @param $stockid      商品id
     * @param $picid        图片id
     * @return bool
     */
    public function set_pic_main($stockid,$picid) {
        $result = false;
        if (!$picid || !$stockid) {
            return $result;
        }
        //获取商品图片对象
        $picObject = $this->_CI->stock_pic_model->get_pic_by_id($picid);

        if (!$picObject || $picObject['picpath'] == '') {
            return $result;
        }

        //获取商品信息对象
        $this->_CI->load->model('stock_model');
        $stockObject = $this->_CI->stock_model->get_stock_by_id($stockid);

        if (empty($stockObject)) {
            return $result;
        }

        //设置商品图片为主要
        $stockObject['showpicpath'] = $picObject['picpath'];

        //更新数据库
        $result = $this->_CI->stock_model->update_stock($stockid,$stockObject);
        return $result;
    }
}
