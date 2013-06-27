<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-2-28
 * Time: 下午8:30
 * 角色管理
 */
class role  extends  Stock__Controller{
	/*
	 * 传递到页面的参数载体
	 * @var
	 */
	private $_data;
	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('stock_lib');
		$this->load->model('role_model');
		$this->load->model("role_Fun_Model","power");

		/** 在继承的自定义父类，获取系统配置。 */
		$this->_data = $this->get_stock_config('0','1');

		$this->_data['page_title'] =  '系统设置';

		$this->_data['fun_path'] = "role/sDataList";

	}
	/**
	 * 角色管理首页
	 * @access public
	 * @return mixed
	 */
	public  function  sDataList(){
		$this->dataList("role/list",$this->role_model,$where=array(),$like=array(),$order = array (),$this->_data)     ;
	}
	/**
	 * 显示数据添加页面
	 * @access public
	 * @return mixed
	 */
	public  function  sDataAdd(){
		$this->load->view("role/add",$this->_data) ;
	}
	/**
	 * 执行角色插入
	 * @access public
	 * @return mixed
	 */
	public  function doSdataAdd(){
		$this->dataInsert($this->role_model);
	}
	/**
	 * 显示数据修改项
	 * @access public
	 * @return mixed
	 */
	public  function sDataEdit(){
		$this->dataEdit("role/edit",$this->role_model,$this->_data) ;
	}
	/**
	 * 执行数据修改项
	 * @access public
	 * @return mixed
	 */
	public  function doSdaatEdit(){
		$this->dataUpdate($this->role_model);
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
			$this->dataDelete($this->role_model,$wh,'id',false);
		}
		echo  2;
	}
	/**
	 * 获得操作的节点
	 * @access public
	 * @return mixed
	 */
	public function getFunList($id=0,$otherwhere=array()){
		$where['funparentid']=0;
		if($id){
			$where['funparentid']=$id;
		}
		$where=array_merge($where,$otherwhere);
		$this->load->model("function_Model");
		$list=$this->function_Model->getAllByWhere($where, $field = array ("id","funcode",'funpath'), $order = array ());
		return $list;
	}
	/**
	 * 设置角色权限
	 * @access public
	 * @return mixed
	 */
	public function doRolePower(){
		$this->session->set_userdata("roleid",$this->input->get('id'));
		$this->_data['list']=$this->getFunList(0);
		$this->_data['slist']=$this->getFunList(isset($_GET["pid"])?$_GET["pid"]:1);
		$this->_data['pid']=$this->input->get("pid");
		$this->_data['power']= $this->getPower();
		$this->_data['powerm']= $this->getPowerM();
		$this->_data['roleid']=$this->input->get("roleid");
		$this->load->view("role/rolepower",$this->_data) ;
	}
	/**
	 * 设置角色权限
	 * @access public
	 * @return mixed
	 */
	public function setRolePower(){
		$idlist=explode(",", $this->input->post("id"));
		$idlist2=explode(",", $this->input->post("id2"));
		$pid=intval($this->input->post('pid'));
		//做权限筛选过滤
		//操作权限
		//==========================================================================================================
		$alist=$this->getFunList($pid);
		//echo $this->db->last_query(); exit;
		foreach($alist as $val){$dalist[]=$val->id;}
		$result = array_diff($dalist,$idlist);
		foreach($result as $val){ //去掉权限
			$info=$this->power->getOneByWhere(array("roleid"=>$this->input->get("roleid"),"funid"=>$val,'sfunid'=>''),array("id"));
			if($info){
				$this->db->where("id",$info->id);
				$this->db->delete('sys_role_fun');
			}else{
				$this->db->where(array("roleid"=>$this->input->get("roleid"),"sfunid"=>$val,'funid'=>$val));
				$updData['funid']='';
				$this->db->update('sys_role_fun',$updData);
			}
		}
		$idlist=array_filter($idlist);
		if($idlist){
			//增加权限
			foreach($idlist as $val){ //添加权限-当前权限
				$info=$this->power->getOneByWhere(array("roleid"=>$this->input->get("roleid"),"funid"=>$val));
				if(!$info){ //权限没有，则添加
					$info2=$this->power->getOneByWhere(array("roleid"=>$this->input->get("roleid"),"sfunid"=>$val));
					if(!$info2){
						$insertData['roleid']=$this->input->get("roleid");
						$insertData['funid']=$val;
						$insertData['id']= md5(uniqid(rand(), true)); //uuid有问题。先采用这种试试
						$this->db->insert('sys_role_fun', $insertData);
					}else{
						$this->db->where('id',$info2->id);
						$updData['funid']=$val;
						$this->db->update('sys_role_fun',$updData);
					}
				}
				//echo $this->db->last_query();
			}
			//添加父基权限
			$info=$this->power->getOneByWhere(array("roleid"=>$this->input->get("roleid"),"funid"=>$pid),array("id"));
			if(!$info){
				$info2=$this->power->getOneByWhere(array("roleid"=>$this->input->get("roleid"),"sfunid"=>$pid),array("id"));
				if(!$info2){
					$insertData['roleid']=$this->input->get("roleid");
					$insertData['funid']=$pid;
					$insertData['id']= md5(uniqid(rand(), true)); //uuid有问题。先采用这种试试
					$this->db->insert('sys_role_fun', $insertData);
				}
			}
		}
		else{
			$info=$this->power->getOneByWhere(array("roleid"=>$this->input->get("roleid"),"funid"=>$pid),array("id"));
			//echo $this->db->last_query(); 
			if($info){
				$this->db->where("id",$info->id);
				$this->db->delete('sys_role_fun');
			}
		}
		//==============================================================================================================
		//模块内操作权限
		//==========================================================================================================
		$alist=$this->getFunList($pid);
		foreach($alist as $val){$dalist[]=$val->id;}
		$result = array_diff($dalist,$idlist2);
		foreach($result as $val){ //去掉权限
			unset($updData);
			$info=$this->power->getOneByWhere(array("roleid"=>$this->input->get("roleid"),"sfunid"=>$val,'funid'=>''),array("id"));
			if($info){
				$this->db->where("id",$info->id);
				$this->db->delete('sys_role_fun');
			}else{
				$this->db->where(array("roleid"=>$this->input->get("roleid"),"sfunid"=>$val,'funid'=>$val));
				$updData['sfunid']='';
				$this->db->update('sys_role_fun',$updData);
			}
			//echo $this->db->last_query();
		}
		$idlist2=array_filter($idlist2);
		unset($updData);
		if($idlist2){
			//增加权限
			foreach($idlist2 as $val){ //添加权限-当前权限
				$info=$this->power->getOneByWhere(array("roleid"=>$this->input->get("roleid"),"sfunid"=>$val));
				if(!$info){ //权限没有，则添加
					$info2=$this->power->getOneByWhere(array("roleid"=>$this->input->get("roleid"),"funid"=>$val));
					if(!$info2){ //权限没有，则添加
						$insertData['roleid']=$this->input->get("roleid");
						$insertData['sfunid']=$val;
						$insertData['id']= md5(uniqid(rand(), true)); //uuid有问题。先采用这种试试
						$this->db->insert('sys_role_fun', $insertData);
					}else{
						$this->db->where("id",$info2->id);
						$updData['sfunid']=$val;
						$this->db->update('sys_role_fun',$updData);
					}
				}
			}
			//添加父基权限
//			$info=$this->power->getOneByWhere(array("roleid"=>$this->input->get("roleid"),"funid"=>$pid),array("id"));
//			if(!$info){
//				$info2=$this->power->getOneByWhere(array("roleid"=>$this->input->get("roleid"),"sfunid"=>$pid),array("id"));
//				if(!$info2){
//					$insertData['roleid']=$this->input->get("roleid");
//					$insertData['funid']=$pid;
//					$insertData['id']= md5(uniqid(rand(), true)); //uuid有问题。先采用这种试试
//					$this->db->insert('sys_role_fun', $insertData);
//				}
//			}
		}
		//		else{
		//			$info=$this->power->getOneByWhere(array("roleid"=>$this->input->get("roleid"),"funid"=>$pid),array("id"));
		//			if($info){
		//				$info2=$this->power->getOneByWhere(array("roleid"=>$this->input->get("roleid"),"funid"=>$pid),array("id"));
		//				if($info2){
		//				   $this->db->where("id",$info->id);
		//				   $this->db->delete('sys_role_fun');
		//				}
		//			}
		//		}
		echo 2;
	}
	/**
	 * 获得当前角色的所有权限
	 * @access public
	 * @return mixed
	 */
	public function getPower(){
		$out=array();
		$list=$this->power->getAllByWhere(array("roleid"=>$this->input->get("roleid"),"funid !="=>''));
		//echo $this->db->last_query();
		foreach($list as $val){
			$out[]=$val->funid;
		}
		return $out;
	}
	/**
	 * 获得当前角色的所有权限
	 * @access public
	 * @return mixed
	 */
	public function getPowerM(){
		$out=array();
		$list=$this->power->getAllByWhere(array("roleid"=>$this->input->get("roleid"),"sfunid !="=>''));
		//echo $this->db->last_query();
		foreach($list as $val){
			$out[]=$val->funid;
		}
		return $out;
	}
}