<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-2-28
 * Time: 下午8:30
 * 消息管理
 */
class notice  extends  Stock__Controller{
	/**
	 * 传递到页面的参数载体
	 * @var
	 */
	private $_data;
	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('stock_lib');
		$this->load->model('message_model','model');
		$this->load->model('files_model');

		/** 在继承的自定义父类，获取系统配置。 */
		$this->_data = $this->get_stock_config('0','36');
		$this->_data['page_title'] =  '消息管理';
		$this->_data['fun_path'] = "notice/sDataList";
	}
	/**
	 * 消息管理首页
	 * @access public
	 * @return mixed
	 */
	public  function  sDataList(){
//		var_dump($this->get_function_son(38));  exit;
		$this->dataList("notice/list",$this->model,$where=array(),$like=array(),$order = array("messagetime"=>'desc'),$this->_data)     ;
	}
	/**
	 * 显示数据添加页面
	 * @access public
	 * @return mixed
	 */
	public  function  sDataAdd(){
		$this->_data['fun_path'] = "notice/sDataAdd";
		$this->load->view("notice/add",$this->_data) ;
	}
	/**
	 * 执行消息插入
	 * @access public
	 * @return mixed
	 */
	public  function doSdataAdd(){
		$_POST['sendid']=$this->account_info_lib->id;
		$_POST['sendman']=$this->account_info_lib->accountname;
		$insid=$this->dataInsert($this->model,$_POST,false);
        if (isset($_POST['fujian'])) {
            foreach($_POST['fujian'] as $val){
                $updata['id']=$val;
                $updata['messageid']=$insid;
                $this->dataUpdate($this->files_model,$updata,false);
            }
        }

		$this->success('操作已成功',site_url('notice/sDataList'));
	}
	public function show(){
		trim($_GET['id'])||$this->error('非法操作');
		$this->_data['fujian']=$this->files_model->getAllByWhere(array("messageid"=>trim($_GET['id'])));
		$this->dataEdit("notice/show",$this->model,$this->_data);
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
			$this->dataDelete($this->model,$wh,'id',false);
			$this->_doDeleFujian($id);
		}
		echo  2;
	}
	//删除附件
	public   function _doDeleFujian($id){
		$list=$this->files_model->getAllByWhere(array("messageid"=>trim($id)));
		foreach($list as $val){
			$path=dirname(dirname(dirname(__FILE__))).'/upload/notice/'.$val->filepath;
			@unlink($path);
			$dal['id']=$val->id;
			$this->dataDelete($this->files_model,$dal,$gk="id",false);
		}
	}
	//执行下载
	public function doDownload(){
		$updinfo=$this->files_model->getOneByWhere(array("id"=>trim($_GET['id'])));
		if(!is_file(dirname(dirname(dirname(__FILE__))).'/upload/notice/'.$updinfo->filepath)){
			$this->error("文件不存在");;
		}
		$this->_download(dirname(dirname(dirname(__FILE__))).'/upload/notice/'.$updinfo->filepath,$updinfo->filename);
	}
	/*
	 * 下载文件
	 * 可以指定下载显示的文件名，并自动发送相应的Header信息
	 * 如果指定了content参数，则下载该参数的内容
	 * @static
	 * @access public
	 * @param string $filename 下载文件名
	 * @param string $showname 下载显示的文件名
	 * @param string $content  下载的内容
	 * @param integer $expire  下载内容浏览器缓存时间
	 * @return void
	 */
	public function _download ($filename, $showname='',$content='',$expire=180) {
			
		$length = filesize($filename);
		if(empty($showname)) {
			$showname = $filename;
		}
		$showname = basename($showname);
			


		if(!empty($filename)) {
			//			if(function_exists("mime_content_type")){
			//				$type = mime_content_type($filename);
			//			}else{
			//				if(function_exists("finfo_open")){
			//					$finfo    = finfo_open(FILEINFO_MIME);
			//					$type = finfo_file($finfo, $filename);
			//					finfo_close($finfo);
			//				}else{
			//					$this->error("服务器不支持下载，请检查");
			//				}
			//			}
			$tpm=explode(".",$filename);
			$type=$tpm[count($tpm)-1]; //最后一个
		}else{
			$type	 =	 "application/octet-stream";
		}
		//发送Http Header信息 开始下载
		header("Pragma: public");
		//header("Cache-control: max-age=".$expire);
		//header('Cache-Control: no-store, no-cache, must-revalidate');
		//header("Expires: " . gmdate("D, d M Y H:i:s",time()+$expire) . "GMT");
		//header("Last-Modified: " . gmdate("D, d M Y H:i:s",time()) . "GMT");
		//header("Content-Disposition: attachment; filename='$showname'");


		$encoded_filename = urlencode($showname);
		$encoded_filename = str_replace("+", "%20", $encoded_filename);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		header('Content-Type: application/octet-stream');
		//		if (preg_match("/MSIE/", $ua)) {
		//			header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
		//		} else if (preg_match("/Firefox/", $ua)) {
		header('Content-Disposition: attachment; filename*="utf8\' \'' . $showname . '"');
		//		} else {
		//			header('Content-Disposition: attachment; filename="' . $filename . '"');
		//		}


		header("Content-Length: ".$length);
		header("Content-type: ".$type);
		header('Content-Encoding: none');
		header("Content-Transfer-Encoding: binary" );
		if($content == '' ) {
			readfile($filename);
		}else {
			echo($content);
		}
		exit();
	}
    /**
     * 获取模块内操作权限
     */
    function get_function_son($funid) {
        return $this->auth_lib->role_fun_operate($funid);
    }
}