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
 * 调拨单 控制器。
 *
 * @package		storehouse_move
 * @subpackage	Controller
 * @category	Controller
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class storehouse_move extends Stock__Controller {

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
        $this->load->model('storehouse_move_model');
        $this->load->model('stock_model');
        $this->load->model('stock_move_model');
        $this->load->model('storehouse_move_content_deal_model');
        $this->load->model('storehouse_model');


        /** 在继承的自定义父类，获取系统配置。 */
        $this->_data = $this->get_stock_config('0','6');

        $this->_data['page_title'] =  '调拨管理';

        $this->_data['fun_path'] = "storehouse_move";

    }

    public function index() {
        redirect('storehouse_move/pages?status=0');
    }

    /**
     * 按调拨单状态，显示调拨单列表
     */
    public function pages() {

        $status = $this->input->get('status') ? $this->input->get('status') : 0;
//        $page = $this->input->get('p') ? $this->input->get('p') : 1;
        $movenumber = $this->input->get('movenumber') ? $this->input->get('movenumber') : '';

        $this->_data['status'] = $status;

        $order = array('createtime'=>'desc');

        if ($status == 0) {
            $otherwhere = 'status = 0';
        }
        else if ($status == 1) {
            $otherwhere = 'status = 1';
        }
        else if ($status == 2) {
            $otherwhere = 'status = 2';
        }
        else if ($status == 3) {
            $otherwhere = '(status = 0 or status = 1 or status = 2)';
            unset($order);
            $order = array('status' => 'asc','createtime'=>'desc');
        }
        else {
            $otherwhere = 'status = 0';
        }

        //获取库房列表，用来显示原库房、目标库房
        $storehouse = $this->storehouse_model->getAllByWhere();
        $this->_data['storehouse'] = $storehouse;

        //获取当前帐户 的模块内操作权限，来判断是否打开添加、修改、删除按钮
        $oper = $this->auth_lib->role_fun_operate('7');
        $this->_data['oper'] = $oper;


        $this->_data['search'] = $movenumber;
        $this->_data['fun_path'] = "storehouse_move";

        $this->dataList('stock/storehouse_move_list',$this->storehouse_move_model,array('oldhouseid','targethouseid'),array('movenumber','moveby','remark'),$order,$this->_data,$otherwhere);
    }

    /*
     * 获取商品的库房
     */
    public function getStorehouse($id){
        $info=$this->storehouse_model->getOneByWhere(array("id"=>$id));
        echo  $info->storehousecode;
    }

    /**
     * 添加调拨单
     */
    public function add() {

        $oldhouseid = $this->input->post('oldhouseid') ? $this->input->post('oldhouseid') : '';
        $targethouseid = $this->input->post('targethouseid') ? $this->input->post('targethouseid') : '';

        if (!empty($oldhouseid)) {
            $this->_data['oldhouseid'] = $oldhouseid;
        }
        else {
            $this->_data['oldhouseid'] = '';
        }

        if (!empty($targethouseid)) {
            $this->_data['targethouseid'] = $targethouseid;
        }
        else {
            $this->_data['targethouseid'] = '';
        }

        //读取验证
        $this->_load_validation_rules();

        if($this->form_validation->run() === FALSE) {
            //读取库房
            $this->load->model('storehouse_model');
            $this->_data['houses'] = $this->storehouse_model->getAllByWhere();
            $this->load->view('stock/storehouse_move_add',$this->_data);
        }
        else {
            //执行inset
            $this->_insert_storehouse_move();
        }
    }

    /**
     * 删除调拨单
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
            //首先删除采购商品
            $this->dataDelete($this->stock_move_model,array('moveid'=>$id),'moveid',false);
            //删除采购单
            $result = $this->dataDelete($this->storehouse_move_model,array('id'=>$id),'id',false);
        }
        $this->output->append_output($result);

    }

    /**
     * 加载验证规则
     *
     * @access private
     * @return void
     */
    private function _load_validation_rules() {
        $this->form_validation->set_rules('movenumber', '调拨单号', 'required|trim|htmlspecialchars');
        $this->form_validation->set_rules('moveby', '运输负责人', 'required|trim');
    }

    /**
     * 添加调拨单
     */
    private function _insert_storehouse_move() {
        /** 获取表单数据 */
        $content = $this->_get_form_data();

        $insert_storehouse_move = array(
            'movenumber'            =>  empty($content['movenumber']) ? '' : $content['movenumber'],
            'remark'                =>  empty($content['remark']) ? '' : $content['remark'],
            'moveby'                =>  empty($content['moveby']) ? '' : $content['moveby'],
            'movedate'              =>  empty($content['movedate']) ? '' : $content['movedate'],
            'oldhouseid'            =>  empty($content['oldhouseid']) ? '' : $content['oldhouseid'],
            'targethouseid'         =>  empty($content['targethouseid']) ? '' : $content['targethouseid'],
        );

        //创建时间
        $insert_storehouse_move['createtime'] = date('Y-m-d H:i:s',now());
        //创建者id
        $insert_storehouse_move['createbyid'] = $this->account_info_lib->id;
        //创建者姓名
        $insert_storehouse_move['createby'] = $this->account_info_lib->accountname;
        //采购单状态 0表示还未结束
        $insert_storehouse_move['status'] = '0';

        $this->load->model('storehouse_model');

        $oldhouse = $this->storehouse_model->getOne($insert_storehouse_move['oldhouseid']);
        $targethouse = $this->storehouse_model->getOne($insert_storehouse_move['targethouseid']);

        $insert_storehouse_move['oldhouse'] = $oldhouse[0]->storehousecode;
        $insert_storehouse_move['targethouse'] = $targethouse[0]->storehousecode;

        $newid = $this->dataInsert($this->storehouse_move_model,$insert_storehouse_move,false);

        if ($newid) {
            $this->success(null,site_url('storehouse_move/show?id='.$newid));
//            $this->success(null,site_url('storehouse_move'));
        }
        else {
            $this->error('保存调拨单出错，请重新尝试或与管理员联系。',site_url('storehouse_move'));
        }
    }

    /**
     * 显示调拨单
     */
    public function show() {
        $id = $this->input->get('id') ? $this->input->get('id') : '';
        //获得page（当前页数）、search（查询值），为返回采购单列表时，作为显示参数
        $p = $this->input->get('p') ? $this->input->get('p') : 1;
        $movenumber = $this->input->get('movenumber') ? $this->input->get('movenumber') : '';

        //获取采购单信息
        $query = false;
        if (!empty($id)) {
            $query = $this->storehouse_move_model->getOne($id);
        }

        $this->_data['row'] = $query;
        $this->_data['p'] = $p;
        $this->_data['movenumber'] = $movenumber;

        $stock_move = array();
        $stock_move_content = array();
        //获取调拨单下全部调拨商品。
        $stock_move = $this->stock_move_model->getAllByWhere(array('moveid'=>$query[0]->id),array(),array());

        //获取调拨单下调拨商品
        if ($stock_move) {
            $ids = Common::array_flatten($stock_move, 'stockid');
            $this->db->where_in('id',$ids);
            $stock_move_content = $this->stock_model->getAllByWhere();
        }
        $this->_data['stock_move_content'] = $stock_move_content;

        //获取商品调拨处理结果
        $move_content_deal = $this->storehouse_move_content_deal_model->getAllByWhere(array('moveid'=>$query[0]->id),array(),array());

        $this->_data['move_content_deal'] = $move_content_deal;

        //获取厂家、品牌、类别、颜色代码，供批量修改采购商品用
        $this->get_field_code();

        $this->load->view('stock/storehouse_move_show',$this->_data);
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
        $this->_data['types'] = $this->commodityType_model->getAllByWhere();

        //获取颜色代码，填充到页面select里
        $this->_data['colors'] = $this->color_model->getAllByWhere();
    }

    /**
     * 显示查询调拨商品
     */
    public function search_stock() {
        $result = false;
        //获取
        $storehouseid = $this->input->post('storehouseid') ? $this->input->post('storehouseid') : '';
        $barcode = $this->input->post('barcode') ? $this->input->post('barcode') : '';
        $code = $this->input->post('code') ? $this->input->post('code') : '';
        $title = $this->input->post('title') ? $this->input->post('title') : '';
        $factoryid = $this->input->post('factoryid') ? $this->input->post('factoryid') : '';
        $colorname = $this->input->post('colorname') ? $this->input->post('colorname') : '';
        $brandid = $this->input->post('brandid') ? $this->input->post('brandid') : '';
        $typeid = $this->input->post('typeid') ? $this->input->post('typeid') : '';

        $where = array();
        $where['statuskey'] = 1;
        $where['storehouseid'] = $storehouseid;
        if ($barcode) {
            $where['barcode'] = $barcode;
        }
        if ($code) {
            $where['code'] = $code;
        }
        if ($factoryid) {
            $where['factoryid'] = $factoryid;
        }
        if ($title) {
            $where['title'] = $title;
        }
        if ($colorname) {
            $where['color'] = $colorname;
        }
        if ($brandid) {
            $where['brandid'] = $brandid;
        }
        if ($typeid) {
            $where['typeid'] = $typeid;
        }

        $result = $this->stock_model->getAllByWhere($where);
        if ($result) {
            //将数据转为json
            require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
            $json = new Services_JSON();
            $result = $json->encode($result);
        }

        $this->output->append_output($result);

    }

    /**
     * 添加调拨商品
     */
    public function add_stock() {
        $result = false;
        //获取入库商品id
        $barcodes = $this->input->post('barcodes') ? $this->input->post('barcodes') : '';
        $storehouse_moveid = $this->input->post('storehouse_moveid') ? $this->input->post('storehouse_moveid') : '';

        if (!$barcodes) {
            $this->output->append_output($result);
            return;
        }

//        require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
//        $json = new Services_JSON();
//        $output = $json->decode($barcodes);

        $output = json_decode($barcodes,true);

        try {
            foreach ($output as $row) {
                $row = (array)$row;

                $row = $this->stock_model->getOneByWhere(array('barcode' => $row['barcode']));

                if (!$row) {
                    continue;
                }

                //判断商品id是否已经在当前调拨单下
                $tmp = $this->stock_move_model->getOneByWhere(array('stockid'=>$row->id,'moveid'=>$storehouse_moveid));
                if ($tmp) {
                    continue;
                }

                if ($row->statuskey == '0') {
                    continue;
                }

                $insert_stock_move = array(
                    'stockid'       =>  $row->id,
                    'moveid'        =>  $storehouse_moveid
                );
                $this->dataInsert($this->stock_move_model,$insert_stock_move,false);
            }
            //获取调拨单下全部调拨商品。
            $stock_move = $this->stock_move_model->getAllByWhere(array('moveid'=>$storehouse_moveid),array(),array());

            if ($stock_move) {
                $ids = Common::array_flatten($stock_move, 'stockid');
            }
            $this->db->where_in('id',$ids);

            $stock_move_content = $this->stock_model->getAllByWhere();

            foreach ($stock_move_content as $stock ) {
                $info=$this->storehouse_model->getOneByWhere(array("id"=>$stock->storehouseid));
                $stock->storehouse = $info->storehousecode;
            }

            //将数据转为json
            require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
            $json = new Services_JSON();
            $output = $json->encode($stock_move_content);
            $this->output->append_output($output);
            return;
        } catch (Exception $e) {
            $this->output->append_output($result);
            return;
        }
    }

    /**
     * 移除调拨商品
     */
    public function remove_stock() {
        $result = false;
        $stockid = $this->input->post('id') ? $this->input->post('id') : '';
        $storehouse_moveid = $this->input->post('storehouse_moveid') ? $this->input->post('storehouse_moveid') : '';

        $stockid = explode(',',$stockid);

        if (empty($stockid)) {
            $this->output->append_output($result);
            return;
        }
        $this->db->where_in('stockid',$stockid);
        //获取商品与调拨单关联
        $stock_move = $this->stock_move_model->getAllByWhere(array('moveid'=>$storehouse_moveid),array(),array());

        foreach ($stock_move as $row) {
            $this->dataDelete($this->stock_move_model,array('id'=>$row['id']),'id',false);
        }

        //获取调拨单下全部调拨商品。
        $stock_move = $this->stock_move_model->getAllByWhere(array('moveid'=>$storehouse_moveid),array(),array());

        if ($stock_move) {
            $ids = Common::array_flatten($stock_move, 'stockid');
        }
        $this->db->where_in('id',$ids);

        $stock_move_content = $this->stock_model->getAllByWhere();

        foreach ($stock_move_content as $stock ) {
            $info=$this->storehouse_model->getOneByWhere(array("id"=>$stock->storehouseid));
            $stock->storehouse = $info->storehousecode;
        }

        //将数据转为json
        require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
        $json = new Services_JSON();
        $result = $json->encode($stock_move_content);
        $this->output->append_output($result);

//        $result = true;
//        $this->output->append_output($result);
    }

    /**
     * 开始调拨
     */
    public function begin_move() {
        $result = false;
        //获取调拨单id
        $id = $this->input->post('id') ? $this->input->post('id') : '';
        if (empty($id)) {
            $this->output->append_output($result);
            return;
        }
        //获取调拨单
        $storehouse_move = $this->storehouse_move_model->getOne($id);

        $update_move = array(
            'id'        =>  $storehouse_move[0]->id,
            'status'    =>  1
        );

        $num = $this->dataUpdate($this->storehouse_move_model,$update_move,false);

        if ($num > 0) {
            $result = true;
        }
        $this->output->append_output($result);
    }

    public function handle() {
//        $page = $this->input->get('p') ? $this->input->get('p') : 1;
        $movenumber = $this->input->get('movenumber') ? $this->input->get('movenumber') : '';

        $order = array('createtime'=>'desc');

        $otherwhere = 'status = 1';

        //读取当前登陆帐户能管理的库房=调拨目标库房的调拨单
        $stores = $this->account_info_lib->store;

        if ($stores) {
            //将数据转为json
            require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
            $json = new Services_JSON();
            $stores = $json->decode($stores);
        }
        else {
//            $this->error('');
        }
        $this->db->where_in('targethouseid',$stores);

        $this->_data['search'] = $movenumber;
        $this->_data['fun_path'] = "storehouse_move/handle";

        $this->dataList('stock/storehouse_move_handle_list',$this->storehouse_move_model,array(),array('movenumber'),$order,$this->_data,$otherwhere);
    }

    /**
     * 显示调拨单
     */
    public function handle_show() {
        $id = $this->input->get('id') ? $this->input->get('id') : '';
        //获得page（当前页数）、search（查询值），为返回采购单列表时，作为显示参数
        $p = $this->input->get('p') ? $this->input->get('p') : 1;
        $movenumber = $this->input->get('movenumber') ? $this->input->get('movenumber') : '';

        //获取单信息
        $query = false;
        if (!empty($id)) {
            $query = $this->storehouse_move_model->getOne($id);
        }

        $this->_data['row'] = $query;
        $this->_data['p'] = $p;
        $this->_data['movenumber'] = $movenumber;

        $stock_move = array();
        $stock_move_content = array();
        //获取调拨单下全部调拨商品。
        $stock_move = $this->stock_move_model->getAllByWhere(array('moveid'=>$query[0]->id),array(),array());

        //获取调拨单下调拨商品
        if ($stock_move) {
            $ids = Common::array_flatten($stock_move, 'stockid');
            $this->db->where_in('id',$ids);
            $stock_move_content = $this->stock_model->getAllByWhere();
        }
        $this->_data['stock_move_content'] = $stock_move_content;

        $this->_data['fun_path'] = "storehouse_move/handle";

        //获取商品调拨处理结果
        $move_content_deal = $this->storehouse_move_content_deal_model->getAllByWhere(array('moveid'=>$query[0]->id),array(),array());

        $this->_data['move_content_deal'] = $move_content_deal;
        //获取厂家、品牌、类别、颜色代码，供批量修改采购商品用
//        $this->get_field_code();

        $this->load->view('stock/storehouse_move_handle_show',$this->_data);
    }

    /**
     * 接收调拨商品
     */
    public function handle_move_stock() {
        $result = false;
        //获取入库商品id
        $barcodes = $this->input->post('barcodes') ? $this->input->post('barcodes') : '';
        $storehouse_moveid = $this->input->post('storehouse_moveid') ? $this->input->post('storehouse_moveid') : '';

        if (!$barcodes) {
            $this->output->append_output($result);
            return;
        }

//        require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
//        $json = new Services_JSON();
//        $output = $json->decode($barcodes);

        $output = json_decode($barcodes,true);

        try {
            foreach ($output as $row) {
                $row = (array)$row;

                $row = $this->stock_model->getOneByWhere(array('barcode' => $row['barcode']));

                if (!$row) {
                    continue;
                }

                //判断商品id是否已经在当前调拨单下
                $tmp = $this->stock_move_model->getOneByWhere(array('stockid'=>$row->id,'moveid'=>$storehouse_moveid));
                if (!$tmp) {
                    continue;
                }

                if ($row->statuskey == '0') {
                    continue;
                }
                //判断商品id是否已经接收过
                $tmp = "";
                $tmp = $this->storehouse_move_content_deal_model->getOneByWhere(array('stockid'=>$row->id,'moveid'=>$storehouse_moveid));

                if ($tmp) {
                    continue;
                }

                $insert_move_content_deal = array(
                    'stockid'       =>  $row->id,
                    'moveid'        =>  $storehouse_moveid,
                    'barcode'       =>  $row->barcode,
                    'dealtime'      =>  date('Y-m-d H:i:s',now()),
                    'dealbyid'      =>  $this->account_info_lib->id,
                    'dealby'        =>  $this->account_info_lib->accountname,
                    'remark'        =>  '[ '.$this->account_info_lib->accountname.' ] 接收了条形码为 [ '.$row->barcode.' ] 的商品.'
                );
                $this->dataInsert($this->storehouse_move_content_deal_model,$insert_move_content_deal,false);

                //调拨商品接收后，将商品的库房更改
                $storehouse_move = $this->storehouse_move_model->getOne($storehouse_moveid);
                $update_stock = array(
                    'id'            =>  $row->id,
                    'storehouseid'  =>  $storehouse_move[0]->targethouseid
                );
                $this->dataUpdate($this->stock_model,$update_stock,false);
            }
            //获取商品调拨处理结果
            $move_content_deal = $this->storehouse_move_content_deal_model->getAllByWhere(array('moveid'=>$storehouse_moveid),array(),array());

            //判断调拨的商品是否全部接收
            if ($move_content_deal) {
                $ids = Common::array_flatten($move_content_deal, 'stockid');
                $this->db->where_not_in('stockid',$ids);
                $stock_move_content = $this->stock_move_model->getAllByWhere(array('moveid'=>$storehouse_moveid));

                if (!$stock_move_content) {
                    //如果调拨商品都接收了。将调拨单的状态修改为1
                    $update_storehouse_move = array(
                        'id'        =>  $storehouse_moveid,
                        'status'    =>  2
                    );

                    $this->dataUpdate($this->storehouse_move_model,$update_storehouse_move,false);

                    $result = 'over';
                    $this->output->append_output($result);
                    return;
                }
            }

            //将数据转为json
            require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
            $json = new Services_JSON();
            $output = $json->encode($move_content_deal);
            $this->output->append_output($output);
            return;
        } catch (Exception $e) {
            $this->output->append_output($result);
            return;
        }
    }

    /**
     * 获取表单数据
     *
     * @access private
     * @return array
     */
    private function _get_form_data() {
        return array(
            'movenumber'            =>  $this->input->post('movenumber',TRUE),
            'remark'                =>  $this->input->post('remark',TRUE),
            'moveby'                =>  $this->input->post('moveby',TRUE),
            'movedate'              =>  $this->input->post('movedate',TRUE),
            'oldhouseid'            => $this->input->post('oldhouseid',TRUE),
            'targethouseid'         => $this->input->post('targethouseid',TRUE),
        );
    }

}