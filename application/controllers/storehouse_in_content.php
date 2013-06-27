<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * stock Login Controller Class
 *
 * 基于ci的商品管理系统
 *
 * @package        stock
 * @author        blues <blues0118@gmail.com>
 * @copyright    Copyright (c) 2013 - 2015, ussoft.net.
 * @license
 * @link
 * @version        0.1.0
 */

//=================================================

/**
 * storehouse_in_content  Controller Class
 *
 * 入库单下入库商品  管理控制器。
 *
 * @package        stock
 * @subpackage    Controller
 * @category    Controller
 * @author        blues <blues0118@gmail.com>
 * @link
 */

class storehouse_in_content extends Stock__Controller {

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
        $this->load->model('s_storehouse_in_model');
        $this->load->model('stock_model');
        $this->load->model('stock_in_model');
        $this->load->model('storehouse_model');
        $this->load->model('stock_pic_model');


        $this->load->model('factory_model');
        $this->load->model('brand_model');
        $this->load->model('commodityType_model');
        $this->load->model('color_model');

        $this->load->library('stock_lib');
        $this->load->library('barcode_lib');


        /** 在继承的自定义父类，获取系统配置。 */
        $this->_data = $this->get_stock_config('0','6');

        $this->_data['page_title'] =  '入库单管理';

        $this->_data['fun_path'] = "storehouse_in";
    }

    /**
     * 显示入库商品信息(不能编辑的)
     */
    public function show_content() {
        //获取入库商品id
        $id = $this->input->get('id') ? $this->input->get('id') : '';
        $storehouseid = $this->input->get('storehouseid') ? $this->input->get('storehouseid') : '';
        $query = $this->stock_model->getOne($id);
        $this->_data['row'] = $query;
        $this->_data['storehouseid'] = $storehouseid;
        $this->load->view('stock/storehouse_in_content_show', $this->_data);
    }

    /**
     * 显示条形码办理入库页面
     */
    public function show_hand_in_by_barcode() {
        $this->load->view('stock/handle_in_by_barcode', $this->_data);
    }

    /**
     * 多条码办理入库
     */
    public function handle_in_by_multibarcode() {
        $result = false;
        //获取入库商品id
        $barcodes = $this->input->post('barcodes') ? $this->input->post('barcodes') : '';

        if (!$barcodes) {
            $this->output->append_output($result);
            return;
        }

        require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
        $json = new Services_JSON();
        $output = $json->decode($barcodes);

        try {
            $tmp_stock_ids = array();
            foreach ($output as $row) {
                $row = (array)$row;

                $row = $this->stock_model->getOneByWhere(array('barcode' => $row['barcode']));

                if (!$row) {
                    continue;
                }
                if ($row->statuskey == 1) {
                    continue;
                }
                $update_stock = array(
                    'id'            =>  $row->id,
                    'statuskey'     =>  '1',
                    'statusvalue'   =>  '在库'
                );
                $this->dataUpdate($this->stock_model,$update_stock,false);

                //判断当前商品的入库单下商品是否全部入库。如果全部入库，结束入库单
                $tmp = $this->stock_in_model->getAllByWhere(array('stockid'=>$row->id));

                $stock_in = false;
                $stock_in = $this->stock_in_model->getAllByWhere(array('inid'=>$tmp[0]['inid']),array(),array());
                if ($stock_in) {
                    $ids = Common::array_flatten($stock_in, 'stockid');
                }

                $this->db->where_in('id',$ids);

                $stock_content = $this->stock_model->getAllByWhere(array('statuskey'=>'0'));

                if (!$stock_content) {
                    //将入库单状态修改
                    $updata_storehouse_in = array(
                        'id'        =>  $tmp[0]['inid'],
                        'checkbyid' => $this->account_info_lib->id,
                        'checkby'   => $this->account_info_lib->accountname,
                        'overtime'  => date('Y-m-d H:i:s',now()),
                        'instatus'  =>  1
                    );
                    $this->dataUpdate($this->s_storehouse_in_model,$updata_storehouse_in,false);
                }

                array_push($tmp_stock_ids,$row->id);
            }

            $this->db->where_in('id',$tmp_stock_ids);

            $stocks = $this->stock_model->getAllByWhere();

            //将数据转为json
            require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
            $json = new Services_JSON();
            $output = $json->encode($stocks);
            $this->output->append_output($output);
        } catch (Exception $e) {
            $this->output->append_output($result);
            return;
        }
    }

    /**
     * 扫描条码，办理入库
     */
    public function handle_in_by_barcode() {
        $result = false;
        //获取入库商品id
        $barcode = $this->input->post('barcode') ? $this->input->post('barcode') : '';
        $auto  = $this->input->post('auto') ? $this->input->post('auto') : '';
        if (!$barcode) {
            $this->output->append_output($result);
            return;
        }
        $row = $this->stock_model->getOneByWhere(array('barcode' => $barcode));
        if ($row->statuskey == 1) {
            $this->output->append_output($result);
            return;
        }
        //如果auto是1 ，自动入库
        if ($auto == 1) {
            $update_stock = array(
                'id'            =>  $row->id,
                'statuskey'     =>  '1',
                'statusvalue'   =>  '在库'
            );
            $this->dataUpdate($this->stock_model,$update_stock,false);
        }

        $row = $this->stock_model->getOneByWhere(array('barcode' => $barcode));

        //判断当前商品的入库单下商品是否全部入库。如果全部入库，结束入库单
        $tmp = $this->stock_in_model->getAllByWhere(array('stockid'=>$row->id));

        $stock_in = false;
        $stock_in = $this->stock_in_model->getAllByWhere(array('inid'=>$tmp[0]['inid']),array(),array());
        if ($stock_in) {
            $ids = Common::array_flatten($stock_in, 'stockid');
        }

        $this->db->where_in('id',$ids);

        $stock_content = $this->stock_model->getAllByWhere(array('statuskey'=>'0'));

        if (!$stock_content) {
            //将入库单状态修改
            $updata_storehouse_in = array(
                'id'        =>  $tmp[0]['inid'],
                'checkbyid' => $this->account_info_lib->id,
                'checkby'   => $this->account_info_lib->accountname,
                'overtime'  => date('Y-m-d H:i:s',now()),
                'instatus'  =>  1
            );
            $this->dataUpdate($this->s_storehouse_in_model,$updata_storehouse_in,false);
        }

        if (!$row) {
            $this->output->append_output($result);
            return;
        }
        //将数据转为json
        require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
        $json = new Services_JSON();
        $output = $json->encode($row);
        $this->output->append_output($output);
    }

    /**
     * 不自动入库。通过扫描后，点击按钮一次性手动入库
     */
    public function no_auto_handle_in() {
        $result = false;
        //获取入库商品id
        $barcodes = $this->input->post('barcodes') ? $this->input->post('barcodes') : '';

        if (!$barcodes) {
            $this->output->append_output($result);
            return;
        }

        require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'JSON.php');
        $json = new Services_JSON();
        $output = $json->decode($barcodes);

        try {
            foreach ($output as $row) {
                $row = (array)$row;

                $row = $this->stock_model->getOneByWhere(array('barcode' => $row['barcode']));
                if ($row->statuskey == 1) {
                    break;
                }

                $update_stock = array(
                    'id'            =>  $row->id,
                    'statuskey'     =>  '1',
                    'statusvalue'   =>  '在库'
                );
                $this->dataUpdate($this->stock_model,$update_stock,false);

                $row = $this->stock_model->getOneByWhere(array('barcode' => $row->barcode));

                //判断当前商品的入库单下商品是否全部入库。如果全部入库，结束入库单
                $tmp = $this->stock_in_model->getAllByWhere(array('stockid'=>$row->id));
                $stock_in = $this->stock_in_model->getAllByWhere(array('inid'=>$tmp[0]['inid']),array(),array());
//        }
                if ($stock_in) {
                    $ids = Common::array_flatten($stock_in, 'stockid');
                }

                $this->db->where_in('id',$ids);

                $stock_content = $this->stock_model->getAllByWhere(array('statuskey'=>'0'));

                if (!$stock_content) {
                    //将入库单状态修改
                    $updata_storehouse_in = array(
                        'id'        =>  $tmp[0]['inid'],
                        'checkbyid' => $this->account_info_lib->id,
                        'checkby'   => $this->account_info_lib->accountname,
                        'overtime'  => date('Y-m-d H:i:s',now()),
                        'instatus'  =>  1
                    );
                    $this->dataUpdate($this->s_storehouse_in_model,$updata_storehouse_in,false);
                }

            }
            $result = true;
        } catch (Exception $e) {
            $this->output->append_output($result);
            return;
        }
        $this->output->append_output($result);
    }



    /**
     * 添加入库单下商品
     */
    public function add_stock() {
        //获取入库单id
        $id = $this->input->get('id') ? $this->input->get('id') : '';
        $this->_data['storehouse_in_id'] = $id;

        //获取库房
        $this->_data['houses']=$this->storehouse_model->getAllByWhere("",array("id","storehousecode"));
        //获取厂家
        $this->_data['factorys'] = $this->factory_model->getAllByWhere();
        //获取品牌
        $this->_data['brands'] = $this->brand_model->getAllByWhere();
        //获取类别
        $this->_data['comtypes'] = $this->commodityType_model->getAllByWhere();
        //获取颜色
        $this->_data['colorlist'] = $this->color_model->getAllByWhere();



        //读取验证
        $this->stock_model->_validate();
        if ($this->stock_model->form_validation->run() === FALSE) {
            $this->load->view('stock/storehouse_in_content_add', $this->_data);
        } else {
        //执行inset
            $this->_insert_storehouse_in_content();
        }
    }

    private function _insert_storehouse_in_content() {
        $id = $this->input->get('id') ? $this->input->get('id') : '';
        /** 获取表单数据 */
        $content = $this->_get_form_data();

        $aa = isset($content['statuskey']);

        $insert_stock = array(
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
            'statuskey'     =>  isset($content['statuskey']) ? $content['statuskey'] : '',
            'statusvalue'   =>  '未入库',
            'picpath'       =>  'upload/stock_image/no_pic.jpg'
        );

        //获取厂家、品牌、类型信息
        $factory = $this->factory_model->getOne($insert_stock['factoryid']);
        $brand = $this->brand_model->getOne($insert_stock['brandid']);
        $type = $this->commodityType_model->getOne($insert_stock['typeid']);
        $color = $this->color_model->getOne($insert_stock['color']);

        $insert_stock['factorycode'] = $factory[0]->factorycode;
        $insert_stock['factoryname'] = $factory[0]->factoryname;
        $insert_stock['brandcode'] = $brand[0]->brandcode;
        $insert_stock['brandname'] = $brand[0]->brandname;
        $insert_stock['typecode'] = $type[0]->typecode;
        $insert_stock['typename'] = $type[0]->typename;
        $insert_stock['colorcode'] = $color[0]->colorcode;
        $insert_stock['color'] = $color[0]->colorname;

        $newid = $this->dataInsert($this->stock_model,$insert_stock,false);

        if (!$newid) {
            $this->error('保存发生错误，请重新尝试或与管理员联系。',site_url('storehouse_in/show?id='.$id));
        }

        //处理入库单与入库商品关联
        $insert_stock_in = array(
            'stockid'       =>  $newid,
            'inid'          =>  $id
        );

        $new_stock_in_id = $this->dataInsert($this->stock_in_model,$insert_stock_in,false);

        if ($new_stock_in_id) {
            $this->success(null,site_url('storehouse_in/show?id='.$id));
        }
        else {
            $this->error('保存发生错误，请重新尝试或与管理员联系。',site_url('storehouse_in/show?id='.$id));
        }
    }

    /**
     * 移除入库单下库存商品
     * @return bool
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
            $result = $this->stock_lib->remove_stock($id);
        }

        $this->output->append_output($result);
    }

    /**
     * 入库商品生成条形码
     */
    public function createbarcode() {
        $result = false;
        $ids = $this->input->post('id') ? $this->input->post('id') : '';

        $ids = explode(',',$ids);

        if (empty($ids)) {
            $this->output->append_output($result);
            return;
        }
        try {
            foreach ($ids as $id) {
                //获取商品信息
                $stock = $this->stock_model->getOne($id);
                $stock = $stock[0];
                $barcode = $this->barcode_lib->create_barcode($stock->factoryid,$stock->brandid,$stock->typeid);
//                $barcode = barcode::create_barcode($stock->factoryid,$stock->brandid,$stock->typeid);
                $update_stock = array(
                    'id'        =>  $stock->id,
                    'barcode'   =>  $barcode
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
            'standardcost'  => 	$this->input->post('standardcost',TRUE),
            'salesprice' 	=>	$this->input->post('salesprice',TRUE),
            'remark' 		=> 	$this->input->post('remark',TRUE),
            'brandid' 	    => 	$this->input->post('brand',TRUE),
            'factoryid' 	=> 	$this->input->post('factory',TRUE),
            'typeid' 		=> 	$this->input->post('commoditytype',TRUE),
            'barcode' 		=> 	$this->input->post('barcode',TRUE),
            'number' 		=> 	$this->input->post('number',TRUE),
            'color' 		=> 	$this->input->post('color',TRUE),
            'format' 		=> 	$this->input->post('format',TRUE),
            'statuskey' 	=> 	$this->input->post('statuskey',TRUE)
        );
    }

}