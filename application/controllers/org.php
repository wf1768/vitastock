<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-2-28
 * Time: 下午8:30
 * 分组管理
 */
class org  extends  Stock__Controller{
	/*
	 * 传递到页面的参数载体
	 * @var
	 */
	private $_data;
	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('stock_lib');
		$this->load->model('org_model');
		$this->load->model('storehouse_model');

		/** 在继承的自定义父类，获取系统配置。 */
		$this->_data = $this->get_stock_config('0','1');

		$this->_data['page_title'] =  '系统设置';

		$this->_data['fun_path'] = "org/sDataList";

	}
	/**
	 * 分组管理首页
	 * @access public
	 * @return mixed
	 */
	public  function  sDataList(){
		$this->dataList("org/list",$this->org_model,$where=array(),$like=array(),$order = array (),$this->_data)     ;
	}
	/**
	 * 显示数据添加页面
	 * @access public
	 * @return mixed
	 */
	public  function  sDataAdd(){
		$this->_data['store']=$this->storehouse_model->getAllByWhere("",array("id","storehousecode"));
		$this->_data['roles']=$this->role_model->getAllByWhere("",array("id","rolecode"));
		//$this->_data['plist']=$this->org_model->getAllByWhere("",array("id","orgcode"));
		$this->load->view("org/add",$this->_data) ;
	}
	/**
	 * 执行分组插入
	 * @access public
	 * @return mixed
	 */
	public  function doSdataAdd(){
		$data=$_POST;
		$data['store']=json_encode($_POST['store']);
		$this->dataInsert($this->org_model,$data);
	}
	/**
	 * 显示数据修改项
	 * @access public
	 * @return mixed
	 */
	public  function sDataEdit(){
		$this->_data['store']=$this->storehouse_model->getAllByWhere("",array("id","storehousecode"));
		//$this->_data['plist']=$this->org_model->getAllByWhere("",array("id","orgcode"));
		$this->_data['roles']=$this->role_model->getAllByWhere("",array("id","rolecode"));
		$this->dataEdit("org/edit",$this->org_model,$this->_data) ;
	}
	/**
	 * 执行数据修改项
	 * @access public
	 * @return mixed
	 */
	public  function doSdaatEdit(){
		$data=$_POST;
		$data['store']=json_encode($_POST['store']);
		$this->dataUpdate($this->org_model,$data);
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
			$this->dataDelete($this->org_model,$wh,'id',false);
		}
		echo  2;
	}
	/**
	 * 获得上级
	 * @access public
	 * @return mixed
	 */
	public function getTopGroup($id=0){
		if($id==0){
			echo "顶级分组";
		}else{
			$info=$this->org_model->getOneByWhere($where = array ("id"=>$id), $field = array ("orgcode"), $order = array ());
			if(!$info){
				echo "顶级分组";
			}else{
				echo $info->orgcode;
			}
		}
			
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
			$this->load->model('role_model');
			$info=$this->role_model->getOneByWhere($where = array ("id"=>$id), $field = array ("rolecode"), $order = array ());
			if(!$info){
				echo "未分配";
			}else{
				echo $info->rolecode;
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