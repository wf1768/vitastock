<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lijin
 * Date: 13-2-28
 * Time: 下午8:30
 * 品牌管理
 */
class brand  extends  Stock__Controller{
    /**
     * 传递到页面的参数载体
     * @var
     */
    private $_data;
    function __construct() {

        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('stock_lib');
        $this->load->model('brand_model');

        /** 在继承的自定义父类，获取系统配置。 */
        $this->_data = $this->get_stock_config('0','1');

        $this->_data['page_title'] =  '系统设置';

        $this->_data['fun_path'] = "brand/brandList";

    }
    /**
     * 品牌管理首页
     * @access public
     * @return mixed
     */
    public  function  brandList(){
        $this->dataList("brand/list",$this->brand_model,$where=array(),$like=array(),$order = array (),$this->_data);
    }
    /**
     * 显示数据添加页面
     * @access public
     * @return mixed
     */
    public  function  brandAdd(){
         $this->load->view("brand/add",$this->_data) ;
    }
    /**
     * 执行品牌插入
     * @access public
     * @return mixed
     */
    public  function doBrandAdd(){
        $this->dataInsert($this->brand_model);
    }
    /**
     * 显示数据修改项
     * @access public
     * @return mixed
     */
    public  function brandEdit(){
         $this->dataEdit("brand/edit",$this->brand_model,$this->_data) ;
    }
    /**
     * 执行数据修改项
     * @access public
     * @return mixed
     */
    public  function doBrandEdit(){
        $this->dataUpdate($this->brand_model);
    }
    /**
     * 执行数据删除
     * @access public
     * @return mixed
     */
    public  function doBrandDel(){
       $idlist=explode(",", $this->input->post("id"));
       if(empty($idlist)){
           echo  1; //错误数据操作
           exit;
       }
       foreach($idlist as $id){
       	   $wh=array("id"=>$id);
           $this->dataDelete($this->brand_model,$wh,'id',false);
       }
       echo  2;
    }
}