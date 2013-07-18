<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-2-28
 * Time: 下午8:30
 * 颜色管理
 */
class peisong  extends  Stock__Controller{
	/**
	 * 传递到页面的参数载体
	 * @var
	 */
	private $_data;
	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('stock_lib');
		$this->load->model('sell_model');
		$this->load->model('sellcont_model');
		$this->load->model('house_out_model');
		$this->load->model('sendContent_model');
		$this->load->model('sellcont_model');
		$this->load->model('send_model');
		$this->load->model('stock_model');
        $this->load->model('storehouse_model');
		/** 在继承的自定义父类，获取系统配置。 */
		$this->_data = $this->get_stock_config('0','6');
		$this->_data['page_title'] =  '配送管理';
		$this->_data['fun_path'] = "peisong/sDataList";
	}
	/**
	 * 配送首页
	 * @access public
	 * @return mixed
	 */
	public  function  sDataList(){
		$type=isset($_GET['type'])?$_GET['type']:2;
		$order = array ("createtime" => "desc");
		if($type==2){
			$otherwehre = array ("status =" => '2'); //所有的财务审核过得订单
		}else{
			$otherwehre ="(status =3 or status = 6 )";//所有的财务审核过得订单
		}
		$this->_data['type']=$type;
		$this->dataList("peisong/orderList", $this->sell_model, array (), $like = array ('sellnumber'), $order, $this->_data, $otherwehre);
	}
	/**
	 * 查看销售单的详情
	 * @access public
	 */
	public function showInfo() {
		$id = trim($_GET['id']);
		!$id && $this->error("错误调用");
		//查询订单信息
		$info['sell'] = $this->sell_model->getOneByWhere(array (
			"id" => $id
		));
		//查询关联的产品
		$prolist = $this->sellcont_model->getAllByWhere(array (
			"sellid" => $id
		), array (
			'stockid'
			));
			//查询具体产品信息
			foreach ($prolist as $val) {
				$product[] = $this->stock_model->getOneByWhere(array (
				"id" => $val->stockid
				));
			}

			$this->_data['outinfo']=$this->house_out_model->getOneByWhere(array("sellid"=>$id),array('getman','ptime'));
			isset ($product) && $info['list'] = $product;
			$datalist = array_merge($this->_data, $info);
			echo $this->load->view("peisong/showOrder", $datalist,true);
	}
	/**
	 * 查看销售单的详情
	 * @access public
	 */
	public function showBillInfo() {
		$id = trim($_GET['id']);
		!$id && $this->error("错误调用");
		//查询订单信息
		$info['sell'] = $this->sell_model->getOneByWhere(array (
			"id" => $id
		));
		//查询关联的产品
		$prolist = $this->sellcont_model->getAllByWhere(array ("sellid" => $id));

        //查询具体产品信息
        foreach ($prolist as $val) {
            $product_id[] = $val->stockid;
        }
        $this->db->where_in('id',$product_id);
        $product = $this->stock_model->getAllByWhere(array(),array(),array('code'=>'asc'));
        foreach ($product as $val) {
            foreach($prolist as $pro) {
                if ($val->id == $pro->stockid) {
                    $val->issend = $pro->issend;
                }
            }
		}

//		//查询具体产品信息
//		foreach ($prolist as $val) {
//			$billinfo = $this->stock_model->getOneByWhere(array ("id" => $val->stockid));
//			$billinfo->issend=$val->issend;
//			$product[]=$billinfo;
//		}
		isset ($product) && $info['list'] = $product;
		//查询对应的配送单
		$this->_data['sendlist']=$this->send_model->getAllByWhere(array("sellid"=>$id));

        //获取当前销售单的审核意见
        $this->load->model('finance_check_model');
        $finance_check = $this->finance_check_model->getAllByWhere(array('sellid'=>$info['sell']->id),array(),array('financetime'=>'asc'));
        $this->_data['finance_check'] = $finance_check;

		$datalist = array_merge($this->_data, $info);
		$this->load->view("peisong/showBillInfo", $datalist);
	}
	/*
	 * 配送单预处理
	 */
	public function preDoSendBill(){
		//		print_r($_POST); exit;
		$id = trim($_POST['billid']);
		!$id && $this->error("错误调用");
		//查询订单信息
		$info['sell'] = $this->sell_model->getOneByWhere(array (
			"id" => $id
		));
		//查询关联的产品
		$prolist = $this->sellcont_model->getAllByWhere(array ("sellid" => $id));
		//查询具体产品信息
		foreach ($prolist as $val) {
			if(!in_array($val->stockid, $_POST['chkitem'])) continue;
			$billinfo = $this->stock_model->getOneByWhere(array ("id" => $val->stockid));
			$billinfo->issend=$val->issend;
			$product[]=$billinfo;
		}
		isset ($product) && $info['list'] = $product;
		$datalist = array_merge($this->_data, $info);
		$this->load->view("peisong/preDoSendBill", $datalist);
	}
	/*
	 * 处理配送单
	 */
	public function DoSendBill(){
		if(!isset($_POST['sellid'])||$_POST['sellid']==''){
			$this->error("非法操作");
		}
		$_POST['sendnumber'] = date("Ymd-His") . '-' . rand(100, 999);
		$sendid=$this->dataInsert($this->send_model,$_POST,false);
		if($sendid !=""){ //配送单插入成功，处理后续操作
			$sendContent=array();//配送单内容
			$sendContent['sendid']=$sendid;
			foreach($_POST['chkitem'] as $val){
				if(!$val) continue;
				//插入配送商品到配送单内容表
				$sendContent['stockid']=$val;
				$this->dataInsert($this->sendContent_model,$sendContent,false);
				//修改销售单内产品状态
				$info=$this->sellcont_model->getOneByWhere(array("sellid"=>trim($_POST['sellid']),"stockid"=>$val));
				$sellContent['stockid']=$val;
				$sellContent['sellid']=$info->sellid;
				$sellContent['id']=$info->id;
				$sellContent['price']=$info->price;
				$sellContent['issend']=1;
				//执行更新
				$this->dataUpdate($this->sellcont_model,$sellContent,false);
				//更新库房的产品状态
				$this->dataUpdate($this->stock_model,array("id"=>$val,"statuskey"=>4,"statusvalue"=>'已配送'),false);
			}
            //更改销售单状态


			//更新订单状态
			//判断是否订单的所有的产品都配送了(原期货变为一个销售单时代码屏蔽)
			$ishave=$this->sellcont_model->getOneByWhere(array("sellid"=>trim($_POST['sellid']),"issend"=>0));
			if(!$ishave){ //已经完全配送完成了
				//查出订单信息
				$billinfo=$this->sell_model->getOneByWhere(array("id"=>trim($_POST['sellid'])));
                $this->dataUpdate($this->sell_model,array("id"=>trim($_POST['sellid']),"status"=>3),false);
//				if($billinfo->issell==1){ //现货销售
//					$this->dataUpdate($this->sell_model,array("id"=>trim($_POST['sellid']),"status"=>3),false);
//				}else{ //期货销售
//					if($billinfo->isall==0){ //期货未全部成销售商品
//						$this->dataUpdate($this->sell_model,array("id"=>trim($_POST['sellid']),"status"=>6),false);
//					}else{ //期货全部成销售商品
//						$this->dataUpdate($this->sell_model,array("id"=>trim($_POST['sellid']),"status"=>3),false);
//					}
//				}
			}
		}
		$this->success("操作已成功",site_url("peisong/showSendBillInfo?id=".$sendid));
	}
	/*
	 * 查看配送单详情
	 */
	public function showSendBillInfo(){
		$sendid = trim($_GET['id']);
		!$sendid && $this->error("错误调用");
		//查询订单信息
		$tempinfo=$this->send_model->getOneByWhere(array("id"=>$sendid));
        if(!$tempinfo) $this->error("错误调用");
        $this->_data['sendinfo']=$tempinfo;
        $id=$tempinfo->sellid;
		//查询订单信息
		$info['sell'] = $this->sell_model->getOneByWhere(array (
			"id" => $id
		));
		//查询关联的产品
		$prolist = $this->sendContent_model->getAllByWhere(array ("sendid" => $sendid));
        //查询具体产品信息
        foreach ($prolist as $val) {
            $product_id[] = $val->stockid;
        }
        $this->db->where_in('id',$product_id);
        $product = $this->stock_model->getAllByWhere(array(),array(),array('code'=>'asc'));
//		//查询具体产品信息
//		foreach ($prolist as $val) {
//			$product[] = $this->stock_model->getOneByWhere(array ("id" => $val->stockid));
//		}
        isset ($product) && $info['list'] = $product;
		$datalist = array_merge($this->_data, $info);
		$this->load->view("peisong/showSendBillInfo", $datalist);
	}

    public function update_peisong_over() {

        $id = trim($_GET['id']);
        !$id && $this->error("错误调用");
        $result = false;

        $update_sell = array(
            'id' => $id,
            'status' => 3
        );
        $num = $this->dataUpdate($this->sell_model,$update_sell,false);
        if ($num > 0) {
            $result = true;
        }
        $this->output->append_output($result);
    }

    /*
     * 获取商品的库房
     */
    public function getStorehouse($id){
        $info=$this->storehouse_model->getOneByWhere(array("id"=>$id));
        echo  $info->storehousecode;
    }
    
}