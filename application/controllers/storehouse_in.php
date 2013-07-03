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
 * stock storehouse_in Controller Class
 *
 * 入库单管理控制器。
 *
 * @package		stock
 * @subpackage	Controller
 * @category	Controller
 * @author		blues <blues0118@gmail.com>
 * @link
 */


class storehouse_in extends Stock__Controller {

    /**
     * 传递到页面的参数载体
     * @var
     */
    private $_data;

    /**
     * 构造函数
     * @access public
     * @return void
     */
    function __construct() {

        parent::__construct();
        $this->load->model('s_storehouse_in_model');
        $this->load->model('stock_model');
        $this->load->model('stock_in_model');
        $this->load->model('b_buy_model');
        $this->load->model('apply_model');
        $this->load->model('apply_stock_model');

        /** 在继承的自定义父类，获取系统配置。 */
        $this->_data = $this->get_stock_config('0','6');

        $this->_data['page_title'] =  '入库单管理';

        $this->_data['fun_path'] = "storehouse_in";

    }

    public function index() {
        redirect('storehouse_in/pages?instatus=0');
    }

    public function pages() {

        $p = $this->input->get('p') ? $this->input->get('p') : 1;
        $search = $this->input->get('search') ? $this->input->get('search') : '';
        $instatus = $this->input->get('instatus') ? $this->input->get('instatus') : 0;

        $this->_data['instatus'] = $instatus;

        $order = array('createtime'=>'desc');


        if ($instatus == 0) {
            $otherwhere = 'instatus = 0';
        }
        else if ($instatus == 1) {
            $otherwhere = 'instatus = 1';
        }
        else if ($instatus == 2) {
            $otherwhere = '';
            unset($order);
            $order = array('instatus' => 'asc','createtime'=>'desc');
        }
        else {
            $otherwhere = 'status = 0';
        }
//
        $this->_data['search'] = $search;

        $this->dataList('stock/storehouse_in',$this->s_storehouse_in_model,array(),array('innumber'),$order,$this->_data,$otherwhere);
    }

    /**
     * 添加入库单
     */
    public function add() {

        //读取验证
        $this->s_storehouse_in_model->_validate();
        if($this->s_storehouse_in_model->form_validation->run() === FALSE) {
            $this->load->view('stock/storehouse_in_add',$this->_data);
        }
        else {
            //执行inset
            $this->_insert_storehouse_in();
        }
    }

    private function _insert_storehouse_in() {
        /** 获取表单数据 */
        $content = $this->_get_form_data();

        $insert_storehouse_in = array(
            'innumber'      =>  empty($content['innumber']) ? '' : $content['innumber'],
            'remark'        =>  empty($content['remark']) ? '' : $content['remark']
        );

        //创建时间
        $insert_storehouse_in['createtime'] = date('Y-m-d H:i:s',now());
        //创建者id
        $insert_storehouse_in['createbyid'] = $this->account_info_lib->id;
        //创建者姓名
        $insert_storehouse_in['createby'] = $this->account_info_lib->accountname;
        //处理人id
        $insert_storehouse_in['checkbyid'] = '';
        //处理人姓名
        $insert_storehouse_in['checkby'] = '';
        //采购、订货 id
        $insert_storehouse_in['buyid'] = '';
        //入库单来源 1 表示库房管理员录入
        $insert_storehouse_in['frombuy'] = 1;
        //入库单来源 说明
        $insert_storehouse_in['fromcode'] = '库管自录';
        //入库单状态 0表示还未处理
        $insert_storehouse_in['instatus'] = '0';

        $newid = $this->dataInsert($this->s_storehouse_in_model,$insert_storehouse_in,false);

        if ($newid) {
            //返回
            $this->success(null,site_url().'/storehouse_in/show?id='.$newid);
        }
        else {
            $this->error('保存数据失败，请重新尝试或与管理员联系。',site_url().'/apply/pages?status=0');
        }
    }

    /**
     * 显示入库单详细
     */
    public function show() {
        $id = $this->input->get('id') ? $this->input->get('id') : '';
        $p = $this->input->get('$p') ? $this->input->get('$p') : 1;
        $search = $this->input->get('search') ? $this->input->get('search') : '';

        $query = false;

        //读取库存信息
        if (!empty($id)) {
            $query = $this->s_storehouse_in_model->getOne($id);
        }

        $this->_data['row'] = $query;
        $this->_data['search'] = $search;
        $this->_data['storehouseid'] = $id;

        //获取入库单商品
        $stock_in = false;
        if (!empty($id)) {
            $stock_in = $this->stock_in_model->getAllByWhere(array('inid'=>$id),array(),array());
        }
        if ($stock_in) {
            $ids = Common::array_flatten($stock_in, 'stockid');
        }

        $this->db->where_in('id',$ids);
        //取未入库的（已入库商品与未入库商品分开显示）

        $stock_content = $this->stock_model->getAllByWhere(array('statuskey'=>0));

        $this->db->where_in('id',$ids);
        $this->db->where('statuskey <>',0);
        $stock_content_in = $this->stock_model->getAllByWhere();
        $this->_data['stock_content'] = $stock_content;

        $this->_data['stock_content_in'] = $stock_content_in;

        $this->load->view('stock/storehouse_in_show',$this->_data);
    }

    /**
     * 入库单办理入库
     */
    public function handle_in() {
        $result = false;
        $storehouse_inid = $this->input->post('storehouse_inid') ? $this->input->post('storehouse_inid') : '';
        $contentids = $this->input->post('contentid') ? $this->input->post('contentid') : '';

        if (empty($storehouse_inid)) {
            $this->output->append_output($result);
            return;
        }
        $contentids = explode(',',$contentids);

        if (empty($contentids)) {
            $this->output->append_output($result);
            return;
        }

        //办理入库时，判断入库的类型如果为订单来的。就将入库商品的状态修改为已销售，并与期货订单关联。等待销售提交销售合同单
        $storehouse_in = $this->s_storehouse_in_model->getOne($storehouse_inid);
        $frombuy = $storehouse_in[0]->frombuy;
        if ($frombuy == 3) {
            //如果是来自订单。获取订单id
            $buy = $this->b_buy_model->getOne($storehouse_in[0]->buyid);
            if (!$buy) {
                $this->output->append_output($result);
                return;
            }
            //获取订单id
            $apply = $this->apply_model->getOne($buy[0]->applyid);
            if (!$apply) {
                $this->output->append_output($result);
                return;
            }
        }

        try {
            //将入库商品状态修改
            //判断是否是订单来的，商品状态不一样
            $statuskey = '1';
            $statusvalue = '在库';
            if ($frombuy == 3) {
//                $statuskey = '3';
//                $statusvalue = '已销售';
                $statuskey = '10';
                $statusvalue = '期货待销售';
            }
            foreach ($contentids as $id) {
                $update_stock = array(
                    'id'            =>  $id,
                    'statuskey'     =>  $statuskey,
                    'statusvalue'   =>  $statusvalue
                );
                $this->dataUpdate($this->stock_model,$update_stock,false);
                //如果是订单来的，将商品id与订单关联
                if ($frombuy == 3) {
                    $insert_apply_stock = array(
                        'applyid'     =>  $apply[0]->id,
                        'stockid'   =>  $id
                    );
                    $newid = $this->dataInsert($this->apply_stock_model,$insert_apply_stock,false);
                }
            }
            //获取入库单下未入库商品。如果没有未入库的商品。将入库单状态修改。

            $stock_in = false;
            if (!empty($storehouse_inid)) {
                $stock_in = $this->stock_in_model->getAllByWhere(array('inid'=>$storehouse_inid),array(),array());
            }
            if ($stock_in) {
                $ids = Common::array_flatten($stock_in, 'stockid');
            }

            $this->db->where_in('id',$ids);

            $stock_content = $this->stock_model->getAllByWhere(array('statuskey'=>'0'));

            if (!$stock_content) {
                //将入库单状态修改
                $updata_storehouse_in = array(
                    'id'        =>  $storehouse_inid,
                    'checkbyid' => $this->account_info_lib->id,
                    'checkby'   => $this->account_info_lib->accountname,
                    'overtime'  => date('Y-m-d H:i:s',now()),
                    'instatus'  =>  1
                );
                $this->dataUpdate($this->s_storehouse_in_model,$updata_storehouse_in,false);
            }
            $result = true;
        }
        catch (Exception $e) {
            $result = false;
        }
        $this->output->append_output($result);
    }

    /**
     * 获取表单数据
     *
     * @access private
     * @return array
     */
    private function _get_form_data() {
        return array(
            'innumber'  =>  $this->input->post('innumber',TRUE),
            'remark'    =>  $this->input->post('remark',TRUE)
        );
    }

    /**
     * 获取入库单号
     */
    public function get_innumber() {
        date_default_timezone_set('PRC');
        $result = date("Ymd-His") . '-' . rand(100,999);
        $this->output->append_output($result);
    }

}
