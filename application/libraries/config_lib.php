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
 * stock Config Library Class
 *
 * 系统配置类。
 * 1.获取系统基础配置，为每个页面提供参数。
 *
 * @package		stock
 * @subpackage	Libraries
 * @category	Libraries
 * @author		blues <blues0118@gmail.com>
 * @link
 */


class Config_lib {

    private $_data = array();

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
        $this->_CI->load->model('config_model','configModel');

        //初始化默认参数。
        $this->_data['sys_title'] = "Stock 企业商品管理系统";
        $this->_data['sys_name'] = "Stock 企业商品管理系统";

        log_message('debug', "stock: Config library Class Initialized");
    }

    /**
     * 读取系统配置基本参数，供页面基础显示用。
     * 例如：sys_title sys_name .后期系统需要可以继续添加需要返回的参数。
     *
     * 获取topmenu菜单、leftmenu菜单
     *
     * @access public
     * @return array
     */
    public function load_sys_config($topmenu_parent=NULL,$leftmenu_parent=NULL,$menu_used=false) {

        //获取所有配置信息
//        $configs = $this->_CI->configModel->get_configs();

        $configs = $this->_CI->configModel->getAllByWhere();
        if (!empty($configs)) {
            foreach ($configs as $config) {
                //处理系统页面title如果为空，则默认系统title
                if ($config->key == 'sys_title') {
                    if (!empty($config->value)) {
                        $this->_data["sys_title"] = $config->value;
                    }
                    else {
                        $this->_data["sys_title"] = "Stock 企业商品管理系统";
                    }
                }
                //处理系统名称，如果为空。则默认系统名称
                else if ($config->key == 'sys_name') {
                    if (!empty($config->value)) {
                        $this->_data["sys_name"] = $config->value;
                    }
                    else {
                        $this->_data["sys_name"] = "Stock 企业商品管理系统";
                    }
                }
                else {
                    //将其他配置写入data
                    $this->_data[$config->key] = $config->value;
                }
            }
        }
        //获取系统菜单
        if (!isset($topmenu_parent)) {
            $this->_data['topmenu'] = false;
        }
        else {
            //获取topmenu、leftmenu
            $this->_CI->load->library('auth_lib');
            $functions = $this->_CI->auth_lib->get_fun_auth($topmenu_parent);
            $this->_data['topmenu'] = $functions;
        }

        $leftmenu = false;
        $num = array();
        if (isset($leftmenu_parent)) {
            if ($menu_used) {
                //获取常用功能列表
                $leftmenu = $this->_CI->auth_lib->get_fun_auth_used();
            }
            else {
                //获取库存管理二级菜单
                $leftmenu = $this->_CI->auth_lib->get_fun_auth($leftmenu_parent);
            }

            $this->_CI->load->model('s_storehouse_in_model');
            $this->_CI->load->model('apply_model');


            //处理左侧菜单提示信息（特定模块，有几条未处理的记录）TODO 以后有其他特殊模块需要显示未处理数量。都在这里
            if ($leftmenu) {
                foreach ($leftmenu as $menu) {
                    //入库单待处理数量显示
                    if ($menu['funpath'] == 'storehouse_in') {
                        $n = $this->_CI->s_storehouse_in_model->getAllByWhere(array('instatus'=>0));
                        $n = count($n);
                        $num[$menu['funpath']] = $n;
                    }
                    //期货订单待审核数量
                    if ($menu['funpath'] == 'apply_check') {
                        $n = $this->_CI->apply_model->getAllByWhere(array('status'=>1));
                        $n = count($n);
                        $num[$menu['funpath']] = $n;
                    }
                    //期货订单已审核数量
                    if ($menu['funpath'] == 'apply?stype=apply') {
                        $n = $this->_CI->apply_model->getAllByWhere(array('status'=>2));
                        $n = count($n);
                        $num[$menu['funpath']] = $n;
                    }
                    //调拨单处理数量
                    if ($menu['funpath'] == 'storehouse_move/handle') {
                        $stores = $this->_CI->account_info_lib->store;

                        if ($stores) {
                            //将数据转为json
                            require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
                            $json = new Services_JSON();
                            $stores = $json->decode($stores);
                        }
                        else {

                        }
                        $this->_CI->db->where_in('targethouseid',$stores);
                        $this->_CI->load->model('storehouse_move_model');
                        $n = $this->_CI->storehouse_move_model->getAllByWhere(array('status'=>1));
                        $n = count($n);
                        $num[$menu['funpath']] = $n;
                    }
                }
            }
        }
        $this->_data['num'] = $num;

        $this->_data['leftmenu'] = $leftmenu;

        return $this->_data;

    }

}
