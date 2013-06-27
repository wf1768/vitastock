<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-2-28
 * Time: 下午8:30
 * 颜色管理
 */
class color  extends  Stock__Controller{
    /**
     * 传递到页面的参数载体
     * @var
     */
    private $_data;
    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('stock_lib');
        $this->load->model('color_model');

        /** 在继承的自定义父类，获取系统配置。 */
        $this->_data = $this->get_stock_config('0','1');

        $this->_data['page_title'] =  '系统设置';

        $this->_data['fun_path'] = "color/sDataList";

    }
    /**
     * 颜色管理首页
     * @access public
     * @return mixed
     */
    public  function  sDataList(){
        $this->dataList("color/list",$this->color_model,$where=array(),$like=array(),$order = array (),$this->_data)     ;
    }
    /**
     * 显示数据添加页面
     * @access public
     * @return mixed
     */
    public  function  sDataAdd(){
         $this->load->view("color/add",$this->_data) ;
    }
    /**
     * 执行颜色插入
     * @access public
     * @return mixed
     */
    public  function doSdataAdd(){
        $this->dataInsert($this->color_model);
    }
    /**
     * 显示数据修改项
     * @access public
     * @return mixed
     */
    public  function sDataEdit(){
         $this->dataEdit("color/edit",$this->color_model,$this->_data) ;
    }
    /**
     * 执行数据修改项
     * @access public
     * @return mixed
     */
    public  function doSdaatEdit(){
        $this->dataUpdate($this->color_model);
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
           $this->dataDelete($this->color_model,$wh,'id',false);
       }
       echo  2;
    }
}