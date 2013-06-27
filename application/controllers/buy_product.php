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
 * buy_product Controller Class
 *
 * 采购商品  管理控制器。
 *
 * @package		buy
 * @subpackage	Controller
 * @category	Controller
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class buy_product extends Stock__Controller{
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
        $this->load->model('buy_product_model');
        $this->load->model('factory_model');
        $this->load->model('brand_model');
        $this->load->model('color_model');
        $this->load->model('commodityType_model');

        /** 在继承的自定义父类，获取系统配置。 */
        $this->_data = $this->get_stock_config('0','13');
        $this->_data['page_title'] =  '采购、期货管理';
        $this->_data['fun_path'] = "buy/buy_list";

    }

    /**
     * 获取表单代码项
     */
    private function get_field_code() {
        //厂家列表
        $this->_data['factorys'] = $this->factory_model->getAllByWhere();

        //品牌列表
        $this->_data['brands'] = $this->brand_model->getAllByWhere();

        //商品类型列表
        $this->_data['comtypes'] = $this->commodityType_model->getAllByWhere();

        //获取颜色代码，填充到页面select里
//        $this->_data['colors'] = $this->color_model->getAllByWhere();
    }
    //打开添加商品页面
    public function add() {

        //得到添加商品的采购单id
        $buyid = $this->input->get('id');

        $this->_data['buyid'] = $buyid;

        $this->get_field_code();

        $this->load->view('buy/buy_product_add',$this->_data);
    }

    /**
     * 执行插入
     * @access public
     * @return mixed
     */
    public  function doBuyProductAdd(){
        //获得数据
        /** 获取表单数据 */
        $content = $this->_get_form_data();
        $insert_buy_product = array(
            'buyid'         =>  empty($content['buyid']) ? '' : $content['buyid'],
            'title'         =>  empty($content['title']) ? '' : $content['title'],
            'code'          =>  empty($content['code']) ? '' : $content['code'],
            'memo'          =>  empty($content['memo']) ? '' : $content['memo'],
            'cost'          =>  empty($content['cost']) ? 0 : floatval($content['cost']),
            'standardcost'  =>  empty($content['standardcost']) ? 0 : floatval($content['standardcost']),
            'totalprice'    =>  empty($content['totalprice']) ? 0 : floatval($content['totalprice']),
            'standardtotalprice'  =>  empty($content['standardtotalprice']) ? 0 : floatval($content['standardtotalprice']),
            'salesprice'    =>  empty($content['salesprice']) ? 0 : floatval($content['salesprice']),
            'remark'        =>  empty($content['remark']) ? '' : $content['remark'],
            'brandid'       =>  empty($content['brandid']) ? '' : $content['brandid'],
            'factoryid'     =>  empty($content['factoryid']) ? '' : $content['factoryid'],
            'typeid'        =>  empty($content['typeid']) ? '' : $content['typeid'],
            'number'        =>  empty($content['number']) ? '' : $content['number'],
            'color'         =>  empty($content['color']) ? '' : $content['color'],
            'format'        =>  empty($content['format']) ? '' : $content['format'],
            'boxno'         =>  empty($content['boxno']) ? '' : $content['boxno'],
            'itemnumber'    =>  empty($content['itemnumber']) ? '' : intval($content['itemnumber']),
            'status'        =>  !isset($content['buystatue']) ? 0 : $content['buystatue'],
            'statusvalue'   =>  '未入库',
            'picpath'       =>  'upload/stock_image/no_pic.jpg'
        );

        //获取厂家、品牌、类型、颜色信息
        $factory = $this->factory_model->getOne($insert_buy_product['factoryid']);
        $brand = $this->brand_model->getOne($insert_buy_product['brandid']);
        $type = $this->commodityType_model->getOne($insert_buy_product['typeid']);
//        $color = $this->color_model->getOne($insert_buy_product['color']);

        $insert_buy_product['factorycode'] = $factory[0]->factorycode;
        $insert_buy_product['factoryname'] = $factory[0]->factoryname;
        $insert_buy_product['brandcode'] = $brand[0]->brandcode;
        $insert_buy_product['brandname'] = $brand[0]->brandname;
        $insert_buy_product['typecode'] = $type[0]->typecode;
        $insert_buy_product['typename'] = $type[0]->typename;
//        $insert_buy_product['colorcode'] = $color[0]->colorcode;
//        $insert_buy_product['color'] = $color[0]->colorname;

        $this->dataInsert($this->buy_product_model,$insert_buy_product,false);

        redirect('buy/show?id='.$insert_buy_product['buyid']);

    }

    /**
     * 获取表单数据
     *
     * @access private
     * @return array
     */
    private function _get_form_data() {
        return array(
            'id'            =>  $this->input->post('id',TRUE),
            'buyid'         =>  $this->input->post('buyid',TRUE),
            'title' 		=> 	$this->input->post('title',TRUE),
            'code' 			=> 	$this->input->post('code',TRUE),
            'memo' 	        => 	$this->input->post('memo',TRUE),
            'cost' 	        => 	$this->input->post('cost',TRUE),
            'standardcost' 	=> 	$this->input->post('standardcost',TRUE),
            'totalprice' 	=> 	$this->input->post('totalprice',TRUE),
            'standardtotalprice' 	=> 	$this->input->post('standardtotalprice',TRUE),
            'salesprice' 	=>	$this->input->post('salesprice',TRUE),
            'remark' 		=> 	$this->input->post('remark',TRUE),
            'brandid' 	    => 	$this->input->post('brand',TRUE),
            'factoryid' 	=> 	$this->input->post('factory',TRUE),
            'typeid' 		=> 	$this->input->post('commoditytype',TRUE),
            'number' 		=> 	$this->input->post('number',TRUE),
            'color' 		=> 	$this->input->post('color',TRUE),
            'boxno' 		=> 	$this->input->post('boxno',TRUE),
            'itemnumber' 	=> 	$this->input->post('itemnumber',TRUE),
            'format' 		=> 	$this->input->post('format',TRUE),
            'status' 	    => 	$this->input->post('status',TRUE)
        );
    }

    /**
     * 删除采购商品
     */
    public function remove() {
        $result = false;
        $buy_product_ids = $this->input->post('id') ? $this->input->post('id') : '';

        $buy_product_ids = explode(',',$buy_product_ids);

        if (empty($buy_product_ids)) {
            $this->output->append_output($result);
            return;
        }
        foreach ($buy_product_ids as $id) {
            $result = $this->dataDelete($this->buy_product_model,array('id'=>$id),'id',false);
        }
        if ($result > 0) {
            $result = true;
        }
        $this->output->append_output($result);
    }

    public function edit() {
//        $this->_data['buyid'] = $this->input->get('buyid');
        //获取表单代码项
        $this->get_field_code();
        $this->dataEdit("buy/buy_product_edit",$this->buy_product_model,$this->_data) ;
    }

    /**
     * 批量修改采购商品的厂家、品牌、类别
     */
    public function batchEdit() {
        $result = false;
        //获取ajax方式传入的参数
        $btn = $this->input->post('btn');
        $value = $this->input->post('value');
        $ids = $this->input->post('ids');

        if (empty($btn) || empty($ids)) {
            $this->output->append_output($result);
            return;
        }
        $ids = explode(',',$ids);

        foreach ($ids as $id) {

            //根据id，获取采购商品实体
            $query = $this->buy_product_model->getOne($id);

            if ($btn == 'factory') {
                $factory = $this->factory_model->getOne($value);
                if (isset($factory)) {
                    $row['factoryid']            = $factory[0]->id;
                    $row['factorycode']          = $factory[0]->factorycode;
                    $row['factoryname']          = $factory[0]->factoryname;
                }
            }
            else if ($btn == 'brand') {
                $brand = $this->brand_model->getOne($value);
                if (isset($brand)) {
                    $row['brandid']              = $brand[0]->id;
                    $row['brandcode']            = $brand[0]->brandcode;
                    $row['brandname']            = $brand[0]->brandname;
                }
            }
            else if ($btn == 'commoditytype') {
                $commoditytype = $this->commodityType_model->getOne($value);
                if (isset($commoditytype)) {
                    $row['typeid']              = $commoditytype[0]->id;
                    $row['typecode']            = $commoditytype[0]->typecode;
                    $row['typename']            = $commoditytype[0]->typename;
                }
            }
            else {
                $this->output->append_output(false);
                return;
            }

//            unset($row['id']);
//            $result = $this->dataUpdate($this->buy_product_model,$row,false);
            $result = $this->buy_product_model->update($query[0]->id,$row);
        }

        $this->output->append_output($result);
    }

    public function doEdit() {
        //获得数据
        /** 获取表单数据 */
        $content = $this->_get_form_data();
        $insert_buy_product = array(
            'id'            =>  $content['id'],
            'buyid'         =>  empty($content['buyid']) ? '' : $content['buyid'],
            'title'         =>  empty($content['title']) ? '' : $content['title'],
            'code'          =>  empty($content['code']) ? '' : $content['code'],
            'memo'          =>  empty($content['memo']) ? '' : $content['memo'],
            'cost'          =>  empty($content['cost']) ? 0 : floatval($content['cost']),
            'standardcost'  =>  empty($content['standardcost']) ? 0 : floatval($content['standardcost']),
            'totalprice'    =>  empty($content['totalprice']) ? 0 : floatval($content['totalprice']),
            'standardtotalprice'  =>  empty($content['standardtotalprice']) ? 0 : floatval($content['standardtotalprice']),
            'salesprice'    =>  empty($content['salesprice']) ? 0 : floatval($content['salesprice']),
            'remark'        =>  empty($content['remark']) ? '' : $content['remark'],
            'brandid'       =>  empty($content['brandid']) ? '' : $content['brandid'],
            'factoryid'     =>  empty($content['factoryid']) ? '' : $content['factoryid'],
            'typeid'        =>  empty($content['typeid']) ? '' : $content['typeid'],
            'number'        =>  empty($content['number']) ? '' : $content['number'],
            'color'         =>  empty($content['color']) ? '' : $content['color'],
            'format'        =>  empty($content['format']) ? '' : $content['format'],
            'boxno'         =>  empty($content['boxno']) ? '' : $content['boxno'],
            'itemnumber'    =>  empty($content['itemnumber']) ? '' : intval($content['itemnumber']),
            'status'        =>  0,
            'statusvalue'   =>  '未入库'
        );

        //获取厂家、品牌、类型、颜色信息
        $factory = $this->factory_model->getOne($insert_buy_product['factoryid']);
        $brand = $this->brand_model->getOne($insert_buy_product['brandid']);
        $type = $this->commodityType_model->getOne($insert_buy_product['typeid']);
//        $color = $this->color_model->getOne($insert_buy_product['color']);

        $insert_buy_product['factorycode'] = $factory[0]->factorycode;
        $insert_buy_product['factoryname'] = $factory[0]->factoryname;
        $insert_buy_product['brandcode'] = $brand[0]->brandcode;
        $insert_buy_product['brandname'] = $brand[0]->brandname;
        $insert_buy_product['typecode'] = $type[0]->typecode;
        $insert_buy_product['typename'] = $type[0]->typename;
//        $insert_buy_product['colorcode'] = $color[0]->colorcode;
//        $insert_buy_product['color'] = $color[0]->colorname;

        $this->dataUpdate($this->buy_product_model,$insert_buy_product,false);

        redirect('buy/show?id='.$insert_buy_product['buyid']);

    }

    /**
     * 打开导入采购商品页面
     */
    public function import() {
//        $this->session->unset_userdata('file_path');
        $buyid = $this->input->get('id') ? $this->input->get('id') : '';
        $this->_data['buyid'] = $buyid;
        $file_path = $this->session->userdata('file_path');
        $this->session->unset_userdata('file_path');
        $this->_data['json_data'] = '';
        if ($file_path) {
            $excel_data = $this->readExcel($file_path);
            $this->_data['excel_data'] = $excel_data;
            //将excel数据转为json
            require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
            $json = new Services_JSON();
            $output = $json->encode($excel_data);
//            $output = json_decode($excel_data,true);
//            $output = addslashes($output);
//            $output = str_replace("\n","",$output);

            $output = urlencode($output);
            $this->_data['json_data'] = $output;
            //检查文件是否存在
            if (file_exists($file_path)) {
                //删除物理文件
                unlink($file_path);
            }
        }
        $this->load->view("buy/buy_product_import",$this->_data) ;
    }

    public function saveImport() {
        $result = false;

        $aa = $this->input->post('buyid',TRUE);

        $buyid = $this->input->post('buyid') ? $this->input->post('buyid') : '';
        $excel_data = $this->input->post('json_data') ?$this->input->post('json_data'):'';
        $excel_data = urldecode($excel_data);
        if (!empty($excel_data)) {
            require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
            $json = new Services_JSON();
            $output = $json->decode($excel_data);

            try {
                foreach ($output as $row) {
                    $row = (array)$row;
                    //将对象里的中文名对应换成字段英文
                    $insert_row = array();
                    $insert_row['id']                   = md5(uniqid(rand(), true));
                    $insert_row['buyid']                = $buyid;
                    $insert_row['title']                = empty($row['名称']) ? '' : $row['名称'];
                    $insert_row['code']                 = empty($row['代码']) ? '' : $row['代码'];
                    $insert_row['memo']                 = empty($row['描述']) ? '' : $row['描述'];
                    $insert_row['cost']                 = empty($row['单价']) ? '' : $row['单价'];
                    $insert_row['standardcost']         = empty($row['标准单价']) ? '' : $row['标准单价'];
                    $insert_row['totalprice']           = empty($row['总价']) ? '' : $row['总价'];
                    $insert_row['standardtotalprice']   = empty($row['标准总价']) ? '' : $row['标准总价'];
                    $insert_row['salesprice']           = empty($row['售价']) ? '' : $row['售价'];
                    $insert_row['remark']               = empty($row['备注']) ? '' : $row['备注'];
                    $insert_row['number']               = empty($row['数量']) ? '' : $row['数量'];
                    $insert_row['boxno']                = empty($row['箱号']) ? '' : $row['箱号'];
                    $insert_row['itemnumber']           = empty($row['件数']) ? '' : $row['件数'];
                    $insert_row['color']                = empty($row['颜色']) ? '未知' : $row['颜色'];
                    $insert_row['format']               = empty($row['材质等级']) ? '0' : $row['材质等级'];
                    $insert_row['status']               = 0;
                    $insert_row['statusvalue']          = '未入库';
                    $insert_row['picpath']              = 'upload/stock_image/no_pic.jpg';


                    $insert_row['factoryid']            = '1';
                    $insert_row['factorycode']          = 'NO';
                    $insert_row['factoryname']          = '未知';

                    $insert_row['brandid']              = '1';
                    $insert_row['brandcode']            = 'NO';
                    $insert_row['brandname']            = '未知';

                    $insert_row['typeid']               = '1';
                    $insert_row['typecode']             = 'NO';
                    $insert_row['typename']             = '未知';

                    $result = $this->buy_product_model->insert($insert_row);
//                    $result = $this->dataInsert($this->buy_product_model,$insert_row,false);
                }
                $result = true;
            } catch (Exception $e) {
                $this->output->append_output($result);
                return;
            }

        }

        $this->output->append_output($result);
    }

    private function readExcel($file_path) {
        $this->load->library('excel_lib');
        $excel_data = $this->excel_lib->readExcel($file_path);
        return $excel_data;
    }

    /**
     * 上传商品图片
     */
    public function upload_buy_product_image() {

        $buy_product_id = $this->input->get('id') ? $this->input->get('id') : '';
        $this->load->library('upload_lib');

        $filepath = $this->upload_lib->upload_file('upload/stock_image');

        //将图片文件信息写入数据库
        if ($filepath) {
            $buy_product['id'] = $buy_product_id;
            $buy_product['picpath'] = $filepath;

            $this->dataUpdate($this->buy_product_model,$buy_product,false);

            $result = json_encode(array(
                'newfilename'    => $filepath
            ));

            $this->output->append_output($result);
        }
    }

    /**
     * 上传商品
     */
    public function upload_buy_product() {

        $buyid = $this->input->get('id') ? $this->input->get('id') : '';
        $this->load->library('upload_lib');

        $filepath = $this->upload_lib->upload_file('upload/tmp');

        $this->session->set_userdata('file_path',$filepath);
    }

}
