<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * stock Controller Class
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
 * stock MY_Controller Class
 *
 * 扩展核心类。
 * @package		stock
 * @subpackage	Controller
 * @category	Controller
 * @author		blues <blues0118@gmail.com>
 * @link
 */


class Stock__Controller extends CI_Controller{

	//扩展控制器的构造函数。用来处理非法登陆。
	function __construct() {

		parent::__construct();

		//设定日期格式
		date_default_timezone_set('PRC');

		$this->load->library('auth_lib');
		$this->load->library('page');

		if(!$this->auth_lib->hasLogin()) {
			redirect('login');
		}

		//这里还可以load 更多每个页面都用到的控制器、medel等。

		//默认加载帐户信息lib
		$this->load->library('account_info_lib');
	}

	/**
	 * 获取
	 * 1、系统配置
	 * 2、当前登陆帐户的角色、角色能操作的功能
	 * 3、获取顶级菜单、左侧二级菜单、采用菜单
	 *
	 * @return
	 */
	function get_stock_config($topmenu_parent=false,$leftmenu_parent='',$used=false) {

		$this->load->library('Config_lib');

		// 获取系统配置
		$data = $this->config_lib->load_sys_config($topmenu_parent,$leftmenu_parent,$used);

		return $data;
	}

    /**
     * 获取模块内操作权限
     */
    function get_function_son($funid) {
        if ($funid) {
            return false;
        }
        $roleid = $this->account_info_lib->roleid;
        $this->load->model('role_Fun_model');
        $sfunid = $$this->role_Fun_model->getOneByWhere(array('roleid'=>$roleid,'funid'=>$funid),array('sfunid'));

        $result = false;
        if (isset($sfunid->sfunid) && $sfunid->sfunid) {
            $result = true;
        }
        return $result;
    }

	/**
	 * 数据展示，提供查询 分页等
	 * @access public
	 * @param strign $template  模板名称
	 * @param obj $model    数据模型
	 * @param array $like   模糊查询字段
	 * @param array $where  查询字段and 链接
	 * @param array $order  排序字段
	 * @param array $other_data 外部传入的业务data
	 * @param string  $otherwhere 额外的查询 条件输入。
	 */
	public function dataList($template,$model=null,$where=array(),$like=array(),$order = array (),$other_data=array(),$otherwhere=null){
		$this->_like($model, $like);
		$this->_where($model,$where,$otherwhere);
		$this->_order($model,$order);
		$data=$this->_list($model,$other_data);
		//echo $this->db->last_query(); exit;
		//合并外部传入的data
		$data = array_merge($other_data,$data);
		$this->load->view($template,$data);
	}
	/**
	 * 获得数据的like 查询
	 * @access public
	 * @param obj $model    数据模型
	 * @param array $like   模糊查询字段
	 */
	public function _like($model, $like = array ()) {
		if(empty($like)) return ;
		$dbfield=$fields = $this->db->list_fields($model->tableName);
		foreach ($dbfield as $key => $val) {
			if (isset ($_REQUEST[$val]) && $_REQUEST[$val] != '') {
				if (in_array($val, $like)) {
					$this->db->like($val, trim(urldecode($_REQUEST[$val])), 'both');
				}
			}
		}
		return ;
	}
	/**
	 * 获得数据的where查询
	 * @access public
	 * @param obj $model    数据模型
	 * @param array $where   模糊查询字段
	 */
	public function _where($model, $where = array (),$otherwhere=null) {
		if(empty($where)&&!$otherwhere) return ;
		$dbfield=$fields = $this->db->list_fields($model->tableName);
		//where 条件生成 
		foreach ($dbfield as $key => $val) {
			if (isset ($_REQUEST[$val]) && $_REQUEST[$val] != '') {
				if (in_array($val, $where)) {
					//print_r(array($val=>trim(urldecode($_REQUEST[$val]))));
					$this->db->where(array($val=>trim(urldecode($_REQUEST[$val]))));
				}
			}
		}
		//$otherwhere  额外查询条件生成 
		if ($otherwhere) {
			$this->db->where($otherwhere);
		}
		return ;
	}
	/**
	 * 数据排序
	 * @access public
	 * @param obj $model    数据模型
	 * @param array $order  排序字段
	 */
	public function _order($model, $order = array ()) {
		if(empty($order)) return ;
		if(!empty($order)) {
			foreach ($order as $key=>$val){
				$this->db->order_by($key,$val);
			}
		}
		return ;
	}
	/**
	 * 根据条件查询出数据
	 * @access public
	 * @param obj $model    数据模型
	 * @return mixed
	 */
	public function _list($model,$other_data) {

		//返回的数据
		$data=array();
		//获得记录总数 以共分页
		$this->db->from($model->tableName);
		$docount= clone  $this->db;
		$count=$docount->get()->num_rows();
		unset($docount);
//		if($count>0){
			$this->page->doconstruct($count,$other_data['page_offset'],'','',$this->uri->segment(2));
			//样式定制
			//            $theme='%upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%';
			$theme='%first% %upPage% %prePage%  %linkPage%  %downPage% %nextPage% %end%';
			$this->page->setConfig("theme",$theme);
			//分页跳转的时候保证查询条件
			foreach ($_GET as $key => $val) {
				if (!is_array($val)) {
					$this->page->parameter .= "$key=" . urlencode($val) . "&";
				}
			}
			//开始查询数据
			$this->db->limit($this->page->listRows,$this->page->firstRow);
			$query = $this->db->get();
			$data['list']=$query->result();
			$data['info']=$this->page->pageInfo();
			$data['page']=$this->page->show();
//		}
		if(!isset($data['list'])) $data['list']=array();
		if(!isset($data['page'])) $data['page']='';
		if(!isset($data['info'])) $data['info']='';
		//设定当前页面的url到session
		$this->session->set_userdata('_currentUrl_', current_url());
		return $data;
	}
	/**
	 * 数据插入操作
	 * @access public
	 * @param obj    $model  数据模型
	 * @param array  $field  待插入的数据
	 * @param bool   $type   执行类型
	 * @return mixed
	 */
	public function dataInsert($model,$field=null,$type=true){
		$field=$field?$field:((count($_POST)>0)?$_POST:$_GET);
		$insertData='';
		if (count($field)>0) {
			//var_dump($model->dataCreate($field)); exit;
			if (false === $model->dataCreate()){
				$this->error($model->getError());
			}else{
				//字段过滤
				$dbfield=$fields = $this->db->list_fields($model->tableName);
				foreach ($dbfield as $key => $val) {
					isset($field[$val])&&$insertData[$val]=$field[$val];
				}
				//------------------------------------------------
				$insertData['id']= md5(uniqid(rand(), true)); //uuid有问题。先采用这种试试
				$this->db->insert($model->tableName, $insertData);
				$insertId=$this->db->affected_rows();
				if ($insertId>0) {
					$this->logWrite($model,'插入记录成功');
					if($type){
						$this->success("操作成功",$this->session->userdata('_currentUrl_'));
					}else{
						return $insertData['id'];
					}
				} else {
					if($type){
						$this->error("操作失败");
					}else{
						return false;
					}
					$this->logWrite($model,'插入记录失败'. $this->error());
				}
			}
		} else {
			$this->error("非法操作,没有字段添加");
		}
	}
	/**
	 * 数据插入操作
	 * @access public
	 * @param obj    $model  数据模型
	 * @param array  $field  待更新的数据
	 * @param bool   $type   执行类型
	 * @return mixed
	 */
	public function dataUpdate($model,$field=null,$type=true,$gk="id"){
		$field=$field?$field:((count($_POST)>0)?$_POST:$_GET);
		if (count($field)>1) {
			//if (false === $model->dataCreate($field)){  //ci 验证待解决
		    if (false === true){  
				$this->error($model->getError());
			}else{
				//字段过滤
				$dbfield=$fields = $this->db->list_fields($model->tableName);
				foreach ($dbfield as $key => $val) {
					isset($field[$val])&&$updData[$val]=$field[$val];
				}
				//------------------------------------------------
				$updid=$updData[$gk];
				$this->db->where($gk,$updData[$gk]);
				unset($updData[$gk]);
				$this->db->update($model->tableName,$updData);
				$afftcount=$this->db->affected_rows();
				if ($afftcount>0) {
					$this->logWrite($model,'修改记录记录成功。id：'.$updid);
					//注意save方法返回的是影响的记录数，如果save的信息和原某条记录相同的话，会返回0
					//所以判断数据是否更新成功必须使用 '$list!== false'这种方式来判断
					if($type){
						$this->success("操作成功",$this->session->userdata('_currentUrl_'));
					}else{
						return $afftcount;
					}

				} else {
					if($type){
						if($afftcount==0){
							$this->logWrite($model,'修改记录记录失败，没有数据被更新。id：'.$field[$gk]);
							$msg="没有数据被更新";
						}else{
							$msg=null;
						}
						$this->error($msg);
					}else{
						//return $afftcount;
					}
				}
			}
		} else {
			$this->logWrite($model,'修改记录记录失败，"非法操作,没有字段更新"。id：'.$field[$gk]);
			$this->error("非法操作,没有字段更新");
		}
	}
	/**
	 * 数据删除操作
	 * @access public
	 * @param obj    $model  数据模型
	 * @param array  $field  待更新的数据
	 * @param bool   $type   执行类型
	 * @return mixed
	 */
	public function dataDelete($model,$field=null,$gk="id",$type=true){
		$field=$field?$field:((count($_POST)>0)?$_POST:$_GET);
		if (count($field)>0) {
			$this->db->where($gk,$field[$gk]);
			$this->db->delete($model->tableName);
			$afftcount=$this->db->affected_rows();
			if ($afftcount>0) {
				$this->logWrite($model,'删除记录成功。id：'.$field[$gk]);
				if($type){
					$this->success("操作成功",$this->session->userdata('_currentUrl_'));
				}else{
					return $afftcount;
				}
			} else {
				if($type){
					$this->error();
				}else{
					return false;
				}
				$this->logWrite($model,'删除记录失败。'. $this->error().'id：'.$field[$gk]);
			}
		} else {
			$this->error("非法操作");
		}
	}
	/**
	 * 错误信息提示
	 * @access public
	 * @param  string     $msg  提示信息
	 * $param  string     $url  跳转地址
	 * @return mixed
	 */
	public function error($msg=null,$url=null){
		$msg=$msg?$msg:'操作失败';
		//$url=$url?$url:$this->input->server('HTTP_REFERER');
		$data['url']=$url;
		$data['msg']=$msg;
		$data['waitSecond']=3; //跳转 等候时间
		$this->load->view("public/error",$data);
	}
	/**
	 * 成功信息提示
	 * @access public
	 * @param  string     $msg  提示信息
	 * $param  string     $url  跳转地址
	 * @return mixed
	 */
	public function success($msg=null,$url=null){
		$msg=$msg?$msg:'操作成功';
		//$url=$url?$url:$this->input->server('HTTP_REFERER');
		$data['url']=$url;
		$data['msg']=$msg;
		$data['waitSecond']=2; //跳转 等候时间
		$this->load->view("public/success",$data);
	}
	/**
	 * 数据修改
	 * @access public
	 * @param  obj        $model     模型
	 * $param  string     $template  修改数据模板
	 * @return mixed
	 */
	public function dataEdit($template=null,$model,$other_data=array()){
		$id=isset($_POST['id'])?$_POST['id']:$_GET['id'];
		$data['info']=$model->getOneByWhere(array("id"=>$id));
		//合并外部传入的data
		$data = array_merge($other_data,$data);
		$this->load->view($template,$data);
	}

	/**
	 * ajax方式 生成各种单号
	 */
	public function get_number() {
		date_default_timezone_set('PRC');
		$result = date("Ymd-His") . '-' . rand(100,999);
		$this->output->append_output($result);
	}
	/**
	 * 日志记录
	 * @access public
	 * $param  string     $msg  日志信息
	 */
	public function logWrite($model,$msg=null){
		$data['id']= md5(uniqid(rand(), true)); //uuid有问题。先采用这种试试
		$data['accountid']=$this->account_info_lib->id;
		$data['accountcode']=$this->account_info_lib->accountcode;
		$data['accountname']=$this->account_info_lib->accountname;
		$msgs="表名：".$model->tableName.'  ';
		$msgs.=$msg?$msg:'错误调用日志记录';
		$data['operatecontent']=$msgs;
		$this->db->insert('sys_operate_log', $data);
	}
}
