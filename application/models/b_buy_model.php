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
 * b_buy model Class
 *
 * 采购单Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class b_buy_model extends MY_Model{

    const TABLE_NAME = "b_buy";

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();

        $this->tableName = 'b_buy';

        log_message('debug', "b_buy Model Class Initialized");
    }


    /**
     * 重写父类表单验证
     */
    public function _validate() {
        $this->form_validation->set_rules('buynumber', '采购单编号', 'required|trim|htmlspecialchars');
    }







    //TODO 因继承了父类。以下代码可以考虑删除=======

    /**
     * 获取单个采购单信息
     *
     * @access public
     * @param string $id 采购单id
     * @return array - 采购单信息
     */
    public function get_buy_by_id($id) {
        $data = array();

        $this->db->select('*')->from(self::TABLE_NAME)->where('id', $id)->limit(1);
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            $data = $query->row_array();
        }
        //释放结果集
        $query->free_result();

        return $data;
    }

    /**
     * 获取采购单信息
     *
     * @access public
     * @return array - 采购单信息
     */
    public function get_buys($status=NULL,$offset=0,$num=NULL,$search_txt=NULL) {

        $sql = "select * from ".self::TABLE_NAME;
        $tmp = '';
        $value = array();

        if (isset($status)) {
            $tmp = " where status = ?";
            array_push($value,$status);
        }
        if ($search_txt && $search_txt != '') {
            if (empty($tmp)) {
                $tmp = ' where ';
            }
            else {
                $tmp = $tmp.' and ';
            }
            //对采购单号、创建时间、创建者、操作者、操作时间进行模糊检索
            $tmp = $tmp.' (buynumber like ? or FROM_UNIXTIME(createtime,"%Y-%m-%d")=? or createby like ? or buyman like ? or FROM_UNIXTIME(buydate,"%Y-%m-%d")=? or remark like ?)';
            array_push($value,'%'.$search_txt.'%');
            array_push($value,$search_txt);
            array_push($value,'%'.$search_txt.'%');
            array_push($value,'%'.$search_txt.'%');
            array_push($value,$search_txt);
            array_push($value,'%'.$search_txt.'%');
        }

        if (!empty($tmp)) {
            $sql = $sql.$tmp;
        }

        $sql = $sql.' order by createtime desc';

        $sql = $sql.' limit '.$offset;

        if ($num) {
            $sql = $sql.','.$num;
        }

        $query = $this->db->query($sql, $value);
        //TODO 这句可以获得执行的sql语句,可以做测试用
        $str = $this->db->last_query();
        //===========
        $data = $query->result_array();
        //释放结果集
        $query->free_result();

        return $data;
    }

    /**
     * 获取条件下的数据总数
     * @param null $status
     * @param null $search_txt
     * @return mixed
     */
    public function get_buy_count($status=NULL,$search_txt=NULL) {

        $sql = 'select count(*) as count from '.self::TABLE_NAME;
        $tmp = '';
        $value = array();

        if (isset($status)) {
            $tmp = $tmp.' where status = ?';
            array_push($value,$status);
        }

        if ($search_txt && $search_txt != '') {
            if (empty($tmp)) {
                $tmp = ' where ';
            }
            else {
                $tmp = $tmp.' and ';
            }
            //对采购单号、创建时间、创建者、操作者、操作时间进行模糊检索
            $tmp = $tmp.' (buynumber like ? or FROM_UNIXTIME(createtime,"%Y-%m-%d")=? or createby like ? or buyman like ? or FROM_UNIXTIME(buydate,"%Y-%m-%d")=? or remark like ?)';
            array_push($value,'%'.$search_txt.'%');
            array_push($value,$search_txt);
            array_push($value,'%'.$search_txt.'%');
            array_push($value,'%'.$search_txt.'%');
            array_push($value,$search_txt);
            array_push($value,'%'.$search_txt.'%');
        }

        if (!empty($tmp)) {
            $sql = $sql.$tmp;
        }

        $query = $this->db->query($sql,$value);
        $count = $query->row_array();
        $query->free_result();

        $count = $count['count'];

        return $count;
    }

    /**
     * 删除一个采购单
     *
     * @access public
     * @param string - $id 采购单id
     * @return boolean - success/failure
     */
    public function remove_buy($id) {

        //TODO 先移除采购单与功能对应关系。再移除采购单。

        $this->db->delete(self::TABLE_NAME, array('id' => $id));

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * 添加一个采购单
     *
     * @access public
     * @param array - $data 采购单信息
     * @return boolean - success/failure
     */
    public function add_buy($data) {

        $this->db->insert(self::TABLE_NAME, $data);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * 修改采购单信息
     *
     * @access public
     * @param string - $id 采购单ID
     * @param array - $data 采购单信息
     * @return boolean - success/failure
     */
    public function update_buy($id, $data) {

        $this->db->where('id', $id);
        $this->db->update(self::TABLE_NAME, $data);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
}
