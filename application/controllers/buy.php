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
 * buy Controller Class
 *
 * 采购管理控制器。
 *
 * @package		buy
 * @subpackage	Controller
 * @category	Controller
 * @author		blues <blues0118@gmail.com>
 * @link
 */


class buy extends Stock__Controller{

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
        $this->load->model('b_buy_model');
        $this->load->model('buy_product_model');

        /** 在继承的自定义父类，获取系统配置。 */
        $this->_data = $this->get_stock_config('0','13');

        $this->_data['page_title'] =  '采购、期货管理';

        $this->_data['fun_path'] = "buy";

    }

    public function index() {
//        $this->load->view('buy/buy_index',$this->_data);
        redirect('buy/buy_pages?status=0');
    }

    public function buy_list() {
        redirect('buy/buy_pages?status=0');
    }

    /**
     * 按采购单状态，显示采购单列表
     */
    public function buy_pages() {

        $status = $this->input->get('status') ? $this->input->get('status') : 0;
//        $page = $this->input->get('p') ? $this->input->get('p') : 1;
        $buynumber = $this->input->get('buynumber') ? $this->input->get('buynumber') : '';

        $this->_data['status'] = $status;

        $otherwhere = "";

        $order = array('createtime'=>'desc');

        if ($status == 0) {
            $otherwhere = 'status = 0';
        }
        else if ($status == 1) {
            $otherwhere = 'status = 1';
        }
        else if ($status == 2) {
            $otherwhere = '(status = 0 or status = 1)';
            unset($order);
            $order = array('status' => 'asc','createtime'=>'desc');
        }
        else {
            $otherwhere = 'status = 0';
        }
        $this->_data['search'] = $buynumber;
        $this->_data['fun_path'] = "buy/buy_list";

        $this->dataList('buy/buy_list',$this->b_buy_model,array(),array('buynumber'),$order,$this->_data,$otherwhere);
    }

    /**
     * 添加采购单
     */
    public function add() {

        $this->_data['fun_path'] = "buy/buy_list";

        //读取验证
        $this->b_buy_model->_validate();

        if($this->b_buy_model->form_validation->run() === FALSE) {
            $this->load->view('buy/buy_add',$this->_data);
        }
        else {
            //执行inset
            $this->_insert_buy();
        }
    }

    /**
     * 添加采购单
     */
    private function _insert_buy() {
        /** 获取表单数据 */
        $content = $this->_get_form_data();

        $insert_buy = array(
            'id'            =>  md5(uniqid(rand(), true)),  //uuid有问题。先采用这种试试
            'buynumber'     =>  empty($content['buynumber']) ? '' : $content['buynumber'],
            'remark'        =>  empty($content['remark']) ? '' : $content['remark'],
            'buyman'        =>  empty($content['buyman']) ? '' : $content['buyman'],
            'buydate'       =>  empty($content['buydate']) ? '' : $content['buydate']
        );

//        date_default_timezone_set('PRC');
        //创建时间
        $insert_buy['createtime'] = date('Y-m-d H:i:s',now());
        //创建者id
        $insert_buy['createbyid'] = $this->account_info_lib->id;
        //创建者姓名
        $insert_buy['createby'] = $this->account_info_lib->accountname;
//        //处理人姓名
//        $insert_buy['buyman'] = '';
//        //处理时间
//        $insert_buy['buydate'] = '';
        //订货单id。通过订货单转来的
        $insert_buy['applyid'] = '';
        //采购单状态 0表示还未结束
        $insert_buy['status'] = '0';

        $newid = $this->dataInsert($this->b_buy_model,$insert_buy,false);

        if ($newid) {
            $this->success(null,site_url('buy/show?id='.$newid));
        }
        else {
            $this->error('保存采购单出错，请重新尝试或与管理员联系。',site_url('buy/buy/buy_pages?status=0'));
        }
    }

    /**
     * 删除采购单
     */
    public function remove_buy() {
        $result = false;
        $buyid = $this->input->post('id') ? $this->input->post('id') : '';

        $buyid = explode(',',$buyid);

        if (empty($buyid)) {
            $this->output->append_output($result);
            return;
        }
        foreach ($buyid as $id) {
            //首先删除采购商品
            $this->dataDelete($this->buy_product_model,array('buyid'=>$id),'buyid',false);
            //删除采购单
            $result = $this->dataDelete($this->b_buy_model,array('id'=>$id),'id',false);
        }
        $this->output->append_output($result);
    }

    public function show_pic() {
        $result = '<div><img src="'.base_url('upload/stock_image/no_pic.jpg').'"></div>';
        $this->output->append_output($result);
    }

    /**
     * 显示采购单
     */
    public function show() {
        $id = $this->input->get('id') ? $this->input->get('id') : '';
        //获得page（当前页数）、search（查询值），为返回采购单列表时，作为显示参数
        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $search = $this->input->get('search') ? $this->input->get('search') : '';

        //获取采购单信息
        $query = false;
        if (!empty($id)) {
            $query = $this->b_buy_model->getOne($id);
        }

        $this->_data['row'] = $query;
        $this->_data['page'] = $page;
        $this->_data['search'] = $search;

        //如果采购单已经入库。获取入库单显示
        $storehouse_ins = false;
        $this->load->model('s_storehouse_in_model');
        $storehouse_ins = $this->s_storehouse_in_model->getAllByWhere(array('buyid'=>$query[0]->id));
        if ($storehouse_ins == null) {
            $storehouse_ins = false;
        }
        $this->_data['storehouse_ins'] = $storehouse_ins;

        //获取采购商品信息
        $product_query = array();
        if (!empty($id)) {
            $product_query = $this->buy_product_model->getAllByWhere(array('buyid'=>$id),array(),array('code'=>'asc'));
        }
        $this->_data['productlist'] = $product_query;

        //fun_path是当前模块路径。用来页面上体现当前是哪个功能，这个功能的css与其他的不同。
        $this->_data['fun_path'] = "buy/buy_list";

        //获取厂家、品牌、类别、颜色代码，供批量修改采购商品用
        $this->get_field_code();

        $this->load->view('buy/buy_show',$this->_data);
    }

    /**
     * 显示采购商品信息
     */
    public function show_product() {
        $id = $this->input->get('id') ? $this->input->get('id') : '';
        $buyid = $this->input->get('buyid') ? $this->input->get('buyid') : '';

        $this->_data['buyid'] = $buyid;

        if (!empty($id)) {
            $query = $this->buy_product_model->getOne($id);
        }
        $this->_data['fun_path'] = "buy/buy_list";

        $this->_data['row'] = $query;
        $this->load->view('buy/buy_product_show',$this->_data);
    }

    /**
     * 获取表单代码项
     */
    private function get_field_code() {

        $this->load->model('factory_model');
        $this->load->model('brand_model');
        $this->load->model('color_model');
        $this->load->model('commodityType_model');
        //厂家列表
        $this->_data['factorys'] = $this->factory_model->getAllByWhere();

        //品牌列表
        $this->_data['brands'] = $this->brand_model->getAllByWhere();

        //商品类型列表
        $this->_data['comtypes'] = $this->commodityType_model->getAllByWhere();

        //获取颜色代码，填充到页面select里
        $this->_data['colors'] = $this->color_model->getAllByWhere();
    }

    /**
     * 获取表单数据
     *
     * @access private
     * @return array
     */
    private function _get_form_data() {
        return array(
            'buynumber'     =>  $this->input->post('buynumber',TRUE),
            'remark'        =>  $this->input->post('remark',TRUE),
            'buyman'        =>  $this->input->post('buyman',TRUE),
            'buydate'       =>  $this->input->post('buydate',TRUE)
        );
    }

    /**
     * 生成入库单
     */
    public function create_storehouse_in() {

        $result = false;

        //获取采购单id
        $id = $this->input->post('id') ? $this->input->post('id') : '';
        //获取采购商品id集合
        $select_productids = $this->input->post('productids') ? $this->input->post('productids') : '';
        $select_productids = explode(',',$select_productids);

        //获得采购单
        $buy = $this->b_buy_model->getOne($id);

        if (empty($buy)) {
            $this->output->append_output($result);
            return;
        }

        //生成入库单
        $frombuy = 2;
        $fromcode = '来源于采购办理入库';
        if ($buy[0]->applyid) {
            $frombuy = 3;
            $fromcode = '来源于订货办理入库';
        }

        $insert_storehouse_in = array(
            'innumber'      =>  $result = date("Ymd-His") . '-' . rand(100,999),
            'createtime'    =>  date('Y-m-d H:i:s',now()),
            'createbyid'    =>  $this->account_info_lib->id,
            'createby'      =>  $this->account_info_lib->accountname,
            'buyid'         =>  $id,
            'frombuy'       =>  $frombuy,
            'fromcode'      =>  $fromcode,
            'instatus'      =>  0,
            'remark'        =>  $buy[0]->remark . ' ['.$this->account_info_lib->accountname.'] 提交的:采购单[单号：'.$buy[0]->buynumber.'] ['.$fromcode.']'
        );

        $this->load->model('s_storehouse_in_model');
        $this->load->model('stock_model');
        $this->load->model('stock_in_model');
        $new_storehouse_in_id = $this->dataInsert($this->s_storehouse_in_model,$insert_storehouse_in,false);

        //生成入库商品

        //获得采购商品
        $buy_products = $this->buy_product_model->getAllByWhere(array('buyid'=>$id));

        foreach ($buy_products as $product) {
            foreach ($select_productids as $selectid) {
                if ($selectid == $product->id) {
                    for ($i = 0; $i < $product->number; $i++) {
                        $insert_stock = array(
                            'storehouseid'      =>  $this->_data['default_stock_house'],
                            'title'             =>  $product->title,
                            'code'              =>  $product->code,
                            'memo'              =>  $product->memo,
                            'cost'              =>  $product->cost,
                            'standardcost'      =>  $product->standardcost,
                            'salesprice'        =>  $product->salesprice,
                            'remark'            =>  $product->remark,
                            'brandid'           =>  $product->brandid,
                            'brandcode'         =>  $product->brandcode,
                            'brandname'         =>  $product->brandname,
                            'factoryid'         =>  $product->factoryid,
                            'factorycode'       =>  $product->factorycode,
                            'factoryname'       =>  $product->factoryname,
                            'typeid'            =>  $product->typeid,
                            'typecode'          =>  $product->typecode,
                            'typename'          =>  $product->typename,
                            'number'            =>  1,
                            'colorcode'         =>  '',
                            'color'             =>  $product->color,
                            'format'            =>  $product->format,
                            'boxno'             =>  $product->boxno,
                            'itemnumber'        =>  $product->itemnumber,
                            'barcode'           =>  '',
                            'statuskey'         =>  0,
                            'statusvalue'       =>  '未入库',
                            'picpath'           =>  $product->picpath
                        );
                        $new_stock_id = $this->dataInsert($this->stock_model,$insert_stock,false);

                        //处理入库单与入库商品关联表
                        $insert_stock_in = array(
                            'stockid'       =>  $new_stock_id,
                            'inid'          =>  $new_storehouse_in_id
                        );
                        $this->dataInsert($this->stock_in_model,$insert_stock_in,false);

                    }
                    //更改采购商品的状态
                    $update_product = (array)$product;
                    $update_product['status'] = 1;
                    $update_product['statusvalue'] = '已入库';
                    $this->dataUpdate($this->buy_product_model,$update_product,false);
                }
            }

        }

        //处理采购单状态
        //先判断采购单下商品是否全部入库。如果全部入库，采购单结束
        $buy_products = $this->buy_product_model->getAllByWhere(array('buyid'=>$id,'status'=>0));
        $num = count($buy_products);
        if ($num == 0) {
            $buy = (array)$buy[0];

            $buy['status'] = 1;
            $num = $this->dataUpdate($this->b_buy_model,$buy,false);
        }

        $result = true;
        $this->output->append_output($result);

    }
}
