<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-2-28
 * Time: 下午8:30
 * 个人账户信息管理
 */
class account extends Stock__Controller{
	/*
	 * 传递到页面的参数载体
	 * @var
	 */
	private $_data;
	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('stock_lib');
		$this->load->model('account_model');
		$this->load->model('org_model');
		$this->load->model('role_model');
		$this->load->model('storehouse_model');
		$this->load->library('auth_lib');
		/** 在继承的自定义父类，获取系统配置。 */
		$this->_data = $this->get_stock_config('0','1');
		$this->_data['page_title'] =  '系统设置';
		$this->_data['fun_path'] = "account/sDataEdit";

	}
	/**
	 * 显示数据修改项
	 * @access public
	 * @return mixed
	 */
	public  function sDataEdit(){
		$_GET['id']=$this->account_info_lib->id;
		$this->_data['store']=$this->storehouse_model->getAllByWhere("",array("id","storehousecode"));
		$this->_data['group']=$this->org_model->getAllByWhere("",array("id","orgcode"));
		$this->_data['roles']=$this->role_model->getAllByWhere("",array("id","rolecode"));
		$this->dataEdit("account/edit",$this->account_model,$this->_data) ;
	}
	/**
	 * 执行数据修改项
	 * @access public
	 * @return mixed
	 */
	public  function doSdaatEdit(){
		$rest= Common::hash_Validate(trim($_POST['ycode']),$this->account_info_lib->password);
		if(!$rest&&$_POST['ycode']){
			$this->error("原密码输入错误");
		}else{
			if($_POST['ycode']){
				$passwrod=$_POST['password']?$_POST['password']:$this->_data['default_password'];
				$_POST['password']=Common::do_hash(trim($passwrod));
				$this->dataUpdate($this->account_model,$_POST,false);
				$this->auth_lib->logout();
			}else{
				unset($_POST['password']);
				$this->dataUpdate($this->account_model,$_POST);
			}
		}
	}
}