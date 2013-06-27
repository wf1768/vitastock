<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-2
 * Time: 下午9:04
 * 厂家管理
 */
class factory extends  Stock__Controller{
    /**
     * 传递到页面的参数载体
     * @var
     */
    private $_data;
    function __construct() {

        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('stock_lib');
        $this->load->model('factory_model');

        /** 在继承的自定义父类，获取系统配置。 */
        $this->_data = $this->get_stock_config('0','1');

        $this->_data['page_title'] =  '系统设置';

        $this->_data['fun_path'] = "factory/factoryList";

    }
 /**
     * 品牌管理首页
     * @access public
     * @return mixed
     */
    public  function  factoryList(){
        $this->dataList("factory/list",$this->factory_model,$where=array(),$like=array(),$order = array (),$this->_data)     ;
    }
    /**
     * 显示数据添加页面
     * @access public
     * @return mixed
     */
    public  function factoryAdd(){
         $this->load->view("factory/add",$this->_data) ;
    }
    /**
     * 执行品牌插入
     * @access public
     * @return mixed
     */
    public  function doFactoryAdd(){
        $this->dataInsert($this->factory_model);
    }
    /**
     * 显示数据修改项
     * @access public
     * @return mixed
     */
    public  function factoryEdit(){
         $this->dataEdit("factory/edit",$this->factory_model,$this->_data) ;
    }
    /**
     * 执行数据修改项
     * @access public
     * @return mixed
     */
    public  function doFactoryEdit(){
        $this->dataUpdate($this->factory_model);
    }
    /**
     * 执行数据删除
     * @access public
     * @return mixed
     */
    public  function doFactoryDel(){
       $idlist=explode(",", $this->input->post("id"));
       if(empty($idlist)){
           echo  1; //错误数据操作
           exit;
       }
       foreach($idlist as $id){
       	   $wh=array("id"=>$id);
           $this->dataDelete($this->factory_model,$wh,'id',false);
       }
       echo  2;
    }
}