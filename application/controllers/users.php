<?php
/**
 * Created by eclips.
 * User: li jin
 * Date: 13-3-5
 * Time: 下午8:30
 * 用户管理
 */
class users  extends  Stock__Controller{
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
		/** 在继承的自定义父类，获取系统配置。 */
		$this->_data = $this->get_stock_config('0','1');

		$this->_data['page_title'] =  '系统设置';

		$this->_data['fun_path'] = "users/sDataList";

	}


	/**
	 * 用户管理首页
	 * @access public
	 * @return mixed
	 */
	public  function  sDataList(){
		$this->_data['norgid']=$this->input->get('orgid');
		$this->_data['group']=$this->org_model->getAllByWhere("",array("id","orgcode"));
		if($this->input->get('orgid')){
			$this->dataList("users/list",$this->account_model,array("orgid"),array(),array (),$this->_data);
		}else{
			$this->dataList("users/list",$this->account_model,'',array(),array (),$this->_data);
		}

	}
	/**
	 * 显示数据添加页面
	 * @access public
	 * @return mixed
	 */
	public  function  sDataAdd(){
		$this->_data['store']=$this->storehouse_model->getAllByWhere("",array("id","storehousecode"));
		$this->_data['norgid']=$this->input->get('orgid');
		$this->_data['group']=$this->org_model->getAllByWhere("",array("id","orgcode"));
		$this->_data['roles']=$this->role_model->getAllByWhere("",array("id","rolecode"));
		$this->load->view("users/add",$this->_data) ;
	}
	/**
	 * 执行用户插入
	 * @access public
	 * @return mixed
	 */
	public  function doSdataAdd(){
		isset($_POST['store'])&&$_POST['store']=json_encode($_POST['store']);
		$passwrod=$_POST['password']?$_POST['password']:$this->_data['default_password'];
		$_POST['password']=Common::do_hash(trim($passwrod));
        $_POST['accountimage']= 'default.gif';
		$this->dataInsert($this->account_model,$_POST);
	}
	/**
	 * 显示数据修改项
	 * @access public
	 * @return mixed
	 */
	public  function sDataEdit(){
		$this->_data['store']=$this->storehouse_model->getAllByWhere("",array("id","storehousecode"));
		$this->_data['group']=$this->org_model->getAllByWhere("",array("id","orgcode"));
		$this->_data['roles']=$this->role_model->getAllByWhere("",array("id","rolecode"));
		$this->dataEdit("users/edit",$this->account_model,$this->_data) ;
	}
	/**
	 * 执行数据修改项
	 * @access public
	 * @return mixed
	 */
	public  function doSdaatEdit(){
		isset($_POST['store'])&&$_POST['store']=json_encode($_POST['store']);
		if($_POST['password']){
			$_POST['password']=Common::do_hash(trim($_POST['password']));
		}else{
			unset( $_POST['password']);
		}
		$this->dataUpdate($this->account_model,$_POST);
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
			$this->dataDelete($this->account_model,$wh,'id',false);
		}
		echo  2;
	}
	/**
	 * 用id 获得角色
	 * @access public
	 * @return mixed
	 */
	public function getRole($id=null){
		if(!$id){
			echo "未分配";
		}else{
			$info=$this->role_model->getOneByWhere($where = array ("id"=>$id), $field = array ("rolecode"), $order = array ());
			if(!$info){
				echo "未分配";
			}else{
				echo $info->rolecode;
			}
		}
			
	}
	 
	/**
	 * 用id 获得分组
	 * @access public
	 * @return mixed
	 */
	public function getGroup($id=null){
		if(!$id){
			echo "未设置";
		}else{
			$info=$this->org_model->getOneByWhere($where = array ("id"=>$id), $field = array ("orgcode"), $order = array ());
			if(!$info){
				echo "未设置";
			}else{
				echo $info->orgcode;
			}
		}
			
	}
	/**
	 * 获取库房
	 * @access public
	 * @return mixed
	 */
	public function getStroe($id){
		$out=array();
		$array=json_decode($id,true);
		if(!$array){
			echo '无库房';
		}else{
			$query = $this->db->query('SELECT id, storehousecode FROM s_storehouse where id in ('.implode(',', $array).')');
			foreach ($query->result() as $row){
				$out[]=$row->storehousecode;
			}
			echo $out?implode(",", $out):'无库房';
		}
	}
}