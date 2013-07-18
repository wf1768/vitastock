<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * stock Login Controller Class
 *
 * 基于ci的商品管理系统
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
 * stock stock Controller Class
 *
 * 库存商品查询 管理控制器。
 *
 * @package		stock
 * @subpackage	Controller
 * @category	Controller
 * @author		blues <blues0118@gmail.com>
 * @link
 */
class stock_find extends Stock__Controller {
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
        $this->load->library('stock_lib');
        $this->load->model('stock_model');
        $this->load->model('storehouse_model');
        $this->load->model('stock_in_model');
        $this->load->model('s_storehouse_in_model');
        $this->load->model('b_buy_model');
        $this->load->model('apply_model');
        $this->load->model('sell_model');
        $this->load->model('sellcont_model');


        /** 在继承的自定义父类，获取系统配置。 */
        $this->_data = $this->get_stock_config('0','6');

        $this->_data['page_title'] =  '商品查询';

        $this->_data['fun_path'] = "stock_find";
    }

    public function index() {
        $barcode = $this->input->post('barcode') ? $this->input->post('barcode') : '';
        if ($barcode) {
            //获取商品本身数据
            $stock = $this->stock_model->getOneByWhere(array('barcode'=>$barcode));
            if ($stock) {
                $this->_data['row'] = $stock;
                //获取商品所在库房
                $house = $this->storehouse_model->getOne($stock->storehouseid);
                if ($house) {
                    $housecode = $house[0]->storehousecode;
                    $this->_data['housecode'] = $housecode;
                }
                //获取商品的入库单
                $stock_in = $this->stock_in_model->getOneByWhere(array('stockid'=>$stock->id));
                if ($stock_in) {
                    $storehouse_in = $this->s_storehouse_in_model->getOne($stock_in->inid);

                    if ($storehouse_in[0]) {
                        $this->_data['storehouse_in'] = $storehouse_in[0];
                        //获取商品的入库单对应的采购单
                        if ($storehouse_in[0]->buyid) {
                            $buy = $this->b_buy_model->getOne($storehouse_in[0]->buyid);
                            if ($buy[0]) {
                                $this->_data['buy'] = $buy[0];
                            }
                            //获取商品采购单对应的订货单
                            if ($buy[0]->applyid) {
                                $apply = $this->apply_model->getOne($buy[0]->applyid);
                                if ($apply[0]) {
                                    $this->_data['apply'] = $apply[0];
                                }
                            }
                        }
                    }

                    //获取销售记录
                    //判断商品是否销售过 考虑商品被退货，可能重新销售，多个销售单里都有的情况
                    $sell_content = $this->sellcont_model->getAllByWhere(array('stockid'=>$stock->id));
                    if ($sell_content) {
                        $content_array = array();
                        foreach ($sell_content as $content) {
                            array_push($content_array,$content->sellid);
                        }
                        //如果销售记录存在，取销售单
                        $this->db->where_in('id',$content_array);
                        $sells = $this->sell_model->getAllByWhere();
                        if ($sells) {
                            $this->_data['sells'] = $sells;
                        }
                    }

                    //获取送货记录 TODO
                }
            }
        }
        $this->load->view('stock/stock_find',$this->_data);
    }

    public function search() {
//        $status = $_REQUEST['status'];
//        $status = $this->input->get('status') ? $this->input->get('status') : 1;
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : 1;
        $this->_data['fun_path'] = "stock_find/search";

        //获取库房列表
        $storehouse = $this->storehouse_model->getAllByWhere();

        $this->_data['storehouse'] = $storehouse;

        //获取类别
        $this->load->model('commodityType_model');
        $type = $this->commodityType_model->getAllByWhere();
        $this->_data['type'] = $type;

        $this->load->model('factory_model');
        $factorys = $this->factory_model->getAllByWhere();
        $this->_data['factory'] = $factorys;

        $this->load->model('brand_model');
        $brands = $this->brand_model->getAllByWhere();
        $this->_data['brand'] = $brands;

        $order = array ('code'=>'asc');

        if ($status == 1000) {
            $otherwhere = '';
        }
        else {
            $otherwhere = 'statuskey = '.$status;
        }

//        if ($storehouseid == '0') {
//            $otherwhere = $status_where;
//        }
//        else {
//            if (empty($status_where)) {
//                $otherwhere = 'storehouseid = '.$storehouseid;
//            }
//            else {
//                $otherwhere = 'storehouseid = '.$storehouseid.' and '.$status_where;
//            }
//
//        }
        $this->_data['status'] = $status;

//        if (isset($_REQUEST['statuskey'])) {
//
//        }
//        else {
//            if (empty($_REQUEST['statuskey'])) {
//                $_REQUEST['statuskey'] = '1000';
//            }
//            else {
//                $_REQUEST['statuskey'] = 1;
//            }
//        }
//        $otherwehre = array ();

        $like = array ('code','title','factorycode','brandcode','color','typename','memo','barcode');
        $this->dataList("stock/stock_search", $this->stock_model, $where = array ('storehouseid'), $like, $order, $this->_data, $otherwhere);
//        $this->load->view('stock/stock_search',$this->_data);
    }

    public function search_show() {
        $this->_data['fun_path'] = "stock_find/search";
        $id = $this->input->get('id') ? $this->input->get('id') : '';

        //获取库存商品对象
        $row = $this->stock_model->getOne($id);

        $this->_data['row'] = $row;

        //获取库房
        $house = $this->storehouse_model->getOneByWhere(array('id'=>$row[0]->storehouseid));
        $this->_data['housecode'] = $house->storehousecode;

        $this->load->view('stock/stock_search_show',$this->_data);
    }
}