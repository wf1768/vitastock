<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * stock Controller Class
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
 * stock barcode Controller Class
 *
 * 获取条形码
 *
 * @package		stock
 * @subpackage	Controller
 * @category	Controller
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class barcode extends CI_Controller {

    /**
     * 构造函数
     * @access public
     * @return void
     */
    function __construct() {
        parent::__construct();
        $this->load->library('barcode_lib');

    }

    /**
     * 创建条形码图片
     * @param $codebar
     * @param $text
     *
     */
    public function buildcode($codebar,$text) {

        $this->barcode_lib->buildcode($codebar,$text);

    }

    /**
     * 获取随机数，用来生成条形码
     */
    public function get_uniqid() {
        $result = false;
        //获取厂家、品牌、类型代码，用来生成条形码
        $factoryid = $this->input->post('factoryid');
        $brandid = $this->input->post('brandid');
        $commoditytypeid = $this->input->post('commoditytypeid');

//        $result = $this->create_barcode($factoryid,$brandid,$commoditytypeid);
        $result = $this->barcode_lib->create_barcode($factoryid,$brandid,$commoditytypeid);

        $this->output->append_output($result);
        // 不要用echo，因为容易跟session或者header产生冲突,还有一个方法是，如果不想用内部api，
        //就新建个view文件，里面就一个行<?php echo$output;
    }

    public function create_barcode1($factoryid,$brandid,$commoditytypeid) {
//        条码生成规则1：随即数
//        $result = uniqid(rand(), true);
        //条码生成规则2：生成日期时间+100-999间随机数的格式
//        $result = date("Ymd-His") . '-' . rand(100,999);
        //条码生成规则3：厂家代码+品牌代码+商品类型代码+毫秒+100-999随机数


        //获得系统配置里的条形码规则
        $this->load->model('config_model');
//        $code_type = $this->config_model->get_config_by_key('barcode_type');
        $code_type = $this->config_model->getOneByWhere(array('key'=>'barcode_type'));
        $result = '';
        if (isset($code_type)) {
            $type = $code_type->value;
            if ($type == '1') {
                //条码生成规则1：随即数
                $result = uniqid(rand(), true);
            }
            else if ($type == '2'){
                //条码生成规则2：生成日期时间+100-999间随机数的格式
                $result = date("Ymd-His") . '-' . rand(100,999);
            }
            else if ($type == '3') {
                //条码生成规则3：厂家代码+品牌代码+商品类型代码+毫秒+100-999随机数
                $time = explode ( " ", microtime () );
                $time = $time [1] . ($time [0] * 1000);
                $time2 = explode ( ".", $time );
                $time = $time2 [0];

                //获取厂家、品牌、类型代码，用来生成条形码
                $this->load->model('factory_model');
                $factory = $this->factory_model->getOne($factoryid);

                $this->load->model('brand_model');
                $brand = $this->brand_model->getOne($brandid);

                $this->load->model('commodityType_model');
                $commoditytype = $this->commodityType_model->getOne($commoditytypeid);

                $result = $factory[0]->factorycode.$brand[0]->brandcode.$commoditytype[0]->typecode.$time.rand(100,999);
            }
        }
        return $result;
    }
}
