<?php
class config  extends  Stock__Controller{
	/*
	 * 传递到页面的参数载体
	 * @var
	 */
	private $_data;
	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('stock_lib');
		$this->load->model('config_Model');

		/** 在继承的自定义父类，获取系统配置。 */
		$this->_data = $this->get_stock_config('0','1');

		$this->_data['page_title'] =  '系统设置';

		$this->_data['fun_path'] = "config/sDataList";

	}
	/**
	 * 角色管理首页
	 * @access public
	 * @return mixed
	 */
	public  function  sDataList(){
		$this->dataList("config/list",$this->config_Model,$where=array(),$like=array(),$order = array (),$this->_data)     ;
	}
	/**
	 * 显示数据添加页面
	 * @access public
	 * @return mixed
	 */
	public  function  sDataAdd(){
		$this->load->view("config/add",$this->_data) ;
	}
	/**
	 * 执行角色插入
	 * @access public
	 * @return mixed
	 */
	public  function doSdataAdd(){
		$this->dataInsert($this->config_Model);
	}
	/**
	 * 显示数据修改项
	 * @access public
	 * @return mixed
	 */
	public  function sDataEdit(){
		if($_GET['id']==6){
			$this->load->model('storehouse_model');
			$this->_data["stlist"]=$this->storehouse_model->getAllByWhere();
			$this->dataEdit("config/edit6",$this->config_Model,$this->_data) ;
		}else{
			$this->dataEdit("config/edit",$this->config_Model,$this->_data) ;
		}
		
	}
	/**
	 * 执行数据修改项
	 * @access public
	 * @return mixed
	 */
	public  function doSdaatEdit(){
		$this->dataUpdate($this->config_Model);
	}
	/**
	 * 执行数据删除
	 * @access public
	 * @return mixed
	 */
	public  function doSdataDel(){
		$idlist=explode(",", $this->input->post("id"));
		if(empty($idlist)){
			echo  1; //错误数据操作
			exit;
		}
		foreach($idlist as $id){
			$wh=array("id"=>$id);
			$this->dataDelete($this->config_Model,$wh,'id',false);
		}
		echo  2;
	}
     

}