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
 * stock upload Library Class
 *
 * execl 处理 lib
 *
 * @package		stock
 * @subpackage	Libraries
 * @category	Libraries
 * @author		blues <blues0118@gmail.com>
 * @link
 */


class excel_lib {

    /**
     * CI句柄
     *
     * @access private
     * @var object
     */
    private $_CI;

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        /** 获取CI句柄 */
        $this->_CI = & get_instance();
        require_once(FCPATH . STOCK_PLUGINS_DIR . '/' . 'phpexcel_1.7.8/Classes/PHPExcel.php');

        log_message('debug', "stock: excel library Class Initialized");
    }

    public function readExcel($filePath = '') {
        if (empty($filePath)) {
            return '';
        }
        $PHPExcel = new PHPExcel();

        /**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/
        $PHPReader = new PHPExcel_Reader_Excel2007();
        if(!$PHPReader->canRead($filePath)){
            $PHPReader = new PHPExcel_Reader_Excel5();
            if(!$PHPReader->canRead($filePath)){
                echo 'no Excel';
                return ;
            }
        }

        $rows = array();
        $PHPExcel = $PHPReader->load($filePath);
        /**读取excel文件中的第一个工作表*/
        $currentSheet = $PHPExcel->getSheet(0);
        /**取得最大的列号*/
        $allColumn = $currentSheet->getHighestColumn();
        /**取得一共有多少行*/
        $allRow = $currentSheet->getHighestRow();
        /**从第二行开始输出，因为excel表中第一行为列名*/
        for($currentRow = 2;$currentRow <= $allRow;$currentRow++){
            $row = array();
            /**从第A列开始输出*/
            for($currentColumn= 'A';$currentColumn<= $allColumn; $currentColumn++){
//                $val = $currentSheet->getCellByColumnAndRow($currentColumn,$currentRow)->getValue();/**ord()将字符转为十进制数*/
//                $columnName = $currentSheet->getCellByColumnAndRow($currentColumn,1)->getValue();
                $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getValue();/**ord()将字符转为十进制数*/
                $columnName = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,1)->getValue();

                if (!isset($val)) {
                    $val = '';
                }
                $row[$columnName] = $val;
//                if($currentColumn == 'A')
//                {
//                    echo GetData($val)."\t";
//                }else{
////echo $val;
//                    /**如果输出汉字有乱码，则需将输出内容用iconv函数进行编码转换，如下将gb2312编码转为utf-8编码输出*/
//                    echo iconv('utf-8','gb2312', $val)."\t";
//                }
            }
            array_push($rows,$row);
        }
        return $rows;
    }

//    /**对excel里的日期进行格式转化*/
//    function GetData($val){
//        $jd = GregorianToJD(1, 1, 1970);
//        $gregorian = JDToGregorian($jd+intval($val)-25569);
//        return $gregorian;/**显示格式为 “月/日/年” */
//    }

    function excelTime($date, $time = false) {
        if(function_exists('GregorianToJD')){
            if (is_numeric( $date )) {
                $jd = GregorianToJD( 1, 1, 1970 );
                $gregorian = JDToGregorian( $jd + intval ( $date ) - 25569 );
                $date = explode( '/', $gregorian );
                $date_str = str_pad( $date [2], 4, '0', STR_PAD_LEFT )
                    ."-". str_pad( $date [0], 2, '0', STR_PAD_LEFT )
                    ."-". str_pad( $date [1], 2, '0', STR_PAD_LEFT )
                    . ($time ? " 00:00:00" : '');
                return $date_str;
            }
        }else{
            $date=$date>25568?$date+1:25569;
            /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
            $ofs=(70 * 365 + 17+2) * 86400;
            $date = date("Y-m-d",($date * 86400) - $ofs).($time ? " 00:00:00" : '');
        }
        return $date;
    }
}
