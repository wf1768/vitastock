<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-2-28
 * Time: 下午8:30
 * 操作日志
 */
class optlog  extends  Stock__Controller{
    /**
     * 传递到页面的参数载体
     * @var
     */
    private $_data;
    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('stock_lib');
        $this->load->model('operatelog_model');
        /** 在继承的自定义父类，获取系统配置。 */
        $this->_data = $this->get_stock_config('0','1');
        $this->_data['page_title'] =  '系统设置';
        $this->_data['fun_path'] = "optlog/sDataList";
    }
    /**
     * 操作日志管理首页
     * @access public
     * @return mixed
     */
    public  function  sDataList(){
        $this->dataList("optlog/list",$this->operatelog_model,$where=array(),$like=array(),array("operatetime"=>'desc'),$this->_data);
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
           $this->dataDelete($this->operatelog_model,$wh,'id',false);
       }
       echo  2;
    }
}