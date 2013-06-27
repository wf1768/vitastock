<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
/**
 * stock Login Controller Class
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
 * finance_check Controller Class
 *
 * 财务审核意见 控制器
 *
 * @package		stock
 * @subpackage	Controller
 * @category	Controller
 * @author		blues <blues0118@gmail.com>
 * @link
 */
class finance_check extends Stock__Controller {
	/**
	 * @var 传递到视图的变量
	 */
	private $_data;

	/**
	 * 构造函数
	 * @access public
	 * @return void
	 */
	function __construct() {
		parent :: __construct();

		/** 在继承的自定义父类，获取系统配置。 */
        $this->load->model('finance_check_model');
        $this->load->model('sell_model');
//		$this->_data = $this->get_stock_config('0', '0', true);
//		$this->load->model('storehouse_model', "storehouse");
//		$this->load->model('stock_model');
//		$this->_data['page_title'] = "首页";
//		$this->_data['fun_path'] = "main";

	}

	public function index() {

	}

    public function add_finance_check() {
        $result = false;

        //插入审批意见表
        $insert_finance_check = array(
            'sellid'               =>  $this->input->post('sellid') ? $this->input->post('sellid') : '',
            'sellnumber'              =>  $this->input->post('sellnumber') ? $this->input->post('sellnumber') : '',
            'financetime'              =>  $this->input->post('financetime') ? $this->input->post('financetime') : '',
            'paymoney'                =>  $this->input->post('paymoney') ? $this->input->post('paymoney') : '',
            'lastmoney'                =>  $this->input->post('lastmoney') ? $this->input->post('lastmoney') : '',
            'remark'                =>  $this->input->post('remark') ? $this->input->post('remark') : '',
            'financeman'                =>  $this->input->post('financeman') ? $this->input->post('financeman') : '',

        );

        $tmpid = $this->dataInsert($this->finance_check_model,$insert_finance_check,false);

        //如果余额为0.就修改销售单的financestatus值为1
        if ($insert_finance_check['lastmoney'] == 0) {
            $update_sell = array(
                'id'                    =>  $this->input->post('sellid') ? $this->input->post('sellid') : '',
                'financestatus'         =>  1,
            );
            $num = $this->dataUpdate($this->sell_model,$update_sell,false);
        }

        if ($tmpid) {
            $result = true;
        }

        $this->output->append_output($result);

    }

}