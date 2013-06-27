<?php
/**
 * Created by lj
 * User: Administrator
 * Date: 13-2-28
 * Time: 下午8:02
 * 系统设置
 */
class settings  extends Stock__Controller{
    function __construct() {

        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('stock_lib');
        $this->load->model('stock_model');

        /** 在继承的自定义父类，获取系统配置。 */
        $this->_data = $this->get_stock_config('0','1');

        $this->_data['page_title'] =  '库存管理';

//        $this->load->library('auth_lib');
//        $functions = $this->auth_lib->get_fun_auth('0');
//        $this->_data['topmenu'] = $functions;
//
//        //获取库存管理二级菜单
//        $leftmenu = $this->auth_lib->get_fun_auth('6');
//        $this->_data['leftmenu'] = $leftmenu;

        $this->_data['fun_path'] = "stock";

    }
     /*
      * @access public
      * @auth  lijin
      * @data  2013.2.28 20:05
      */
    public  function  index(){
          $this->load->view("settings/index",$this->_data);
    }
}