<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-2-28
 * Time: 下午8:30
 * 产品列表 -添加订单
 */
class product extends  Stock__Controller{
    /**
     * 传递到页面的参数载体
     * @var
     */
    private $_data;
    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('stock_lib');
        $this->load->model('stock_model');
        $this->load->model('cart_model');
        $this->load->model('color_model');
        $this->load->model('factory_model');
        $this->load->model('storehouse_model');
        /** 在继承的自定义父类，获取系统配置。 */
        $this->_data = $this->get_stock_config('0','25');
        $this->_data['page_title'] =  '系统设置';
        $this->_data['fun_path'] = "product/proList";
    }
    /**
     * 产品列表 -添加订单
     * @access public
     * @return mixed
     */
    public  function  proList(){
    	$otherwhere=array("statuskey"=>'1');
    	$like=array('barcode','title');
    	$where=array('code','factorycode','colorcode');
    	$this->db->from($this->cart_model->tableName);
		$this->db->where(array('uid'=>$this->account_info_lib->id));
    	$this->_data['carcount']=$this->db->get()->num_rows();
    	$this->_data['color']=$this->color_model->getAllByWhere();
    	$this->_data['factory']=$this->factory_model->getAllByWhere();
    	 //过滤已经在购物车的产品
    	$list=$this->cart_model->getAllByWhere(array("uid"=>$this->account_info_lib->id),array('goodsid'));
    	if($list){
    	   foreach($list as $val){
    	      $wherenotin[]=$val->goodsid;
    	   }
    	}
  	    isset($wherenotin)&&$this->db->where_not_in("id",$wherenotin);
    	$names = array('Frank', 'Todd', 'James');
        $this->dataList("product/proList",$this->stock_model,$where,$like,$order = array (),$this->_data,$otherwhere);
        //echo $this->db->last_query();
    }
    /**
     * ajax  添加产品到购物车
     * @access public
     * @return mixed
     */
    public function  addProToCart(){
    	$data['uid']=$this->account_info_lib->id;
    	$list=explode("||",trim($_GET['id']));
    	foreach($list as $val){
    	   if($val){
    	     $data['goodsid']=$val;
    	     $this->dataInsert($this->cart_model,$data,false);
    	   }
    	}
        echo 1;
    }
    /**
     * ajax  删除购物车产品
     * @access public
     * @return mixed
     */
    public function  delProToCart(){
    	$data['uid']=$this->account_info_lib->id;
    	$list=explode("||",trim($_GET['id']));
    	foreach($list as $val){
    	   if($val){
    	      $data['goodsid']=$val;
    	      $this->db->where($data);
			  $this->db->delete($this->cart_model->tableName);
			  ///echo $this->db->last_query();
    	   }
    	}
        echo 1;
    }
    /**
     * ajax  清空购物车
     * @access public
     * @return mixed
     */
    public function  delProToCartAll(){
    	$data['uid']=$this->account_info_lib->id;
    	$this->db->where($data);
	    $this->db->delete($this->cart_model->tableName);
	    //echo $this->db->last_query();
        echo 1;
    }
    public function getStorehouse($id){
        $info=$this->storehouse_model->getOneByWhere(array("id"=>$id));
        echo  $info->storehousecode;
    }
    
     /**
     * 生成订货单
     * @access public
     * @return mixed
     */
    public  function  creatOrder(){
        //修改销售单号生成方式。屏蔽原销售单号生成。
//    	date_default_timezone_set('PRC');
    	$this->_data['storehose']=$this->storehouse_model->getAllByWhere();
//		$this->_data['sellnumber']= date("Ymd-His") . '-' . rand(100,999);
		$data['list']=$this->cart_model->getProduct($this->account_info_lib->id);
		$list= array_merge($this->_data,$data);
		$this->load->view("product/creatOrder",$list);
    }
}