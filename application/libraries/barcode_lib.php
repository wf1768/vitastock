<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* stock System
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

// ------------------------------------------------------------------------

/**
 * stock barcode Library Class
 *
 * 条形码操作类
 *
 * @package		stock
 * @subpackage	Libraries
 * @category	Libraries
 * @author		blues <blues0118@gmail.com>
 * @link
 */


class barcode_lib {

    /**
     * CI句柄
     *
     * @access private
     * @var object
     */
    private $_CI;

    /**
     * 构造函数
     */
    public function __construct() {
        /** 获取CI句柄 */
        $this->_CI = & get_instance();
        log_message('debug', "stock: barcode library Class Initialized");
    }

    /**
     * 生成条形码字符串
     * @param $factoryid
     * @param $brandid
     * @param $commoditytypeid
     * @return string
     */
    public function create_barcode($factoryid,$brandid,$commoditytypeid) {
//        条码生成规则1：随即数
//        $result = uniqid(rand(), true);
        //条码生成规则2：生成日期时间+100-999间随机数的格式
//        $result = date("Ymd-His") . '-' . rand(100,999);
        //条码生成规则3：厂家代码+品牌代码+商品类型代码+毫秒+100-999随机数

        //获得系统配置里的条形码规则
        $this->_CI->load->model('config_model');
        $code_type = $this->_CI->config_model->getOneByWhere(array('key'=>'barcode_type'));
        $result = '';
        if (isset($code_type)) {
            $type = $code_type->value;
            if ($type == '1') {
                //条码生成规则1：随即数
                $result = uniqid(rand(), true);
            }
            else if ($type == '2'){
                //条码生成规则2：生成日期时间+100-999间随机数的格式
                $result = date("YmdHis") . '-' . rand(100,999);
            }
            else if ($type == '3') {
                //条码生成规则3：厂家代码+品牌代码+商品类型代码+毫秒+100-999随机数
                $time = explode ( " ", microtime () );
                $time = $time [1] . ($time [0] * 1000);
                $time2 = explode ( ".", $time );
                $time = $time2 [0];

                //获取厂家、品牌、类型代码，用来生成条形码
                $this->_CI->load->model('factory_model');
                $factory = $this->_CI->factory_model->getOne($factoryid);

                $this->_CI->load->model('brand_model');
                $brand = $this->_CI->brand_model->getOne($brandid);

                $this->_CI->load->model('commodityType_model');
                $commoditytype = $this->_CI->commodityType_model->getOne($commoditytypeid);

                $result = $factory[0]->factorycode.$brand[0]->brandcode.$commoditytype[0]->typecode.$time.rand(100,999);
            }
        }
        return $result;
    }


    /**
     * 生成条形码
     * @param string $codebar
     * @param string $text
     */
    public function buildcode($codebar='BCGcode128',$text='test') {

        $path = FCPATH . STOCK_PLUGINS_DIR . '/' . 'barcodegen/class/BCGFontFile.php';

        require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'barcodegen/class/BCGFontFile.php');
        require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'barcodegen/class/BCGColor.php');
        require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'barcodegen/class/BCGDrawing.php');

//        require_once('class/BCGFontFile.php');
//        require_once('class/BCGColor.php');
//        require_once('class/BCGDrawing.php');

//        $codebar = $_REQUEST['codebar']; //条形码将要数据的内容

        // Including the barcode technology
        require_once(FCPATH . STOCK_PLUGINS_DIR . '/' .'barcodegen/class/'.$codebar.'.barcode.php');

        // Loading Font
        $font = new BCGFontFile(FCPATH . STOCK_PLUGINS_DIR . '/' .'barcodegen/font/Arial.ttf', 10);

        // The arguments are R, G, B for color.
        $color_black = new BCGColor(0, 0, 0);
        $color_white = new BCGColor(255, 255, 255);

        $drawException = null;
        try {
            $code = new $codebar();//实例化对应的编码格式
            $code->setScale(2); // Resolution
            $code->setThickness(24); // Thickness
            $code->setForegroundColor($color_black); // Color of bars
            $code->setBackgroundColor($color_white); // Color of spaces
            $code->setFont($font); // Font (or 0)
//            $text = $_REQUEST['text']; //条形码将要数据的内容
            $code->parse($text);
        } catch(Exception $exception) {
            $drawException = $exception;
        }

        /* Here is the list of the arguments
        1 - Filename (empty : display on screen)
        2 - Background color */
        $drawing = new BCGDrawing('', $color_white);
        if($drawException) {
            $drawing->drawException($drawException);
        } else {
            $drawing->setBarcode($code);
            $drawing->draw();
        }

        // Header that says it is an image (remove it if you save the barcode to a file)
        header('Content-Type: image/png');

        // Draw (or save) the image into PNG format.
        $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
    }

    /**
     * 在库存中判断条形码是否唯一
     * @param string $barcode
     * @return bool
     */
    public function barcode_check($barcode='',$stockid='') {

        $result = false;
        if (empty($barcode)) {
            return $result;
        }
        $this->_CI->load->model('stock_model');
        $result = $this->_CI->stock_model->check_exist('barcode',$barcode,$stockid);

        return $result;

    }


}
