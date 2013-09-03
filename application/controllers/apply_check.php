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
 * 期货订单审核 控制器。
 *
 * @package		apply_check
 * @subpackage	Controller
 * @category	Controller
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class apply_check extends Stock__Controller {

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
        $this->_data['fun_path'] = "apply_check";
        $this->_data['page_title'] =  '财务期货审核';
        $this->_data = $this->get_stock_config('0','5');

        $this->load->model('apply_model');
        $this->load->model('apply_content_model');
        $this->load->model('stock_model');
    }

    public function index() {
        redirect('apply_check/pages?type=0');
    }

    /**
     * 按采购单状态，显示采购单列表
     */
    public function pages() {

        $this->_data['fun_path'] = "apply_check";
        $this->_data['page_title'] =  '财务期货审核';

        $status = $this->input->get('status') ? $this->input->get('status') : 1;
        $p = $this->input->get('p') ? $this->input->get('p') : 1;

        $this->_data['p'] = $p;
        $this->_data['status'] = $status;

        $this->load->model('storehouse_model');
        $storehouse = $this->storehouse_model->getAllByWhere();
        $this->_data['storehouse'] = $storehouse;

        $order = array('createtime'=>'desc');

        if(isset($_GET['type'])){
            if($_GET['type']==0){
                $otherwhere = array ("status" => '1');
            }elseif($_GET['type']==1){
                $otherwhere = "financestatus =0 and status != 1";
            }elseif($_GET['type']==2){
                $otherwhere = "financestatus =1 and status !=1";
            }
        }else{
            $otherwhere = array ("status" => '1');
        }

        $this->_data['type'] = $_GET['type'];
//        $this->_data['search'] = $applynumber;

        $this->dataList('buy/apply_check_list',$this->apply_model,array('storehouseid'),array('applynumber','clientname','applyby','remark'),$order,$this->_data,$otherwhere);
    }

    public function check() {
        $this->_data = $this->get_stock_config('0','5');
        $this->_data['page_title'] =  '财务期货审核';
        $this->_data['fun_path'] = "apply_check";

        $type = $_GET['type'];
        $this->_data['type'] = $type;

        $id = trim($_GET['id']);
        !$id && $this->error("错误调用");
        //查询订单信息
        $info['apply'] = $this->apply_model->getOneByWhere(array (
            "id" => $id
        ));

        //获取当前期货订单的审核意见
        $this->load->model('finance_check_model');
        $finance_check = $this->finance_check_model->getAllByWhere(array('sellnumber'=>$info['apply']->applynumber),array(),array('financetime'=>'asc'));
        $this->_data['finance_check'] = $finance_check;
        //查询关联的产品
        $prolist = $this->apply_content_model->getAllByWhere(array('applyid'=>$id),array(),array('factoryname'=>'asc'));
//        //查询具体产品信息
//        foreach ($prolist as $val) {
//            $product[] = $this->stock_model->getOneByWhere(array ("id" => $val->stockid));
////            $salprice[$val->stockid]=$val->price;
//        }
//        isset ($product) && $info['list'] = $product;
//        isset ($salprice) && $info['salprice'] = $salprice;
        $info['list'] = $prolist;
        $datalist = array_merge($this->_data, $info);
        $this->load->view("buy/apply_check", $datalist);
    }

}