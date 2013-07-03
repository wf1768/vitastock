<?php
if (!defined('BASEPATH'))
	exit ('No direct script access allowed');
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
 * stock main Controller Class
 *
 * 登陆后进入首页。
 *
 * @package		stock
 * @subpackage	Controller
 * @category	Controller
 * @author		blues <blues0118@gmail.com>
 * @link
 */
class Main extends Stock__Controller {
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
		$this->_data = $this->get_stock_config('0', '0', true);
		$this->load->model('storehouse_model', "storehouse");
		$this->load->model('stock_model');
		$this->_data['page_title'] = "首页";
		$this->_data['fun_path'] = "main";

	}

	public function index() {
		$allcount = 0;
		for ($i = 12; $i > -1; $i--) {
			$times = mktime(0, 0, 0, date("m") - $i, date("d"), date("Y"));
			$data = date("Y-m", $times);
			$sql = 'SELECT count(*) count FROM `e_sell` s ,e_sell_content c where s.id=c.sellid  and s.status != 1 and s.createtime  like "' . $data . '%"';
			$res = $this->db->query($sql)->result();
			$ydata[] = intval($res[0]->count);
			$xdata[] = date("y/m", $times);
			;
			$allcount += intval($res[0]->count);
		}
		$this->_data['ydata'] = json_encode($ydata);
		$this->_data['xdata'] = json_encode($xdata);
		$this->_data['ydatas'] = $ydata;
		$this->_data['xdatas'] = $xdata;
		$this->_data['allcount'] = $allcount;
		//采购数量
		$sql="SELECT sum(c.number) count FROM `b_buy` b ,b_buy_content c where  b.id=c.buyid and  createtime like '".date('Y-m')."%'" ;
		$res=$this->db->query($sql)->result();
		if(isset($res[0])){
		   $this->_data['cgcount']=$res[0]->count;	
		}else{
		   $this->_data['cgcount']=0;
		}
		//销售数量
		$sql="SELECT COUNT(*) count FROM `e_sell` b ,e_sell_content c where  b.id=c.sellid and  `status` !=1 and createtime like '".date('Y-m')."%'" ;
		$res=$this->db->query($sql)->result();
		if(isset($res[0])){
		   $this->_data['xscount']=$res[0]->count;	
		}else{
		   $this->_data['xscount']=0;
		}
		//期货数量 
		$sql="SELECT sum(c.number) count  FROM `b_order_apply` b ,b_apply_content c where  b.id=c.applyid and  b.applydate like '".date('Y-m')."%'" ;
		$res=$this->db->query($sql)->result();
		if(isset($res[0])){
		   $this->_data['qhcount']=$res[0]->count;	
		}else{
		   $this->_data['qhcount']=0;
		}
		//退货数量
		$sql="SELECT COUNT(*) count FROM `e_sell` b ,e_sell_content c where  b.id=c.sellid and  `status` =1 and createtime like '".date('Y-m')."%'" ;
		$res=$this->db->query($sql)->result();
		if(isset($res[0])){
		   $this->_data['thcount']=$res[0]->count;	
		}else{
		   $this->_data['thcount']=0;
		}
		$this->load->view('main', $this->_data);
	}

}