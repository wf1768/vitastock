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
 * 库存管理控制器。
 *
 * @package		stock
 * @subpackage	Controller
 * @category	Controller
 * @author		blues <blues0118@gmail.com>
 * @link
 */


class stock extends Stock__Controller {

    /**
     * 传递到页面的参数载体
     * @var
     */
    private $_data;

    /**
     * 库存商品id。用来修改时，传入验证是否条码重复
     * @var
     */
    private $_stockid;

    /**
     * 构造函数
     * @access public
     * @return void
     */
    function __construct() {

        parent::__construct();
        $this->load->library('stock_lib');
        $this->load->model('stock_model');

        /** 在继承的自定义父类，获取系统配置。 */
        $this->_data = $this->get_stock_config('0','6');

        $this->_data['page_title'] =  '库存管理';

        $this->_data['fun_path'] = "stock";

    }

    public function index() {
        $this->stock_list();
//        $this->load->view('stock/stock_index',$this->_data);
    }

    /**
     * 按库房id显示库存列表
     */
    public function stock_pages() {

        $storehouseid = $_REQUEST['houseid'];
        $p = $this->input->get('p') ? $this->input->get('p') : 1;
//        $status = $this->input->get('status') ? $this->input->get('status') : 1;
//        $status = $_REQUEST['status'] ? $_REQUEST['status'] : 1;

        if (isset ($_REQUEST['status']) && $_REQUEST['status'] != '') {
            $status = $_REQUEST['status'];
        }
        else {
            $status = 1;
        }

        if (isset ($_REQUEST['barcode']) && $_REQUEST['barcode'] != '') {
            $barcode = $_REQUEST['barcode'];
        }
        else {
            $barcode = '';
        }

        if (isset ($_REQUEST['search_type']) && $_REQUEST['search_type'] != '') {
            $search_type = $_REQUEST['search_type'];
        }
        else {
            $search_type = 1;
        }

        $this->_data['search_type'] = $search_type;

        //获取高级查询值，查看、修改，返回列表页用参数
        $super_search = '';
        //如果是高级检索
        if ($search_type == 1) {
            $super_search = '&p='.$p.'&search_type='.$search_type;
            //条形码
            if ($barcode) {
                $super_search = $super_search.'&barcode='.$barcode;
            }
            //商品名称
            if (isset ($_REQUEST['title']) && $_REQUEST['title'] != '') {
                $super_search = $super_search.'&title='.$_REQUEST['title'];
            }
            //商品箱号
            if (isset ($_REQUEST['boxno']) && $_REQUEST['boxno'] != '') {
                $super_search = $super_search.'&boxno='.$_REQUEST['boxno'];
            }
            //商品颜色
            if (isset ($_REQUEST['color']) && $_REQUEST['color'] != '') {
                $super_search = $super_search.'&color='.$_REQUEST['color'];
            }
            //商品品牌
            if (isset ($_REQUEST['factorycode']) && $_REQUEST['factorycode'] != '') {
                $super_search = $super_search.'&factorycode='.$_REQUEST['factorycode'];
            }
            //商品类别
            if (isset ($_REQUEST['typecode']) && $_REQUEST['typecode'] != '') {
                $super_search = $super_search.'&typecode='.$_REQUEST['typecode'];
            }
        }
        $this->_data['super_search'] = $super_search;

        $this->session->set_userdata('super_search', $super_search);
        $this->session->set_userdata('search_type', $search_type);

        $this->_data['search'] = $barcode;
        $this->_data['p'] = $p;

        //当前帐户能访问的库房列表
        $houses = $this->_get_storehouses();
        $this->_data['houses'] = $houses;
        $this->_data['fun_path'] = "stock/stock_list";

        //获取全部库房。用来显示商品所在库房
        $this->load->model('storehouse_model');
        $storehouses = $this->storehouse_model->getAllByWhere();
        $this->_data['storehouses'] = $storehouses;

        //获取厂家
        $factory = $this->_get_factorys();
        $this->_data['factory'] = $factory;

        //获取类别
        $type = $this->_get_commodity_types();
        $this->_data['type'] = $type;

        //获取当前帐户是否有商品管理模块的模块内操作权限，来判断是否打开添加、修改、删除按钮
        $oper = $this->auth_lib->role_fun_operate('7');
        $this->_data['oper'] = $oper;

        if ($status == 1000) {
            $status_where = '';
        }
        else {
            $status_where = 'statuskey = '.$status;
        }

        if ($storehouseid == '0') {
            $otherwhere = $status_where;
        }
        else {
            if (empty($status_where)) {
                $otherwhere = 'storehouseid = '.$storehouseid;
            }
            else {
                $otherwhere = 'storehouseid = '.$storehouseid.' and '.$status_where;
            }

        }
        $this->_data['status'] = $status;




        $this->_data['storehouseid'] = $storehouseid;

        //获取当前库房的库存数据
        $this->dataList('stock/stock_list',$this->stock_model,array(),array('barcode','title','boxno','color','factorycode','typecode'),array('code'=>'asc'),$this->_data,$otherwhere);
    }

    /**
     * 库存列表页面
     */
    public function stock_list() {
        $houses = $this->_get_storehouses();

        if (!$houses) {
            $this->error('当前登陆帐户没有库房浏览权限，请与管理员联系。','main');
            return;
        }
        redirect('stock/stock_pages?houseid='.$houses[0]->id.'&status=1');
    }

    /**
     * 根据id，获取库房对象
     * @param $houseid
     * @return mixed
     */
    private function _get_storehouse($houseid) {
        $this->load->model('storehouse_model');
        $query = $this->storehouse_model->getOne($houseid);

        return $query;
    }

    /**
     * 获取库房列表，填充网页上select下拉框
     */
    private function _get_storehouses() {

        $houses = $this->auth_lib->get_house_auth();
        return $houses;
    }

    /**
     * 获取厂家列表，填充网页input框
     * @return mixed
     */
    private function _get_factorys() {
        $this->load->model('factory_model');
        $factorys = $this->factory_model->getAllByWhere();
        return $factorys;
    }

    /**
     * 获取品牌。
     * @return mixed
     */
    private function _get_brands() {
        $this->load->model('brand_model');
        $brands = $this->brand_model->getAllByWhere();
        return $brands;
    }

    /**
     * 获取商品类型
     * @return mixed
     */
    private function _get_commodity_types() {
        $this->load->model('commodityType_model');
        $commodittypes = $this->commodityType_model->getAllByWhere();
        return $commodittypes;
    }

    /**
     * 添加商品
     */
    public function add() {

        $storehouseid = $this->input->get('houseid') ? $this->input->get('houseid') : '';

        //库房列表
        $houses = $this->_get_storehouses();
        $this->_data['houses'] = $houses;

        //厂家列表
        $factorys = $this->_get_factorys();
        $this->_data['factorys'] = $factorys;
        //品牌列表
        $brands = $this->_get_brands();
        $this->_data['brands'] = $brands;
        //商品类型列表
        $comtypes = $this->_get_commodity_types();
        $this->_data['comtypes'] = $comtypes;

        if (!empty($storehouseid)) {
            $this->_data['storehouseid'] = $storehouseid;
        }
        else {
            $this->_data['storehouseid'] = '';
        }


        //fun_path是当前模块路径。用来页面上体现当前是哪个功能，这个功能的css与其他的不同。
        $this->_data['fun_path'] = "stock/stock_list";

        //读取验证
        $this->_load_validation_rules();

        if($this->form_validation->run() === FALSE) {
            $select_house = $this->input->post('storehouse',TRUE);
            if (!empty($select_house)) {
                $this->_data['storehouseid'] = $select_house;
            }

            $this->load->view('stock/stock_add',$this->_data);
        }
        else {
            //执行inset
            $this->_insert_stock();
        }


    }

    private function _insert_stock() {
        /** 获取表单数据 */
        $content = $this->_get_form_data();



        $insert_stock = array(
            'id'            =>  md5(uniqid(rand(), true)),  //uuid有问题。先采用这种试试
            'storehouseid'  =>  empty($content['storehouseid']) ? '1' : $content['storehouseid'],
            'title'         =>  empty($content['title']) ? '' : $content['title'],
            'code'          =>  empty($content['code']) ? '' : $content['code'],
            'memo'          =>  empty($content['memo']) ? '' : $content['memo'],
            'cost'          =>  empty($content['cost']) ? 0 : floatval($content['cost']),
            'standardcost'  =>  empty($content['standardcost']) ? 0 : floatval($content['standardcost']),
            'salesprice'    =>  empty($content['salesprice']) ? 0 : floatval($content['salesprice']),
            'remark'        =>  empty($content['remark']) ? '' : $content['remark'],
            'brandid'       =>  empty($content['brandid']) ? '' : $content['brandid'],
            'factoryid'     =>  empty($content['factoryid']) ? '' : $content['factoryid'],
            'typeid'        =>  empty($content['typeid']) ? '' : $content['typeid'],
            'barcode'       =>  empty($content['barcode']) ? '' : $content['barcode'],
            'number'        =>  empty($content['number']) ? '' : $content['number'],
            'color'         =>  empty($content['color']) ? '' : $content['color'],
            'format'        =>  empty($content['format']) ? '' : $content['format'],
            'boxno'         =>  empty($content['boxno']) ? '' : $content['boxno'],
            'itemnumber'    =>  empty($content['itemnumber']) ? '' : $content['itemnumber'],
            'statuskey'     =>  empty($content['statuskey']) ? '' : $content['statuskey']
        );

        if ($insert_stock['statuskey'] == 1) {
            $insert_stock['statusvalue'] = '在库';
        }
        else {
            $insert_stock['statusvalue'] = '已销售';
        }

        //获取厂家、品牌、类型信息
        $factory = $this->_get_factory($insert_stock['factoryid']);
        $brand = $this->_get_brand($insert_stock['brandid']);
        $type = $this->_get_commoditytype($insert_stock['typeid']);
//        $color = $this->_get_color($insert_stock['color']);

        $insert_stock['factorycode'] = $factory[0]->factorycode;
        $insert_stock['factoryname'] = $factory[0]->factoryname;
        $insert_stock['brandcode'] = $brand[0]->brandcode;
        $insert_stock['brandname'] = $brand[0]->brandname;
        $insert_stock['typecode'] = $type[0]->typecode;
        $insert_stock['typename'] = $type[0]->typename;
//        $insert_stock['colorcode'] = $color[0]->colorcode;
//        $insert_stock['color'] = $color[0]->colorname;

        $insert_stock['picpath'] = 'upload/stock_image/no_pic.jpg';

        $newid = $this->dataInsert($this->stock_model,$insert_stock,false);

        $draft = $this->input->post('draft',true);
        if ($newid) {
            if ($draft == 0) {
                $this->success(null,site_url().'/stock/stock_pages?houseid='.$insert_stock['storehouseid']);
            }
            else {
                $this->success(null,site_url().'/stock/stock_show?houseid='.$insert_stock['storehouseid'].'&stockid='.$insert_stock['id']);
            }

        }
    }

    /**
     * 修改商品
     */
    public function edit() {

        $stockid = $this->input->get('stockid') ? $this->input->get('stockid') : '';
        $storehouseid = $this->input->get('houseid') ? $this->input->get('houseid') : '';
        $p = $this->input->get('p') ? $this->input->get('p') : 1;
        $search = $this->input->get('search') ? $this->input->get('search') : '';

        $this->_data['search'] = $search;
        $this->_data['p'] = $p;

        //获取商品信息
//        $stock = $this->stock_model->get_stock_by_id($stockid);
        $stock = $this->stock_model->getOne($stockid);
        if(empty($stock)) {
            show_error('发生错误：商品不存在或已被删除。');
            exit();
        }

        $this->_data['stock'] = $stock;

        //库房列表
        $houses = $this->_get_storehouses();
        $this->_data['houses'] = $houses;

        //厂家列表
        $factorys = $this->_get_factorys();
        $this->_data['factorys'] = $factorys;
        //品牌列表
        $brands = $this->_get_brands();
        $this->_data['brands'] = $brands;
        //商品类型列表
        $comtypes = $this->_get_commodity_types();
        $this->_data['comtypes'] = $comtypes;



        //获取颜色代码，填充到页面select里
//        $this->load->model('color_model');
//        $this->_data['colorlist'] = $this->color_model->getAllByWhere();

        if (!empty($storehouseid)) {
            $this->_data['storehouseid'] = $storehouseid;
        }
        else {
            $this->_data['storehouseid'] = '';
        }


        //fun_path是当前模块路径。用来页面上体现当前是哪个功能，这个功能的css与其他的不同。
        $this->_data['fun_path'] = "stock/stock_list";

        $this->_stockid = $stockid;

        //读取验证
        $this->_load_validation_rules();

        if($this->form_validation->run() === FALSE) {
            $this->load->view('stock/stock_edit',$this->_data);
        }
        else {
            //执行edit
            $this->_edit_stock($stockid,$p);
        }
    }

    /**
     * 保存修改商品信息
     */
    private function _edit_stock($id,$p) {
        /** 获取表单数据 */
        $content = $this->_get_form_data();
        $insert_stock = array(
            'id'            => $id,
            'storehouseid'  =>  empty($content['storehouseid']) ? '1' : $content['storehouseid'],
            'title'         =>  empty($content['title']) ? '' : $content['title'],
            'code'          =>  empty($content['code']) ? '' : $content['code'],
            'memo'          =>  empty($content['memo']) ? '' : $content['memo'],
            'cost'          =>  empty($content['cost']) ? 0 : floatval($content['cost']),
            'standardcost'  =>  empty($content['standardcost']) ? 0 : floatval($content['standardcost']),
            'salesprice'    =>  empty($content['salesprice']) ? 0 : floatval($content['salesprice']),
            'remark'        =>  empty($content['remark']) ? '' : $content['remark'],
            'brandid'       =>  empty($content['brandid']) ? '' : $content['brandid'],
            'factoryid'     =>  empty($content['factoryid']) ? '' : $content['factoryid'],
            'typeid'        =>  empty($content['typeid']) ? '' : $content['typeid'],
            'barcode'       =>  empty($content['barcode']) ? '' : $content['barcode'],
            'number'        =>  empty($content['number']) ? '' : $content['number'],
            'color'         =>  empty($content['color']) ? '' : $content['color'],
            'format'        =>  empty($content['format']) ? '' : $content['format'],
            'boxno'         =>  empty($content['boxno']) ? '' : $content['boxno'],
            'itemnumber'    =>  empty($content['itemnumber']) ? '' : $content['itemnumber'],
            'statuskey'     =>  empty($content['statuskey']) ? '' : $content['statuskey']
        );

        if ($insert_stock['statuskey'] == 1) {
            $insert_stock['statusvalue'] = '在库';
        }
        else {
            $insert_stock['statusvalue'] = '已销售';
        }

        //获取厂家、品牌、类型信息
        $factory = $this->_get_factory($insert_stock['factoryid']);
        $brand = $this->_get_brand($insert_stock['brandid']);
        $type = $this->_get_commoditytype($insert_stock['typeid']);
//        $color = $this->_get_color($insert_stock['color']);

        $insert_stock['factorycode'] = $factory[0]->factorycode;
        $insert_stock['factoryname'] = $factory[0]->factoryname;
        $insert_stock['brandcode'] = $brand[0]->brandcode;
        $insert_stock['brandname'] = $brand[0]->brandname;
        $insert_stock['typecode'] = $type[0]->typecode;
        $insert_stock['typename'] = $type[0]->typename;

        $num = $this->dataUpdate($this->stock_model,$insert_stock,false);
        $search = $this->input->post('search',true);

        $search_type = $this->session->userdata('search_type');

//        $draft = $this->input->post('draft',true);
        if ($num >= 0) {
            if ($search_type == '1') {
                $this->success(null,site_url().'/stock/stock_pages?houseid='.$insert_stock['storehouseid'].$this->session->userdata('super_search'));
                return;
            }
            $this->success(null,site_url().'/stock/stock_pages?houseid='.$insert_stock['storehouseid'].'&p='.$p.'&barcode='.$search);
        }
        else {
            if ($search_type == '1') {
                $this->error('保存出错或没有数据被修改。',site_url().'/stock/stock_pages?houseid='.$insert_stock['storehouseid'].$this->session->userdata('super_search'));
            }
            $this->error('保存出错或没有数据被修改。',site_url().'/stock/stock_pages?houseid='.$insert_stock['storehouseid'].'&p='.$p.'&barcode='.$search);
        }
    }

    /**
     * ajax方式修改商品的配送类型
     */
    public function update_stock_sendtype() {
        $result = false;
//        $ids = $this->input->get('ids') ? $this->input->get('ids') : '';
//        $sendtype = $this->input->get('sendtype') ? $this->input->get('sendtype') : '';

        $ids = $_POST['ids'];
        $sendtype = $_POST['sendtype'];

        $ids = explode(',',$ids);

        if (empty($ids)) {
            $this->output->append_output($result);
            return;
        }
        if (!isset($sendtype)) {
            $this->output->append_output($result);
            return;
        }
        try {
            foreach ($ids as $id) {
                $update_stock = array(
                    'id' => $id,
                    'sendtype' => $sendtype
                );
                $this->dataUpdate($this->stock_model,$update_stock,false);

            }
        }
        catch (Exception $e) {
            $this->output->append_output($result);
            return;
        }
        $result = true;
        $this->output->append_output($result);
    }


    /**
     * 移除库存商品
     * @return bool
     */
    public function remove_stock() {
        $result = false;
        $stockid = $this->input->post('id') ? $this->input->post('id') : '';

        $stockid = explode(',',$stockid);

        if (empty($stockid)) {
            $this->output->append_output($result);
            return;
        }
        foreach ($stockid as $id) {
            $this->dataDelete($this->stock_model,array('id'=>$id),'id',false);
//            $result = $this->stock_lib->remove_stock($id);
        }
        $result = true;
        $this->output->append_output($result);
    }

    /**
     * 上传商品图片
     */
    public function upload_stock_image() {

        $buy_product_id = $this->input->get('id') ? $this->input->get('id') : '';
        $this->load->library('upload_lib');

        $filepath = $this->upload_lib->upload_file('upload/stock_image');

        //将图片文件信息写入数据库
        if ($filepath) {
            $stock['id'] = $buy_product_id;
            $stock['picpath'] = $filepath;

            $this->dataUpdate($this->stock_model,$stock,false);

            $result = json_encode(array(
                'newfilename'    => $filepath
            ));

            $this->output->append_output($result);
        }
    }

    /**
     * 上传商品图片（作废）
     */
    public function upload_stock1_image() {

        $stockid = $this->input->get('id') ? $this->input->get('id') : '';
        $this->load->library('upload_lib');

        $filepath = $this->upload_lib->upload_file('upload/stock_image');

        //将图片文件信息写入数据库
        if ($filepath) {
            $pic['id'] = md5(uniqid(rand(), true));
            $pic["stockid"] = $stockid;
            $pic['barcode'] = '';
            $pic['picpath'] = $filepath;

            $this->load->model('stock_pic_model');
            $this->stock_pic_model->add_pic($pic);
        }
    }

    /**
     * 删除商品图片（作废）
     */
    public function remove_pic() {

        $result = false;
        $picid = $this->input->post('picid');

        //如果pic是空，返回false
        if (empty($picid)) {
            $this->output->append_output($result);
            return;
        }

//        $this->load->library('stock_lib');
        $result = $this->stock_lib->remove_pic($picid);
        $this->output->append_output($result);
    }

    /**
     * 设置图片为主要（作废）
     * @return bool
     */
    public function set_pic_main() {
        $result = false;
        $picid = $this->input->post('picid');
        $stockid = $this->input->post('stockid');
        if (empty($picid) || empty($stockid)) {
            return $result;
        }
//        $this->load->library('stock_lib');
        $result = $this->stock_lib->set_pic_main($stockid,$picid);
        $this->output->append_output($result);

    }

    /**
     * 显示库存商品信息 传入库存商品id
     */
    public function stock_show() {

        $stockid = $this->input->get('stockid') ? $this->input->get('stockid') : '';
        $storehouseid = $this->input->get('houseid') ? $this->input->get('houseid') : '';
        $p = $this->input->get('p') ? $this->input->get('p') : 1;
        $barcode = $this->input->get('barcode') ? $this->input->get('barcode') : '';

        $query = false;

        //读取库存信息
        if (!empty($stockid)) {
            $query = $this->stock_model->getOne($stockid);

            //获取商品所属库房
            if (!empty($query)) {
                $house = $this->_get_storehouse($query[0]->storehouseid);
                $this->_data['housecode'] = $house[0]->storehousecode;
            }
        }

        $this->_data['row'] = $query;
        $this->_data['p'] = $p;
        $this->_data['search'] = $barcode;

        //获取当前帐户是否有商品管理模块的模块内操作权限，来判断是否打开添加、修改、删除按钮
        $oper = $this->auth_lib->role_fun_operate('7');
        $this->_data['oper'] = $oper;

        if (!empty($storehouseid)) {
            $this->_data['storehouseid'] = $storehouseid;
        }
        else {
            $this->_data['storehouseid'] = '0';
        }

        //fun_path是当前模块路径。用来页面上体现当前是哪个功能，这个功能的css与其他的不同。
        $this->_data['fun_path'] = "stock/stock_list";
        $this->load->view('stock/stock_show',$this->_data);

    }

    /**
     * 获取厂家对象
     * @param $id       厂家id
     * @return mixed
     */
    private function _get_factory($id) {
        $this->load->model('factory_model');
        $query = $this->factory_model->getOne($id);
        return $query;
    }

    /**
     * 获取品牌对象
     * @param $id       品牌id
     * @return mixed
     */
    private function _get_brand($id) {
        $this->load->model('brand_model');
        $query = $this->brand_model->getOne($id);
        return $query;
    }

    /**
     * 获取商品类型对象
     * @param $id       类型id
     * @return mixed
     */
    private function _get_commoditytype($id) {
        $this->load->model('commodityType_model');
        $query = $this->commodityType_model->getOne($id);
        return $query;
    }

    /**
     * 获取颜色
     * @param $colorid
     * @return mixed
     */
    private function _get_color($colorid) {
        $this->load->model('color_model');
        $query = $this->color_model->getOne($colorid);
        return $query;
    }

    /**
     * 获取表单数据
     *
     * @access private
     * @return array
     */
    private function _get_form_data() {
        return array(
            'storehouseid'  =>  $this->input->post('storehouse',TRUE),
            'title' 		=> 	$this->input->post('title',TRUE),
            'code' 			=> 	$this->input->post('code',TRUE),
            'memo' 	        => 	$this->input->post('memo',TRUE),
            'cost' 	        => 	$this->input->post('cost',TRUE),
            'standardcost' 	=> 	$this->input->post('standardcost',TRUE),
            'salesprice' 	=>	$this->input->post('salesprice',TRUE),
            'remark' 		=> 	$this->input->post('remark',TRUE),
            'brandid' 	    => 	$this->input->post('brand',TRUE),
            'factoryid' 	=> 	$this->input->post('factory',TRUE),
            'typeid' 		=> 	$this->input->post('commoditytype',TRUE),
            'barcode' 		=> 	$this->input->post('barcode',TRUE),
            'number' 		=> 	$this->input->post('number',TRUE),
            'color' 		=> 	$this->input->post('color',TRUE),
            'format' 		=> 	$this->input->post('format',TRUE),
            'boxno' 		=> 	$this->input->post('boxno',TRUE),
            'itemnumber'    => 	$this->input->post('itemnumber',TRUE),
            'statuskey' 	=> 	$this->input->post('statuskey',TRUE)
        );
    }

    /**
     * 加载验证规则
     *
     * @access private
     * @return void
     */
    private function _load_validation_rules() {
        $this->form_validation->set_rules('storehouse', '库房', 'trim');
        $this->form_validation->set_rules('title', '标题', 'required|trim|htmlspecialchars');
        $this->form_validation->set_rules('code', '代码', 'required|trim');
        $this->form_validation->set_rules('memo', '商品描述', 'trim');
        $this->form_validation->set_rules('cost', '单价', 'required|trim|numeric');
        $this->form_validation->set_rules('standardcost', '标准单价', 'required|trim|numeric');
        $this->form_validation->set_rules('salesprice', '售价', 'required|trim|numeric');
        $this->form_validation->set_rules('remark', '备注', 'trim');
        $this->form_validation->set_rules('brand', '品牌', 'trim');
        $this->form_validation->set_rules('factory', '厂家', 'trim');
        $this->form_validation->set_rules('commoditytype', '商品类别', 'trim');
        //条形码自定义验证，是否唯一
        $this->form_validation->set_rules('barcode', '条形码', 'required|trim|callback_barcode_check');
        $this->form_validation->set_rules('number', '数量', 'required|trim|is_natural');
        $this->form_validation->set_rules('color', '颜色', 'trim');
        $this->form_validation->set_rules('format', '规格', 'trim');
        $this->form_validation->set_rules('boxno', '箱号', 'trim');
        $this->form_validation->set_rules('itemnumber', '件数', 'trim');
        $this->form_validation->set_rules('statusvalue', '商品状态', 'trim');
    }

    /**
     * 批量打印条形码
     */
    public function multi_barcode() {
        $stockids = $this->input->post('stockids') ? $this->input->post('stockids') : '';
        $templet_type = $this->input->post('templet_type') ? $this->input->post('templet_type') : '';
        $path = $this->input->post('path') ? $this->input->post('path') : '';
        if (!$stockids) {
            $this->error('没有获取批量打印条码的数据，请重新选择。','stock');
        }

        //判断是什么模块过来的批量
        if ($templet_type == 'storehouse_in') {
            //如果是入库单过来的批量打印

        }

        $this->_data['path'] = $path;

        $this->_data['fun_path'] = "stock";
        $stockids = explode(',',$stockids);

        $this->db->where_in('id',$stockids);

        $this->dataList('stock/stock_multi_barcode_print',$this->stock_model,array(),array(),array(),$this->_data,array());

    }

    /**
     * 期货过来的入库商品，直接生成销售单，审核后，直接送货
     */
    public function create_order() {
        $result = false;

//        $stocks = $this->input->post('stocks') ? $this->input->post('stocks') : '';
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

        try {
            //======生成销售单
            //获取入库单信息
            $this->load->model('s_storehouse_in_model');
            $this->load->model('b_buy_model');
            $this->load->model('buy_product_model');
            $this->load->model('apply_model');
            $this->load->model('apply_content_model');
            $this->load->model('sell_model');
            $this->load->model('sellcont_model');

            $storehouse_in = $this->s_storehouse_in_model->getOne($storehouse_inid);


            $buy = $this->b_buy_model->getOne($storehouse_in[0]->buyid);


            $apply = $this->apply_model->getOne($buy[0]->applyid);

            //判断这个期货订单是否已经生成销售单
            $tmp_sell = $this->sell_model->getOneByWhere(array('sellnumber'=>$apply[0]->applynumber));

            if ($tmp_sell) {
                $new_sellid = $tmp_sell->id;
                //修改销售单的状态为0，等待审批
                if ($tmp_sell->status != 0) {
                    $update_sell = array();
                    $update_sell['id'] = $tmp_sell->id;
                    $update_sell['status'] = 0;
                    $this->dataUpdate($this->sell_model,$update_sell,false);
                }
            }
            else {
                $insert_sell = array();
                $insert_sell['sellnumber'] = $apply[0]->applynumber;
                $insert_sell['createbyid'] = $apply[0]->createbyid;
                $insert_sell['createtime'] = $apply[0]->createtime;
                $insert_sell['createby'] = $apply[0]->createby;
                $insert_sell['selldate'] = $apply[0]->selldate;
                $insert_sell['clientname'] = $apply[0]->clientname;
                $insert_sell['clientphone'] = $apply[0]->clientphone;
                $insert_sell['clientadd'] = $apply[0]->clientadd;
                $insert_sell['checkbyid'] = $apply[0]->checkbyid;
                $insert_sell['checkby'] = $apply[0]->checkby;
                $insert_sell['remark'] = $apply[0]->remark;
                $insert_sell['totalmoney'] = $apply[0]->totalmoney;
                $insert_sell['discount'] = $apply[0]->discount;
                $insert_sell['storehouseid'] = $apply[0]->storehouseid;
                $insert_sell['storehousecode'] = $apply[0]->storehousecode;
                $insert_sell['paymoney'] = $apply[0]->paymoney;
                $insert_sell['lastmoney'] = $apply[0]->lastmoney;
                $insert_sell['applyid'] = $apply[0]->id;
                $insert_sell['checkbyid'] = $apply[0]->checkbyid;
                $insert_sell['checkby'] = $apply[0]->checkby;

                $new_sellid = $this->dataInsert($this->sell_model,$insert_sell,false);
            }
            //将期货入库的商品与销售单关联

            foreach ($contentids as $content) {
                $insert_sell_content = array();
                $insert_sell_content['stockid'] = $content;
                $insert_sell_content['sellid'] = $new_sellid;
                $insert_sell_content['issend'] = 0;
                $this->dataInsert($this->sellcont_model,$insert_sell_content,false);
            }

            //获取期货订单的商品总数。
            //如果订单商品数量等于销售单的商品数量，证明订单商品都入库，设置销售单的isall为1，配送后，可以将销售单的状态设置为3（已配送），销售单结束。

            $apply_content = $this->apply_content_model->getAllByWhere(array('applyid'=>$apply[0]->id));
            $apply_num = 0;
            if ($apply_content) {
                foreach($apply_content as $content) {
                    $apply_num = $apply_num + $content->number;
                }
            }

            //获取销售单的商品数量
            $sell_content = $this->sellcont_model->getAllByWhere(array('sellid'=>$new_sellid));

            if ($apply_num == count($sell_content)) {
                $update_sell = array();
                $update_sell['id'] = $new_sellid;
                $update_sell['isall'] = 1;
                $this->dataUpdate($this->sell_model,$update_sell,false);
            }

            //将入库商品状态修改
            foreach ($contentids as $id) {
                $update_stock = array(
                    'id'            =>  $id,
                    'statuskey'     =>  '3',
                    'statusvalue'   =>  '已销售'
                );
                $this->dataUpdate($this->stock_model,$update_stock,false);
            }
            $result = true;
        }
        catch (Exception $e) {
            $result = false;
        }
        $this->output->append_output($result);
    }

    /**
     * 自定义条形码验证规则(必需唯一)
     * @param $str
     * @return bool
     */
    public function barcode_check($str) {

//        date_default_timezone_set('PRC');
//        $bb = time();
//        $aa =  date('Y-m-d H:i:s',$bb);
//
//        $cc = strtotime($aa);

        $result = false;
        if (empty($str)) {
            $this->form_validation->set_message('barcode_check', '这个 %s 是必须填写的。');
            return $result;
        }

        $this->load->library('barcode_lib');
        $result = $this->barcode_lib->barcode_check($str,$this->_stockid);

        if ($result) {
            $this->form_validation->set_message('barcode_check', '这个 %s 在数据库中已经存在，请重新生成。');
            return false;
        }
        else {
            return true;
        }

    }

}
