<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-2-28
 * Time: 下午8:30
 * 颜色管理
 */
class saleorder extends Stock__Controller {
	private $_data;
	function __construct() {
		parent :: __construct();
		$this->load->library('form_validation');
		$this->load->library('stock_lib');
		$this->load->model('sell_model');
		$this->load->model('stock_model');
		$this->load->model('sellcont_model');
		$this->load->model('cart_model');
		$this->load->model('color_model');
		$this->load->model('factory_model');
		$this->load->model('brand_model');
		$this->load->model('storehouse_model');
		$this->load->model('billreturn_model');
		/** 在继承的自定义父类，获取系统配置。 */
		$this->_data = $this->get_stock_config('0', '25');
		$this->_data['page_title'] = '系统设置';
		$this->_data['fun_path'] = "saleorder/orderList";
	}
	/**
	 * 订单列表
	 * @access public
	 * @return mixed
	 */
	public function orderList() {
		$order = array ("createtime" => "desc");
		$otherwehre = array ("status !=" => '1');
        //获取库房
        $houses = $this->storehouse_model->getAllByWhere();
        $this->_data['houses'] = $houses;
        $storehouseid = $this->input->post('storehouseid') ? $this->input->post('storehouseid') : '';
        $this->_data['storehouseid'] = $storehouseid;
		$like = array ('sellnumber','storehouseid','clientname','checkby');
		$this->dataList("saleorder/orderList", $this->sell_model, $where = array (), $like, $order, $this->_data, $otherwehre);
			
	}
	/**
	 * 添加销售单
	 * @access public
	 */
	public function addOrder() {
        //销售单号改为1000001开始的流水。这里屏蔽原生成销售单号代码
//		date_default_timezone_set('PRC');
//		$this->_data['sellnumber'] = date("Ymd-His") . '-' . rand(100, 999);

		$this->_data['color'] = $this->color_model->getAllByWhere();
		$this->_data['factory'] = $this->factory_model->getAllByWhere();
		$this->_data['brand'] = $this->brand_model->getAllByWhere();
		$this->_data['storehose']=$this->storehouse_model->getAllByWhere();
		$this->load->view("saleorder/addOrder", $this->_data);
	}

    /**
     * 换货销售单，重新生成新销售单
     */
    public function returnCreatOrder() {
        $this->_data['fun_path'] = "saleorder/reBillList";
        //获取换销售单id
        $id = trim($_GET['id']);
        !$id && $this->error("错误调用");

        //获取旧销售单信息
        $sell = $this->sell_model->getOne($id);
        $this->_data['sell'] = $sell;

        date_default_timezone_set('PRC');
        $this->_data['storehose']=$this->storehouse_model->getAllByWhere();
        $this->_data['sellnumber']= date("Ymd-His") . '-' . rand(100,999);
        //获取原销售单的销售商品
//        $old_sell_content = $this->sellcont_model->getAllByWhere(array('sellid'=>$id));

        $old_sell_content = $this->sellcont_model->getAllByWhere(array (
            "sellid" => $id
        ), array (
            'stockid'
        ));
        //查询具体产品信息
        foreach ($old_sell_content as $val) {
            $product[] = $this->stock_model->getOneByWhere(array (
                "id" => $val->stockid
            ));
        }

        foreach ($product as $key => $val) {
            $product[$key]->storehouse = $this->getStore($val->storehouseid);
        }

        isset ($product) && $data['list'] = $product;
//        $datalist = array_merge($this->_data, $info);

//        $data['list'] = $old_sell_content;
        $list= array_merge($this->_data,$data);
        $this->load->view("saleorder/returnCreateOrder",$list);

    }
	/*
	 *  ajax  调用 查询 产品信息
	 *  dosearch
	 */
	public function dosearch() {
		$model = $this->stock_model;
		$like = array (
			'title',
            'storehouseid',
            'barcode',
			'code',
			'factorycode',
			'color'
			);
			$this->_where($model, array(), array (
			"statuskey" => 1
			));
			$this->_like($model, $like);
			$this->db->from($model->tableName);
			$query = $this->db->get();
			//echo $this->db->last_query();
			$list = $query->result_array();
			//print_r($list);
			foreach ($list as $key => $val) {
				$list[$key]['storehouse'] = $this->getStore($val['storehouseid']);
			}
			echo json_encode($list);
	}
	/**
	 * 執行添加销售单
	 * @access public
	 */
	public function doAddOrder() {
		//print_r($_POST); exit;
		//插入信息到订单表
		//获得总金额
		$total=0;

        $price = $_POST['price'];
        if ($price) {
            foreach($_POST['price'] as $key=> $val) {
                $val=$val?$val:$_POST['price2'][$key];
                $total += $val*1;
            }
        }

		$error = false;
		$_POST['totalmoney']=$total;
        $_POST['createbyid']= $this->account_info_lib->id;
        //查询分店名称
        $stroehoserinfo=$this->storehouse_model->getOneByWhere(array("id"=>$_POST['storehouseid']));
        $_POST['storehousecode']=$stroehoserinfo->storehousecode;
        $_POST['issell']=1;
		$id = $this->dataInsert($this->sell_model, $_POST, false);
		
		//echo $this->db->last_query(); exit;
		if ($id !== false) {
			//记录订单的产品
			$data['sellid'] = $id;
			if (isset ($_POST['product']))
			foreach ($_POST['product'] as $val) {
				$data['stockid'] = $val;
				$data['price']  =$_POST['price'][$val]?$_POST['price'][$val]:$_POST['price2'][$val];
				//	print_r($data); exit;
				$res = $this->dataInsert($this->sellcont_model, $data, false);
				if ($res === false) { //数据添加失败。
					//删除订单
					$this->dataDelete($this->sell_model, array (
							"id" => $id
					), "id", false);
					$this->dataDelete($this->sellcont_model, array (
							"sellid" => $id
					), "sellid", false);
					$error = true;
					break;
				} else { //重置产品的状态
                    //判断是否是自提。如果是自提的。将商品的状态修改为已配送
                    $stock = $this->stock_model->getOne($val);
                    $statuskey = 3;
                    $statusvalue = '已销售';
                    if ($stock[0]->sendtype == 1) {
                        $statuskey = 4;
                        $statusvalue = '已配送';

                        //更改自提的商品的销售单与商品关联表的商品状态issend为1
                        $update_sellcont = array(
                            'id' => $res,
                            'issend' => 1
                        );
                        $this->dataUpdate($this->sellcont_model,$update_sellcont,false);
                    }
					$upddata['id'] = $val;
					$upddata['statuskey'] = $statuskey;
					$upddata['statusvalue'] = $statusvalue;
					$this->dataUpdate($this->stock_model, $upddata, false);

				}
			}
		}
		//清空购物车
		$this->dataDelete($this->cart_model, array (
			"uid" => $this->account_info_lib->id
		), "uid", false);
		if ($error) {
			$this->error('销售单操作失败');
		} else {
//			$this->success('销售单操作成功', site_url("saleorder/orderList"));
            $this->success('销售单操作成功', site_url("saleorder/showInfo?id=").$id);
		}
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
        //获取销售单附属的配送单
        $this->load->model('send_model');
        $sends = $this->send_model->getAllBywhere(array('sellid'=>$id),array(),array('senddate'=>'asc'));
        $this->_data['sends'] = $sends;

        //获取当前销售单的审核意见
        $this->load->model('finance_check_model');
        $finance_check = $this->finance_check_model->getAllByWhere(array('sellid'=>$id),array(),array('financetime'=>'asc'));
        $this->_data['finance_check'] = $finance_check;

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
			isset ($product) && $info['list'] = $product;
			$datalist = array_merge($this->_data, $info);
			$this->load->view("saleorder/showOrder", $datalist);
	}

    /*
	 * 查看配送单详情
	 */
    public function showSendBillInfo(){
        $sendid = trim($_GET['id']);
        !$sendid && $this->error("错误调用");
        //查询订单信息
        $this->load->model('send_model');
        $tempinfo=$this->send_model->getOneByWhere(array("id"=>$sendid));
        if(!$tempinfo) $this->error("错误调用");
        $this->_data['sendinfo']=$tempinfo;
        $id=$tempinfo->sellid;
        //查询订单信息
        $info['sell'] = $this->sell_model->getOneByWhere(array (
            "id" => $id
        ));
        //查询关联的产品
        $this->load->model('sendContent_model');
        $prolist = $this->sendContent_model->getAllByWhere(array ("sendid" => $sendid));
        //查询具体产品信息
        foreach ($prolist as $val) {
            $product[] = $this->stock_model->getOneByWhere(array ("id" => $val->stockid));
        }
        isset ($product) && $info['list'] = $product;
        $datalist = array_merge($this->_data, $info);
        $this->load->view("saleorder/showSendBillInfo", $datalist);
    }

    /**
     * ajax方式获取商品的所在库房，用于页面显示
     * @param $id
     */
    public function getStorehouse($id){
        $info=$this->storehouse_model->getOneByWhere(array("id"=>$id));
        echo  $info->storehousecode;
    }
	/*
	 * 获得所在仓库
	 */
	public function getStore($storeid) {
		if (!$storeid)
		return '未设定';
		$info = $this->storehouse_model->getOneByWhere(array (
			"id" => $storeid
		), array (
			'storehousecode'
			));
			return $info->storehousecode;
	}
	/*
	 *退单
	 */
	public function billReturn() {
		$upddatab['id'] = $this->input->post('bid');
		$upddatab['status'] = 1; //退单了
		$updres = $this->dataUpdate($this->sell_model, $upddatab, false);

        //获取销售单号
        $sell = $this->sell_model->getOne(trim($this->input->post('bid')));

		if ($updres) {
//			$data['returnnumber'] = date("Ymd-His") . '-' . rand(100, 999);
            //退单号跟销售单号一致。
            $data['returnnumber'] = $sell[0]->sellnumber;
			$data['sellid'] = trim($this->input->post('bid'));
			$data['returntime'] = date("Y-m-d H:i:s");
			$data['returnmemo'] = trim($this->input->post('returnmemo'));
			$data['createby'] = $this->account_info_lib->accountname;
			$data['createbyid'] = $this->account_info_lib->id;
			$data['createtime'] = date("Y-m-d H:i:s");
			$data['checkbyid'] = $this->account_info_lib->id;
			$data['checkby'] = $this->account_info_lib->accountname;
			$data['remark'] = trim($this->input->post('remark'));
			//$this->session->set_userdata('_currentUrl_',site_url("saleorder/reBillList"));
			$res = $this->dataInsert($this->billreturn_model, $data, false);
			if ($res) {
				//重置产品状态为 在库
				$prolist = $this->sellcont_model->getAllByWhere(array (
					"sellid" => $this->input->post('bid'
					)), array (
					'stockid'
					));
					//查询具体产品信息
					foreach ($prolist as $val) {
						$upddata['id'] = $val->stockid;
						$upddata['statuskey'] = 1;
						$upddata['statusvalue'] = '在库';
						$this->dataUpdate($this->stock_model, $upddata, false);
					}
			}
			$this->success("操作成功", site_url("saleorder/reBillList"));
		} else {
			$this->error("操作失败");
		}

	}
	/*
	 * 销售单退订列表
	 */
	public function reBillList() {
		$this->_data['fun_path'] = "saleorder/reBillList";
		$order = array (
			"createtime" => "desc"
			);
			$this->dataList("saleorder/reBillList", $this->billreturn_model, $where = array (), $like = array ('returnnumber'), $order, $this->_data);
	}
	/*
	 * 退订列表
	 */
	public function showReturnOrder() {
		$this->_data['fun_path'] = "saleorder/reBillList";
		$id = $this->input->get('id');
		if (!$id)
		$this->error("非法调用");
		$info['rebill'] = $this->billreturn_model->getOneByWhere(array (
			"id" => $id
		));
		$id = $info['rebill']->sellid;
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
			//$sql = 'SELECT count(*) count FROM `e_sell` s ,e_sell_content c where s.id=c.sellid  and s.createtime  like "' . $data . '%"';
			$sql = 'SELECT count(*) count FROM `e_sell` s ,e_sell_content c where s.id=c.sellid';
			$res = $this->db->query($sql)->result();
			$ydata[] = intval($res[0]->count);
			isset ($product) && $info['list'] = $product;
			$datalist = array_merge($this->_data, $info);
			$this->load->view("saleorder/showReturnOrder", $datalist);
	}
	/*
	 * 财务审核
	 */
	public function cwCheck() {
		$this->_data = $this->get_stock_config('0','5');
		$this->_data['page_title'] =  '财务审核';
		$this->_data['fun_path'] = "saleorder/cwCheck?type=2";
		$order = array ("createtime" => "desc");

        $this->load->model('storehouse_model');
        $storehouse = $this->storehouse_model->getAllByWhere();
        $this->_data['storehouse'] = $storehouse;

		if(isset($_GET['type'])){
			if($_GET['type']==0){
//				$otherwehre = "status !=1 and status != 3";
                $otherwehre = "financestatus =1 and status !=0";
			}elseif($_GET['type']==1){
//				$otherwehre = array ("status" => '2');
                $otherwehre = "financestatus =0 and status != 0";
			}elseif($_GET['type']==2){
				$otherwehre = array ("status" => '0');
			}
		}else{
			$otherwehre = array ("status" => '0');
		}
        $this->_data['type'] = $_GET['type'];
		$this->dataList("saleorder/cwCheck", $this->sell_model, $where = array ('storehouseid'), $like = array ('sellnumber','clientname'), $order, $this->_data, $otherwehre);
	}
	/*
	 * 执行财务审核
	 */
	public function doCwCheck(){
        $result = false;
		$id=trim($_GET['id']);
        $type=trim($_GET['type']);
		!$id && $this->error('错误调用');
		$data['id']=$id;
		$data['status']=2;
		$num = $this->dataUpdate($this->sell_model,$data,false);
        if ($num) {
            $result = true;
        }
        $this->output->append_output($result);
	}
	public function shCwCheck(){

		
		$this->_data = $this->get_stock_config('0','5');
		$this->_data['page_title'] =  '财务审核';
		$this->_data['fun_path'] = "saleorder/cwCheck?type=2";

        $type = $_GET['type'];
        $this->_data['type'] = $type;
		
		$id = trim($_GET['id']);
		!$id && $this->error("错误调用");
		//查询订单信息
		$info['sell'] = $this->sell_model->getOneByWhere(array (
			"id" => $id
		));

        //获取当前销售单的审核意见
        $this->load->model('finance_check_model');
        $finance_check = $this->finance_check_model->getAllByWhere(array('sellid'=>$info['sell']->id),array(),array('financetime'=>'asc'));
        $this->_data['finance_check'] = $finance_check;
		//查询关联的产品
		$prolist = $this->sellcont_model->getAllByWhere(array ("sellid" => $id), array ('stockid','price'));
		//查询具体产品信息
		foreach ($prolist as $val) {
			$product[] = $this->stock_model->getOneByWhere(array ("id" => $val->stockid));
			$salprice[$val->stockid]=$val->price;
		}
		isset ($product) && $info['list'] = $product;
		isset ($salprice) && $info['salprice'] = $salprice;
		$datalist = array_merge($this->_data, $info);
		$this->load->view("saleorder/shCwCheck", $datalist);
	}
}