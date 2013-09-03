<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * stock Login Controller Class
 *
 * 基于ci的商品管理系统1
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
 * apply Controller Class
 *
 * 期货订单 控制器。
 *
 * @package		apply
 * @subpackage	Controller
 * @category	Controller
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class apply extends Stock__Controller {

	/**
	 * 传递到页面的参数载体
	 * @var
	 */
	private $_data;

	/**
	 * 构造函数
	 */
	function __construct() {

		parent::__construct();
		$this->load->model('apply_model');
		$this->load->model('apply_content_model');
		$this->load->model('b_buy_model');
        $this->load->model('buy_product_model');
		$this->load->model('apply_deal_model');
		$this->load->model('apply_status_model');
        $this->load->model('apply_content_status_model');
        $this->load->model('apply_content_deal_model');

        $this->load->model('storehouse_model');
        $this->load->model('factory_model');
        $this->load->model('brand_model');
        $this->load->model('commodityType_model');

        $this->load->model('stock_model');

		$this->load->library('auth_lib');

        //获取当前stype
        /**
         * url中 stype参数的意义：
         * apply        期货订货模块操作.
         * sale         销售的订货模块
         * financial    财务的订货模块
         */
        $stype = isset($_GET['stype']) ? $_GET['stype'] : '';


        $oper = false;
		/**
         * 在继承的自定义父类，获取系统配置。
         * 判断当前的stype 引导到不同菜单模块
         */
		if($stype == 'apply'){
			$this->_data = $this->get_stock_config('0','13');
            $this->_data['page_title'] =  '采购、期货管理';
            $this->_data['fun_path'] = "apply?stype=apply";
            $oper = $this->auth_lib->role_fun_operate('29');
//            $this->_data['stype'] = '?stype=apply';
        }else if ($stype == 'sale'){
			$this->_data = $this->get_stock_config('0','25');
            $this->_data['page_title'] =  '销售期货申请';
            $this->_data['fun_path'] = "apply?stype=sale";
            $oper = $this->auth_lib->role_fun_operate('3');
//            $this->_data['stype'] = '?stype=slae';
		}
        else if ($stype == 'financial') {
            $this->_data = $this->get_stock_config('0','5');
            $this->_data['page_title'] =  '财务期货审核';
            $this->_data['fun_path'] = "apply?stype=financial";
            $oper = $this->auth_lib->role_fun_operate('30');
//            $this->_data['stype'] = '?stype=financial';
        }
        else {

        }
        $this->_data['stype'] = $stype;

        //帐户或组的期货订单模块内操作权限
//        if(isset($_GET['stype'])){
//            $oper = $this->auth_lib->role_fun_operate('3');
//        }else{
//            $oper = $this->auth_lib->role_fun_operate('29');
//        }

        $this->_data['oper'] = $oper;
		
		
//        if(isset($_GET['stype'])){
//           $this->_data['stype']='?stype=sale';
//        }else{
//           $this->_data['stype']='';
//        }
	}

	public function index() {
        if($_GET['stype'] == 'sale'){
            redirect('apply/pages?status=1&stype=sale');
        }else if($_GET['stype'] == 'apply'){
            redirect('apply/pages?status=1&stype=apply');
        }else if($_GET['stype'] == 'financial'){
            redirect('apply/pages?status=1&stype=financial');
        }
        else {

        }
	}

	/**
	 * 按状态，显示单列表
	 */
	public function pages() {

		$status = $this->input->get('status') ? $this->input->get('status') : 1;
		$p = $this->input->get('p') ? $this->input->get('p') : 1;
		$applynumber = $this->input->get('applynumber') ? $this->input->get('applynumber') : '';

        $storehouse = $this->storehouse_model->getAllByWhere();
        $this->_data['storehouse'] = $storehouse;


        $this->_data['p'] = $p;
		$this->_data['status'] = $status;

		$order = array('createtime'=>'desc');
        if ($status == 1) {
            $otherwhere = 'status = 1 or status = 2 or status = 3';
        }
        else if ($status == 2) {
            $otherwhere = 'status = 4';
        }
        else if ($status == 4) {
            $otherwhere = 'status = 5';
        }
        else if ($status == 3) {
            $otherwhere = '';
//            unset($order);
//            $order = array('status' => 'asc','createtime'=>'desc');
        }
        else {
            $otherwhere = 'status = 1 or status = 2 or status = 3';
        }
//        $otherwhere = 'status = '.$status;

        //如果是销售管理中的订单功能，不管什么状态的，只取当前帐户的信息。
//        if ($this->_data['stype'] == 'sale') {
//            $otherwhere = $otherwhere.' and createbyid=\''.$this->account_info_lib->id.'\'';
//        }

//        if ($status == 5) {
//            $otherwhere = '';
//            if ($this->_data['stype'] == 'sale') {
//                $otherwhere = 'createbyid = \''.$this->account_info_lib->id.'\'';
//            }
//            else {
//                $otherwhere = '';
//            }
//
//            unset($order);
//            $order = array('status' => 'asc','createtime'=>'desc');
//        }

		$this->_data['search'] = $applynumber;

		$this->dataList('buy/apply_list',$this->apply_model,array('storehouseid',),array('applynumber','clientname','applyby','remark'),$order,$this->_data,$otherwhere);
	}

	/**
	 * 添加订单
	 */
	public function add() {
		//读取验证
		$this->apply_model->_validate();
		if($this->apply_model->form_validation->run() === FALSE) {

            $houses = $this->storehouse_model->getAllByWhere();
            $this->_data['houses'] = $houses;

            $factorys = $this->factory_model->getAllByWhere();
            $this->_data['factorys'] = $factorys;
            $brands = $this->brand_model->getAllByWhere();
            $this->_data['brands'] = $brands;
            $commodittypes = $this->commodityType_model->getAllByWhere();
            $this->_data['comtypes'] = $commodittypes;

			$this->load->view('buy/apply_add',$this->_data);
		}
		else {
			//执行inset
			$this->insert_apply();
		}
	}

    /**
     * 修改期货订单
     */
    public function  edit() {
        $id = $this->input->get('id') ? $this->input->get('id') : '';
        //根据期货订单id，获取订单下订单商品。
        //获取订单商品信息
		$apply_content = array();
		if (!empty($id)) {
            $apply_content = $this->apply_content_model->getAllByWhere(array('applyid'=>$id),array(),array('factoryname'=>'asc'));
        }
		$this->_data['apply_content'] = $apply_content;

        $houses = $this->storehouse_model->getAllByWhere();
        $this->_data['houses'] = $houses;

        $factorys = $this->factory_model->getAllByWhere();
        $this->_data['factorys'] = $factorys;
        $brands = $this->brand_model->getAllByWhere();
        $this->_data['brands'] = $brands;
        $commodittypes = $this->commodityType_model->getAllByWhere();
        $this->_data['comtypes'] = $commodittypes;

        $this->dataEdit("buy/apply_edit",$this->apply_model,$this->_data) ;
    }

    public function doupdate() {
        $this->dataUpdate($this->apply_model,null,false);
        $this->success(null,site_url().'/apply/pages?status=1&stype=sale');
    }

	/**
	 * 保存订单
	 */
	public  function insert_apply() {
		/** 获取表单数据 */
		$content = $this->_get_form_data();

		$insert_apply = array(
            'applynumber'           =>  empty($content['applynumber']) ? '' : $content['applynumber'],
            'storehouseid'           =>  empty($content['storehouseid']) ? '' : $content['storehouseid'],
            'selldate'              =>  empty($content['selldate']) ? '' : $content['selldate'],
            'totalmoney'              =>  empty($content['totalmoney']) ? '' : $content['totalmoney'],
            'discount'              =>  empty($content['discount']) ? '' : $content['discount'],
            'paymoney'              =>  empty($content['paymoney']) ? '' : $content['paymoney'],
            'lastmoney'              =>  empty($content['lastmoney']) ? '' : $content['lastmoney'],
            'clientname'            =>  empty($content['clientname']) ? '' : $content['clientname'],
            'clientphone'            =>  empty($content['clientphone']) ? '' : $content['clientphone'],
            'clientadd'            =>  empty($content['clientadd']) ? '' : $content['clientadd'],
            'applyby'               =>  empty($content['applyby']) ? '' : $content['applyby'],
            'applydate'             =>  empty($content['applydate']) ? '' : $content['applydate'],
            'commitgetdate'         =>  empty($content['commitgetdate']) ? '' : $content['commitgetdate'],
            'email'                 =>  empty($content['email']) ? '' : $content['email'],
            'remark'                =>  empty($content['remark']) ? '' : $content['remark']
		);

        //销售店
        $house = $this->storehouse_model->getOne($insert_apply['storehouseid']);
        if ($house) {
            $insert_apply['storehousecode'] = $house[0]->storehousecode;
        }
        else {
            $insert_apply['storehousecode'] = '';
        }
		//创建时间
		$insert_apply['createtime'] = date('Y-m-d H:i:s',now());
		//创建者id
		$insert_apply['createbyid'] = $this->account_info_lib->id;
		//创建者姓名
		$insert_apply['createby'] = $this->account_info_lib->accountname;
		//处理人姓名
		$insert_apply['checkby'] = '';
		//处理id
		$insert_apply['checkbyid'] = '';
		//状态key sys_apply_status表记录着状态的代码值
		$insert_apply['status'] = '1';  //待审核
        //状态value
        $insert_apply['statusvalue'] = '待审核';

		$newid = $this->dataInsert($this->apply_model,$insert_apply,false);

		//处理订货商品信息

		if ($newid) {
			$apply_content = $this->input->post('apply_content_json',TRUE);
			if ($apply_content) {
				require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
				$json = new Services_JSON();
				$output = $json->decode($apply_content);

				try {
					foreach ($output as $row) {
						$row = (array)$row;
						$insert_row = array();
						$insert_row['applyid']    = $newid;
                        $insert_row['title']  = empty($row['title']) ? '' : $row['title'];
                        $insert_row['code']  = empty($row['code']) ? '' : $row['code'];
                        $insert_row['factoryid']  = empty($row['factoryid']) ? '' : $row['factoryid'];
						$insert_row['brandid']    = empty($row['brandid']) ? '' : $row['brandid'];
                        $insert_row['typeid']     = empty($row['typeid']) ? '' : $row['typeid'];
                        $insert_row['color']      = empty($row['color']) ? '' : $row['color'];
                        $insert_row['memo']       = empty($row['memo']) ? '' : $row['memo'];
						$insert_row['number']     = empty($row['number']) ? '' : $row['number'];
						$insert_row['salesprice'] = empty($row['salesprice']) ? '' : $row['salesprice'];
                        $insert_row['remark']     = empty($row['remark']) ? '' : $row['remark'];

                        $factorys = $this->factory_model->getAllByWhere();
                        $this->_data['factorys'] = $factorys;
                        $brands = $this->brand_model->getAllByWhere();
                        $this->_data['brands'] = $brands;
                        $commodittypes = $this->commodityType_model->getAllByWhere();
                        $this->_data['comtypes'] = $commodittypes;

                        //获取厂家信息、品牌信息、商品类别信息
                        if ($insert_row['factoryid']) {
                            $factory = $this->factory_model->getOne($insert_row['factoryid']);
                            $insert_row['factorycode'] = $factory[0]->factorycode;
                            $insert_row['factoryname'] = $factory[0]->factoryname;
                        }
                        if ($insert_row['brandid']) {
                            $brand = $this->brand_model->getOne($insert_row['brandid']);
                            $insert_row['brandcode'] = $brand[0]->brandcode;
                            $insert_row['brandname'] = $brand[0]->brandname;
                        }
                        if ($insert_row['typeid']) {
                            $type = $this->commodityType_model->getOne($insert_row['typeid']);
                            $insert_row['typecode'] = $type[0]->typecode;
                            $insert_row['typename'] = $type[0]->typename;
                        }

						$this->dataInsert($this->apply_content_model,$insert_row,false);
					}
				} catch (Exception $e) {
					$this->error('保存数据失败，请重新尝试或与管理员联系。','apply/pages?status=0'.str_replace('?', '&',$this->_data['stype']));
                    return;
				}
			}
		}

        //处理订单进度表
        $insert_apply_deal = array(
            'applyid'               =>  $newid,
            'dealtime'              =>  $insert_apply['createtime'],
            'dealby'                =>  $insert_apply['createby'],
            'dealbyid'              =>  $insert_apply['createbyid'],
            'remark'                =>  '提交期货订单',
            'dealstatuskey'         =>  '1',
            'dealstatusvalue'       =>  '待审核'

        );

        $tmpid = $this->dataInsert($this->apply_deal_model,$insert_apply_deal,false);

		//返回
//        $this->success(null,site_url().'/apply/pages?status=1&stype='.$this->_data['stype']);
		$this->success(null,site_url().'/apply/show?id='.$newid.'&stype='.$this->_data['stype']);
	}


    //ajax方式，显示订单商品详细
    public function show_apply_content() {
        $result = false;
        $id = $this->input->get('id') ? $this->input->get('id') : '';

        if (empty($id)) {
            $this->output->append_output($result);
            return;
        }
        $row = $this->apply_content_model->getOne($id);
        echo json_encode($row);
//        $this->output->append_output($result);
    }

    //ajax方式，显示入库商品详细
    public function show_stock_content() {
        $result = false;
        $id = $this->input->get('id') ? $this->input->get('id') : '';

        if (empty($id)) {
            $this->output->append_output($result);
            return;
        }
        $row = $this->stock_model->getOne($id);

        //获取库房
        $storehouse = $this->storehouse_model->getOne($row[0]->storehouseid);
        $row[0]->storehouse = $storehouse[0]->storehousecode;
        echo json_encode($row);
    }

    /**
     * 删除期货商品。在订单修改里，物理删除期货商品
     */
    public function delete_apply_content() {
        $result = false;
        $id = $this->input->post('id') ? $this->input->post('id') : '';


        if (empty($id)) {
            $this->output->append_output($result);
            return;
        }
        $result = $this->dataDelete($this->apply_content_model,array('id'=>$id),'id',false);
        $this->output->append_output($result);
    }

    /**
     * ajax获取期货商品。修改订单时，修改订单商品
     */
    public function read_apply_content() {
        $result = false;
        $id = $this->input->post('id') ? $this->input->post('id') : '';


        if (empty($id)) {
            $this->output->append_output($result);
            return;
        }

        $apply_content = $this->apply_content_model->getOne($id);

        require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
        $json = new Services_JSON();
        $row = $json->encode($apply_content);

        $this->output->append_output($row);
    }

    /**
     * 修改期货商品。在订单修改里，物理修改保存期货商品
     */
    public function edit_save_apply_content() {
        $result = false;
        $apply_content = $this->input->post('apply_content_json',TRUE);
        if ($apply_content) {
            require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
            $json = new Services_JSON();
            $row = $json->decode($apply_content);

            try {
                $row = (array)$row;
                $update_row = array();
                $update_row['id']    = empty($row['id']) ? '' : $row['id'];
                $update_row['title']  = empty($row['title']) ? '' : $row['title'];
                $update_row['code']  = empty($row['code']) ? '' : $row['code'];
                $update_row['factoryid']  = empty($row['factoryid']) ? '' : $row['factoryid'];
                $update_row['brandid']    = empty($row['brandid']) ? '' : $row['brandid'];
                $update_row['typeid']     = empty($row['typeid']) ? '' : $row['typeid'];
                $update_row['color']      = empty($row['color']) ? '' : $row['color'];
                $update_row['memo']       = empty($row['memo']) ? '' : $row['memo'];
                $update_row['number']     = empty($row['number']) ? '' : $row['number'];
                $update_row['salesprice'] = empty($row['salesprice']) ? '' : $row['salesprice'];
                $update_row['remark']     = empty($row['remark']) ? '' : $row['remark'];

                $factorys = $this->factory_model->getAllByWhere();
                $this->_data['factorys'] = $factorys;
                $brands = $this->brand_model->getAllByWhere();
                $this->_data['brands'] = $brands;
                $commodittypes = $this->commodityType_model->getAllByWhere();
                $this->_data['comtypes'] = $commodittypes;

                //获取厂家信息、品牌信息、商品类别信息
                if ($update_row['factoryid']) {
                    $factory = $this->factory_model->getOne($update_row['factoryid']);
                    $update_row['factorycode'] = $factory[0]->factorycode;
                    $update_row['factoryname'] = $factory[0]->factoryname;
                }
                if ($update_row['brandid']) {
                    $brand = $this->brand_model->getOne($update_row['brandid']);
                    $update_row['brandcode'] = $brand[0]->brandcode;
                    $update_row['brandname'] = $brand[0]->brandname;
                }
                if ($update_row['typeid']) {
                    $type = $this->commodityType_model->getOne($update_row['typeid']);
                    $update_row['typecode'] = $type[0]->typecode;
                    $update_row['typename'] = $type[0]->typename;
                }

                $num = $this->dataUpdate($this->apply_content_model,$update_row,false);

                if ($num) {
                    $result = true;
                }
                $this->output->append_output($result);
            } catch (Exception $e) {
                $this->output->append_output($result);
                return;
            }
        }
    }

    /**
     * 添加期货商品。在订单修改里，物理增加期货商品
     */
    public function add_apply_content() {
        $result = false;
        $apply_content = $this->input->post('apply_content_json',TRUE);
        if ($apply_content) {
            require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
            $json = new Services_JSON();
            $row = $json->decode($apply_content);

            try {
                $row = (array)$row;
                $insert_row = array();
                $insert_row['applyid']    = empty($row['applyid']) ? '' : $row['applyid'];
                $insert_row['title']  = empty($row['title']) ? '' : $row['title'];
                $insert_row['code']  = empty($row['code']) ? '' : $row['code'];
                $insert_row['factoryid']  = empty($row['factoryid']) ? '' : $row['factoryid'];
                $insert_row['brandid']    = empty($row['brandid']) ? '' : $row['brandid'];
                $insert_row['typeid']     = empty($row['typeid']) ? '' : $row['typeid'];
                $insert_row['color']      = empty($row['color']) ? '' : $row['color'];
                $insert_row['memo']       = empty($row['memo']) ? '' : $row['memo'];
                $insert_row['number']     = empty($row['number']) ? '' : $row['number'];
                $insert_row['salesprice'] = empty($row['salesprice']) ? '' : $row['salesprice'];
                $insert_row['remark']     = empty($row['remark']) ? '' : $row['remark'];

                $factorys = $this->factory_model->getAllByWhere();
                $this->_data['factorys'] = $factorys;
                $brands = $this->brand_model->getAllByWhere();
                $this->_data['brands'] = $brands;
                $commodittypes = $this->commodityType_model->getAllByWhere();
                $this->_data['comtypes'] = $commodittypes;

                //获取厂家信息、品牌信息、商品类别信息
                if ($insert_row['factoryid']) {
                    $factory = $this->factory_model->getOne($insert_row['factoryid']);
                    $insert_row['factorycode'] = $factory[0]->factorycode;
                    $insert_row['factoryname'] = $factory[0]->factoryname;
                }
                if ($insert_row['brandid']) {
                    $brand = $this->brand_model->getOne($insert_row['brandid']);
                    $insert_row['brandcode'] = $brand[0]->brandcode;
                    $insert_row['brandname'] = $brand[0]->brandname;
                }
                if ($insert_row['typeid']) {
                    $type = $this->commodityType_model->getOne($insert_row['typeid']);
                    $insert_row['typecode'] = $type[0]->typecode;
                    $insert_row['typename'] = $type[0]->typename;
                }

                $tmpid = $this->dataInsert($this->apply_content_model,$insert_row,false);

                if ($tmpid) {
                    $result = true;
                }
                $this->output->append_output($result);
            } catch (Exception $e) {
                $this->output->append_output($result);
                return;
            }
        }
    }

	/**
	 * 删除期货订单
	 */
	public function remove() {
		$result = false;
		$ids = $this->input->post('id') ? $this->input->post('id') : '';

		$ids = explode(',',$ids);

		if (empty($ids)) {
			$this->output->append_output($result);
            return;
		}
		foreach ($ids as $id) {
            //删除订单商品处理进度
            $this->dataDelete($this->apply_content_deal_model,array('applyid'=>$id),'applyid',false);
			//删除订单商品
			$this->dataDelete($this->apply_content_model,array('applyid'=>$id),'applyid',false);
			//删除订单处理意见
			$this->dataDelete($this->apply_deal_model,array('applyid'=>$id),'applyid',false);
            //TODO 删除订单与入库商品的关联
            $this->load->model('apply_stock_model');
            $this->dataDelete($this->apply_stock_model,array('applyid'=>$id),'applyid',false);
			//删除订单
			$result = $this->dataDelete($this->apply_model,array('id'=>$id),'id',false);
		}
		$this->output->append_output($result);
	}

    /**
     * 移除入库商品与期货订单的关联。并将商品状态修改为［在库］
     */
    public function remove_stock() {
        $result = false;
        $id = $_POST['id'];
        !$id && $this->error("错误调用");

        //删除订单与商品的关联
        $this->load->model('apply_stock_model');
        $this->dataDelete($this->apply_stock_model,array('stockid'=>$id),'stockid',false);

        //修改商品状态
        $update_stock = array(
            'id' => $id,
            'statuskey' =>  1,
            'statusvalue' =>    '在库'
        );
        $num = $this->dataUpdate($this->stock_model,$update_stock,false);

        if ($num > 0) {
            $result = true;
        }

        $this->output->append_output($result);
    }

    public function add_stock() {
        $result = false;
        $barcode = $_POST['barcode'];
        $applyid = $_POST['applyid'];
        !$barcode && $this->error("错误调用");

        //获取barcode的商品对象
        $stock = $this->stock_model->getOneByWhere(array('barcode'=>$barcode));

        if (!$stock) {
            $this->output->append_output($result);
            return;
        }

        //判断商品是否已经在与期货订单关联。
        $this->load->model('apply_stock_model');
        $row = $this->apply_stock_model->getOneByWhere(array('stockid'=>$stock->id,'applyid'=>$applyid));

        if ($row) {
            $this->output->append_output($result);
            return;
        }

        if ($stock->statuskey != 1) {
            $this->output->append_output($result);
            return;
        }

        //将商品与期货订单关联
        $insert_apply_stock = array(
            'applyid' => $applyid,
            'stockid' => $stock->id
        );


        $this->dataInsert($this->apply_stock_model,$insert_apply_stock,false);

        //修改商品状态为10 期货待销售
        $update_stock = array(
            'id' => $stock->id,
            'statuskey' =>  10,
            'statusvalue' => '期货待销售'
        );
        $num = $this->dataUpdate($this->stock_model,$update_stock,false);

        if ($num > 0) {
            $result = true;
        }

        $this->output->append_output($result);
    }

	/**
	 * 显示订单
	 */
	public function show() {
		$id = $this->input->get('id') ? $this->input->get('id') : '';
		//获得page（当前页数）、search（查询值），为返回采购单列表时，作为显示参数
		$p = $this->input->get('p') ? $this->input->get('p') : 1;
        $status = $this->input->get('status') ? $this->input->get('status') : 1;

		$search = $this->input->get('search') ? $this->input->get('search') : '';

        $stype = $_GET['stype'];
        $this->_data['stype'] = $stype;

		//获取订单信息
		$query = false;
		if (!empty($id)) {
			$query = $this->apply_model->getOne($id);
		}

		$this->_data['row'] = $query;
		$this->_data['p'] = $p;
        $this->_data['status'] = $status;
		$this->_data['search'] = $search;

		//如果订单已经生成采购单。获取采购单显示
		$buys = $this->b_buy_model->getAllByWhere(array('applyid'=>$query[0]->id));;
		$this->_data['buys'] = $buys;

        //获取订单生成的销售合同单
        $this->load->model('sell_model');
        $sells = $this->sell_model->getAllByWhere(array('applyid'=>$id));
        $this->_data['sells'] = $sells;

        //获取当前期货订单的审核意见
        $this->load->model('finance_check_model');
        $finance_check = $this->finance_check_model->getAllByWhere(array('sellid'=>$id),array(),array('financetime'=>'asc'));
        $this->_data['finance_check'] = $finance_check;

		//获取订单商品信息
		$apply_content = array();
		if (!empty($id)) {
			$apply_content = $this->apply_content_model->getAllByWhere(array('applyid'=>$id),array(),array('factoryname'=>'asc'));
		}
		$this->_data['apply_content'] = $apply_content;

		//获取订单处理进度
		$apply_deal = array();
		if (!empty($id)) {
			$apply_deal = $this->apply_deal_model->getAllByWhere(array('applyid'=>$id),array(),array('dealstatuskey'=>'asc'));
		}
		$this->_data['apply_deal'] = $apply_deal;

        //获取订单的商品，入库后的真实商品
        $this->load->model('apply_stock_model');
        $apply_stock = $this->apply_stock_model->getAllByWhere(array('applyid'=>$id));
        $stock = false;
        if ($apply_stock) {
            $ids = Common::array_flatten($apply_stock, 'stockid');
            $this->db->where_in('id',$ids);
            $stock = $this->stock_model->getAllByWhere();
        }
        $this->_data['stock'] = $stock;

        $storehouse = $this->storehouse_model->getAllByWhere();
        $this->_data['storehouse'] = $storehouse;


        //获取商品处理进度状态码
        $this->_data['apply_content_status'] = $this->apply_content_status_model->getAllByWhere(array(),array(),array('skey' => 'asc'));
		$this->load->view('buy/apply_show',$this->_data);
	}

	/**
	 * 处理订单
	 */
	public function add_apply_deal() {
        $key = $this->input->post('key') ? $this->input->post('key') : '';
		$result = false;

        //处理订单进度表
        $insert_apply_deal = array(
            'applyid'               =>  $this->input->post('applyid') ? $this->input->post('applyid') : '',
            'dealtime'              =>  date('Y-m-d H:i:s',now()),
            'dealby'                =>  $this->account_info_lib->accountname,
            'dealbyid'              =>  $this->account_info_lib->id,
            'remark'                =>  '提交期货订单',
            'dealstatuskey'         =>  '1',
            'dealstatusvalue'       =>  '待审核'
        );

        if ($key == '2') {
            $insert_apply_deal['dealstatuskey'] = '2';
            $insert_apply_deal['dealstatusvalue'] = '已审核';
            $insert_apply_deal['remark'] = '财务人员【'.$this->account_info_lib->accountname.'】审核通过了期货订单.';
        }
        else if ($key == '4') {
            $insert_apply_deal['dealstatuskey'] = '4';
            $insert_apply_deal['dealstatusvalue'] = '已结束';
            $insert_apply_deal['remark'] = '期货订单已结束.';
        }
        else if ($key == '5') {
            $insert_apply_deal['dealstatuskey'] = '5';
            $insert_apply_deal['dealstatusvalue'] = '已作废';
            $insert_apply_deal['remark'] = '期货订单已作废.';
        }

        $tmpid = $this->dataInsert($this->apply_deal_model,$insert_apply_deal,false);

        if ($tmpid) {
            $result = true;
        }

        //修改订单状态
//            $forecastgetdate = $this->input->post('forecastgetdate') ? $this->input->post('forecastgetdate') : '';

        $update_apply = array(
            'id'                    =>  $insert_apply_deal['applyid'],
            'checkby'               =>  $insert_apply_deal['dealby'],
            'checkbyid'             =>  $insert_apply_deal['dealbyid'],
            'status'                =>  $insert_apply_deal['dealstatuskey'],
            'statusvalue'           =>  $insert_apply_deal['dealstatusvalue']
        );
        $num = $this->dataUpdate($this->apply_model,$update_apply,false);
        $this->output->append_output($result);
	}

    /**
     * 处理商品状态
     */
    public function add_apply_content_deal() {
        $select_content_id = $this->input->post('select_content_id') ? $this->input->post('select_content_id') : '';

        $result = false;

        if (!$select_content_id) {
            $this->output->append_output($result);
            return;
        }

        $select_content_id = explode(',',$select_content_id);

        foreach ($select_content_id as $id) {
            //处理订单商品处理进度表
            $insert_apply_content_deal = array(
                'applyid'               =>  $this->input->post('applyid') ? $this->input->post('applyid') : '',
                'contentid'             =>  $id,
                'dealtime'              =>  $this->input->post('dealtime') ? $this->input->post('dealtime') : '',
                'dealby'                =>  $this->account_info_lib->accountname,
                'dealbyid'              =>  $this->account_info_lib->id,
                'remark'                =>  $this->input->post('remark') ? $this->input->post('remark') : ''
            );

            $apply_content_status_id = $this->input->post('apply_content_status') ? $this->input->post('apply_content_status') : '';

            //得到处理进度
            $apply_content_status = $this->apply_content_status_model->getOne($apply_content_status_id);

            $insert_apply_content_deal['value'] = $apply_content_status[0]->svalue;
            $insert_apply_content_deal['key'] = $apply_content_status[0]->skey;

            $tmpid = $this->dataInsert($this->apply_content_deal_model,$insert_apply_content_deal,false);

            //更新订单商品表处理进度状态
            $forecastgetdate = $this->input->post('forecastgetdate') ? $this->input->post('forecastgetdate') : '';
            $update_apply_content = array(
                'id'                =>  $id,
                'forecastgetdate'   =>  $forecastgetdate,
                'checkbyid'         =>  $this->account_info_lib->id,
                'checkby'           =>  $this->account_info_lib->accountname,
                'remark'            =>  $insert_apply_content_deal['remark'],
                'status'            =>  $insert_apply_content_deal['key'],
                'statusvalue'       =>  $insert_apply_content_deal['value']
            );
            $this->dataUpdate($this->apply_content_model,$update_apply_content,false);
        }

        //更新订单经办人和状态
        $update_apply = array(
            'id'                    =>  $this->input->post('applyid') ? $this->input->post('applyid') : '',
            'checkby'               =>  $this->account_info_lib->accountname,
            'checkbyid'             =>  $this->account_info_lib->id,
            'status'                =>  3,
            'statusvalue'           =>  '处理中'
        );
        $num = $this->dataUpdate($this->apply_model,$update_apply,false);

        //插入订单状态表
		$insert_apply_deal = array(
            'applyid'               =>  $update_apply['id'],
            'dealtime'              =>  $this->input->post('dealtime') ? $this->input->post('dealtime') : '',
            'dealby'                =>  $this->account_info_lib->accountname,
            'dealbyid'              =>  $this->account_info_lib->id,
            'remark'                =>  $this->input->post('remark') ? $this->input->post('remark') : '',
            'dealstatusvalue'       =>  '处理中',
            'dealstatuskey'         =>  3
        );

        $tmpid = $this->dataInsert($this->apply_deal_model,$insert_apply_deal,false);

        if ($tmpid) {
            $result = true;
        }

        $this->output->append_output($result);
    }

    /**
     * 获取订单商品的处理进度
     */
    public function read_apply_content_deal() {
        $id = $this->input->get('id');
        $result = false;
        if (!$id) {
            $this->output->append_output($result);
            return;
        }
        $result = $this->apply_content_deal_model->getAllByWhere(array('contentid'=>$id),array(),array('dealtime'=>'asc'));

        require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
        $json = new Services_JSON();
        $output = $json->encode($result);

        $this->output->append_output($output);
    }

	/**
	 * 生成采购单
	 */
	public function create_buy() {

		$result = false;

		$id = $this->input->post('id') ? $this->input->post('id') : '';
        $select_apply_id = $this->input->post('select_content_id') ? $this->input->post('select_content_id') : '';

        //获得订单
        $apply = $this->apply_model->getOne($id);

        if (empty($apply)) {
            $this->output->append_output($result);
            return;
        }

        $insert_buy = array(
            'buynumber'      =>  $result = date("Ymd-His") . '-' . rand(100,999),
            'createtime'    =>  date('Y-m-d H:i:s',now()),
            'createbyid'    =>  $this->account_info_lib->id,
            'createby'      =>  $this->account_info_lib->accountname,
            'buyman'        =>  $this->account_info_lib->accountname,
            'buydate'       =>  date('Y-m-d H:i:s',now()),
            'applyid'       =>  $id,
            'applynumber'   =>  $apply[0]->applynumber,
            'status'        =>  0,
            'remark'        =>  '['.$this->account_info_lib->accountname.'] 提交的:期货订单[单号：'.$apply[0]->applynumber.'] [生成采购单。]'
            );

        //保存采购单
        $tmpid = $this->dataInsert($this->b_buy_model,$insert_buy,false);

        if ($tmpid) {
            //获取选中的订单商品，转为采购商品
            if ($select_apply_id) {
                $this->load->model('apply_content_model');
                $select_apply_id = explode(',',$select_apply_id);
                $this->db->where_in('id',$select_apply_id);
                $apply_contents = $this->apply_content_model->getAllByWhere();

                if ($apply_contents) {

                    foreach($apply_contents as $content) {
                        $insert_buy_content = array();
                        $insert_buy_content['buyid'] = $tmpid;
                        $insert_buy_content['title'] = $content->title;
                        $insert_buy_content['code'] = $content->code;
                        $insert_buy_content['memo'] = $content->memo;
                        $insert_buy_content['salesprice'] = $content->salesprice;
                        $insert_buy_content['remark'] = $content->remark;
                        $insert_buy_content['brandid'] = $content->brandid;
                        $insert_buy_content['brandcode'] = $content->brandcode;
                        $insert_buy_content['brandname'] = $content->brandname;
                        $insert_buy_content['factoryid'] = $content->factoryid;
                        $insert_buy_content['factorycode'] = $content->factorycode;
                        $insert_buy_content['factoryname'] = $content->factoryname;
                        $insert_buy_content['typeid'] = $content->typeid;
                        $insert_buy_content['typecode'] = $content->typecode;
                        $insert_buy_content['typename'] = $content->typename;
                        $insert_buy_content['number'] = $content->number;
                        $insert_buy_content['color'] = $content->color;
                        $insert_buy_content['status'] = 0;
                        $insert_buy_content['statusvalue'] = '未入库';

                        $this->dataInsert($this->buy_product_model,$insert_buy_content,false);
                    }
                }
            }
            $result = true;
        }

		$this->output->append_output($result);

	}

	/**
	 * 获取表单数据
	 */
	private function _get_form_data() {
		return array(
            'applynumber'           =>  $this->input->post('applynumber',TRUE),
            'storehouseid'          =>  $this->input->post('storehouse',TRUE),
//            'contractnumber'        =>  $this->input->post('contractnumber',TRUE),
            'selldate'              =>  $this->input->post('selldate',TRUE),
            'totalmoney'            =>  $this->input->post('totalmoney',TRUE),
            'discount'              =>  $this->input->post('discount',TRUE),
            'paymoney'              =>  $this->input->post('paymoney',TRUE),
            'lastmoney'             =>  $this->input->post('lastmoney',TRUE),
            'clientname'            =>  $this->input->post('clientname',TRUE),
            'clientphone'           =>  $this->input->post('clientphone',TRUE),
            'clientadd'             =>  $this->input->post('clientadd',TRUE),
            'applyby'               =>  $this->input->post('applyby',TRUE),
            'applydate'             =>  $this->input->post('applydate',TRUE),
            'commitgetdate'         =>  $this->input->post('commitgetdate',TRUE),
            'email'                 =>  $this->input->post('email',TRUE),
//            'pay'                   =>  $this->input->post('pay',TRUE),
            'remark'                =>  $this->input->post('remark',TRUE),
		);
	}

    //期货商品创建销售合同单
    public function cteate_sell() {
        $this->_data = $this->get_stock_config('0','25');
        $this->_data['page_title'] =  '销售期货申请';
        $this->_data['fun_path'] = "apply?stype=sale";
        $oper = $this->auth_lib->role_fun_operate('3');
        $this->_data['oper'] = $oper;
        //获取期货订单id
        $id = trim($_GET['id']);
        $contentid = trim($_GET['contentid']);
        !$id && $this->error("错误调用");

        //获取订单信息
        $apply = $this->apply_model->getOne($id);
        $this->_data['apply'] = $apply;

        date_default_timezone_set('PRC');
        $this->_data['storehose']=$this->storehouse_model->getAllByWhere();
        $this->_data['sellnumber']= date("Ymd-His") . '-' . rand(100,999);

        //获取订单对应的入库销售商品
        $this->load->model('apply_stock_model');
        $apply_stock = explode(',',$contentid);
//        $apply_stock = $this->apply_stock_model->getAllByWhere(array (
//            "applyid" => $id
//        ), array (
//            'stockid'
//        ));
        //查询具体产品信息
        foreach ($apply_stock as $val) {
            $product[] = $this->stock_model->getOneByWhere(array (
                "id" => $val
            ));
        }

        foreach ($product as $key => $val) {
            $product[$key]->storehouse = $this->getStore($val->storehouseid);
        }

        isset ($product) && $data['list'] = $product;
        $list= array_merge($this->_data,$data);
        $this->load->view("buy/apply_create_sell",$list);
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

}